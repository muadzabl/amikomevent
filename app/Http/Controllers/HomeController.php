<?php

namespace App\Http\Controllers;

use App\Models\Category;  // ← pastikan ada ini
use App\Models\Partner;   // ← pastikan ada ini
use App\Models\Event;     // ← pastikan ada ini

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $partners   = Partner::orderBy('name')->get();

        $selectedCategory = request('category');

        $events = Event::with('category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('category_id', $selectedCategory);
            })
            ->orderBy('date', 'asc')
            ->get();

        return view('welcome', compact('partners', 'categories', 'events', 'selectedCategory'));
    }
}