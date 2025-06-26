#!/bin/bash

# DiveForge Laravel Code Completion Script
# This script creates the remaining Laravel components after the database setup
# Version: 1.0
# License: GPL v3

set -e

echo "🌊 DiveForge Laravel Code Completion Script"
echo "==========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the root of a Laravel project"
    exit 1
fi

print_status "Creating remaining Laravel components for DiveForge..."

# Create missing models
print_status "Creating missing models..."

# Create all the missing models referenced in migrations
MODELS=(
    "Equipment" "EquipmentCategory" "EquipmentRental" "EquipmentMaintenance"
    "Student" "Instructor" "Certification" "CourseSchedule" "CourseEnrollment"
    "DiveSite" "DiveCondition" "DiveTrip" "TripParticipant"
    "CustomerProfile" "CustomerCommunication" "LoyaltyProgram"
    "Transaction" "Invoice" "InvoiceItem" "Payment" "Refund" "GiftCard" "AirCard"
    "StaffProfile" "StaffCertification" "WorkSchedule" "TimeTracking" "Commission"
    "IncidentReport" "SafetyInspection" "MedicalForm" "Waiver" "InsuranceClaim"
    "EmailCampaign" "SMSCampaign" "Newsletter" "SocialMediaPost" "Review"
    "Report" "AnalyticsData" "BusinessMetric" "PerformanceIndicator"
    "Permission" "UserPermission" "AuditLog" "Notification"
    "Supplier" "PurchaseOrder" "VendorCatalog" "ProductImage"
    "BoatOperation" "CharterBooking" "CompressorOperation" "NitroxAnalyzer" "GasAnalysisLog"
)

for model in "${MODELS[@]}"; do
    if [ ! -f "app/Models/$model.php" ]; then
        print_status "Creating model: $model"
        php artisan make:model "$model"
    else
        print_warning "Model $model already exists, skipping..."
    fi
done

# Create comprehensive controllers
print_status "Creating controllers..."

CONTROLLERS=(
    "Admin/EquipmentController" "Admin/CourseController" "Admin/InstructorController"
    "Admin/StudentController" "Admin/DiveSiteController" "Admin/TripController"
    "Admin/ReportController" "Admin/SettingsController" "Admin/AgencyController"
    "Customer/BookingController" "Customer/ProfileController" "Customer/EquipmentRentalController"
    "Customer/TripController" "Customer/CertificationController"
    "Instructor/CourseController" "Instructor/StudentController" "Instructor/ScheduleController"
    "Equipment/RentalController" "Equipment/MaintenanceController" "Equipment/InventoryController"
    "Financial/InvoiceController" "Financial/PaymentController" "Financial/ReportController"
    "API/V1/CustomerController" "API/V1/CourseController" "API/V1/EquipmentController"
)

for controller in "${CONTROLLERS[@]}"; do
    print_status "Creating controller: $controller"
    php artisan make:controller "$controller" --resource
done

# Create form requests for validation
print_status "Creating form request validators..."

REQUESTS=(
    "StoreEquipmentRequest" "UpdateEquipmentRequest"
    "StoreCourseRequest" "UpdateCourseRequest"
    "StoreInstructorRequest" "UpdateInstructorRequest"
    "StoreStudentRequest" "UpdateStudentRequest"
    "StoreDiveSiteRequest" "UpdateDiveSiteRequest"
    "BookingRequest" "EquipmentRentalRequest"
    "CustomerProfileRequest" "InstructorProfileRequest"
)

for request in "${REQUESTS[@]}"; do
    print_status "Creating request: $request"
    php artisan make:request "$request"
done

# Create middleware
print_status "Creating middleware..."

MIDDLEWARE=(
    "EnsureUserIsInstructor" "EnsureUserIsStudent" "EnsureShopOwner"
    "CheckEquipmentAvailability" "ValidateCertificationLevel"
    "CheckMedicalClearance" "RateLimitBookings"
)

