<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'date_of_birth', 'gender', 'description', 'profile_photo', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function recentViews()
    {
        return $this->hasMany(CampaignView::class)->orderBy('last_viewed_at', 'desc');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function isFundraiser()
    {
        return $this->role === 'fundraiser';
    }

    public function isVerified()
    {
        return $this->verification && $this->verification->status === 'approved';
    }

    public function verification()
    {
        return $this->hasOne(FundraiserVerification::class);
    }
}
