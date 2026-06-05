<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();

        if ($admin) {
            DB::table('users')
                ->where('id', $admin->id)
                ->update([
                    'name' => 'admin',
                    'password' => Hash::make('admin'),
                    'is_admin' => true,
                    'updated_at' => now(),
                ]);

            return;
        }

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
    }
};
