<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'family_id', 
        'category_id', 
        'user_id_responsible', 
        'budget_amount', 
        'month_year'
    ];
    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id_responsible');
    }
}
