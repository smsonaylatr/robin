<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProviderPhoto;

class ProviderPhotoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'NetEnt',
                'gorsel' => 'assets/providers/netent.webp',
                'link' => '#',
                'sira' => 1,
                'aktif' => true
            ],
            [
                'name' => 'Microgaming',
                'gorsel' => 'assets/providers/microgaming.webp',
                'link' => '#',
                'sira' => 2,
                'aktif' => true
            ],
            [
                'name' => 'Playtech',
                'gorsel' => 'assets/providers/playtech.webp',
                'link' => '#',
                'sira' => 3,
                'aktif' => true
            ],
            [
                'name' => 'Evolution Gaming',
                'gorsel' => 'assets/providers/evolution.webp',
                'link' => '#',
                'sira' => 4,
                'aktif' => true
            ],
            [
                'name' => 'Pragmatic Play',
                'gorsel' => 'assets/providers/pragmatic.webp',
                'link' => '#',
                'sira' => 5,
                'aktif' => true
            ],
            [
                'name' => 'Betsoft',
                'gorsel' => 'assets/providers/betsoft.webp',
                'link' => '#',
                'sira' => 6,
                'aktif' => true
            ]
        ];

        foreach ($providers as $provider) {
            ProviderPhoto::create($provider);
        }
    }
} 