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
                'first_usable_ip' => '10.100.5.1',
                'last_usable_ip' => '10.100.5.62',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.5.65',
                'last_usable_ip' => '10.100.5.126',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.5.129',
                'last_usable_ip' => '10.100.5.190',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.6.1',
                'last_usable_ip' => '10.100.6.62',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.6.65',
                'last_usable_ip' => '10.100.6.126',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.6.129',
                'last_usable_ip' => '10.100.1.190',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.6.193',
                'last_usable_ip' => '10.100.1.254',
                'network_range' => '',
                'description' => '',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.100.7.1',
                'last_usable_ip' => '10.100.7.62',
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

