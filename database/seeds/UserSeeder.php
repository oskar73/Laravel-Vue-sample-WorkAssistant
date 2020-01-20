<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();

        $admin = User::create([
            'first_name' => 'Bizinabox',
            'last_name' => '',
            'username' => 'admin',
            'email' => 'jong@bizinabox.com',
            'email_verified_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'password' => bcrypt('secret'),
        ]);

        $admin->syncRoles(['admin', 'client', 'employee', 'developer']);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
