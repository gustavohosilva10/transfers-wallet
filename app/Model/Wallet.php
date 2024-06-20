<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 */
class Wallet extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['user_id', 'balance'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
