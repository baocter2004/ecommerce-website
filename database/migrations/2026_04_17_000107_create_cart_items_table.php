<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Product::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Variant::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(VariantOption::class)->nullable()->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price',15,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
