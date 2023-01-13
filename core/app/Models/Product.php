<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Searchable;
    protected $guarded = [];
    protected $casts   = [
        'tag'              => 'array',
        'category_details' => 'array',
        'screenshots'      => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    public function orders()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function reviewHistories()
    {
        return $this->hasMany(ReviewHistory::class);
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

        if ($this->status == Status::PRODUCT_PENDING) {
            $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
        } elseif ($this->status == Status::PRODUCT_APPROVE) {
            $html = '<span><span class="badge badge--success">' . trans('Approved') . '</span>';
        } elseif ($this->status == Status::PRODUCT_SOFT_REJECT) {
            $html = '<span class="badge badge--danger">' . trans('Soft Rejected') . '</span>';
        } elseif ($this->status == Status::PRODUCT_HARD_REJECT) {
            $html = '<span><span class="badge badge--danger">' . trans('Hard Rejected') . '</span>';
        } elseif ($this->status == Status::PRODUCT_DELETE) {
            $html = '<span><span class="badge badge--dark">' . trans('Deleted') . '</span>';
        } elseif ($this->status = Status::PRODUCT_RESUBMIT) {
            $html = '<span><span class="badge badge--primary">' . trans('Resubmitted') . '</span>';
        }

        return $html;
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::PRODUCT_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::PRODUCT_APPROVE);
    }
    public function scopeAvailable($query)
    {
        return $query->approved()
            ->whereHas('user', function ($user) {
                $user->active();
            })
            ->whereHas('category', function ($category) {
                $category->active();
            })
            ->where(function ($product) {
                $product->where('subcategory_id', 0)->orWhereHas('subcategory', function ($subcategory) {
                    $subcategory->active();
                });
            });
    }
    public function scopeHidden($query)
    {
        return $query->whereNot(function ($product) {
            $product->available();
        })->whereNot(function ($product) {
            $product->hardRejected();
        })->whereNot(function ($product) {
            $product->deleted();
        });
    }

    public function scopeSoftRejected($query)
    {
        return $query->where('status', Status::PRODUCT_SOFT_REJECT);
    }
    public function scopeHardRejected($query)
    {
        return $query->where('status', Status::PRODUCT_HARD_REJECT);
    }
    public function scopeDeleted($query)
    {
        return $query->where('status', Status::PRODUCT_DELETE);
    }
    public function scopeResubmitted($query)
    {
        return $query->where('status', Status::PRODUCT_RESUBMIT);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', Status::YES);
    }
    public function scopeReviewedByMe($query)
    {
        return $query->where('reviewer_id', auth()->guard('reviewer')->id());
    }
}
