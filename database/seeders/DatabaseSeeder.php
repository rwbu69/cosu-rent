<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Costume;
use App\Models\CostumeComponent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('user'),
        ]);

        \App\Models\UserAddress::create([
            'user_id' => $user->id,
            'address_line' => 'Jl. Kebon Jeruk Raya No. 27, Jakarta Barat',
            'is_primary' => true,
        ]);

        $costumes = [
            [
                'name' => 'Kostum Tokai Teio (Kemenangan)',
                'series' => 'Uma Musume',
                'size' => 'M',
                'base_price' => 150000,
                'description' => 'Kostum kemenangan Tokai Teio yang ikonik. Sangat cocok untuk acara cosplay besar atau photoshoot.',
                'components' => ['Wig Tokai Teio', 'Jas Atasan', 'Rok Berlipat', 'Aksesoris Ekor', 'Hiasan Kepala']
            ],
            [
                'name' => 'Hakurei Reimu Miko Set',
                'series' => 'Touhou Project',
                'size' => 'All Size',
                'base_price' => 120000,
                'description' => 'Set Miko klasik Hakurei Reimu dengan kualitas bahan premium dan gohei yang presisi.',
                'components' => ['Pita Merah Rambut', 'Baju Dalaman Putih', 'Rompi Merah Miko', 'Rok Merah', 'Lengan Terpisah', 'Gohei (Tongkat)']
            ],
            [
                'name' => 'Genshin Impact - Zhongli',
                'series' => 'Genshin Impact',
                'size' => 'L',
                'base_price' => 200000,
                'description' => 'Kostum jas Zhongli lengkap dengan prop senjata Vortex Vanquisher. Cocok untuk postur tinggi.',
                'components' => ['Wig Zhongli', 'Jas Ekor Panjang', 'Kemeja Hitam', 'Celana Panjang', 'Dasi', 'Sarung Tangan', 'Vortex Vanquisher (Prop)']
            ],
            [
                'name' => 'Marin Kitagawa (Kuroe Shizuku)',
                'series' => 'My Dress-Up Darling',
                'size' => 'S',
                'base_price' => 180000,
                'description' => 'Gaun Lolita Kuroe Shizuku seperti yang dipakai Marin. Sangat detail dengan renda hitam khas.',
                'components' => ['Wig Hitam Shizuku', 'Bando Renda', 'Gaun Lolita', 'Celemek', 'Pita Dada', 'Choker']
            ]
        ];

        foreach ($costumes as $data) {
            $costume = Costume::create([
                'name' => $data['name'],
                'series' => $data['series'],
                'size' => $data['size'],
                'base_price' => $data['base_price'],
                'description' => $data['description'],
            ]);

            foreach ($data['components'] as $compName) {
                CostumeComponent::create([
                    'costume_id' => $costume->id,
                    'name' => $compName,
                    'barcode_string' => 'BRC-' . strtoupper(uniqid()),
                    'status' => 'In Warehouse'
                ]);
            }
        }
    }
}
