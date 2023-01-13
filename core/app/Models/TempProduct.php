<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempProduct extends Model {
    use HasFactory;
    protected $guarded = [];
    protected $casts   = [
        'tag'              => 'array',
        'category_details' => 'array',
        'screenshots'      => 'array',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subcategory() {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function statusBadge(): Attribute {
        return new Attribute(
            get:fn() => $this->badgeData(),
        );
    }

    public function badgeData() {
        $html = '';

        if ($this->product->update_status == Status::PRODUCT_UPDATE_PENDING) {
            $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
        } elseif ($this->product->update_status == Status::PRODUCT_UPDATE_APPROVED) {
            $html = '<span><span class="badge badge--success">' . trans('Approved') . '</span>';
        } elseif ($this->product->update_status == Status::PRODUCT_UPDATE_REJECTED) {
            $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
        }

        return $html;
    }

}
