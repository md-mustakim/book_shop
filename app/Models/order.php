<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class order extends Model
{
    protected $fillable = [
      'seller_id', 'customer_id', 'product_id', 'product_count', 'product_price', 'total_price', 'getaway', 'address'
    ];
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
