<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('products', 'code')->ignore($productId)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_unit' => ['required', 'in:tangkai,item'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],

            // Validate prices
            'prices' => ['required', 'array'],
            'prices.*.type' => ['required', 'string', Rule::in($this->getPriceTypes())],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
            'prices.*.unit_equivalent' => ['required', 'integer', 'min:1'],
            'prices.*.is_default' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'prices.*.price.required' => 'Harga harus diisi untuk setiap jenis',
            'prices.*.price.numeric' => 'Harga harus berupa angka',
            'prices.*.price.min' => 'Harga tidak boleh negatif',
            'prices.*.unit_equivalent.required' => 'Unit equivalent harus diisi',
            'prices.*.unit_equivalent.integer' => 'Unit equivalent harus berupa angka bulat',
            'prices.*.unit_equivalent.min' => 'Unit equivalent minimal 1',
        ];
    }

    public static function getPriceTypes(): array
    {
        return [
            'per_tangkai',
            'ikat_5',
            'ikat_10',
            'ikat_20',
            'reseller',
            'normal',
            'promo'
        ];
    }

    public static function getDefaultUnitEquivalent(string $type): int
    {
        return match ($type) {
            'per_tangkai' => 1,
            'ikat_5' => 5,
            'ikat_10' => 10,
            'ikat_20' => 20,
            default => 1,
        };
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'prices' => collect($this->prices ?? [])->map(function ($price) {
                return array_merge($price, [
                    'is_default' => isset($price['is_default']) && $price['is_default'] == $price['type']
                ]);
            })->toArray()
        ]);
    }
}
