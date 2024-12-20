<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'category_image'
    ];

    public $attributes = [
        'is_active' => 0
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
