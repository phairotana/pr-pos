<?php

namespace App\Repositories;

use App\Models\Stock;

class StockPurchaseRepository extends BaseRepository
{

    public function getFieldsSearchable()
    {
        return [];
    }
    public function model()
    {
        return Stock::class;
    }
    public function generateStockCode()
    {
        return 'ST-' . time();
    }
    public function removeSpecificStock($prod)
    {
        $product = $this->model->firstWhere('product_id', $prod->id);
        if ($product) {
            $product->quantity -= $prod->qty;
            // if ($product->quantity < 0) {
            //     $product->quantity = 0;
            // }
            $product->sale_out -= $prod->qty;
            $product->save();
        }else{
            $this->insertInvoiceOnNullStock($prod,null ,['quantity' => -$prod->qty]);
        }
    }
    public function removeStock($productArr)
    {
        foreach ($productArr as $prod) {
            $this->removeSpecificStock($prod);
        }
    }
    public function insertSpecificStock($prod,  $purchase_id = null , $mergeInsertRequest = [])
    {
        $product = $this->model->firstWhere('product_code', $prod->product_code);
        if ($product) {
            $product->quantity += $prod->qty;
            $product->purchase += $prod->qty;
            $product->save();
        } else {
            $arrayInsertProduct = [
                'product_id' => $prod->id,
                'stock_code' => $this->generateStockCode(),
                'product_code' => $prod->product_code,
                'quantity' => $prod->qty,
                'purchase' => $prod->qty,
                'branch_id' => '1',
                'purchase_id' => $purchase_id,
                'sale_out' => 0,
                'description' => $prod->note ?? '',
            ];
            $arrayInsertProduct = array_merge($arrayInsertProduct, $mergeInsertRequest);
            $this->model->create($arrayInsertProduct);
        }
    }
    public function insertInvoiceOnNullStock($prod,  $purchase_id = null , $mergeInsertRequest = [])
    {
        $product = $this->model->firstWhere('product_code', $prod->product_code);
        if ($product) {
            $product->quantity += $prod->qty;
            $product->purchase += $prod->qty;

            $product->save();
        } else {
            $arrayInsertProduct = [
                'product_id' => $prod->id,
                'stock_code' => $this->generateStockCode(),
                'product_code' => $prod->product_code,
                'quantity' => $prod->qty,
                'branch_id' => '1',
                'purchase_id' => $purchase_id,
                'sale_out' => 0,
                'description' => $prod->note ?? '',
            ];
            $arrayInsertProduct = array_merge($arrayInsertProduct, $mergeInsertRequest);
            $this->model->create($arrayInsertProduct);
        }
    }

    public function insertStock($productArr, $branch_id = null, $purchase_id = null)
    {
        foreach ($productArr as $prod) {
            $this->insertSpecificStock($prod,  $purchase_id ,$branch_id ?  ['branch_id'=>  $branch_id] : [],);
        }
    }
}
