<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            GedungSeeder::class,
            HotelSeeder::class,
            DestinasiWisataSeeder::class,
            RentalSeeder::class,
            KulinerSeeder::class,
            RundownSeeder::class,
            LandingSeeder::class,
            FooterLinkSeeder::class,
            WilayahProvinsiSeeder::class,
            PicSeeder::class,
        ]);
    }
}
