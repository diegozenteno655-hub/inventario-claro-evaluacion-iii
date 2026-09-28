<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('products', function (Blueprint $table) {
        $table->id(); $table->string('name', 120); $table->string('sku', 40)->unique(); $table->string('category', 80);
        $table->unsignedInteger('quantity'); $table->decimal('price', 10, 2); $table->unsignedInteger('minimum_stock')->default(5);
        $table->timestamps(); $table->index(['quantity', 'minimum_stock']);
    }); }
    public function down(): void { Schema::dropIfExists('products'); }
};
