<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // Array of SQL files to be seeded
                $sqlFiles = [
                    'seeds/18-March-2024_00h05/railway_departments.sql',
                    'seeds/18-March-2024_00h05/railway_users.sql',
                    'seeds/18-March-2024_00h05/railway_clients.sql',
                    'seeds/18-March-2024_00h05/railway_roles.sql',
                    'seeds/18-March-2024_00h05/railway_permissions.sql',
                    'seeds/18-March-2024_00h05/railway_role_has_permissions.sql',
                    'seeds/18-March-2024_00h05/railway_model_has_roles.sql',
                    'seeds/18-March-2024_00h05/railway_model_has_permissions.sql',
                    'seeds/18-March-2024_00h05/railway_account_transferts.sql',
                    'seeds/18-March-2024_00h05/railway_providers.sql',
                    'seeds/18-March-2024_00h05/railway_provider_payments.sql',
                    'seeds/18-March-2024_00h05/railway_role_and_permissions.sql',
                    'seeds/18-March-2024_00h05/railway_role_has_permissions.sql',
                    'seeds/18-March-2024_00h05/railway_transactions.sql',
                    'seeds/18-March-2024_00h05/railway_rates.sql',
                    'seeds/18-March-2024_00h05/railway_accounts.sql',
                    'seeds/18-March-2024_00h05/railway_account_user.sql',
                    'seeds/18-March-2024_00h05/railway_transaction_comments.sql',
                    'seeds/18-March-2024_00h05/railway_user_accounts.sql',
                    'seeds/18-March-2024_00h05/railway_system_accounts.sql',
                    'seeds/18-March-2024_00h05/railway_user_bank_accounts.sql',
                    'seeds/18-March-2024_00h05/railway_system_bank_accounts.sql',
                    'seeds/18-March-2024_00h05/railway_agent_deposits.sql',
                    'seeds/18-March-2024_00h05/railway_cashouts.sql',
                    'seeds/18-March-2024_00h05/railway_cashins.sql',
                    'seeds/18-March-2024_00h05/railway_user_visits.sql',
                    'seeds/18-March-2024_00h05/railway_notifications.sql',
                    'seeds/18-March-2024_00h05/railway_replenishments.sql',
                    'seeds/18-March-2024_00h05/railway_agent_investments.sql',
                    'seeds/18-March-2024_00h05/railway_agent_debt_deposits.sql',
                    'seeds/18-March-2024_00h05/railway_envoy_deposits.sql',
                    'seeds/18-March-2024_00h05/railway_send_money.sql',
                    'seeds/18-March-2024_00h05/railway_transfert_profit_to_recharges.sql',
                    'seeds/18-March-2024_00h05/railway_account_activits.sql',
                    'seeds/18-March-2024_00h05/railway_user_activits.sql',
                    'seeds/18-March-2024_00h05/railway_transaction_activits.sql',
                    'seeds/18-March-2024_00h05/railway_send_money_activits.sql',
                    'seeds/18-March-2024_00h05/railway_cashout_activits.sql',
                    'seeds/18-March-2024_00h05/railway_agent_deposit_activits.sql',
                    'seeds/18-March-2024_00h05/railway_cancel_transactions.sql',
                    'seeds/18-March-2024_00h05/railway_context_sort_user_sort.sql',
                    'seeds/18-March-2024_00h05/railway_context_sorts.sql',
                    'seeds/18-March-2024_00h05/railway_expenses.sql',
                    'seeds/18-March-2024_00h05/railway_expense_types.sql',
                    'seeds/18-March-2024_00h05/railway_user_sorts.sql',
                    'seeds/18-March-2024_00h05/railway_account_transfert_activits.sql',
                    'seeds/18-March-2024_00h05/railway_cashin_activits.sql',
                    'seeds/18-March-2024_00h05/railway_envoy_deposit_activits.sql',
                    'seeds/18-March-2024_00h05/railway_envoy_transferts.sql',
                    'seeds/18-March-2024_00h05/railway_envoy_transfert_activits.sql',

                    'seeds/18-March-2024_00h05/railway_agent_loans.sql',
                    'seeds/18-March-2024_00h05/railway_agent_loan_activits.sql',

                    'seeds/18-March-2024_00h05/railway_agent_loan_repays.sql',

                    'seeds/18-March-2024_00h05/railway_agent_loan_transaction_repays.sql',
                    'seeds/18-March-2024_00h05/railway_agent_loan_transaction_repay_activits.sql',

                    'seeds/18-March-2024_00h05/railway_jobs.sql',
                    'seeds/18-March-2024_00h05/railway_failed_jobs.sql',
                ];
        // $user = User::create([
        //     'code' => 'JB000',
        //     'first_name' => 'Junior',
        //     'last_name' => 'Bodeau',
        //     'email' => 'judebo45@gmail.com',
        //     'password' => Hash::make('1111'),
        //     'limit_min' => 9999999999999.0,
        //     'limit_max' => 9999999999999.0,
        //     'phone' => '+1 000 000 0000',
        //     'address' => 'Autopista duarte, 18 plaza pablo media moralez',
        //     'location' => 'Autopista duarte, 18 plaza pablo media moralez',
        //     'percentage' => '0'
        // ]);

        // $systemUser = User::create([
        //     'id' => 1000000,
        //     'code' => '000',
        //     'first_name' => 'System',
        //     'last_name' => 'System',
        //     'email' => 'system@gmail.com',
        //     'password' => Hash::make('System@123#'),
        //     'limit_min' => 9999999999999.0,
        //     'limit_max' => 9999999999999.0,
        //     'phone' => '+1 000 000 0000',
        //     'address' => '_',
        //     'location' => '_',
        //     'percentage' => '0'
        // ]);
        

        // $adminRole = Role::select('id')->where('name', 'super-admin')->first();
        // $user->roles()->attach($adminRole);

        // $sqlFile = database_path('seeds/railway_users.sql');
        // $sql = file_get_contents($sqlFile);
        // DB::unprepared($sql);

        // $sqlFile2 = database_path('seeds/railway_accounts.sql');
        // $sql2 = file_get_contents($sqlFile2);
        // DB::unprepared($sql2);

        // info('User table seeded successfully!');
        //  $adminRole = Role::select('id')->where('name', 'super-admin')->first();
        //  $user->roles()->attach($adminRole);

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