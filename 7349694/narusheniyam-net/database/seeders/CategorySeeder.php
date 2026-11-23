<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Ямы на дорогах','Неисправное освещение','Мусор и свалки','Повреждение тротуара',
            'Сломанная детская площадка','Незаконная парковка','Повреждение дорожных знаков','Открытые люки и опасные участки',
        ];
        foreach ($categories as $name) Category::firstOrCreate(['name'=>$name]);
    }
}
