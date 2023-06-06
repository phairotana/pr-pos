<?php

namespace App\Http\Controllers\Admin\Imports;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection(Collection $rows)
    {
        foreach($rows as $key => $row)
        {
            if($key != 0 && !empty($row[0]) && !empty($row[1]) && !empty($row[7]) && !empty($row[8])){
                $brand = Brand::firstOrCreate([
                    'name' => $row[2]
                ]);
                $category = Category::firstOrCreate([
                    'category_name' => $row[3],
                    'created_by' => \Auth::id()
                ]);
                $unit = ProductUnit::firstOrCreate([
                    'name' => $row[4]
                ]);
                $location = Storage::firstOrCreate([
                    'storage_name' => $row[6],
                    'created_by' => \Auth::id()
                ]);
                Product::firstOrCreate(
                    [
                        'product_code' => $row[0]
                    ],
                    [
                        'product_name' => $row[1],
                        'brand' => $brand->id,
                        'category_id' => $category->id,
                        'unit_id' => $unit->id,
                        'pre_order' => $row[5],
                        'location_id' => $location->id,
                        'cost_price' => is_numeric($row[7]) ? $row[7] : 0,
                        'sell_price' => is_numeric($row[8]) ? $row[8] : 0,
                        'stock_alert' => !empty($row[9]) && is_numeric($row[9]) ? $row[9] : 0,
                        'description' => $row[10],
                    ]
                );
            }
        }
    }
}