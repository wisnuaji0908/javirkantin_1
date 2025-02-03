<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupCartSystem extends Command
{
    /**
     * Nama dan deskripsi command.
     */
    protected $signature = 'setup:cart';
    protected $description = 'Setup fitur keranjang dari migration, model, controller, dan seeder';

    /**
     * Jalankan perintah ini.
     */
    public function handle()
    {
        $this->info('🚀 Mulai setup fitur keranjang...');

        // 1. Buat migration
        Artisan::call('make:migration create_carts_table');
        $this->info('✅ Migration carts dibuat');

        // 2. Buat model
        Artisan::call('make:model Cart');
        $this->info('✅ Model Cart dibuat');

        // 3. Buat controller
        Artisan::call('make:controller Buyer/CartController --resource');
        $this->info('✅ Controller CartController dibuat');

        // 4. Buat seeder
        Artisan::call('make:seeder CartSeeder');
        $this->info('✅ Seeder CartSeeder dibuat');

        $this->info('🔥 Setup selesai! Jangan lupa edit migration dan jalankan `php artisan migrate --seed`');
    }
}
