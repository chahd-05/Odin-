<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Test User
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Create Categories
        $catDev = Category::create(['name' => 'Development', 'slug' => 'development', 'user_id' => $user->id]);
        $catNews = Category::create(['name' => 'News', 'slug' => 'news', 'user_id' => $user->id]);

        // Create Tags
        $tagLaravel = Tag::create(['name' => 'Laravel']);
        $tagPHP = Tag::create(['name' => 'PHP']);
        $tagNews = Tag::create(['name' => 'Tech']);

        // Create Links
        $link1 = Link::create([
            'user_id' => $user->id,
            'category_id' => $catDev->id,
            'title' => 'Laravel Documentation',
            'url' => 'https://laravel.com/docs',
        ]);
        $link1->tags()->attach([$tagLaravel->id, $tagPHP->id]);

        $link2 = Link::create([
            'user_id' => $user->id,
            'category_id' => $catNews->id,
            'title' => 'Hacker News',
            'url' => 'https://news.ycombinator.com',
        ]);
        $link2->tags()->attach([$tagNews->id]);
    }
}
