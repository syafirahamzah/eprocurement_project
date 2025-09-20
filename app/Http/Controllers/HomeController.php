<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ini akan membuka file resources/views/home.blade.php
        return view('home');
    }
}
