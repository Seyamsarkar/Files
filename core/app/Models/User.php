<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Searchable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'ver_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime',
    ];

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('status', Status::PRODUCT_APPROVE);
    }

    public function tempProducts()
    {
        return $this->hasMany(TempProduct::class)->where('status', Status::TEMP_PRODUCT_RESUBMIT);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function buy()
    {
        return $this->hasMany(Sell::class);
    }

    public function sell()
    {
        return $this->hasMany(Sell::class, 'author_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'author_id');
    }

    public function myCart()
    {
        return $this->hasMany(Cart::class, 'order_number');
    }

    public function cartBuy()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function referral()
    {
        return $this->belongsTo(User::class, 'ref_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function allReferrals()
    {
        return $this->referrals()->with('referral');
    }
    public function ips()
    {
        return $this->hasMany(ApiIp::class);
    }

    public function existedRating($id)
    {
        return $this->reviews->where('product_id', $id)->first();
    }

    public function mySell()
    {
        return $this->hasMany(Sell::class, 'user_id')->where('status', Status::SELL_APPROVED);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function buyProductId(): Attribute
    {
        return new Attribute(
            get: fn () => $this->mySell->pluck('product_id', 'id')->toArray(),
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        $query->where('status', Status::USER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        $query->where('ev', Status::NO);
    }

    public function scopeMobileUnverified($query)
    {
        $query->where('sv', Status::NO);
    }

    public function scopeKycUnverified($query)
    {
        $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        $query->where('sv', Status::VERIFIED);
    }

    public function scopeTopAuthor($query)
    {
        return $query->where('top_author', Status::YES);
    }

    public function scopeWithBalance($query)
    {
        $query->where('balance', '>', 0);
    }
    public function scopeWithProducts($query)
    {
        $query->whereHas('products', function ($q) {
            $q->approved();
        })->withCount(['products' => function ($q) {
            $q->approved();
        }]);
    }
    public function email(): Attribute
    {
        return new Attribute(
            get: fn () => '[Email is protected for the demo]',
        );
    }

    public function mobile(): Attribute
    {
        return new Attribute(
            get: fn () => '[Mobile number is protected for the demo]',
        );
    }
}
