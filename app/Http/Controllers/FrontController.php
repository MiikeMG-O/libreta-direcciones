<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{
    public function index()
    {
        return response()->file(public_path('front/index.html'));
    }
}
