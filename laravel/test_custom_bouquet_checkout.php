<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Simulate cart with custom bouquet
    $cart = [
        'custom_bouquet_35' => [
            'id' => 'custom_bouquet_35',
            'name' => 'Custom Bouquet (Salidago 1 tangkai, Matahari 1 tangkai, Aster Merah Regen 2 tangkai)',
            'price' => 209000.0,
            'qty' => 1,
            'price_type' => 'Custom',
            'type' => 'custom_bouquet',
            'custom_bouquet_id' => 35,
            'image' => 'custom-bouquets/eEhmYiVEo3IzMAQ7s4tDFuZ4IWMfPUse6kfYFRgU.jpg',
            'components_summary' => ['Salidago 1 tangkai', 'Matahari 1 tangkai', 'Aster Merah Regen 2 tangkai']
        ]
    ];

    // Simulate validated request data
    $validated = [
        'customer_name' => 'Test Customer',
        'wa_number' => '08123456789',
        'pickup_date' => '2025-08-06',
        'pickup_time' => '10:00',
        'delivery_method' => 'pickup',
        'destination' => 'Toko',
        'notes' => 'Test order',
        'custom_instructions' => 'Test custom instructions',
    ];

    echo "Starting checkout test...\n";

    DB::beginTransaction();

    $publicCode = bin2hex(random_bytes(8));
    echo "Generated public code: $publicCode\n";

    // Create order
    $order = \App\Models\PublicOrder::create([
        'public_code' => $publicCode,
        'customer_name' => $validated['customer_name'],
        'pickup_date' => $validated['pickup_date'],
        'pickup_time' => $validated['pickup_time'],
        'delivery_method' => $validated['delivery_method'],
        'destination' => $validated['destination'],
        'notes' => $validated['notes'],
        'wa_number' => $validated['wa_number'],
        'status' => 'pending',
        'payment_status' => 'waiting_confirmation',
    ]);

    echo "Order created with ID: " . $order->id . "\n";

    foreach ($cart as $cartKey => $item) {
        // Handle different product types
        $productId = $item['id'];

        if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
            // For custom bouquet, set product_id to null since we use custom_bouquet_id
            $productId = null;
        }

        $orderItemData = [
            'product_id' => $productId,
            'product_name' => $item['name'],
            'price_type' => $item['price_type'] ?? 'default',
            'unit_equivalent' => 1,
            'quantity' => $item['qty'],
            'price' => $item['price'] ?? 0,
            'item_type' => $item['type'] ?? 'product',
        ];

        // For custom bouquet, add the custom_bouquet_id
        if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
            $orderItemData['custom_bouquet_id'] = $item['custom_bouquet_id'] ?? null;
            // Add custom instructions if provided
            if (!empty($validated['custom_instructions'])) {
                $orderItemData['custom_instructions'] = $validated['custom_instructions'];
            }
            // Add reference image if exists
            if (!empty($item['image'])) {
                $orderItemData['reference_image'] = $item['image'];
            }
        }

        echo "Creating order item with data:\n";
        print_r($orderItemData);

        $orderItem = $order->items()->create($orderItemData);
        echo "Order item created with ID: " . $orderItem->id . "\n";
    }

    DB::commit();
    echo "Transaction committed successfully!\n";
    echo "Order saved with public code: $publicCode\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
