<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use Carbon\Carbon;

class EquipmentService
{
    public function scheduleMaintenanceCheck(Equipment $equipment)
    {
        if (!$equipment->next_service_date) {
            return false;
        }

        $daysDue = now()->diffInDays($equipment->next_service_date, false);
        
        if ($daysDue <= 7) {
            // Create maintenance record
            EquipmentMaintenance::create([
                'equipment_id' => $equipment->id,
                'maintenance_type' => 'scheduled',
                'scheduled_date' => $equipment->next_service_date,
                'description' => 'Scheduled maintenance check',
                'status' => 'scheduled',
            ]);

            // Update equipment status
            $equipment->update(['status' => 'maintenance']);

            return true;
        }

        return false;
    }

    public function completeRental(EquipmentRental $rental, array $condition = [])
    {
        $rental->update([
            'return_date' => now(),
            'return_condition' => $condition['condition'] ?? 'good',
            'return_notes' => $condition['notes'] ?? null,
            'status' => 'completed',
        ]);

        // Update equipment availability
        $equipment = $rental->equipment;
        $equipment->increment('quantity_available');
        $equipment->decrement('quantity_reserved');
        $equipment->increment('total_rental_days', $rental->actual_days);

        // Check if maintenance is needed
        $this->scheduleMaintenanceCheck($equipment);
    }

    public function searchAvailableEquipment(array $criteria = [])
    {
        $query = Equipment::where('status', 'available')
            ->where('available_for_rental', true);

        if (isset($criteria['category_id'])) {
            $query->where('category_id', $criteria['category_id']);
        }

        if (isset($criteria['start_date']) && isset($criteria['end_date'])) {
            $startDate = Carbon::parse($criteria['start_date']);
            $endDate = Carbon::parse($criteria['end_date']);
            
            $query->whereDoesntHave('rentals', function ($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function ($query) use ($startDate, $endDate) {
                      $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                  });
            });
        }

        return $query->get();
    }
}
