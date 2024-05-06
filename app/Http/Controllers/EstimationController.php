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
        
    }
}
