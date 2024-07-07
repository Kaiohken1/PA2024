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
            'tag_id' => ['array'],
            'aspect_rating' => ['required', 'numeric'],  
            'location_rating' => ['required', 'numeric']
        ]);

        $basePrice = ($validateData['surface']) * (1+$validateData['location_rating']/5) * (1+$validateData['aspect_rating']/10);
        $priceEstimation = $basePrice * (1+$validateData['roomCount']/100);

        if(isset($validateData['tag_id'])){
            $tags_id = $validateData['tag_id'];
            $tags = Tag::query()
            ->select(['id', 'name', 'valorisation_coeff'])
            ->WhereIn('id', $tags_id)
            ->get();
            foreach($tags as $tag){
                $priceEstimation *= $tag->valorisation_coeff;
            }
        }


        
        

        return view('estimation.result', [
            'priceEstimation' => $priceEstimation,
        ]);

    }
}
