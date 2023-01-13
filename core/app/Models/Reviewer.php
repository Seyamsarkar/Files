<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Reviewer extends Authenticatable
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'address'           => 'object',
        'ver_code_send_at'  => 'datetime',
    ];

    protected $data = [
        'data' => 1,
    ];

    public function login_logs()
    {
        return $this->hasMany(ReviewerLogin::class);
    }

    public function totalReviewed()
    {
        return $this->hasMany(Product::class, 'reviewer_id');
    }
    public function totalApproved()
    {
        return $this->hasMany(Product::class, 'reviewer_id')->where('status', Status::PRODUCT_APPROVE);
    }
    public function totalSoftReject()
    {
        return $this->hasMany(Product::class, 'reviewer_id')->where('status', Status::PRODUCT_SOFT_REJECT);
    }
    public function totalHardReject()
    {
        return $this->hasMany(Product::class, 'reviewer_id')->where('status', Status::PRODUCT_HARD_REJECT);
    }
    // SCOPES

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', Status::REVIEWER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }

    public function scopeBanned()
    {
        return $this->where('status', Status::REVIEWER_BAN);
    }

    public function scopeEmailVerified()
    {
        return $this->where('ev', Status::VERIFIED);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileVerified()
    {
        return $this->where('sv', Status::VERIFIED);
    }

    public function scopeMobileUnverified()
    {
        return $this->where('sv', Status::UNVERIFIED);
    }
}
