<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class EstimationController extends Controller
{
    public function index(){
        $tags = Tag::all();    
        return view('estimation.index',[
            'tags' => $tags
        ]);
    }

    public function result(Request $request)
    {
        $validateData = $request->validate([
            'surface' => ['required', 'numeric'],   
            'guestCount' => ['required', 'numeric'],   
            'roomCount' => ['required', 'numeric'],
            'tag_id' => ['array']
        ]);

        $basePrice = ($validateData['surface']) * 2;
        $priceEstimation = $basePrice * (1+$validateData['roomCount']/100);
        dd($priceEstimation);
    }
}
