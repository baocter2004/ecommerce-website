<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'option',
        'price_modifier'
    ];

    public function variant() {
        return $this->belongsTo(Variant::class);
    }
    public function items() {
        return $this->hasMany(CartItem::class);
    }
}
