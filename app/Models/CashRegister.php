<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class CashFlow
 * @package App\Models
 */
class CashRegister extends Model
{
    /**
     * @var string
     */
    protected $table = 'cash_register';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'type',
        'denomination',
        'quantity',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function logs(): BelongsToMany
    {
        return $this->belongsToMany(Log::class)->withPivot('cash_register_total');
    }
}
