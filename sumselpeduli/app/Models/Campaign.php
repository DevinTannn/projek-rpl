<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'title', 'description', 'goal_amount', 'collected_amount', 'tag', 'banner', 'status'])]
class Campaign extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function milestones()
    {
        return $this->hasMany(CampaignMilestone::class);
    }

    public function getPercentageAttribute()
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->goal_amount) * 100));
    }
}
