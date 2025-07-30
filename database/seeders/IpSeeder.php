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
                'first_usable_ip' => '192.168.1.10',
                'last_usable_ip' => '192.168.1.50',
                'network_range' => '192.168.1.0/24',
                'description' => 'Network range for Hotel properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '192.168.2.10',
                'last_usable_ip' => '192.168.2.50',
                'network_range' => '192.168.2.0/24',
                'description' => 'Network range for Factory properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '192.168.3.10',
                'last_usable_ip' => '192.168.3.50',
                'network_range' => '192.168.3.0/24',
                'description' => 'Network range for Office properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '192.168.4.10',
                'last_usable_ip' => '192.168.4.50',
                'network_range' => '192.168.4.0/24',
                'description' => 'Network range for Residential properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '192.168.5.10',
                'last_usable_ip' => '192.168.5.50',
                'network_range' => '192.168.5.0/24',
                'description' => 'Network range for Other properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.0.1.10',
                'last_usable_ip' => '10.0.1.100',
                'network_range' => '10.0.1.0/24',
                'description' => 'Additional network range for high density properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.0.2.10',
                'last_usable_ip' => '10.0.2.100',
                'network_range' => '10.0.2.0/24',
                'description' => 'Additional network range for medium density properties',
                'in_use' => false,
            ],
            [
                'first_usable_ip' => '10.0.3.10',
                'last_usable_ip' => '10.0.3.100',
                'network_range' => '10.0.3.0/24',
                'description' => 'Additional network range for low density properties',
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

