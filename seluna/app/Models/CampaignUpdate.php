<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CampaignUpdate extends Model
{
    protected $fillable = ['campaign_id', 'title', 'content', 'media_path', 'media_type'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getMediaUrlAttribute()
    {
        return $this->media_path ? Storage::url($this->media_path) : null;
    }
}
