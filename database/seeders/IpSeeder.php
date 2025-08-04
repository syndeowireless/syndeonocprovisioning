<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ipRanges = [
            [
                'first_usable_ip' => '10.100.0.1',
                'last_usable_ip' => '10.100.0.62',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.0.65',
                'last_usable_ip' => '10.100.0.126',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.0.129',
                'last_usable_ip' => '10.100.0.190',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.1.1',
                'last_usable_ip' => '10.100.1.62',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.1.65',
                'last_usable_ip' => '10.100.1.126',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.1.129',
                'last_usable_ip' => '10.100.1.190',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.1.193',
                'last_usable_ip' => '10.100.1.254',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.2.1',
                'last_usable_ip' => '10.100.2.62',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
        ];

        foreach ($ipRanges as $range) {
            DB::table('ips')->insert([
                'first_usable_ip' => $range['first_usable_ip'],
                'last_usable_ip' => $range['last_usable_ip'],
                'network_range' => $range['network_range'],
                'description' => $range['description'],
                'in_use' => $range['in_use'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

