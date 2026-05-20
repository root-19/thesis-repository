<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'thesis_date',
        'department',
        'author',
        'pdf_file_path',
        'keywords',
    ];

    protected $casts = [
        'thesis_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function coAuthors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'thesis_co_author', 'thesis_id', 'user_id');
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_theses', 'thesis_id', 'user_id')->withTimestamps();
    }
}
