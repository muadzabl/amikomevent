<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Partner;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman Utama / Beranda
     */
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

    /**
     * Halaman Katalog Event
     */
    public function katalog()
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

    /**
     * Halaman Pusat Bantuan & FAQ
     */
    public function bantuan()
    {
        $categories = Category::orderBy('name')->get();
        return view('bantuan', compact('categories'));
    }

    /**
     * Halaman Kontak Support
     */
    public function contact()
    {
        $categories = Category::orderBy('name')->get();
        return view('bantuan', compact('categories'));
    }

    /**
     * Halaman Profil Pengguna
     */
    public function profil()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $categories = Category::orderBy('name')->get();
        $user = auth()->user();

        return view('profil', compact('categories', 'user'));
    }
}