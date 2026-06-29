<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Store Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // 2. Create Sizes
        $sizeOrder = 0;
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'One Size', 'Standard', 'Free Size',
            'US 6', 'US 7', 'US 8', 'US 9', 'US 10', 'US 11', 'US 12',
            'EU 36', 'EU 38', 'EU 40', 'EU 42', 'EU 44',
            'UK 4', 'UK 6', 'UK 8', 'UK 10',
            '38mm', '40mm', '42mm', '44mm', '45mm',
        ];

        $sizeCategories = [
            'XS' => 'clothing', 'S' => 'clothing', 'M' => 'clothing', 'L' => 'clothing',
            'XL' => 'clothing', 'XXL' => 'clothing', 'XXXL' => 'clothing',
            'One Size' => 'general', 'Standard' => 'general', 'Free Size' => 'general',
            'US 6' => 'shoe', 'US 7' => 'shoe', 'US 8' => 'shoe', 'US 9' => 'shoe',
            'US 10' => 'shoe', 'US 11' => 'shoe', 'US 12' => 'shoe',
            'EU 36' => 'shoe', 'EU 38' => 'shoe', 'EU 40' => 'shoe', 'EU 42' => 'shoe', 'EU 44' => 'shoe',
            'UK 4' => 'shoe', 'UK 6' => 'shoe', 'UK 8' => 'shoe', 'UK 10' => 'shoe',
            '38mm' => 'watch', '40mm' => 'watch', '42mm' => 'watch', '44mm' => 'watch', '45mm' => 'watch',
        ];

        foreach ($sizes as $sizeName) {
            Size::create([
                'name' => $sizeName,
                'slug' => Str::slug($sizeName),
                'category' => $sizeCategories[$sizeName] ?? null,
                'sort_order' => $sizeOrder++,
            ]);
        }

        // 3. Create Colors
        $colors = [
            ['Matte Black', '#1e293b'],
            ['Glossy Black', '#0f172a'],
            ['Chalk White', '#f8fafc'],
            ['Pearl White', '#f1f5f9'],
            ['Midnight Blue', '#1e3a8a'],
            ['Sky Blue', '#7dd3fc'],
            ['Royal Blue', '#2563eb'],
            ['Navy Blue', '#1e3a8a'],
            ['Rose Gold', '#f472b6'],
            ['Silver Chrome', '#94a3b8'],
            ['Platinum Silver', '#e2e8f0'],
            ['Gold', '#fbbf24'],
            ['Obsidian Black', '#0f172a'],
            ['Charcoal Grey', '#334155'],
            ['Slate Grey', '#64748b'],
            ['Storm Grey', '#475569'],
            ['Crimson Red', '#ef4444'],
            ['Coral Red', '#f87171'],
            ['Fire Orange', '#f97316'],
            ['Sunset Orange', '#fdba74'],
            ['Forest Green', '#166534'],
            ['Mint Green', '#6ee7b7'],
            ['Olive Green', '#65a30d'],
            ['Emerald', '#10b981'],
            ['Vintage Brown', '#78350f'],
            ['Chestnut Brown', '#92400e'],
            ['Tan Leather', '#d97706'],
            ['Caramel', '#b45309'],
            ['Lavender', '#c4b5fd'],
            ['Violet Purple', '#a855f7'],
            ['Indigo', '#6366f1'],
            ['Neon Yellow', '#a3e635'],
            ['Teal', '#14b8a6'],
        ];

        foreach ($colors as $i => [$name, $hex]) {
            Color::create([
                'name'       => $name,
                'slug'       => Str::slug($name),
                'hex_code'   => $hex,
                'sort_order' => $i,
            ]);
        }

        // 4. Create Categories
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets, smart wearables, and sound gear.',
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Trendy clothing, accessories, and footwear.',
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'description' => 'Beautiful decor, furniture, and daily essentials.',
            ],
            [
                'name' => 'Stationery',
                'slug' => 'stationery',
                'description' => 'Premium notebooks, pens, and desk organizers.',
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = \App\Models\Category::create($cat);
        }

        // 5. Create Products
        $products = [
            // Electronics
            [
                'category_id' => $createdCategories[0]->id,
                'name' => 'Premium Wireless Headphones',
                'slug' => 'premium-wireless-headphones',
                'description' => 'Immersive sound quality with active noise cancellation, 40 hours of battery life, and ultra-comfortable earcups.',
                'price' => 199.99,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sizes' => ['Standard'],
                'colors' => ['Matte Black', 'Platinum Silver', 'Midnight Blue'],
            ],
            [
                'category_id' => $createdCategories[0]->id,
                'name' => 'Minimalist Smart Watch',
                'slug' => 'minimalist-smart-watch',
                'description' => 'Track your activity, heart rate, sleep quality, and stay connected with elegant notifications. 7-day battery life.',
                'price' => 149.00,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sizes' => ['38mm', '42mm'],
                'colors' => ['Silver Chrome', 'Rose Gold', 'Obsidian Black'],
            ],
            [
                'category_id' => $createdCategories[0]->id,
                'name' => 'Waterproof Bluetooth Speaker',
                'slug' => 'waterproof-bluetooth-speaker',
                'description' => 'Compact and portable speaker with rich bass and crystal clear sound. IPX7 waterproof rating, perfect for outdoor pool parties.',
                'price' => 59.99,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&auto=format&fit=crop&q=80',
                'is_featured' => false,
                'sizes' => ['Standard'],
                'colors' => ['Teal Blue', 'Crimson Red', 'Forest Green'],
            ],
            // Fashion
            [
                'category_id' => $createdCategories[1]->id,
                'name' => 'Vintage Leather Backpack',
                'slug' => 'vintage-leather-backpack',
                'description' => 'Handcrafted from full-grain genuine leather. Spacious main compartment with dedicated laptop sleeve, perfect for travel or daily commute.',
                'price' => 129.50,
                'stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sizes' => ['Medium', 'Large'],
                'colors' => ['Chestnut Brown', 'Tan Leather', 'Charcoal Black'],
            ],
            [
                'category_id' => $createdCategories[1]->id,
                'name' => 'Classic Aviator Sunglasses',
                'slug' => 'classic-aviator-sunglasses',
                'description' => 'Polarized lenses with 100% UV400 protection. Lightweight stainless steel frame with comfortable nose pads.',
                'price' => 45.00,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=800&auto=format&fit=crop&q=80',
                'is_featured' => false,
                'sizes' => ['Standard'],
                'colors' => ['Gold Frame / Green Lens', 'Black Frame / Dark Lens'],
            ],
            [
                'category_id' => $createdCategories[1]->id,
                'name' => 'Performance Running Shoes',
                'slug' => 'performance-running-shoes',
                'description' => 'Engineered mesh upper for breathability and responsive cushioning for maximum energy return on every run.',
                'price' => 89.99,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
                'sizes' => ['US 8', 'US 9', 'US 10', 'US 11'],
                'colors' => ['Neon Red', 'Volt Yellow', 'Stealth Grey'],
            ],
            // Home & Living
            [
                'category_id' => $createdCategories[2]->id,
                'name' => 'Artisanal Ceramic Coffee Mug',
                'slug' => 'artisanal-ceramic-coffee-mug',
                'description' => 'Individually glazed ceramic mug. Dishwasher and microwave safe. Holds 14 oz of your favorite brew.',
                'price' => 18.00,
                'stock' => 100,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $createdCategories[2]->id,
                'name' => 'Luxury Scented Soy Candle',
                'slug' => 'luxury-scented-soy-candle',
                'description' => 'Hand-poured natural soy wax candle with notes of lavender, sandalwood, and amber. 50-hour burn time.',
                'price' => 24.50,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1603006905003-be475563bc59?w=800&auto=format&fit=crop&q=80',
                'is_featured' => false,
            ],
            [
                'category_id' => $createdCategories[2]->id,
                'name' => 'Modern Brass Desk Lamp',
                'slug' => 'modern-brass-desk-lamp',
                'description' => 'Adjustable arm and shade finished in polished brass. Perfect task lighting for your workspace or study room.',
                'price' => 75.00,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            // Stationery
            [
                'category_id' => $createdCategories[3]->id,
                'name' => 'Classic Leather Journal',
                'slug' => 'classic-leather-journal',
                'description' => 'Refillable leather cover with 200 pages of thick, ink-friendly ruled paper. Includes a ribbon bookmark.',
                'price' => 32.00,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?w=800&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
        ];

        foreach ($products as $prod) {
            $p = \App\Models\Product::create($prod);

            // Add some reviews
            \App\Models\Review::create([
                'product_id' => $p->id,
                'customer_name' => 'Sarah Connor',
                'rating' => 5,
                'comment' => 'Absolutely love this! Exceeded my expectations in every way.',
                'is_approved' => true,
            ]);

            \App\Models\Review::create([
                'product_id' => $p->id,
                'customer_name' => 'Alex Mercer',
                'rating' => 4,
                'comment' => 'Very good quality for the price. Delivery was prompt.',
                'is_approved' => true,
            ]);
        }

        // 6. Create Settings
        $settings = [
            'site_name' => 'TechEx Store',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'contact_email' => 'support@techex.com',
            'shipping_fee' => '10.00',
        ];

        foreach ($settings as $key => $val) {
            \App\Models\Setting::set($key, $val);
        }
    }
}
