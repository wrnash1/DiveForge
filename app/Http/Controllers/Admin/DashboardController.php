<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DiveShop;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get basic statistics
        $stats = $this->getDashboardStats();
        
        // Get recent activities
        $recentCustomers = $this->getRecentCustomers();
        
        // Get monthly data for charts
        $monthlyData = $this->getMonthlyData();
        
        return view('admin.dashboard', compact('stats', 'recentCustomers', 'monthlyData'));
    }

    private function getDashboardStats(): array
    {
        $totalCustomers = User::customers()->count();
        $activeCustomers = User::customers()->active()->count();
        $newCustomersThisMonth = User::customers()
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        
        $totalShops = DiveShop::count();
        $activeShops = DiveShop::where('is_active', true)->count();

        // Calculate growth percentages
        $lastMonthCustomers = User::customers()
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->count();
        
        $customerGrowth = $lastMonthCustomers > 0 
            ? round((($newCustomersThisMonth - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1)
            : ($newCustomersThisMonth > 0 ? 100 : 0);

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'new_customers_this_month' => $newCustomersThisMonth,
            'customer_growth' => $customerGrowth,
            'total_shops' => $totalShops,
            'active_shops' => $activeShops,
            'total_admins' => User::admins()->count(),
        ];
    }

    private function getRecentCustomers()
    {
        return User::customers()
            ->latest()
            ->take(5)
            ->select('id', 'name', 'email', 'created_at', 'total_dives', 'certification_level')
            ->get();
    }

    private function getMonthlyData(): array
    {
        $months = [];
        $customerData = [];
        
        // Get last 6 months of data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $customerCount = User::customers()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $customerData[] = $customerCount;
        }

        return [
            'months' => $months,
            'customers' => $customerData,
        ];
    }
}
