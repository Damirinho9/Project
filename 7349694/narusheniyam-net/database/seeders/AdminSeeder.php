<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Пароль из задания сохраняется только как bcrypt-хеш.
        User::updateOrCreate(['login'=>'admin'], [
            'password'=>Hash::make('adm123'),
            'fio'=>'Администратор Системы Сайта',
            'phone'=>'+7(000)000-00-00',
            'email'=>'admin@narusheniyam.local',
            'role'=>'admin',
        ]);
    }
}
