<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Validate and store the product
    }
}
