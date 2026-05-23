<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ADMIN
        User::firstOrCreate(
            ['phone' => '08100000001'],
            [
                'name'        => 'Admin HCP',
                'phone'       => '08100000001',
                'email'       => 'admin@hcp',  
                'password'    => 'password123',
                'role'        => 'admin',
                'is_verified' => true,
            ]
        );

        // KARYAWAN 1
        User::firstOrCreate(
            ['phone' => '08100000002'],
            [
                'name'        => 'Employee One',
                'phone'       => '08100000002',
                'email'       => 'employee1@hcp',
                'password'    => 'password123',
                'role'        => 'employee',
                'is_verified' => true,
            ]
        );

        // KARYAWAN 2
        User::firstOrCreate(
            ['phone' => '08100000003'],
            [
                'name'        => 'Employee One',
                'phone'       => '08100000003',
                'email'       => 'employee2@hcp',
                'password'    => 'password123',
                'role'        => 'employee',
                'is_verified' => true,
            ]
        );

        // PELANGGAN 1
        User::firstOrCreate(
            ['phone' => '08100000004'],
            [
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
