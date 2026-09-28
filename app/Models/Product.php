<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $fillable = ['name', 'sku', 'category', 'quantity', 'price', 'minimum_stock'];
    protected function casts(): array { return ['quantity' => 'integer', 'minimum_stock' => 'integer', 'price' => 'decimal:2']; }
    public function getLowStockAttribute(): bool { return $this->quantity <= $this->minimum_stock; }
}
