<?php

use Illuminate\Database\Seeder;

class Products extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker\Factory::create();

	    foreach (range(1,50) as $index) {

	        DB::table('products')->insert([
	            'name' => Str::random(10),
	            'available_stock' => $faker->unique()->numberBetween($min = 1, $max = 200),
	            'created_at' => $faker->dateTime($max = 'now', $timezone = null),
	            'updated_at' => $faker->dateTime($max = 'now', $timezone = null)
	        ]);

	    }
    }
}
