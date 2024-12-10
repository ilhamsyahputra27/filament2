<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'author_id',
        'status',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if (empty($post->slug) || $post->isDirty('title')) {
                $baseSlug = Str::slug($post->title);
                $slug = $baseSlug;
                $count = 1;

                while (self::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $post->slug = $slug;
            }
        });

        static::creating(function ($post) {
            if (Auth::check()) {
                $post->author_id = Auth::id();
            }
        });
    }

    // Relasi ke kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke pengguna (author)
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
