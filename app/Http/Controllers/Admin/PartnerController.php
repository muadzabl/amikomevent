<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Soal 2 & 3: Tampilkan daftar partner + fitur pencarian
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $partners = Partner::when($search, function ($query, $search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.partners.index', compact('partners', 'search'));
    }

    /**
     * Soal 2: Tampilkan form tambah partner
     */
    public function create()
    {
        return view('admin.partners.create');
    }

    /**
     * Soal 2: Simpan partner baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'logo_url'  => 'nullable|string|max:2048',
            'logo_file' => 'nullable|image|max:2048',
        ]);

        $logoUrl = null;

        if ($request->hasFile('logo_file')) {
            $logoUrl = $this->uploadLogoFile($request->file('logo_file'));
        } elseif ($request->filled('logo_url')) {
            $logoUrl = trim($request->logo_url);
            if (!\Illuminate\Support\Str::startsWith($logoUrl, ['http://', 'https://'])) {
                $logoUrl = 'https://' . $logoUrl;
            }
        }

        Partner::create([
            'name'     => $request->name,
            'logo_url' => $logoUrl,
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan!');
    }

    /**
     * Soal 2: Tampilkan form edit partner
     */
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Soal 2: Update data partner
     */
    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'logo_url'  => 'nullable|string|max:2048',
            'logo_file' => 'nullable|image|max:2048',
        ]);

        $logoUrl = $partner->logo_url;

        if ($request->hasFile('logo_file')) {
            $logoUrl = $this->uploadLogoFile($request->file('logo_file'));
        } elseif ($request->filled('logo_url')) {
            $logoUrl = trim($request->logo_url);
            if (!\Illuminate\Support\Str::startsWith($logoUrl, ['http://', 'https://'])) {
                $logoUrl = 'https://' . $logoUrl;
            }
        }

        $partner->update([
            'name'     => $request->name,
            'logo_url' => $logoUrl,
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil diperbarui!');
    }

    /**
     * Soal 2: Hapus partner
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus!');
    }

    /**
     * Helper to upload partner logo image to Cloudinary if credentials are configured,
     * otherwise fallback to local public disk storage.
     */
    private function uploadLogoFile($file)
    {
        $cloudinaryUrl = config('services.cloudinary.url')
            ?? env('CLOUDINARY_URL')
            ?? $_ENV['CLOUDINARY_URL']
            ?? $_SERVER['CLOUDINARY_URL']
            ?? getenv('CLOUDINARY_URL')
            ?? null;

        $cloudName = config('services.cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME') ?? getenv('CLOUDINARY_CLOUD_NAME');
        $uploadPreset = config('services.cloudinary.upload_preset') ?? env('CLOUDINARY_UPLOAD_PRESET') ?? getenv('CLOUDINARY_UPLOAD_PRESET');
        $apiKey = config('services.cloudinary.api_key') ?? env('CLOUDINARY_API_KEY') ?? getenv('CLOUDINARY_API_KEY');
        $apiSecret = config('services.cloudinary.api_secret') ?? env('CLOUDINARY_API_SECRET') ?? getenv('CLOUDINARY_API_SECRET');

        if ($cloudinaryUrl) {
            $parsed = parse_url($cloudinaryUrl);
            if (isset($parsed['host'])) {
                $cloudName = $parsed['host'];
                $apiKey = isset($parsed['user']) ? rawurldecode($parsed['user']) : $apiKey;
                $apiSecret = isset($parsed['pass']) ? rawurldecode($parsed['pass']) : $apiSecret;
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
                \Illuminate\Support\Facades\Log::error('Cloudinary Partner Upload Error: ' . $e->getMessage());
            }
        }

        return $file->store('partners', 'public');
    }
}