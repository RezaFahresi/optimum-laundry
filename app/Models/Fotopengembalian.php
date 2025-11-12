<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fotopengembalian extends Model
{
    use HasFactory;

    protected $table = 'fotopengembalian';
    public $timestamps = false;

    protected $guarded = [];

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
