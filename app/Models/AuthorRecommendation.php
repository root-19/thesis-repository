<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommender_id',
        'recommended_user_id',
        'recommended_name',
        'recommended_email',
        'reason',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function recommender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recommender_id');
    }

    public function recommendedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recommended_user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
