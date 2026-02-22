<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MpesaCallbackController extends Controller
{
    public function callback(Request $request)
    {
        // M-Pesa callback handling - to be implemented
        return response()->json(['status' => 'received']);
    }

    public function timeout(Request $request)
    {
        return response()->json(['status' => 'timeout received']);
    }
}
