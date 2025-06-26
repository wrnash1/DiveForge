# DiveForge Admin Dashboard - Post Installation

## Application & Feature Verification

1. **Review your current application features and modules.**
2. **Compare each feature with the requirements in `Developer_Guide.md`.**
3. **Make a checklist of missing features (see below for major categories).**
4. **Prioritize and plan implementation for missing features.**

### Major Feature Areas to Verify (from Developer_Guide.md):

-   Equipment Repair & Service Management
-   Air Card & Gift Card Management
-   Trip & Charter Management
-   Advanced Course Management
-   Commission & Incentive Management
-   Advanced Inventory & Vendor Integration
-   Customer Loyalty & Communication
-   Financial Management Enhancements
-   Advanced Reporting & Analytics
-   Dive Site Integration & Management
-   Commercial/B2B Account Management
-   Boat Operations & Maintenance
-   Security Camera Integration
-   Student Validation & Requirements
-   Digital Forms & Documentation
-   Photography & Social Media
-   Advanced Student Management
-   Compressor & Nitrox Operations

## Once Features Match Developer Guide

### Database Setup Steps

1. **Review and update your database schema to support all modules.**
2. **Create/modify migration files for new tables and columns.**
3. **Run migrations:**
    ```bash
    php artisan migrate
    ```
4. **Seed initial data if needed:**
    ```bash
    php artisan db:seed
    ```
5. **Test all relationships and constraints.**

---

## DiveForge Database Implementation Plan (per Developer_Guide.md)

### 1. **Module-to-Table Mapping**

For each major module in Developer_Guide.md, create a migration for the main table(s).  
Below is a mapping and recommended migration commands:

```bash
php artisan make:migration create_equipment_repairs_table
php artisan make:migration create_equipment_service_histories_table
php artisan make:migration create_equipment_parts_table
php artisan make:migration create_air_cards_table
php artisan make:migration create_gift_cards_table
php artisan make:migration create_trips_table
php artisan make:migration create_trip_bookings_table
php artisan make:migration create_boats_table
php artisan make:migration create_crew_members_table
php artisan make:migration create_courses_table
php artisan make:migration create_course_sessions_table
php artisan make:migration create_course_materials_table
php artisan make:migration create_commissions_table
php artisan make:migration create_commission_structures_table
php artisan make:migration create_vendors_table
php artisan make:migration create_inventory_items_table
php artisan make:migration create_loyalty_programs_table
php artisan make:migration create_loyalty_points_table
php artisan make:migration create_reports_table
php artisan make:migration create_dive_sites_table
php artisan make:migration create_commercial_accounts_table
php artisan make:migration create_purchase_orders_table
php artisan make:migration create_invoices_table
php artisan make:migration create_boat_maintenance_table
php artisan make:migration create_compressors_table
php artisan make:migration create_compressor_logs_table
php artisan make:migration create_security_cameras_table
php artisan make:migration create_student_validations_table
php artisan make:migration create_digital_forms_table
php artisan make:migration create_photos_table
php artisan make:migration create_student_lifecycles_table
php artisan make:migration create_hr_staff_profiles_table
php artisan make:migration create_payrolls_table
php artisan make:migration create_customer_communications_table
php artisan make:migration create_marketing_campaigns_table
php artisan make:migration create_audit_logs_table
php artisan make:migration create_api_tokens_table
# ...continue for all modules as needed...
```

### 2. **Migration Example: Equipment Repairs**

```php
// database/migrations/xxxx_xx_xx_create_equipment_repairs_table.php
Schema::create('equipment_repairs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('equipment_id')->constrained();
    $table->foreignId('customer_id')->nullable()->constrained('users');
    $table->foreignId('technician_id')->nullable()->constrained('users');
    $table->string('status');
    $table->text('description')->nullable();
    $table->decimal('labor_hours', 5, 2)->nullable();
    $table->decimal('labor_cost', 10, 2)->nullable();
    $table->decimal('parts_cost', 10, 2)->nullable();
    $table->boolean('warranty')->default(false);
    $table->date('service_date')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### 3. **General Guidelines**

-   Use `foreignId()->constrained()` for relationships.
-   Add `softDeletes()` for records that may need to be restored.
-   Use `timestamps()` for created/updated tracking.
-   Add indexes for frequently queried fields.
-   Use `enum` or `string` for status fields as appropriate.
-   For audit/compliance, add `created_by`, `updated_by` where needed.

### 4. **Seeding Data**

-   Create seeders for agencies, roles, permissions, default settings, and demo data.
-   Example:
    ```bash
    php artisan make:seeder AgencySeeder
    php artisan make:seeder RoleSeeder
    php artisan db:seed
    ```

### 5. **Testing Relationships**

-   Use Laravel Tinker or PHPUnit tests to verify relationships.
-   Example:
    ```php
    $repair = App\Models\EquipmentRepair::first();
    $repair->equipment;
    $repair->technician;
    ```

### 6. **Documentation**

-   Document each table and relationship in `/docs/database/README.md`.

---

## **Summary Table: Major Tables to Create**

| Module                 | Table(s) Example                     |
| ---------------------- | ------------------------------------ |
| Equipment Repair       | equipment_repairs, equipment_parts   |
| Air/Gift Cards         | air_cards, gift_cards                |
| Trips/Charters         | trips, trip_bookings, boats, crew    |
| Courses                | courses, course_sessions, materials  |
| Commissions            | commissions, commission_structures   |
| Inventory/Vendors      | inventory_items, vendors             |
| Loyalty/CRM            | loyalty_programs, loyalty_points     |
| Reporting/Analytics    | reports, audit_logs                  |
| Dive Sites             | dive_sites                           |
| B2B/Commercial         | commercial_accounts, purchase_orders |
| Boat/Compressor Ops    | boats, boat_maintenance, compressors |
| Security Cameras       | security_cameras                     |
| Student Validation     | student_validations                  |
| Digital Forms          | digital_forms                        |
| Photography/Social     | photos                               |
| Advanced Student Mgmt  | student_lifecycles                   |
| HR/Payroll             | hr_staff_profiles, payrolls          |
| Customer Communication | customer_communications, campaigns   |
| API/Auth               | api_tokens                           |

---

## **Next Steps**

1. For each module, create the migration(s) as above.
2. Edit each migration to match the Developer_Guide.md requirements.
3. Run `php artisan migrate`.
4. Seed initial data.
5. Test relationships and constraints.
6. Document your schema.

---

**Need Help?**

-   Reference the [Developer_Guide.md](Developer_Guide.md) for required fields and relationships.
-   Use Laravel's migration documentation for syntax and best practices.
-   Ask in the DiveForge community for schema design advice.

```

### 6. Test Relationships

-   Use Tinker or automated tests to verify relationships and data integrity.

### 7. Document Your Schema

-   Update `/docs/database/README.md` with your table structure and relationships.

---

**Tip:**
For each new feature/module from the Developer Guide, repeat this process:

1. Plan tables and relationships
2. Create migrations
3. Run and test migrations
4. Seed data if needed
5. Document changes

---

**Need Help?**

-   Reference the [Developer_Guide.md](Developer_Guide.md) for required modules and fields.
-   Use Laravel's documentation for migration syntax and best practices.
-   Ask in the DiveForge community for schema design advice.
```
