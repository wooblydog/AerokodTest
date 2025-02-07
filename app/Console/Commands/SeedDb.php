<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class SeedDb extends Command
{
    protected $signature = 'seed:db';
    protected $description = 'Импорт пользователей, постов и комментариев из https://jsonplaceholder.typicode.com/';

    public function handle(): void
    {
        $this->importUsers();
        $this->importPosts();
        $this->importComments();
    }

    private function importUsers(): void
    {
        $this->info('Импорт пользователей');

        $response = Http::get('https://jsonplaceholder.typicode.com/users');
        if ($response->failed()) {
            $this->error('Ошибка при получении пользователей');
            return;
        }

        $users = $response->json();
        foreach ($users as $user) {
            $explodeName = explode(' ', $user['name'], 2);

            $splitName = [
                'name' => $explodeName[0],
                'lastName' => $explodeName[1] ?? '',
            ];

            User::query()->updateOrCreate(
                [
                    'external_id' => $user['id'],
                ],
                [
                    'name' => $splitName['name'],
                    'last_name' => $splitName['lastName'],
                    'email' => $user['email'],
                    'phone' => $user['phone'] ?? null,
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                ],
            );
        }

        $this->info('Пользователи импортированы.');
    }

    private function importPosts(): void
    {
        $this->info('Импорт постов');

        $response = Http::get('https://jsonplaceholder.typicode.com/posts');
        if ($response->failed()) {
            $this->error('Ошибка при получении постов');
            return;
        }

        $posts = $response->json();
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                [
                    'external_id' => $post['id'],
                ],
                [
                    'user_id' => User::query()->firstWhere('external_id', $post['userId'])->id,
                    'title' => $post['title'],
                    'description' => $post['body'],
                ],
            );
        }

        $this->info('Посты импортированы.');
    }

    private function importComments(): void
    {
        $this->info('Импорт комментариев');

        $response = Http::get('https://jsonplaceholder.typicode.com/comments');
        if ($response->failed()) {
            $this->error('Ошибка при получении комментариев');
            return;
        }

        $comments = $response->json();
        foreach ($comments as $comment) {
            Comment::query()->updateOrCreate(
                [
                    'external_id' => $comment['id'],
                ],
                [
                    'post_id' => $comment['postId'],
                    'name' => $comment['name'],
                    'email' => $comment['email'],
                    'description' => $comment['body'],
                    'is_active' => false,
                ],
            );
        }

        $this->info('Посты импортированы.');
    }
}
