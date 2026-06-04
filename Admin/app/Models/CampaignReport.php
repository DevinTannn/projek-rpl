<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignReport extends Model
{
    protected $fillable = [
        'campaign_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
        'status',
        'admin_note'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getUrlAttribute()
    {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_path);
    }

    public function getGoogleViewerUrlAttribute()
    {
        return 'https://docs.google.com/viewer?url=' . urlencode($this->url) . '&embedded=true';
    }
}
