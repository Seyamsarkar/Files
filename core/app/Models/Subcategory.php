<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model {

    use HasFactory, GlobalStatus, Searchable;
    protected $guarded = [];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product() {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function statusBadge(): Attribute {
        return new Attribute(
            get:fn() => $this->badgeData(),
        );
    }

    public function badgeData() {
        $html = '';
        if ($this->status == Status::ENABLE) {
            $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
        } else {
            $html = '<span class="badge badge--danger">' . trans('Inactive') . '</span>';
        }
        return $html;
    }

    public function scopeActive($query) {
        return $query->where('status', Status::SUBCATEGORY_ACTIVE);
    }
}
