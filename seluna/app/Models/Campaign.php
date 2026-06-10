<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'title', 'description', 'goal_amount', 'collected_amount', 'tag', 'banner', 'status'])]
class Campaign extends Model
{
    use HasFactory;

    protected $appends = ['percentage'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function milestones()
    {
        return $this->hasMany(CampaignMilestone::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function views()
    {
        return $this->hasMany(CampaignView::class);
    }

    public function media()
    {
        return $this->hasMany(CampaignMedia::class)->orderBy('sort_order');
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class)->latest();
    }

    public function donations()
    {
        return $this->hasMany(Donation::class)->latest();
    }

    public function reports()
    {
        return $this->hasMany(CampaignReport::class)->latest();
    }

    public function verified_reports()
    {
        return $this->hasMany(CampaignReport::class)->where('status', 'verified')->latest();
    }

    public function getPercentageAttribute()
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->goal_amount) * 100));
    }
}
