<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ManagementUser;
use Illuminate\Support\Facades\Hash;

class ManagementUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        ManagementUser::updateOrCreate(
            ['email' => 'admin@brilliant.com'],
            [
                'name' => 'Admin Brilliant',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'role_id' => $adminRole ? $adminRole->id : null,
                'birth_date' => '1995-05-20',
                'gender' => 'Male',
                'phone_number' => '081234567890',
                'address' => 'Jl. Brilliant No. 1, Jakarta',
                'status' => 'Available',
                'total_events_handled' => 349,
                'joined_at' => '2020-01-01',
            ]
        );

        $crews = [
            [
                'name' => 'Aditya Pratama',
                'email' => 'aditya.pratama@brilliant.com',
                'birth_date' => '1993-04-12',
                'gender' => 'Male',
                'phone_number' => '081298765432',
                'address' => 'Jl. Kemang Raya No. 45, Jakarta Selatan',
                'status' => 'Available',
                'total_events_handled' => 45,
                'joined_at' => '2022-03-15',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti.rahma@brilliant.com',
                'birth_date' => '1996-08-22',
                'gender' => 'Female',
                'phone_number' => '085612345678',
                'address' => 'Jl. Margonda No. 12, Depok',
                'status' => 'Available',
                'total_events_handled' => 38,
                'joined_at' => '2022-09-01',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@brilliant.com',
                'birth_date' => '1990-11-05',
                'gender' => 'Male',
                'phone_number' => '081399887766',
                'address' => 'Jl. Pajajaran No. 88, Bogor',
                'status' => 'Busy',
                'total_events_handled' => 62,
                'joined_at' => '2021-06-10',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@brilliant.com',
                'birth_date' => '1995-02-18',
                'gender' => 'Female',
                'phone_number' => '087822334455',
                'address' => 'Jl. Dago No. 104, Bandung',
                'status' => 'Available',
                'total_events_handled' => 29,
                'joined_at' => '2023-01-15',
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian.hidayat@brilliant.com',
                'birth_date' => '1994-07-30',
                'gender' => 'Male',
                'phone_number' => '081277665544',
                'address' => 'Jl. Sudirman No. 50, Jakarta Pusat',
                'status' => 'Busy',
                'total_events_handled' => 50,
                'joined_at' => '2021-10-01',
            ],
            [
                'name' => 'Mega Utami',
                'email' => 'mega.utami@brilliant.com',
                'birth_date' => '1997-05-14',
                'gender' => 'Female',
                'phone_number' => '085788990011',
                'address' => 'Jl. Boulevard Raya Blok WA2, Kelapa Gading',
                'status' => 'Off',
                'total_events_handled' => 18,
                'joined_at' => '2023-08-20',
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@brilliant.com',
                'birth_date' => '1992-12-03',
                'gender' => 'Male',
                'phone_number' => '081122334455',
                'address' => 'Jl. Kaliurang Km 5.6, Yogyakarta',
                'status' => 'Available',
                'total_events_handled' => 74,
                'joined_at' => '2020-05-01',
            ],
            [
                'name' => 'Anisa Fitriani',
                'email' => 'anisa.fitri@brilliant.com',
                'birth_date' => '1998-01-25',
                'gender' => 'Female',
                'phone_number' => '081344556677',
                'address' => 'Jl. Gatsu No. 200, Bandung',
                'status' => 'Available',
                'total_events_handled' => 12,
                'joined_at' => '2024-02-01',
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@brilliant.com',
                'birth_date' => '1989-09-17',
                'gender' => 'Male',
                'phone_number' => '081255667788',
                'address' => 'Jl. Pahlawan No. 47, Surabaya',
                'status' => 'Available',
                'total_events_handled' => 95,
                'joined_at' => '2019-11-15',
            ],
            [
                'name' => 'Rina Astuti',
                'email' => 'rina.astuti@brilliant.com',
                'birth_date' => '1996-03-08',
                'gender' => 'Female',
                'phone_number' => '081933445566',
                'address' => 'Jl. Sunset Road No. 82, Bali',
                'status' => 'Off',
                'total_events_handled' => 31,
                'joined_at' => '2022-07-01',
            ],
        ];

        $crewRole = \App\Models\Role::where('name', 'crew')->first();
        foreach ($crews as $crewData) {
            ManagementUser::updateOrCreate(
                ['email' => $crewData['email']],
                array_merge($crewData, [
                    'password' => Hash::make('password'),
                    'role' => 'crew',
                    'role_id' => $crewRole ? $crewRole->id : null,
                ])
            );
        }
    }
}
