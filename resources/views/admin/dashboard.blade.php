@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100 mt-1">Here's what's happening with your dive shops today.</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-water text-6xl text-blue-300 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow-sm p-6 admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
                    @if($stats['customer_growth'] > 0)
                        <p class="text-sm text-green-600">
                            <i class="fas fa-arrow-up"></i> {{ $stats['customer_growth'] }}% from last month
                        </p>
                    @elseif($stats['customer_growth'] < 0)
                        <p class="text-sm text-red-600">
                            <i class="fas fa-arrow-down"></i> {{ abs($stats['customer_growth']) }}% from last month
                        </p>
                    @else
                        <p class="text-sm text-gray-500">No change from last month</p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Customers -->
        <div class="bg-white rounded-lg shadow-sm p-6 admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Customers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_customers']) }}</p>
                    <p class="text-sm text-gray-500">
                        {{ round(($stats['active_customers'] / max($stats['total_customers'], 1)) * 100, 1) }}% of total
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- New This Month -->
        <div class="bg-white rounded-lg shadow-sm p-6 admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New This Month</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['new_customers_this_month']) }}</p>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Dive Shops -->
        <div class="bg-white rounded-lg shadow-sm p-6 admin-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Shops</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_shops']) }}</p>
                    <p class="text-sm text-gray-500">of {{ $stats['total_shops'] }} total</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-store text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer Growth Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Customer Growth</h3>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span class="text-sm text-gray-600">New Customers</span>
                </div>
            </div>
            <canvas id="customerChart" height="200"></canvas>
        </div>

        <!-- Recent Customers -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Customers</h3>
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentCustomers as $customer)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ $customer->initials }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $customer->total_dives }} dives</p>
                            <p class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No customers yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.create') }}" 
               class="flex items-center justify-center p-4 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-user-plus mr-2"></i>
                Add New Customer
            </a>
            <a href="{{ route('admin.shops.create') }}" 
               class="flex items-center justify-center p-4 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-store mr-2"></i>
                Add New Shop
            </a>
            <a href="#" 
               class="flex items-center justify-center p-4 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-chart-bar mr-2"></i>
                View Reports
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Customer Growth Chart
    const ctx = document.getElementById('customerChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyData['months']),
            datasets: [{
                label: 'New Customers',
                data: @json($monthlyData['customers']),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
