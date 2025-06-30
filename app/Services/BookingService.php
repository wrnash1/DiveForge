<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\CourseEnrollment;
use App\Models\Equipment;
use App\Models\EquipmentRental;
use Carbon\Carbon;

class BookingService
{
    public function bookCourse(User $user, Course $course, array $data = [])
    {
        // Validate user can book course
        if (!$course->canUserEnroll($user)) {
            throw new \Exception('User cannot enroll in this course');
        }

        // Create enrollment
        $enrollment = CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_date' => now(),
            'status' => 'enrolled',
            'special_requirements' => $data['special_requirements'] ?? null,
        ]);

        // Send confirmation notification
        $user->notify(new \App\Notifications\BookingConfirmed($enrollment));

        return $enrollment;
    }

    public function rentEquipment(User $user, Equipment $equipment, Carbon $startDate, Carbon $endDate)
    {
        // Check availability
        if (!$this->isEquipmentAvailable($equipment, $startDate, $endDate)) {
            throw new \Exception('Equipment not available for selected dates');
        }

        // Calculate rental cost
        $days = $startDate->diffInDays($endDate) + 1;
        $cost = $equipment->rental_price_daily * $days;

        // Create rental
        $rental = EquipmentRental::create([
            'user_id' => $user->id,
            'equipment_id' => $equipment->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'daily_rate' => $equipment->rental_price_daily,
            'total_cost' => $cost,
            'status' => 'reserved',
        ]);

        // Update equipment availability
        $equipment->decrement('quantity_available');
        $equipment->increment('quantity_reserved');

        return $rental;
    }

    private function isEquipmentAvailable(Equipment $equipment, Carbon $startDate, Carbon $endDate): bool
    {
        if ($equipment->quantity_available <= 0) {
            return false;
        }

        // Check for overlapping rentals
        $overlapping = EquipmentRental::where('equipment_id', $equipment->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->count();

        return $overlapping < $equipment->quantity_available;
    }
}
