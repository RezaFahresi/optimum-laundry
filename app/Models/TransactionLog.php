<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $table = 'transaction_logs';

    protected $fillable = [
        'transaction_id',
        'changed_by',
        'old_status',
        'new_status',
        'note',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Relasi ke Transaction (opsional)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
