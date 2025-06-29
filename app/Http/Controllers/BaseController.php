<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function jsonResponse($messaje = 'success', $data = [], $status = 'success', $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $messaje,
            'data' => $data
        ], $code);
    }
}
