<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignMilestone;
use App\Models\Follow;
use App\Models\CampaignView;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Test User (The Main User)
        $testUser = User::firstOrCreate(
            ['email' => 'testuser@gmail.com'],
            [
                'username' => 'testuser',
                'password' => Hash::make('password'),
                'role' => 'fundraiser',
                'description' => 'I am a passionate donor and community leader in South Sumatra.',
                'profile_photo' => null,
            ]
        );

        // 2. Create other Fundraisers
        $fundraisers = [];
        for ($i = 1; $i <= 5; $i++) {
            $fundraisers[] = User::firstOrCreate(
                ['email' => "fundraiser$i@gmail.com"],
                [
                    'username' => "fundraiser_$i",
                    'password' => Hash::make('password'),
                    'role' => 'fundraiser',
                    'description' => "Official fundraiser account #$i.",
                ]
            );
        }

        $tags = ['Social', 'Health', 'Education', 'Disaster', 'Nature', 'Religion'];
        $campaignData = [
            ['Bantu Renovasi Sekolah di Pelosok', 'Education'],
            ['Operasi Jantung Dek Alif', 'Health'],
            ['Pembangunan Masjid Jami Sumsel', 'Religion'],
            ['Bantuan Logistik Banjir Musi', 'Disaster'],
            ['Beasiswa Anak Yatim Palembang', 'Education'],
            ['Penanaman 1000 Pohon Mangrove', 'Nature'],
            ['Sembako untuk Lansia Dhuafa', 'Social'],
            ['Rehabilitasi Satwa Langka', 'Nature'],
            ['Ambulance Gratis untuk Desa', 'Health'],
            ['Rumah Qur\'an Pedesaan', 'Religion'],
        ];

        // 3. Create 20 Campaigns
        $allCampaigns = [];

        // Your Campaigns (Owned by testUser) - 10 data
        foreach ($campaignData as $index => $data) {
            $goal = rand(5, 50) * 1000000;
            $collected = rand(0, $goal);
            
            $campaign = Campaign::create([
                'user_id' => $testUser->id,
                'title' => $data[0],
                'description' => "Ini adalah deskripsi lengkap untuk campaign {$data[0]}. Mari bantu sesama untuk mewujudkan senyum mereka.",
                'goal_amount' => $goal,
                'collected_amount' => $collected,
                'tag' => $data[1],
                'status' => 'active',
            ]);
            $allCampaigns[] = $campaign;
            $this->seedMilestones($campaign);
        }

        // Other Campaigns - 10 more
        foreach ($fundraisers as $fIndex => $fundraiser) {
            for ($j = 1; $j <= 2; $j++) {
                $title = "Campaign dari {$fundraiser->username} #$j";
                $goal = rand(10, 100) * 1000000;
                $collected = rand(0, $goal);
                
                $campaign = Campaign::create([
                    'user_id' => $fundraiser->id,
                    'title' => $title,
                    'description' => "Deskripsi untuk $title. Mari kita gotong royong membantu.",
                    'goal_amount' => $goal,
                    'collected_amount' => $collected,
                    'tag' => $tags[array_rand($tags)],
                    'status' => 'active',
                ]);
                $allCampaigns[] = $campaign;
                $this->seedMilestones($campaign);
            }
        }

        // 4. Seed Follows (Kampanye yang diikuti)
        // Test user follows 10 campaigns (not their own)
        $others = array_slice($allCampaigns, 10);
        foreach ($others as $c) {
            Follow::create([
                'user_id' => $testUser->id,
                'campaign_id' => $c->id,
            ]);
        }

        // 5. Seed Recently Accessed (Akses terakhir)
        // Test user viewed 5 campaigns recently
        $views = array_slice($allCampaigns, 5, 5);
        foreach ($views as $index => $c) {
            CampaignView::create([
                'user_id' => $testUser->id,
                'campaign_id' => $c->id,
                'last_viewed_at' => Carbon::now()->subHours($index),
            ]);
        }
    }

    private function seedMilestones($campaign)
    {
        $milestones = [
            25 => 'Persiapan Awal',
            50 => 'Pelaksanaan Tahap 1',
            75 => 'Finishing & Evaluasi',
            100 => 'Program Selesai'
        ];

        foreach ($milestones as $perc => $label) {
            CampaignMilestone::create([
                'campaign_id' => $campaign->id,
                'percentage' => $perc,
                'amount' => ($perc / 100) * $campaign->goal_amount,
                'badge_label' => $label,
            ]);
        }
    }
}
