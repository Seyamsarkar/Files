<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'deposit_id');
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

        if ($this->status == Status::SELL_APPROVED) {
            $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
        } elseif ($this->status == Status::SELL_REJECTED) {
            $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
        } elseif ($this->status == Status::SELL_PENDING) {
            $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
        } elseif ($this->status == Status::SELL_INITIATE) {
            $html = '<span class="badge badge--dark">' . trans('Initiated') . '</span>';
        }

        return $html;
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'sell_id')->latestOfMany();
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::SELL_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::SELL_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::SELL_REJECTED);
    }
}
