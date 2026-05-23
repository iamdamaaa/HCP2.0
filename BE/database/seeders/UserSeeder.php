<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password123');

        // ADMIN
        User::firstOrCreate(
            ['phone' => '08100000001'],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Admin HCP',
                'phone'       => '08100000001',
                'email'       => 'admin@hcp',  
                'password'    => 'admin123',
                'role'        => 'admin',
                'is_verified' => true,
            ]
        );

        // KARYAWAN 1
        User::firstOrCreate(
            ['phone' => '08100000002'],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Employee One',
                'phone'       => '08100000002',
                'email'       => 'employee1@hcp',
                'password'    => 'employee123',
                'role'        => 'employee',
                'is_verified' => true,
            ]
        );

        // KARYAWAN 2
        User::firstOrCreate(
            ['phone' => '08100000003'],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Employee One',
                'phone'       => '08100000003',
                'email'       => 'employee2@hcp',
                'password'    => 'employee123',
                'role'        => 'employee',
                'is_verified' => true,
            ]
        );

        // PELANGGAN 1
        User::firstOrCreate(
            ['phone' => '08100000004'],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Customer One',
                'phone'       => '08100000004',
                'email'       => 'customer1@hcp',
                'password'    => 'password123',
                'role'        => 'user',
                'is_verified' => true,
            ]
        );

        // PELANGGAN 2
        User::firstOrCreate(
            ['phone' => '08100000005'],
            [
                'id'          => Str::uuid()->toString(),
                'name'        => 'Customer two',
                'phone'       => '08100000005',
                'email'       => 'customer2@hcp',
                'password'    => 'password123',
                'role'        => 'user',
                'is_verified' => true,
            ]
        );
    }
}
