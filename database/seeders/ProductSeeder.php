<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Products::factory(10)->create();


        foreach (Products::all() as $products) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $products->categories()->attach($categories);
        }
    }
}
