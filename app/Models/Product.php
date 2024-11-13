<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        "product_name",
        "price",
        "product_image",
        "description",
        "short_description",
        "is_active",
        "category_id"
    ];

    public $attributes = [
        'is_active' => 0
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function variants() {
        return $this->hasMany(Variant::class);
    }
}
