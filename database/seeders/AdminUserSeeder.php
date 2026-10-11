<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@strathmore.edu'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('Demo1234'),
            ]
        );

        // Day-to-day desk staff: can log/edit/claim items and send disposal
        // notices, but cannot delete items or confirm a batch handover
        // (see app/Policies/ItemPolicy.php and DisposalBatchPolicy.php).
        User::updateOrCreate(
            ['email' => 'officer@strathmore.edu'],
            [
                'name' => 'Front Desk Officer',
                'role' => 'officer',
                'password' => Hash::make('Demo1234'),
            ]
        );
    }
}