for middleware in "${MIDDLEWARE[@]}"; do
    print_status "Creating middleware: $middleware"
    php artisan make:middleware "$middleware"
done

# Create jobs for background processing
print_status "Creating background jobs..."

JOBS=(
    "ProcessCertificationSubmission" "SendBookingReminder" "GenerateInvoice"
    "ProcessEquipmentMaintenance" "SendMarketingEmail" "ProcessPayment"
    "GenerateReport" "BackupDatabase" "CleanupExpiredBookings"
    "UpdateEquipmentAvailability" "ProcessWaiverSigning"
)

for job in "${JOBS[@]}"; do
    print_status "Creating job: $job"
    php artisan make:job "$job"
done

# Create notifications
print_status "Creating notification classes..."

NOTIFICATIONS=(
    "BookingConfirmed" "BookingReminder" "BookingCancelled"
    "CertificationIssued" "EquipmentOverdue" "MaintenanceDue"
    "InvoiceGenerated" "PaymentReceived" "TripReminder"
    "WelcomeCustomer" "CourseCompleted" "SafetyAlert"
)

for notification in "${NOTIFICATIONS[@]}"; do
    print_status "Creating notification: $notification"
    php artisan make:notification "$notification"
done

# Create service classes
print_status "Creating service classes..."

mkdir -p app/Services

cat > "app/Services/BookingService.php" << 'EOF'
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
EOF

cat > "app/Services/CertificationService.php" << 'EOF'
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
EOF

cat > "app/Services/EquipmentService.php" << 'EOF'
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
EOF

# Create Policies for authorization
print_status "Creating authorization policies..."

POLICIES=(
    "CoursePolicy" "EquipmentPolicy" "DiveSitePolicy" "UserPolicy"
    "InstructorPolicy" "BookingPolicy" "ReportPolicy"
)

for policy in "${POLICIES[@]}"; do
    print_status "Creating policy: $policy"
    php artisan make:policy "$policy"
done

# Create API Resources
print_status "Creating API resources..."

RESOURCES=(
    "UserResource" "CourseResource" "EquipmentResource" "DiveSiteResource"
    "BookingResource" "CertificationResource" "InstructorResource"
)

for resource in "${RESOURCES[@]}"; do
    print_status "Creating API resource: $resource"
    php artisan make:resource "$resource"
done

# Create Events and Listeners
print_status "Creating events and listeners..."

EVENTS=(
    "CourseBooked" "CertificationIssued" "EquipmentRented" "TripBooked"
    "MaintenanceScheduled" "PaymentProcessed" "UserRegistered"
)

for event in "${EVENTS[@]}"; do
    print_status "Creating event: $event"
    php artisan make:event "$event"
done

# Create comprehensive routes
print_status "Creating comprehensive route files..."

