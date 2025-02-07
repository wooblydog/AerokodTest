<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'external_id',
        'post_id',
        'is_active',
        'name',
        'email',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
