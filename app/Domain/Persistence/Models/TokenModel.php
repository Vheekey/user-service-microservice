<?php

namespace App\Domain\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenModel extends Model
{
    use HasFactory;

    protected $table = 'tokens';

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}
