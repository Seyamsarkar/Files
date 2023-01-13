<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionLog extends Model {
    use HasFactory;

    protected $guarded = [];
    protected $table   = "commission_logs";
    public function user() {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }
    public function byWho() {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }
}
