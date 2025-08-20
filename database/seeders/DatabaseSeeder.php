<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        

        // never truncate/seed demo data in production
        if (! app()->environment('production')) {
            $this->call([
                IpSeeder::class,
                xmlTemplateSeeder::class,
            ]);
        }
    
        // If you truly have prod seeders, gate them behind an env flag:
        if (env('ALLOW_PROD_SEEDING', false)) {
            $this->call([
                // Seeders that add non-destructive reference data
            ]);
        }



        

    }
    


}





