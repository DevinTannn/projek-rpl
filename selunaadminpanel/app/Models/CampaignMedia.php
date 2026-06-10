<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignMedia extends Model
{
    protected $fillable = ['campaign_id', 'file_path', 'file_type', 'original_name', 'sort_order'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getUrlAttribute(): string
    {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_path);
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }
}
