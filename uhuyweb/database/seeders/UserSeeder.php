<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Board;
use App\Models\Pin;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        // User 1 - Main test user
        $users[] = User::create([
            "name" => "Sarah Johnson",
            "email" => "test@example.com",
            "password" => Hash::make("password"),
            "username" => "sarahjohnson",
            "bio" =>
                "UI/UX Designer | Love minimalist design and beautiful interfaces",
        ]);

        // User 2 - Photographer
        $users[] = User::create([
            "name" => "Mike Chen",
            "email" => "mike@example.com",
            "password" => Hash::make("password"),
            "username" => "mikechen",
            "bio" =>
                "Professional photographer capturing life's beautiful moments",
        ]);

        // User 3 - Architect
        $users[] = User::create([
            "name" => "Emma Rodriguez",
            "email" => "emma@example.com",
            "password" => Hash::make("password"),
            "username" => "emmarodriguez",
            "bio" => "Architect passionate about sustainable and modern design",
        ]);

        // User 4 - Artist
        $users[] = User::create([
            "name" => "David Kim",
            "email" => "david@example.com",
            "password" => Hash::make("password"),
            "username" => "davidkim",
            "bio" => "Digital artist and creative director",
        ]);

        // Create sample boards for each user
        $boards = [];

        // Sarah's boards (Designer)
        $boards[] = Board::create([
            "user_id" => $users[0]->id,
            "name" => "UI/UX Inspiration",
            "description" => "Beautiful user interface and experience designs",
            "is_private" => false,
        ]);

        $boards[] = Board::create([
            "user_id" => $users[0]->id,
            "name" => "Color Palettes",
            "description" => "Inspiring color combinations for design projects",
            "is_private" => false,
        ]);

        // Mike's boards (Photographer)
        $boards[] = Board::create([
            "user_id" => $users[1]->id,
            "name" => "Landscape Photography",
            "description" => "Breathtaking landscape and nature photography",
            "is_private" => false,
        ]);

        $boards[] = Board::create([
            "user_id" => $users[1]->id,
            "name" => "Portrait Sessions",
            "description" => "Professional portrait photography work",
            "is_private" => false,
        ]);

        // Emma's boards (Architect)
        $boards[] = Board::create([
            "user_id" => $users[2]->id,
            "name" => "Modern Architecture",
            "description" => "Contemporary architectural designs and concepts",
            "is_private" => false,
        ]);

        $boards[] = Board::create([
            "user_id" => $users[2]->id,
            "name" => "Interior Design",
            "description" => "Beautiful interior spaces and design ideas",
            "is_private" => false,
        ]);

        // David's boards (Artist)
        $boards[] = Board::create([
            "user_id" => $users[3]->id,
            "name" => "Digital Art",
            "description" => "Digital artwork and creative illustrations",
            "is_private" => false,
        ]);

        $boards[] = Board::create([
            "user_id" => $users[3]->id,
            "name" => "Personal Sketches",
            "description" => "Personal artwork and sketch collection",
            "is_private" => true,
        ]);

        // Create realistic sample pins
        $samplePins = [
            // Sarah's UI/UX pins
            [
                "user_id" => $users[0]->id,
                "board_id" => $boards[0]->id,
                "title" => "Modern Dashboard Design",
                "description" =>
                    "Clean and intuitive dashboard interface with beautiful data visualization components.",
                "image_url" => null, // Will show placeholder
                "link" => "https://dribbble.com/shots/dashboard-ui",
            ],
            [
                "user_id" => $users[0]->id,
                "board_id" => $boards[0]->id,
                "title" => "Mobile App Interface",
                "description" =>
                    "Minimalist mobile app design with focus on user experience and accessibility.",
                "image_url" => null,
                "link" => "https://behance.net/gallery/mobile-app",
            ],
            [
                "user_id" => $users[0]->id,
                "board_id" => $boards[1]->id,
                "title" => "Ocean Blue Palette",
                "description" =>
                    "Calming blue color palette inspired by ocean waves and sky.",
                "image_url" => null,
            ],

            // Mike's photography pins
            [
                "user_id" => $users[1]->id,
                "board_id" => $boards[2]->id,
                "title" => "Mountain Sunrise",
                "description" =>
                    "Breathtaking sunrise over mountain peaks captured during golden hour.",
                "image_url" => null,
                "link" => "https://unsplash.com/@mikechen",
            ],
            [
                "user_id" => $users[1]->id,
                "board_id" => $boards[2]->id,
                "title" => "Forest Path",
                "description" =>
                    "Mystical forest path with dappled sunlight filtering through trees.",
                "image_url" => null,
            ],
            [
                "user_id" => $users[1]->id,
                "board_id" => $boards[3]->id,
                "title" => "Professional Headshot",
                "description" =>
                    "Corporate headshot with natural lighting and professional styling.",
                "image_url" => null,
            ],

            // Emma's architecture pins
            [
                "user_id" => $users[2]->id,
                "board_id" => $boards[4]->id,
                "title" => "Glass House Design",
                "description" =>
                    "Contemporary glass house design that blends with natural surroundings.",
                "image_url" => null,
                "link" => "https://archdaily.com/glass-house",
            ],
            [
                "user_id" => $users[2]->id,
                "board_id" => $boards[4]->id,
                "title" => "Sustainable Office Building",
                "description" =>
                    "Eco-friendly office building with green roof and solar panels.",
                "image_url" => null,
            ],
            [
                "user_id" => $users[2]->id,
                "board_id" => $boards[5]->id,
                "title" => "Scandinavian Living Room",
                "description" =>
                    "Minimalist Scandinavian interior with natural materials and clean lines.",
                "image_url" => null,
            ],

            // David's art pins
            [
                "user_id" => $users[3]->id,
                "board_id" => $boards[6]->id,
                "title" => "Digital Portrait",
                "description" =>
                    "Digital art portrait created with vibrant colors and dynamic brushstrokes.",
                "image_url" => null,
                "link" => "https://artstation.com/davidkim",
            ],
            [
                "user_id" => $users[3]->id,
                "board_id" => $boards[6]->id,
                "title" => "Abstract Composition",
                "description" =>
                    "Modern abstract digital artwork exploring color theory and form.",
                "image_url" => null,
            ],
            [
                "user_id" => $users[3]->id,
                "board_id" => $boards[7]->id,
                "title" => "Character Sketch",
                "description" =>
                    "Personal character sketch exploring different artistic techniques.",
                "image_url" => null,
            ],
        ];

        foreach ($samplePins as $pinData) {
            Pin::create($pinData);
        }

        echo "✅ Created 4 realistic users:\n";
        echo "   - Sarah Johnson (test@example.com) - UI/UX Designer\n";
        echo "   - Mike Chen (mike@example.com) - Photographer\n";
        echo "   - Emma Rodriguez (emma@example.com) - Architect\n";
        echo "   - David Kim (david@example.com) - Digital Artist\n";
        echo "✅ Created 8 boards and 12 sample pins\n";
        echo "✅ All users use password: 'password'\n";
        echo "🚀 You can now login and test the My Pins system!\n";
        echo "🎨 Explore page will now show diverse content from different users!\n";
    }
}
