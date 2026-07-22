<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //  RELASI KE MODEL REVIEW 
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    //  FUNGSI MENGHITUNG RATA-RATA RATING 
    public function averageRating()
    {
        // Menghitung rata-rata kolom rating, dibulatkan 1 angka di belakang koma
        return number_format($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}