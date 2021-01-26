<?php

use Illuminate\Database\Seeder;
use App\Post;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        //al momento della creazione di questi nuovi post abbiamo la possibilità di canceellare qll vecchi presenti
        Post::truncate();


        //FAKER
        for ($i=0; $i < 10; $i++) { 

            $title = $faker->text(50);

            //creiamo nuova istanza da modello
            $newPost = new Post();
            //popolazione properties dall'istanza col db
            $newPost->title = $title;
            $newPost->body = $faker->paragraphs(2, true);
            $newPost->slug = Str::slug($title, '-');
            //salvataggio record(istanza) nel db
            $newPost->save();
        }


        // foreach ($posts as $post) {      DA USARE QUANDO ABBIAMO DATI STATICI
        //     //creiamo nuova istanza da modello
        //     $newPost= new Post();

        //     //popolazione properties dall'istanza col db
        //     $newPost->title = $post['title'];
        //     $newPost->body = $post['body'];
        //     $newPost->slug = Str::slug($post['title'],'-');

        //     //salvataggio record(istanza) nel db
        //     $newPost->save();
        // }
    }
}
