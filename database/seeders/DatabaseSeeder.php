<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        User::factory()->count(10)->create()->each(function ($user) {
            $imageUrl = fake()->imageUrl(200, 200, 'people');
            $imageContent = file_get_contents($imageUrl);
            $imagePath = 'profile_images/' . Str::random(10) . '.jpg';
            Storage::disk('public')->put($imagePath, $imageContent);
            $user->update(['photo_profil' => $imagePath]);
        });
        $testUser = User::factory()->create([
            'nom' => 'Test User',
            'email' => 'test@example.com',
            'photo_profil' => 'profile_images/' . Str::random(10) . '.jpg',
        ]);
        $imageUrl = fake()->imageUrl(200, 200, 'people');
        $imageContent = file_get_contents($imageUrl);
        $imagePath = 'profile_images/' . Str::random(10) . '.jpg';
        Storage::disk('public')->put($imagePath, $imageContent);
        $testUser->update(['photo_profil' => $imagePath]);
    }
}
