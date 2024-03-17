<?php

namespace Database\Seeders;

use App\Models\SystemAccount;
use Illuminate\Database\Seeder;

class SystemAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemAccount = SystemAccount::create([
            'funds' => 0,
            'depts' => 0,
            'user_payroll' => 0,
            'employee_payroll' => 0,
            'revenues' => 0,
            'claims' => 0,
            'user_id' => 1
        ]);
    }
}
