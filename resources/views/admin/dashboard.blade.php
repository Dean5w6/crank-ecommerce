<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4" novalidate>
                    <div class="flex-1">
                        <x-input-label for="start_date" :value="__('Start Date')" />
                        <x-text-input id="start_date" name="start_date" type="date" class="block mt-1 w-full" :value="$startDate" />
                    </div>
                    <div class="flex-1">
                        <x-input-label for="end_date" :value="__('End Date')" />
                        <x-text-input id="end_date" name="end_date" type="date" class="block mt-1 w-full" :value="$endDate" />
                    </div>
                    <div>
                        <x-primary-button>
                            {{ __('Filter Sales') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
 
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8"> 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Revenue</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">₱{{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Transactions</div>
                    <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $stats['transactions'] }}</div>
                </div>
 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['users'] }}</div>
                </div>
 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Products</div>
                    <div class="mt-2 text-3xl font-bold text-orange-600">{{ $stats['products'] }}</div>
                </div>
            </div>
 
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8"> 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ date('Y') }} Yearly Sales (Monthly)</h3>
                    <div class="h-64">
                        <canvas id="yearlySalesChart"></canvas>
                    </div>
                </div>
 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Sales from {{ $startDate }} to {{ $endDate }}</h3>
                    <div class="h-64">
                        <canvas id="dateRangeSalesChart"></canvas>
                    </div>
                </div>
            </div>
 
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"> 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Sales by Product</h3>
                    <div class="h-64">
                        <canvas id="productPieChart"></canvas>
                    </div>
                </div>
 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.products.index') }}" class="block text-indigo-600 hover:underline">Manage Products</a>
                                <a href="{{ route('admin.categories.index') }}" class="block text-indigo-600 hover:underline">Manage Categories</a>
                                <a href="{{ route('admin.brands.index') }}" class="block text-indigo-600 hover:underline">Manage Brands</a>
                                <a href="{{ route('admin.transactions.index') }}" class="block text-indigo-600 hover:underline">View Transactions</a>
                                <a href="{{ route('admin.reports.index') }}" class="block text-indigo-600 hover:underline">Business Reports</a>
                                <a href="{{ route('admin.users.index') }}" class="block text-indigo-600 hover:underline">Manage Users</a>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">System Summary</h3>
                            <p class="text-gray-600">Categories: <span class="font-bold">{{ $stats['categories'] }}</span></p>
                            <p class="text-gray-600">Brands: <span class="font-bold">{{ $stats['brands'] }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script> 
        const yearlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const yearlyData = Array(12).fill(0);
        @foreach($yearlySales as $sale)
            yearlyData[{{ $sale->month - 1 }}] = {{ $sale->total }};
        @endforeach

        new Chart(document.getElementById('yearlySalesChart'), {
            type: 'line',
            data: {
                labels: yearlyLabels,
                datasets: [{
                    label: 'Revenue (₱)',
                    data: yearlyData,
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
 
        const dateRangeLabels = {!! json_encode($dateRangeSales->pluck('date')) !!};
        const dateRangeData = {!! json_encode($dateRangeSales->pluck('total')) !!};

        new Chart(document.getElementById('dateRangeSalesChart'), {
            type: 'bar',
            data: {
                labels: dateRangeLabels,
                datasets: [{
                    label: 'Revenue (₱)',
                    data: dateRangeData,
                    backgroundColor: 'rgb(99, 102, 241)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
 
        const productLabels = {!! json_encode($productSales->pluck('product.name')) !!};
        const productData = {!! json_encode($productSales->pluck('total')) !!};

        new Chart(document.getElementById('productPieChart'), {
            type: 'pie',
            data: {
                labels: productLabels,
                datasets: [{
                    data: productData,
                    backgroundColor: [
                        '#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
                        '#EC4899', '#3B82F6', '#06B6D4', '#6366F1', '#F43F5E'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
