<?php

namespace Database\Seeders;

use App\Models\ServiceArea;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Service Categories
        // $categories = ServiceCategory::factory()->count(100)->create();

        // Service Categories
        $categories = [
            [
                'name' => 'Plumbing',
                'icon' => 'pipe-wrench',
                'description' => 'Professional plumbing services including pipe repairs, leak detection, water heater installation, and routine maintenance to ensure your water systems operate efficiently and safely.'
            ],
            [
                'name' => 'Electrical',
                'icon' => 'bolt',
                'description' => 'Certified electrical services covering wiring repairs, socket and switch installation, lighting upgrades, and electrical troubleshooting for a safe and efficient home environment.'
            ],
            [
                'name' => 'Air Conditioning',
                'icon' => 'snowflake',
                'description' => 'Comprehensive air conditioning services including installation, regular servicing, and repairs to ensure your home remains cool and comfortable throughout the year.'
            ],
            [
                'name' => 'Cleaning',
                'icon' => 'broom',
                'description' => 'Residential and commercial cleaning services covering deep cleaning, move-in/out cleaning, and regular maintenance cleaning to keep your spaces hygienic and welcoming.'
            ],
            [
                'name' => 'Home Appliance Repair',
                'icon' => 'tools',
                'description' => 'Repair and maintenance for various home appliances such as washing machines, refrigerators, ovens, and more to keep your household running smoothly.'
            ],
            [
                'name' => 'Painting',
                'icon' => 'paint-roller',
                'description' => 'Interior and exterior painting services using high-quality materials and techniques to refresh your home with a clean, modern look and long-lasting finishes.'
            ],
            [
                'name' => 'Carpentry',
                'icon' => 'hammer',
                'description' => 'Custom carpentry services including furniture repairs, cabinet installation, and general woodworking to enhance the functionality and aesthetics of your home.'
            ],
            [
                'name' => 'Pest Control',
                'icon' => 'bug',
                'description' => 'Safe and effective pest control services to manage and eliminate pests such as termites, rodents, and insects to protect your home and family.'
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\ServiceCategory::firstOrCreate(
                ['name' => $category['name']],
                [
                    'icon' => $category['icon'],
                    'description' => $category['description'],
                ]
            );
        }

        $states = [
            'Johor',
            'Kedah',
            'Kelantan',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Perak',
            'Perlis',
            'Pulau Pinang',
            'Sabah',
            'Sarawak',
            'Selangor',
            'Terengganu',
            'Wilayah Persekutuan Kuala Lumpur',
            'Wilayah Persekutuan Labuan',
            'Wilayah Persekutuan Putrajaya',
        ];

        foreach ($states as $state) {
            State::create(['name' => $state]);
        }

        $data = [
            // Johor
            ['name' => 'Johor Bahru', 'state' => 'Johor'],
            ['name' => 'Batu Pahat', 'state' => 'Johor'],
            ['name' => 'Muar', 'state' => 'Johor'],
            ['name' => 'Kluang', 'state' => 'Johor'],
            ['name' => 'Segamat', 'state' => 'Johor'],
            ['name' => 'Pontian', 'state' => 'Johor'],
            ['name' => 'Kulai', 'state' => 'Johor'],
            ['name' => 'Tangkak', 'state' => 'Johor'],

            // Kedah
            ['name' => 'Alor Setar', 'state' => 'Kedah'],
            ['name' => 'Sungai Petani', 'state' => 'Kedah'],
            ['name' => 'Kulim', 'state' => 'Kedah'],
            ['name' => 'Baling', 'state' => 'Kedah'],
            ['name' => 'Yan', 'state' => 'Kedah'],
            ['name' => 'Pendang', 'state' => 'Kedah'],
            ['name' => 'Langkawi', 'state' => 'Kedah'],

            // Kelantan
            ['name' => 'Kota Bharu', 'state' => 'Kelantan'],
            ['name' => 'Pasir Mas', 'state' => 'Kelantan'],
            ['name' => 'Tumpat', 'state' => 'Kelantan'],
            ['name' => 'Gua Musang', 'state' => 'Kelantan'],
            ['name' => 'Tanah Merah', 'state' => 'Kelantan'],

            // Melaka
            ['name' => 'Melaka City', 'state' => 'Melaka'],
            ['name' => 'Alor Gajah', 'state' => 'Melaka'],
            ['name' => 'Jasin', 'state' => 'Melaka'],

            // Negeri Sembilan
            ['name' => 'Seremban', 'state' => 'Negeri Sembilan'],
            ['name' => 'Port Dickson', 'state' => 'Negeri Sembilan'],
            ['name' => 'Nilai', 'state' => 'Negeri Sembilan'],
            ['name' => 'Tampin', 'state' => 'Negeri Sembilan'],

            // Pahang
            ['name' => 'Kuantan', 'state' => 'Pahang'],
            ['name' => 'Temerloh', 'state' => 'Pahang'],
            ['name' => 'Bentong', 'state' => 'Pahang'],
            ['name' => 'Raub', 'state' => 'Pahang'],
            ['name' => 'Jerantut', 'state' => 'Pahang'],
            ['name' => 'Rompin', 'state' => 'Pahang'],

            // Perak
            ['name' => 'Ipoh', 'state' => 'Perak'],
            ['name' => 'Taiping', 'state' => 'Perak'],
            ['name' => 'Teluk Intan', 'state' => 'Perak'],
            ['name' => 'Sitiawan', 'state' => 'Perak'],
            ['name' => 'Batu Gajah', 'state' => 'Perak'],
            ['name' => 'Kuala Kangsar', 'state' => 'Perak'],

            // Perlis
            ['name' => 'Kangar', 'state' => 'Perlis'],
            ['name' => 'Arau', 'state' => 'Perlis'],
            ['name' => 'Padang Besar', 'state' => 'Perlis'],

            // Pulau Pinang
            ['name' => 'George Town', 'state' => 'Pulau Pinang'],
            ['name' => 'Butterworth', 'state' => 'Pulau Pinang'],
            ['name' => 'Bayan Lepas', 'state' => 'Pulau Pinang'],
            ['name' => 'Bukit Mertajam', 'state' => 'Pulau Pinang'],
            ['name' => 'Nibong Tebal', 'state' => 'Pulau Pinang'],

            // Sabah
            ['name' => 'Kota Kinabalu', 'state' => 'Sabah'],
            ['name' => 'Sandakan', 'state' => 'Sabah'],
            ['name' => 'Tawau', 'state' => 'Sabah'],
            ['name' => 'Lahad Datu', 'state' => 'Sabah'],
            ['name' => 'Keningau', 'state' => 'Sabah'],
            ['name' => 'Semporna', 'state' => 'Sabah'],

            // Sarawak
            ['name' => 'Kuching', 'state' => 'Sarawak'],
            ['name' => 'Miri', 'state' => 'Sarawak'],
            ['name' => 'Sibu', 'state' => 'Sarawak'],
            ['name' => 'Bintulu', 'state' => 'Sarawak'],
            ['name' => 'Sri Aman', 'state' => 'Sarawak'],

            // Selangor
            ['name' => 'Shah Alam', 'state' => 'Selangor'],
            ['name' => 'Petaling Jaya', 'state' => 'Selangor'],
            ['name' => 'Subang Jaya', 'state' => 'Selangor'],
            ['name' => 'Klang', 'state' => 'Selangor'],
            ['name' => 'Kajang', 'state' => 'Selangor'],
            ['name' => 'Rawang', 'state' => 'Selangor'],
            ['name' => 'Selayang', 'state' => 'Selangor'],

            // Terengganu
            ['name' => 'Kuala Terengganu', 'state' => 'Terengganu'],
            ['name' => 'Dungun', 'state' => 'Terengganu'],
            ['name' => 'Kemaman', 'state' => 'Terengganu'],
            ['name' => 'Marang', 'state' => 'Terengganu'],
            ['name' => 'Besut', 'state' => 'Terengganu'],

            // Wilayah Persekutuan Kuala Lumpur
            ['name' => 'Bukit Bintang', 'state' => 'Wilayah Persekutuan Kuala Lumpur'],
            ['name' => 'Cheras', 'state' => 'Wilayah Persekutuan Kuala Lumpur'],
            ['name' => 'Setapak', 'state' => 'Wilayah Persekutuan Kuala Lumpur'],
            ['name' => 'Wangsa Maju', 'state' => 'Wilayah Persekutuan Kuala Lumpur'],
            ['name' => 'Sentul', 'state' => 'Wilayah Persekutuan Kuala Lumpur'],

            // Wilayah Persekutuan Labuan
            ['name' => 'Labuan', 'state' => 'Wilayah Persekutuan Labuan'],

            // Wilayah Persekutuan Putrajaya
            ['name' => 'Putrajaya', 'state' => 'Wilayah Persekutuan Putrajaya'],
        ];


        foreach ($data as $item) {
            $state = State::where('name', $item['state'])->first();
            if ($state) {
                ServiceArea::firstOrCreate([
                    'name' => $item['name'],
                    'state_id' => $state->id,
                ]);
            }
        }
    }
}
