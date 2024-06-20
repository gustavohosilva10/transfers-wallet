<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 */
class Transaction extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     */
     protected array $fillable = ['payer_id', 'payee_id', 'amount'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}
