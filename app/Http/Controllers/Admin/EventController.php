<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memakai relasi dan pengaturan limit paginasi (10 entri per halaman)
        $events = \App\Models\Event::with('category')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $organizers = \App\Models\Organizer::all();
        return view('admin.events.create', compact('categories', 'organizers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Menerapkan validasi data request dari pengguna
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'organizer_id' => 'nullable|exists:organizers,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'date'         => 'required|date',
            'location'     => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|numeric|min:1',
            'poster'       => 'nullable|image|max:2048' // Maksimal 2MB
        ]);

        if (empty($data['organizer_id'])) {
            $firstOrg = \App\Models\Organizer::first();
            $data['organizer_id'] = $firstOrg ? $firstOrg->id : null;
        }

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $this->uploadPosterFile($request->file('poster'));
        }

        // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model
        \App\Models\Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = \App\Models\Category::all();
        $organizers = \App\Models\Organizer::all();
        return view('admin.events.edit', compact('event', 'categories', 'organizers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'organizer_id' => 'nullable|exists:organizers,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'date'         => 'required|date',
            'location'     => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|numeric|min:1',
            'poster'       => 'nullable|image|max:2048'
        ]);

        if (empty($data['organizer_id'])) {
            $firstOrg = \App\Models\Organizer::first();
            $data['organizer_id'] = $firstOrg ? $firstOrg->id : null;
        }

        if ($request->hasFile('poster')) {
            // Hapus gambar lama jika sebelumnya berupa file lokal storage
            if ($event->poster_path && !\Illuminate\Support\Str::startsWith($event->poster_path, ['http://', 'https://'])) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
            }
            // Upload gambar baru (ke Cloudinary atau lokal)
            $data['poster_path'] = $this->uploadPosterFile($request->file('poster'));
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Hapus berkas poster jika berbentuk file lokal storage
        if ($event->poster_path && !\Illuminate\Support\Str::startsWith($event->poster_path, ['http://', 'https://'])) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
        }

        // Hapus baris data dari database
        $event->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.events.index')->with('success', 'Data Event beserta berkas poster berhasil dihapus.');
    }

    /**
     * Helper to upload poster image to Cloudinary if credentials are configured,
     * otherwise fallback to local public disk storage.
     */
    private function uploadPosterFile($file)
    {
        $cloudinaryUrl = env('CLOUDINARY_URL');
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if ($cloudinaryUrl) {
            $parsed = parse_url($cloudinaryUrl);
            if (isset($parsed['host'])) {
                $cloudName = $parsed['host'];
                $apiKey = $parsed['user'] ?? $apiKey;
                $apiSecret = $parsed['pass'] ?? $apiSecret;
            }
        }

        if ($cloudName) {
            try {
                $params = [];
                if ($uploadPreset) {
                    $params['upload_preset'] = $uploadPreset;
                } elseif ($apiKey && $apiSecret) {
                    $timestamp = time();
                    $signature = sha1("timestamp={$timestamp}" . $apiSecret);
                    $params['api_key'] = $apiKey;
                    $params['timestamp'] = $timestamp;
                    $params['signature'] = $signature;
                }

                if (!empty($params)) {
                    $response = \Illuminate\Support\Facades\Http::attach(
                        'file',
                        file_get_contents($file->getRealPath()),
                        $file->getClientOriginalName()
                    )->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", $params);

                    if ($response->successful() && isset($response->json()['secure_url'])) {
                        return $response->json()['secure_url'];
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Cloudinary Upload Error: ' . $e->getMessage());
            }
        }

        // Fallback to local storage disk if Cloudinary is not configured or upload fails
        return $file->store('posters', 'public');
    }
}
