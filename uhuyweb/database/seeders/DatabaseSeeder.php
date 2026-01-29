<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Board;
use App\Models\Pin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        $user1 = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "username" => "johndoe",
            "password" => Hash::make("password"),
            "bio" => "Photography enthusiast and nature lover",
        ]);

        $user2 = User::create([
            "name" => "Jane Smith",
            "email" => "jane@example.com",
            "username" => "janesmith",
            "password" => Hash::make("password"),
            "bio" => "Digital artist and creative designer",
        ]);

        $user3 = User::create([
            "name" => "Admin User",
            "email" => "admin@example.com",
            "username" => "admin",
            "password" => Hash::make("password"),
            "bio" => "Platform administrator",
        ]);

        // Create sample boards
        $board1 = Board::create([
            "user_id" => $user1->id,
            "name" => "Nature Photography",
            "description" => "Beautiful landscapes and nature shots",
            "is_private" => false,
        ]);

        $board2 = Board::create([
            "user_id" => $user1->id,
            "name" => "Travel Destinations",
            "description" => "Amazing places to visit around the world",
            "is_private" => false,
        ]);

        $board3 = Board::create([
            "user_id" => $user2->id,
            "name" => "Digital Art",
            "description" => "Creative digital artwork and designs",
            "is_private" => false,
        ]);

        $board4 = Board::create([
            "user_id" => $user2->id,
            "name" => "UI/UX Inspiration",
            "description" =>
                "Modern interface designs and user experience concepts",
            "is_private" => false,
        ]);

        // Create sample pins
        $samplePins = [
            [
                "user_id" => $user1->id,
                "board_id" => $board1->id,
                "title" => "Mountain Sunrise",
                "description" =>
                    "Beautiful sunrise over the mountains in early morning light",
                "image_url" =>
                    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500",
                "link" => "https://unsplash.com/photos/mountain-sunrise",
            ],
            [
                "user_id" => $user1->id,
                "board_id" => $board1->id,
                "title" => "Forest Path",
                "description" =>
                    "A peaceful forest path surrounded by tall trees",
                "image_url" =>
                    "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=500",
                "link" => "https://unsplash.com/photos/forest-path",
            ],
            [
                "user_id" => $user1->id,
                "board_id" => $board2->id,
                "title" => "Tokyo Street",
                "description" => "Vibrant street scene in Tokyo, Japan",
                "image_url" =>
                    "https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=500",
                "link" => "https://unsplash.com/photos/tokyo-street",
            ],
            [
                "user_id" => $user1->id,
                "board_id" => $board2->id,
                "title" => "Santorini Sunset",
                "description" =>
                    "Breathtaking sunset view in Santorini, Greece",
                "image_url" =>
                    "https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?w=500",
                "link" => "https://unsplash.com/photos/santorini-sunset",
            ],
            [
                "user_id" => $user2->id,
                "board_id" => $board3->id,
                "title" => "Abstract Digital Art",
                "description" =>
                    "Colorful abstract digital artwork with geometric patterns",
                "image_url" =>
                    "https://images.unsplash.com/photo-1558591710-4b4a1ae0f04d?w=500",
                "link" => "https://unsplash.com/photos/abstract-art",
            ],
            [
                "user_id" => $user2->id,
                "board_id" => $board3->id,
                "title" => "Digital Portrait",
                "description" => "Stylized digital portrait illustration",
                "image_url" =>
                    "https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500",
                "link" => "https://unsplash.com/photos/digital-portrait",
            ],
            [
                "user_id" => $user2->id,
                "board_id" => $board4->id,
                "title" => "Modern Dashboard UI",
                "description" =>
                    "Clean and modern dashboard user interface design",
                "image_url" =>
                    "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500",
                "link" => "https://unsplash.com/photos/dashboard-ui",
            ],
            [
                "user_id" => $user2->id,
                "board_id" => $board4->id,
                "title" => "Mobile App Interface",
                "description" => "Beautiful mobile application user interface",
                "image_url" =>
                    "https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500",
                "link" => "https://unsplash.com/photos/mobile-ui",
            ],
            [
                "user_id" => $user1->id,
                "board_id" => null,
                "title" => "Ocean Waves",
                "description" => "Peaceful ocean waves on a sunny day",
                "image_url" =>
                    "https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=500",
                "link" => "https://unsplash.com/photos/ocean-waves",
            ],
            [
                "user_id" => $user2->id,
                "board_id" => null,
                "title" => "City Skyline",
                "description" =>
                    "Modern city skyline at night with illuminated buildings",
                "image_url" =>
                    "https://images.unsplash.com/photo-1480714378408-67cf0d13bc1f?w=500",
                "link" => "https://unsplash.com/photos/city-skyline",
            ],
        ];

        foreach ($samplePins as $pinData) {
            Pin::create($pinData);
        }

        $this->command->info("Sample data created successfully!");
        $this->command->info("Users created:");
        $this->command->info("- john@example.com (password: password)");
        $this->command->info("- jane@example.com (password: password)");
        $this->command->info("- admin@example.com (password: password)");
    }
}
