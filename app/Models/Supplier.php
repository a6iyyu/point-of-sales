<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'm_supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];

    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'supplier_id', 'supplier_id');
    }
}