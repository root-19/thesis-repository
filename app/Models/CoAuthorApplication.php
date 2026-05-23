<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CoAuthorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'thesis_id',
        'title',
        'description',
        'thesis_date',
        'pdf_file_path',
        'keywords',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'thesis_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coAuthors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'co_author_application_user', 'co_author_application_id', 'user_id');
    }
}
