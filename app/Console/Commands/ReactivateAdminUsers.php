<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ReactivateAdminUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reactivate-admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reactivate all admin and superadmin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::whereIn('role', ['admin', 'super_admin'])
            ->where('active', false)
            ->update(['active' => true]);

        $this->info("Reactivated {$count} admin/superadmin users.");
    }
}
