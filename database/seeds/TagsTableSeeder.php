<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'Educational',
            'Laravel',
            'PHP',
            'HTML'
        ];

        foreach ($tags as $tag) {

            //creazione
            $newTag = new Tag();
            $newTag->name = $tag;
            $newTag->save();


            
        }
    }
}
