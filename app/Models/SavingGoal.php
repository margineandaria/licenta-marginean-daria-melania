<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingGoal extends Model
{
    protected $fillable = [
        'family_id', 
        'goal_name', 
        'target_amount', 
        'current_amount', 
        'target_date'
    ];
    public function getProgressAttribute(): float
    {
        if($this->target_amount<=0)
            return 0;
        $percentage = ($this->current_amount / $this->target_amount) *100;
        return round(min($percentage, 100),2);

    }
    public function family() {
        return $this->belongsTo(Family::class);
    }
}
