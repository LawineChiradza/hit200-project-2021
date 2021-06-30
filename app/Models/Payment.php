<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Attributes that should be cast to native values
     *
     * @var array
     */
    protected $casts = [
        'listings' => 'integer'
    ];

    /**
     * Eager loaded relations
     *
     * @var array
     */
    protected $with = [
        'order'
    ];

    /**
     * Fields that are not mass assignable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Scope query to show only valid payments
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('listings', '>', 0);
    }

    /**
     * Get the user that owns the payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that owns the payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
