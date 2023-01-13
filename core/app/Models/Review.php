<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    use HasFactory, Searchable;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function sell() {
        return $this->belongsTo(Sell::class, 'sell_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeReported($query) {
        return $query->where('status', Status::REVIEW_REPORTED);
    }
}