cat > "routes/admin.php" << 'EOF'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', Admin\UserController::class);
    Route::post('users/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Dive Shop Management
    Route::resource('shops', Admin\DiveShopController::class);
    Route::post('shops/{shop}/toggle-status', [Admin\DiveShopController::class, 'toggleStatus'])->name('shops.toggle-status');
    
    // Agency Management
    Route::resource('agencies', Admin\AgencyController::class);
    Route::post('agencies/{agency}/sync', [Admin\AgencyController::class, 'syncData'])->name('agencies.sync');
    
    // Course Management
    Route::resource('courses', Admin\CourseController::class);
    Route::post('courses/{course}/duplicate', [Admin\CourseController::class, 'duplicate'])->name('courses.duplicate');
    
    // Equipment Management
    Route::resource('equipment', Admin\EquipmentController::class);
    Route::post('equipment/{equipment}/maintenance', [Admin\EquipmentController::class, 'scheduleMaintenance'])->name('equipment.maintenance');
    Route::get('equipment/categories', [Admin\EquipmentController::class, 'categories'])->name('equipment.categories');
    
    // Instructor Management
    Route::resource('instructors', Admin\InstructorController::class);
    Route::post('instructors/{instructor}/certifications', [Admin\InstructorController::class, 'addCertification'])->name('instructors.certifications');
    
    // Student Management
    Route::resource('students', Admin\StudentController::class);
    Route::get('students/{student}/progress', [Admin\StudentController::class, 'progress'])->name('students.progress');
    
    // Dive Site Management
    Route::resource('dive-sites', Admin\DiveSiteController::class);
    Route::post('dive-sites/{diveSite}/conditions', [Admin\DiveSiteController::class, 'updateConditions'])->name('dive-sites.conditions');
    
    // Trip Management
    Route::resource('trips', Admin\TripController::class);
    Route::get('trips/{trip}/participants', [Admin\TripController::class, 'participants'])->name('trips.participants');
    
    // Reports
    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/revenue', [Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/equipment', [Admin\ReportController::class, 'equipment'])->name('reports.equipment');
    Route::get('reports/safety', [Admin\ReportController::class, 'safety'])->name('reports.safety');
    
    // Settings
    Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
});
EOF

