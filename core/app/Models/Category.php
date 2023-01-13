<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory, Searchable, GlobalStatus;

    protected $guarded = [];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class)->active();
    }

    public function categoryFeature()
    {
        return $this->hasMany(CategoryFeature::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->available();
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->badgeData(),
        );
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == Status::ENABLE) {
            $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
        } else {
            $html = '<span class="badge badge--danger">' . trans('Inactive') . '</span>';
        }
        return $html;
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::CATEGORY_ACTIVE)->whereHas('subcategories', function ($q) {
            $q->active();
        });
    }
    public function scopeFeatured($query)
    {
        return $query->where('featured', Status::YES);
    }
}
