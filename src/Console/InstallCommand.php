<?php

namespace ArnaldoTomo\AutoAlert\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'auto-alert:install';

    protected $description = 'Install the Laravel Auto Alert package and configure layout';

    public function handle()
    {
        $this->info('Installing Laravel Auto Alert...');

        if (!File::exists(config_path('auto-alert.php'))) {
            $this->call('vendor:publish', [
                '--tag' => 'auto-alert-config'
            ]);
            $this->info('Config file published.');
        }

        $config = File::get(config_path('auto-alert.php'));

        if (str_contains($config, "'layout' => env('AUTO_ALERT_LAYOUT', null)")) {
            $layout = $this->choice(
                'Are you using Bootstrap or Tailwind CSS?',
                ['bootstrap', 'tailwind'],
                1
            );

            $config = str_replace(
                "'layout' => env('AUTO_ALERT_LAYOUT', null)",
                "'layout' => env('AUTO_ALERT_LAYOUT', '{$layout}')",
                $config
            );

            File::put(config_path('auto-alert.php'), $config);

            if (File::exists(base_path('.env'))) {
                $env = File::get(base_path('.env'));
                if (!str_contains($env, 'AUTO_ALERT_LAYOUT=')) {
                    File::append(base_path('.env'), "\nAUTO_ALERT_LAYOUT={$layout}\n");
                }
            }

            $this->info("Layout set to: {$layout}");
        }

        $this->info('Laravel Auto Alert installed successfully.');
    }
}
