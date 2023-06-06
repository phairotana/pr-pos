<?php

namespace App\Http\Controllers\TraitUse;

use App\Models\Product;
use Exception;

trait AssociateProduct
{
    protected function fetchProduct()
    {
        $query = (new Product())->newQuery();

        $form = backpack_form_input();

        if (request()->id) {
            return $query->find(request()->id);
        }
        try {
            if (!($form['branch_id'])) {
                return [];
            } else {
                $query = $query->where('branch_id', $form['branch_id']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => "Please select branch id first"], 422);
        }



        if (request()->q) {
            $query->where("product_name", 'LIKE', "%" . request()->q . "%");
        }
        return $query->paginate(10);
    }
}
