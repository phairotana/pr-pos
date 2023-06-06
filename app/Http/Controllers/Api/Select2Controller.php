<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\Request;

class Select2Controller extends Controller
{
    //
    public function indexBranch(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Branch::where('branch_name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Branch::paginate(10);
        }

        return $results;
    }

    public function indexCustomer(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term)
        {
            $results = Customer::where('customer_name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Customer::paginate(10);
        }

        return $results;
    }
}
