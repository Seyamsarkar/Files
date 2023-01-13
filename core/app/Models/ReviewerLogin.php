<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewerLogin extends Model {
    use HasFactory;

    public function reviewer() {
        return $this->belongsTo(Reviewer::class);
    }
}
