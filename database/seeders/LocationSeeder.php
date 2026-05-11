<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        // Clear tables if needed or use updateOrInsert
        
        // 38 Provinces
        $provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau',
            'Jambi', 'Bengkulu', 'Sumatera Selatan', 'Kepulauan Bangka Belitung', 'Lampung',
            'Banten', 'Jawa Barat', 'DKI Jakarta', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur',
            'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
            'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Tengah', 'Papua Pegunungan', 'Papua Selatan', 'Papua Barat Daya'
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->updateOrInsert(['name' => $province], ['updated_at' => now()]);
        }

        $sumutId = DB::table('provinces')->where('name', 'Sumatera Utara')->first()->id;

        // Regencies with categories
        $regencies = [
            ['name' => 'Kabupaten Samosir', 'category' => 'Destinasi Prioritas'],
            ['name' => 'Kabupaten Toba', 'category' => 'Destinasi Prioritas'],
            ['name' => 'Kabupaten Karo', 'category' => 'Pegunungan & Alam'],
            ['name' => 'Kabupaten Simalungun', 'category' => 'Wisata Danau'],
            ['name' => 'Kabupaten Dairi', 'category' => 'Pegunungan & Alam'],
            ['name' => 'Kabupaten Humbang Hasundutan', 'category' => 'Wisata Danau'],
            ['name' => 'Kabupaten Tapanuli Utara', 'category' => 'Budaya & Religi'],
            ['name' => 'Kota Medan', 'category' => 'Pusat Kota & Belanja'],
            ['name' => 'Kota Pematangsiantar', 'category' => 'Transit & Kuliner'],
            ['name' => 'Kabupaten Deli Serdang', 'category' => 'Transit & Alam'],
            ['name' => 'Kabupaten Langkat', 'category' => 'Wisata Alam & Sungai'],
            ['name' => 'Kabupaten Nias', 'category' => 'Wisata Bahari'],
            ['name' => 'Kabupaten Nias Selatan', 'category' => 'Wisata Bahari & Budaya'],
            ['name' => 'Kota Sibolga', 'category' => 'Wisata Bahari'],
            ['name' => 'Kabupaten Tapanuli Tengah', 'category' => 'Wisata Bahari'],
            ['name' => 'Kabupaten Asahan', 'category' => 'Pesisir'],
            ['name' => 'Kabupaten Batubara', 'category' => 'Pesisir'],
            ['name' => 'Kabupaten Serdang Bedagai', 'category' => 'Pesisir'],
        ];

        foreach ($regencies as $reg) {
            DB::table('regencies')->updateOrInsert(
                ['name' => $reg['name'], 'province_id' => $sumutId],
                ['category' => $reg['category'], 'updated_at' => now()]
            );
        }
    }

    public function run()
    {
        $this->up();
    }
}
