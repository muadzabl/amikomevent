<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Partner;

class HomeController extends Controller
{
    /**
     * Soal 4: Tampilkan homepage publik dengan data Partner & Category
     */
   

public function index()
{
    $partners   = Partner::orderBy('name')->get();
    $categories = Category::orderBy('name')->get();

    return view('welcome', compact('partners', 'categories'));
}
}