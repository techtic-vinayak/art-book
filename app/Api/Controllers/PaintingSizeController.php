<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaintingSize;

class PaintingSizeController extends Controller
{
    
    public function index()
    {
        $paintingSize = PaintingSize::get();
        return response()->json([
            'status_code' => 200,
            'data'        => $paintingSize,
		]);
        
    }
    
}
