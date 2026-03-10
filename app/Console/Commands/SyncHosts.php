<?php

namespace App\Console\Commands;

use App\Models\Organization;
use Illuminate\Console\Command;

class SyncHosts extends Command
{
    protected $signature = 'dev:sync-hosts';
    protected $description = 'Add all organization subdomains to the Windows hosts file (run as admin)';

    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('This command is for local development only.');
            return 1;
        }

        $hostsFile = 'C:\Windows\System32\drivers\etc\hosts';
        $domain = config('app.domain', 'rentify.test');

        // Read existing hosts
        $existing = file_get_contents($hostsFile);

        // Remove old rentify entries
        $lines = array_filter(
            explode("\n", $existing),
            fn($line) => !str_contains($line, $domain) || str_starts_with(trim($line), '#')
        );

        // Add base entries
        $lines[] = "127.0.0.1 {$domain}";
        $lines[] = "127.0.0.1 admin.{$domain}";

        // Add all org subdomains
        $slugs = Organization::withoutGlobalScopes()->pluck('slug');
        $count = 0;
        foreach ($slugs as $slug) {
            $lines[] = "127.0.0.1 {$slug}.{$domain}";
            $this->line("  + {$slug}.{$domain}");
            $count++;
        }

        $result = @file_put_contents($hostsFile, implode(PHP_EOL, $lines) . PHP_EOL);

        if ($result === false) {
            $this->error('Cannot write to hosts file. Run this terminal as Administrator:');
            $this->line('  Right-click terminal → Run as administrator');
            $this->line('  Then run: php artisan dev:sync-hosts');
            return 1;
        }

        $this->info("Done! Added {$count} org subdomains + base entries.");
        return 0;
    }
}
