<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order History Details') }} - {{ $history->order_number }}
            </h2>
            <a href="{{ route('order-histories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to History
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Order Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Order Number:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $history->order_number }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Customer:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">
                                        {{ $history->customer_name }}<br>
                                        {{ $history->customer_phone }}<br>
                                        {{ $history->customer_email }}
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $history->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $history->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $history->status === 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $history->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($history->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Original Created Date:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $history->original_created_at->format('d M Y H:i:s') }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Archived Date:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $history->archived_at->format('d M Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Delivery Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Pickup Date:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $history->pickup_date->format('d M Y H:i') }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Delivery Method:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ ucfirst($history->delivery_method) }}</dd>
                                </div>
                                @if($history->delivery_method !== 'pickup')
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Delivery Address:</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $history->delivery_address }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Order Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($history->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item['product_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item['quantity'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Subtotal:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($history->total - $history->delivery_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($history->delivery_fee > 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Delivery Fee:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($history->delivery_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="font-bold">
                                        <td colspan="3" class="px-6 py-4 text-right">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($history->total, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($history->down_payment > 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Down Payment:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($history->down_payment, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Remaining Payment:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($history->total - $history->down_payment, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
