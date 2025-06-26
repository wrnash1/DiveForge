<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Agency;
use App\Models\Certification;

class CertificationService
{
    public function issueCertification(User $user, Course $course, array $data = [])
    {
        // Validate course completion
        $enrollment = $user->courses()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();

        if (!$enrollment) {
            throw new \Exception('Course not completed');
        }

        // Create certification record
        $certification = Certification::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'agency_id' => $course->agency_id,
            'certification_level' => $course->certification_issued,
            'issue_date' => now(),
            'card_number' => $this->generateCardNumber($course->agency),
            'instructor_id' => $data['instructor_id'] ?? null,
            'status' => 'active',
        ]);

        // Update user's certification level
        $user->update([
            'certification_level' => $course->certification_issued
        ]);

        // Submit to agency if API available
        if ($course->agency->hasApiIntegration()) {
            $this->submitToAgency($certification);
        }

        // Send notification
        $user->notify(new \App\Notifications\CertificationIssued($certification));

        return $certification;
    }

    private function generateCardNumber(Agency $agency): string
    {
        $prefix = $agency->code;
        $timestamp = now()->format('ymdHis');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }

    private function submitToAgency(Certification $certification)
    {
        // Implement agency API submission
        // This would vary by agency
    }
}