cat > "routes/customer.php" << 'EOF'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer;

Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Profile Management
    Route::get('profile', [Customer\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/avatar', [Customer\ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    
    // Course Bookings
    Route::get('courses', [Customer\BookingController::class, 'courses'])->name('courses.index');
    Route::get('courses/{course}', [Customer\BookingController::class, 'showCourse'])->name('courses.show');
    Route::post('courses/{course}/book', [Customer\BookingController::class, 'bookCourse'])->name('courses.book');
    
    // Equipment Rentals
    Route::get('equipment', [Customer\EquipmentRentalController::class, 'index'])->name('equipment.index');
    Route::get('equipment/{equipment}', [Customer\EquipmentRentalController::class, 'show'])->name('equipment.show');
    Route::post('equipment/{equipment}/rent', [Customer\EquipmentRentalController::class, 'rent'])->name('equipment.rent');
    Route::get('rentals', [Customer\EquipmentRentalController::class, 'myRentals'])->name('rentals.index');
    
    // Trip Bookings
    Route::get('trips', [Customer\TripController::class, 'index'])->name('trips.index');
    Route::get('trips/{trip}', [Customer\TripController::class, 'show'])->name('trips.show');
    Route::post('trips/{trip}/book', [Customer\TripController::class, 'book'])->name('trips.book');
    
    // Certifications
    Route::get('certifications', [Customer\CertificationController::class, 'index'])->name('certifications.index');
    Route::get('certifications/{certification}', [Customer\CertificationController::class, 'show'])->name('certifications.show');
    Route::get('certifications/{certification}/card', [Customer\CertificationController::class, 'downloadCard'])->name('certifications.card');
    
    // Bookings and History
    Route::get('bookings', [Customer\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [Customer\BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/cancel', [Customer\BookingController::class, 'cancel'])->name('bookings.cancel');
});
EOF

# Update web.php to include new route files
print_status "Updating main web routes..."

cat >> "routes/web.php" << 'EOF'

// Include additional route files
if (file_exists(__DIR__ . '/admin.php')) {
    require __DIR__ . '/admin.php';
}

if (file_exists(__DIR__ . '/customer.php')) {
    require __DIR__ . '/customer.php';
}

// API Routes
Route::prefix('api/v1')->name('api.v1.')->group(function () {
    Route::apiResource('courses', App\Http\Controllers\API\V1\CourseController::class);
    Route::apiResource('equipment', App\Http\Controllers\API\V1\EquipmentController::class);
    Route::apiResource('dive-sites', App\Http\Controllers\API\V1\DiveSiteController::class);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('customers', App\Http\Controllers\API\V1\CustomerController::class);
        Route::post('bookings', [App\Http\Controllers\API\V1\BookingController::class, 'store']);
        Route::get('bookings', [App\Http\Controllers\API\V1\BookingController::class, 'index']);
    });
});

// Public API endpoints
Route::prefix('api/public')->name('api.public.')->group(function () {
    Route::get('courses', [App\Http\Controllers\API\V1\CourseController::class, 'index']);
    Route::get('dive-sites', [App\Http\Controllers\API\V1\DiveSiteController::class, 'index']);
    Route::get('equipment/available', [App\Http\Controllers\API\V1\EquipmentController::class, 'available']);
});
EOF

# Update the middleware registration
print_status "Updating middleware registration..."

cat >> "app/Http/Kernel.php.additions" << 'EOF'
// Add these to your Kernel.php middleware array:

'instructor' => \App\Http\Middleware\EnsureUserIsInstructor::class,
'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
'shop.owner' => \App\Http\Middleware\EnsureShopOwner::class,
'equipment.available' => \App\Http\Middleware\CheckEquipmentAvailability::class,
'certification.level' => \App\Http\Middleware\ValidateCertificationLevel::class,
'medical.clearance' => \App\Http\Middleware\CheckMedicalClearance::class,
'booking.limit' => \App\Http\Middleware\RateLimitBookings::class,
EOF

print_warning "You need to manually add the middleware entries from app/Http/Kernel.php.additions to your actual Kernel.php file"

# Create configuration files
print_status "Creating configuration files..."

cat > "config/diveforge.php" << 'EOF'
<?php

return [
    'version' => '1.0.0',
    
    'equipment' => [
        'default_rental_duration' => 7, // days
        'max_rental_duration' => 30, // days
        'damage_deposit_percentage' => 0.1, // 10% of retail price
        'late_fee_daily' => 25.00,
        'maintenance_interval_days' => 90,
    ],
    
    'booking' => [
        'advance_booking_days' => 30,
        'cancellation_hours' => 24,
        'reminder_hours' => 24,
        'max_group_size' => 12,
    ],
    
    'certification' => [
        'temporary_card_valid_days' => 90,
        'processing_time_days' => 5,
        'renewal_reminder_days' => 30,
    ],
    
    'safety' => [
        'medical_clearance_valid_months' => 12,
        'max_depth_open_water' => 18, // meters
        'max_depth_advanced' => 30, // meters
        'emergency_contact_required' => true,
    ],
    
    'financial' => [
        'payment_terms_days' => 30,
        'late_fee_percentage' => 1.5, // monthly
        'refund_period_days' => 7,
        'deposit_percentage' => 0.3, // 30%
    ],
    
    'agencies' => [
        'supported' => ['PADI', 'SSI', 'TDI', 'NAUI', 'BSAC', 'GUE'],
        'api_timeout' => 30, // seconds
        'sync_interval_hours' => 24,
    ],
];
EOF

print_success "Laravel code completion script finished!"
print_status "Created components:"
echo "  - 🏗️  Missing Models (40+)"
echo "  - 🎮 Controllers (25+)"
echo "  - ✅ Form Requests (16+)"
echo "  - 🛡️  Middleware (7)"
echo "  - ⚡ Background Jobs (11)"
echo "  - 📧 Notifications (12)"  
echo "  - 🔧 Service Classes (3)"
echo "  - 🔐 Authorization Policies (7)"
echo "  - 📊 API Resources (7)"
echo "  - 🎯 Events (7)"
echo "  - 🛣️  Route Files (admin.php, customer.php)"
echo "  - ⚙️  Configuration (diveforge.php)"
echo ""
print_warning "Manual steps still required:"
echo "1. Update Kernel.php with new middleware"
echo "2. Implement controller methods"
echo "3. Create Blade templates"
echo "4. Configure service providers"
echo "5. Set up API authentication"
echo "6. Implement agency API integrations"
echo ""
print_success "🌊 DiveForge Laravel completion ready! 🌊"
