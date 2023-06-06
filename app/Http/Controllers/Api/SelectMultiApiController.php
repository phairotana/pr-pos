<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Exception;
use Illuminate\Http\Request;

class SelectMultiApiController extends Controller
{
    //
    public function indexCategories(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = Category::where('category_name', 'LIKE', '%' . $search_term . '%')->paginate(10);
        } else {
            $results = Category::paginate(10);
        }

        return $results;
    }
    public function productSearch(Request $request)
    {
        $query = new Product();
        $hasShown = request()->has('show_all');
        $hasRefId = request()->get('ref_id');
        $model = request()->get('model');
        $search_term = request()->search_term;

        if ($search_term) {
            $query =  $query
                ->where('product_name', 'LIKE', "%" . $search_term . "%")
                ->orWhere('product_code', 'LIKE', "%" . $search_term . "%");


            /* remove when stock has none and pre order no */
            if (!$hasShown) {
                $query = $query->get()->reject(function ($val) {
                    return $val->pre_order == "No" && $val->StockQuantity <= 0;
                });
            } else {
                $query = $query->get();
            }

            if ($hasRefId) {
                /* query from invoice or purchase detail  */
                if ($model == "Invoice") {
                    $query = InvoiceDetail::where('product_name', 'LIKE', "%" . $search_term . "%")
                        ->orWhere('product_code', 'LIKE', "%" . $search_term . "%");
                    $query = $query->get()->where('invoice_id', $hasRefId);
                } else if ($model == "Purchase") {
                    $query = PurchaseDetail::where('product_name', 'LIKE', "%" . $search_term . "%")
                        ->orWhere('product_code', 'LIKE', "%" . $search_term . "%");
                    $query = $query->get()->where('purchase_id', $hasRefId);
                }
                $query = $query->map(function($el) {
                    $el->id = $el->product_id;
                    return $el;
                });
                /* convert invoice or purchase model to collection of product */
                $query = Product::hydrate($query->toArray());
            }

            return ProductResource::collection($query->take(5));
        } else {
            return ['data' => []];
        }
    }

    public function showProduct($id)
    {
        return response()->json(['data' => Product::firstWhere('id', $id)]);
    }

    public function showCategory($id)
    {
        return Category::find($id);
    }
}
