<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MultipleSeeder extends Seeder
{
    public function run()
    {
        // Array of SQL files to be seeded
        $sqlFiles = [
            'seeds/railway_users.sql',
            'seeds/railway_clients.sql',
            'seeds/railway_permissions.sql',
            'seeds/railway_transactions.sql',
            'seeds/railway_rates.sql',
            'seeds/railway_accounts.sql',
            'seeds/railway_account_user.sql',
            'seeds/railway_providers.sql',
            'seeds/railway_transaction_comments.sql',
            'seeds/railway_user_accounts.sql',
            'seeds/railway_system_accounts.sql',
            'seeds/railway_user_bank_accounts.sql',
            'seeds/railway_system_bank_accounts.sql',
            'seeds/railway_agent_deposits.sql',
            'seeds/railway_cashouts.sql',
            'seeds/railway_cashins.sql'
        ];

        foreach ($sqlFiles as $file) {
            $sqlFile = database_path($file);
            if (File::exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                DB::unprepared($sql);
                info("Data from $file seeded successfully!");
            } else {
                info("File $file not found!");
            }
        }
    }
}
