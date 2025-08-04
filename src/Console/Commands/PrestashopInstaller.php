<?php

namespace Webkul\Prestashop\Console\Commands;

use Illuminate\Console\Command;

class PrestashopInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prestashop-package:install';

    protected $description = 'Install the Prestashop package';

    public function handle()
    {
        $this->info('Installing Unopim Prestashop connector...');

        if ($this->confirm('Would you like to run the migrations now?', true)) {
            $this->call('migrate');
            $this->call('db:seed', ['--class' => 'Webkul\Prestashop\Database\Seeders\PrestashopSettingConfigurationValuesSeeder']);
        }

        $this->call('vendor:publish', [
            '--tag' => 'prestashop-config',
        ]);

        $this->info('Unopim Prestashop connector installed successfully!');
    }
}
