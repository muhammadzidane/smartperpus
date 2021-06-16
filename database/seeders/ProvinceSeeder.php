<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            'ID' => [
                [
                    'name' => 'Aceh'],
                [
                    'name' => 'Bali'],
                [
                    'name' => 'Banten'],
                [
                    'name' => 'Bengkulu'],
                [
                    'name' => 'DKI Jakarta'],
                [
                    'name' => 'Gorontalo'],
                [
                    'name' => 'Jambi'],
                [
                    'name' => 'Jawa Barat'],
                [
                    'name' => 'Jawa Tengah'],
                [
                    'name' => 'Jawa Timur'],
                [
                    'name' => 'Kalimantan Barat'],
                [
                    'name' => 'Kalimantan Selatan'],
                [
                    'name' => 'Kalimantan Tengah'],
                [
                    'name' => 'Kalimantan Timur'],
                [
                    'name' => 'Kalimantan Utara'],
                [
                    'name' => 'Kepulauan Bangka Belitung'],
                [
                    'name' => 'Kepulauan Riau'],
                [
                    'name' => 'Lampung'],
                [
                    'name' => 'Maluku'],
                [
                    'name' => 'Maluku Utara'],
                [
                    'name' => 'Nusa Tenggara Barat'],
                [
                    'name' => 'Nusa Tenggara Timur'],
                [
                    'name' => 'Papua'],
                [
                    'name' => 'Papua Barat'],
                [
                    'name' => 'Riau'],
                [
                    'name' => 'Sulawesi Barat'],
                [
                    'name' => 'Sulawesi Selatan'],
                [
                    'name' => 'Sulawesi Tengah'],
                [
                    'name' => 'Sulawesi Tenggara'],
                [
                    'name' => 'Sulawesi Utara'],
                [
                    'name' => 'Sumatera Barat'],
                [
                    'name' => 'Sumatera Selatan'],
                [
                    'name' => 'Sumatera Utara'],
                [
                    'name' => 'Yogyakarta'],
            ]
        ];

        foreach ($provinces['ID'] as  $province) {
            Province::create(
                array(
                    'name'     => $province['name']
                    )
            );
        }
    }
}
