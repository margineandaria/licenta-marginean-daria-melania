<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'type'];
    
    public function budgets() {
        return $this->hasMany(Budget::class);
    }

    public function transactionsAi() {
        return $this->hasMany(Transaction::class, 'category_id_ai');
    }

    public function transactionsFinal() {
        return $this->hasMany(Transaction::class, 'category_id_final');
    }
}
