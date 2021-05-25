<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Log
 * @package App\Models
 */
class Log extends Model
{
    /**
     * @var string
     */
    protected $table = 'logs';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'type',
        'total',
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
    public function cashRegister(): BelongsToMany
    {
        return $this->belongsToMany(CashRegister::class)->withPivot('cash_register_total');
    }
}
