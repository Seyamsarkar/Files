<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    use HasFactory, Searchable;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product() {

        return $this->belongsTo(Product::class, 'product_id');
    }
    public function replies() {

        return $this->hasMany(Reply::class, 'comment_id');
    }
}
