<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
        protected $fillable = [
        'family_id', 
        'user_id_allocated', 
        'category_id_ai', 
        'category_id_final', 
        'description', 
        'amount', 
        'type',
        'transaction_date', 
        'payment_method', 
        'is_anomaly',
    ];
    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id_allocated');
    }
    public function categoryAi() {
        return $this->belongsTo(Category::class, 'category_id_ai');
    }

    public function categoryFinal() {
        return $this->belongsTo(Category::class, 'category_id_final');
    }
}
