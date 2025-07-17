<?php

namespace App\Http\Controllers;

use App\Models\Bouquet;
use Illuminate\Http\Request;

class PublicBouquetController extends Controller
{
    public function detail($id)
    {
        $bouquet = Bouquet::with(['category', 'components.product', 'sizes', 'prices'])
            ->findOrFail($id);

        return view('public.bouquet.detail', [
            'bouquet' => $bouquet
        ]);
    }
}
