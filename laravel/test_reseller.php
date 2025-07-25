<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Carbon\Carbon;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "=== DEBUGGING CUSTOMER MODEL ISSUE ===\n\n";
    
    $wa_number = '082182285912';
    
    echo "1. Using DB Query Builder (with deleted_at):\n";
    $dbCustomer = DB::table('customers')->where('phone', $wa_number)->first();
    echo "  - Found: " . ($dbCustomer ? "YES (ID: {$dbCustomer->id}, deleted_at: " . ($dbCustomer->deleted_at ?? 'NULL') . ")" : "NO") . "\n";
    
    echo "\n2. Using Eloquent Model (includes soft deleted):\n";
    $modelCustomer = Customer::where('phone', $wa_number)->first();
    echo "  - Found (active): " . ($modelCustomer ? "YES (ID: {$modelCustomer->id})" : "NO") . "\n";
    
    $modelCustomerWithTrashed = Customer::withTrashed()->where('phone', $wa_number)->first();
    echo "  - Found (with trashed): " . ($modelCustomerWithTrashed ? "YES (ID: {$modelCustomerWithTrashed->id}, deleted_at: " . ($modelCustomerWithTrashed->deleted_at ?? 'NULL') . ")" : "NO") . "\n";
    
    echo "\n3. Model table check:\n";
    $customerModel = new Customer();
    echo "  - Table name: " . $customerModel->getTable() . "\n";
    echo "  - Connection: " . $customerModel->getConnectionName() . "\n";
    
    echo "\n4. All customers via Model:\n";
    $allCustomers = Customer::all();
    echo "  - Count: " . $allCustomers->count() . "\n";
    foreach ($allCustomers as $customer) {
        echo "    - ID: {$customer->id}, Phone: '{$customer->phone}', Name: {$customer->name}\n";
    }
    
    echo "\n5. Test create customer:\n";
    try {
        $testCustomer = Customer::create([
            'phone' => '081999888777',
            'name' => 'Test Customer',
            'is_reseller' => false
        ]);
        echo "  - Created: YES (ID: {$testCustomer->id})\n";
        
        // Delete test customer
        $testCustomer->delete();
        echo "  - Deleted: YES\n";
    } catch (\Exception $e) {
        echo "  - Error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
