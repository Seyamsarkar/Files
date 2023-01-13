<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewHistory extends Model
{
    use HasFactory;
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function getStatusTextAttribute()
    {
        $text = '';
        if ($this->status == Status::PRODUCT_APPROVE) {
            $text = 'APPROVED';
        } elseif ($this->status == Status::PRODUCT_DELETE) {
            $text = 'DELETED';
        } elseif ($this->status == Status::PRODUCT_HARD_REJECT) {
            $text = 'HARD REJECT';
        } elseif ($this->status == Status::PRODUCT_SOFT_REJECT) {
            $text = 'SOFT REJECT';
        }

        return $text;
    }
}
