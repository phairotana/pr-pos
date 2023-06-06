<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OfferResource;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = (new Offer())->newQuery();
        $query->orderBy('id', 'DESC');
        $offer = $query->paginate(10);
        return OfferResource::collection($offer);
    }
}
