<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class ApiSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $article=[];

        $faker=Factory::create();

        for ($i=0; $i <30 ; $i++)
        { 
            $name=$faker->sentence();
            $quantity=$faker->numberBetween(1,19);

            $article[]=["name"=>$name,"quantity"=>$quantity];
        }

         $api=$this->table("api");

        $api->insert($article)->saveData();
    }
}
