<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin';

    protected $description = 'Создаёт админа';

    public function handle(): void
    {
        $lastUser = User::query()->latest('id')->first();
        $adminUserExists = User::query()->where('is_admin', true)->exists();

        if (is_null($lastUser)) {
            $this->error('Сперва импортируйте данные -- docker compose exec -it php php artisan seed:db');
            return;
        } else if ($adminUserExists) {
            $this->error('Администратор уже существует');
            return;
        }

        $adminPassword = 'password';
        
        User::query()->create([
            'external_id' => $lastUser->external_id + 1,
            'name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone' => '1234567890',
            'password' => $adminPassword,
            'is_admin' => true,
        ]);

        $this->info("Email: admin@admin.com -- Password: {$adminPassword}");
    }
}
