<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'qty', 'category', 'min_stock'];

    public function getStockStatusAttribute(): string
    {
        if ($this->qty === 0) return 'low';
        if ($this->qty <= $this->min_stock) return 'warn';
        return 'ok';
    }

    public function getStockLabelAttribute(): string
    {
        return match($this->stock_status) {
            'low'  => 'Stok Habis',
            'warn' => 'Stok Rendah',
            default => 'Stok Aman',
        };
    }
}
