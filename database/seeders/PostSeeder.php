<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post =  new Post;
        $post->user_id='2';
        $post->title="How are You?";
        $post->content="I am fine every one";
        $post->save();
    }
}
