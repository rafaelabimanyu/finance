<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Eco-Plumbing Service Revenue', 'type' => 'income'],
            ['name' => 'Office Rent & Facilities', 'type' => 'expense'],
            ['name' => 'Electricity, Water & Internet OPEX', 'type' => 'expense'],
            ['name' => 'Staff Salaries & Incentives', 'type' => 'expense'],
            ['name' => 'Equipment Maintenance & Tools', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['type' => $category['type']]
            );
        }
    }
}
