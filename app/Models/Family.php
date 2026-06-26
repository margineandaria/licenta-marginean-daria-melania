<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = ['name'];

    public function users(){
        return $this->hasMany(User::class);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class); 
    }
    public function savingGoals() {
        return $this->hasMany(SavingGoal::class);
    }
    public function budgets() {
        return $this->hasMany(Budget::class);
    }

}
