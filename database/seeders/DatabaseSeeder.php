<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Store;
use App\Models\Rider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Review;
use App\Models\Creator;
use App\Models\RecipeAndVlog;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\RiderWallet;
use App\Models\RiderWalletTransaction;
use App\Models\SystemSetting;
use App\Services\LipaLocationService;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. System Settings
        SystemSetting::set('base_delivery_fare', '40.00', 'pricing', 'Base delivery fare covering first 1.5 km in Lipa City');
        SystemSetting::set('base_distance_km', '1.5', 'pricing', 'Base distance included in base fare');
        SystemSetting::set('rate_per_km', '10.00', 'pricing', 'Gas & distance rate per succeeding kilometer');
        SystemSetting::set('platform_fee', '10.00', 'pricing', 'Fixed platform maintenance fee per order');
        SystemSetting::set('max_active_rider_orders', '3', 'delivery', 'Maximum concurrent active orders per rider');
        SystemSetting::set('operating_city', 'Lipa City, Batangas', 'general', 'Exclusive service area');

        // 2. Users
        // Admin
        $adminUser = User::create([
            'name' => 'NutriGo Admin',
            'email' => 'admin@nutrigo.ph',
            'phone' => '09171234567',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Customer
        $customerUser = User::create([
            'name' => 'Maria Clara Lipa',
            'email' => 'customer@nutrigo.ph',
            'phone' => '09181112233',
            'role' => 'customer',
            'password' => Hash::make('password123'),
        ]);

        $russelUser = User::create([
            'name' => 'Russel Customer',
            'email' => 'russel@nutrigo.ph',
            'phone' => '09192223344',
            'role' => 'customer',
            'password' => Hash::make('password123'),
        ]);

        // Store Owners
        $storeUser1 = User::create([
            'name' => 'Elena Reyes (Green Bites)',
            'email' => 'greenbites@nutrigo.ph',
            'phone' => '09203334455',
            'role' => 'store',
            'password' => Hash::make('password123'),
        ]);

        $storeUser2 = User::create([
            'name' => 'Carlos Mendoza (Barako Fit)',
            'email' => 'barakofit@nutrigo.ph',
            'phone' => '09214445566',
            'role' => 'store',
            'password' => Hash::make('password123'),
        ]);

        $storeUser3 = User::create([
            'name' => 'Dr. Liza Bautista (NutriFit Supplements)',
            'email' => 'nutrifit@nutrigo.ph',
            'phone' => '09225556677',
            'role' => 'store',
            'password' => Hash::make('password123'),
        ]);

        $storeUser4 = User::create([
            'name' => 'Sarah Plant (Pure Organics)',
            'email' => 'pureorganics@nutrigo.ph',
            'phone' => '09236667788',
            'role' => 'store',
            'password' => Hash::make('password123'),
        ]);

        $pendingStoreUser = User::create([
            'name' => 'Mario Deep Fry (Pending Store)',
            'email' => 'greasyburger@nutrigo.ph',
            'phone' => '09247778899',
            'role' => 'store',
            'password' => Hash::make('password123'),
        ]);

        // Rider Users
        $riderUser1 = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'rider.juan@nutrigo.ph',
            'phone' => '09258889900',
            'role' => 'rider',
            'password' => Hash::make('password123'),
        ]);

        $riderUser2 = User::create([
            'name' => 'Mark Santos',
            'email' => 'rider.mark@nutrigo.ph',
            'phone' => '09269990011',
            'role' => 'rider',
            'password' => Hash::make('password123'),
        ]);

        $riderUser3 = User::create([
            'name' => 'Pedro Penduko (Pending Rider)',
            'email' => 'rider.pedro@nutrigo.ph',
            'phone' => '09270001122',
            'role' => 'rider',
            'password' => Hash::make('password123'),
        ]);

        // 3. Categories
        $catSalads = Category::create([
            'name' => 'Fresh Salads & Bowls',
            'slug' => 'fresh-salads-bowls',
            'icon' => '🥗',
            'description' => 'Nutrient-rich microgreens, organic salads, and grain bowls.',
            'badge_color' => '#16A34A',
            'order_num' => 1,
        ]);

        $catProtein = Category::create([
            'name' => 'High-Protein & Gym Meals',
            'slug' => 'high-protein-gym-meals',
            'icon' => '🍗',
            'description' => 'Macro-balanced lean meats, fish, and protein power plates.',
            'badge_color' => '#E11D48',
            'order_num' => 2,
        ]);

        $catKeto = Category::create([
            'name' => 'Keto & Low-Carb',
            'slug' => 'keto-low-carb',
            'icon' => '🥑',
            'description' => 'Low glycemic, keto-friendly cauliflower rice and healthy fats.',
            'badge_color' => '#D97706',
            'order_num' => 3,
        ]);

        $catVegan = Category::create([
            'name' => '100% Plant-Based & Vegan',
            'slug' => 'plant-based-vegan',
            'icon' => '🌱',
            'description' => 'Delicious cruelty-free meals made with whole plant ingredients.',
            'badge_color' => '#059669',
            'order_num' => 4,
        ]);

        $catJuices = Category::create([
            'name' => 'Cold-Pressed Juices & Smoothies',
            'slug' => 'cold-pressed-juices',
            'icon' => '🥤',
            'description' => 'Raw, unpasteurized detox elixirs and superfood smoothies.',
            'badge_color' => '#0284C7',
            'order_num' => 5,
        ]);

        $catSupplements = Category::create([
            'name' => 'Supplements & Wellness',
            'slug' => 'supplements-wellness',
            'icon' => '💊',
            'description' => 'Whey protein, collagen, multivitamins, and herbal tonics.',
            'badge_color' => '#7C3AED',
            'order_num' => 6,
        ]);

        // 4. Stores in Lipa City
        $store1 = Store::create([
            'user_id' => $storeUser1->id,
            'store_name' => 'Green Bites Lipa',
            'slug' => 'green-bites-lipa',
            'description' => 'Lipa City’s premier organic salad bar and cold-pressed juice hub. Serving freshly picked local Batangas greens.',
            'logo' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1200&q=80',
            'barangay' => 'Marauoy',
            'address_line' => 'Unit 3, Ayala Highway, Brgy. Marauoy, Lipa City (Near SM City Lipa)',
            'latitude' => 13.9575,
            'longitude' => 121.1612,
            'phone' => '09203334455',
            'health_category' => 'Organic & Salads',
            'business_permit_no' => 'BP-LIPA-2026-0812',
            'health_certificate' => 'HC-BATANGAS-9921',
            'gcash_name' => 'Elena Reyes (Green Bites)',
            'gcash_number' => '09203334455',
            'status' => 'approved',
            'commission_percent' => 10.00,
            'opening_time' => '07:30:00',
            'closing_time' => '20:30:00',
            'is_open' => true,
            'rating' => 4.95,
            'total_reviews' => 48,
        ]);

        $store2 = Store::create([
            'user_id' => $storeUser2->id,
            'store_name' => 'Barako Fit Kitchen',
            'slug' => 'barako-fit-kitchen',
            'description' => 'High-protein, calorie-counted gourmet meal preps crafted for athletes and fitness enthusiasts across Lipa.',
            'logo' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80',
            'barangay' => 'Sabang',
            'address_line' => 'Block 4, B. Morada Ave, Brgy. Sabang, Lipa City',
            'latitude' => 13.9340,
            'longitude' => 121.1570,
            'phone' => '09214445566',
            'health_category' => 'High-Protein & Gym Meals',
            'business_permit_no' => 'BP-LIPA-2026-1144',
            'health_certificate' => 'HC-BATANGAS-7819',
            'gcash_name' => 'Carlos Mendoza',
            'gcash_number' => '09214445566',
            'status' => 'approved',
            'commission_percent' => 10.00,
            'opening_time' => '08:00:00',
            'closing_time' => '21:00:00',
            'is_open' => true,
            'rating' => 4.88,
            'total_reviews' => 62,
        ]);

        $store3 = Store::create([
            'user_id' => $storeUser3->id,
            'store_name' => 'NutriFit Supplements Lipa',
            'slug' => 'nutrifit-supplements-lipa',
            'description' => 'Official certified supplier of FDA-approved sports nutrition, pure whey isolate, collagen peptides, and plant vitamins in Batangas.',
            'logo' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1579722821273-0f6c7d44362f?w=1200&q=80',
            'barangay' => 'Banaybanay',
            'address_line' => 'G/F Commercial Plaza, Brgy. Banaybanay, Lipa City',
            'latitude' => 13.9620,
            'longitude' => 121.1480,
            'phone' => '09225556677',
            'health_category' => 'Supplements & Wellness',
            'business_permit_no' => 'BP-LIPA-2026-3390',
            'health_certificate' => 'FDA-PH-2026-4410',
            'gcash_name' => 'Liza Bautista',
            'gcash_number' => '09225556677',
            'status' => 'approved',
            'commission_percent' => 8.00,
            'opening_time' => '09:00:00',
            'closing_time' => '19:00:00',
            'is_open' => true,
            'rating' => 4.92,
            'total_reviews' => 31,
        ]);

        $store4 = Store::create([
            'user_id' => $storeUser4->id,
            'store_name' => 'Pure Organics & Vegan Deli',
            'slug' => 'pure-organics-vegan-deli',
            'description' => 'Guilt-free 100% plant-based delicacies, vegan burgers, dairy-free smoothies, and diabetic-friendly desserts.',
            'logo' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=1200&q=80',
            'barangay' => 'Tambo',
            'address_line' => 'Near Lipa Medix Medical Center, Brgy. Tambo, Lipa City',
            'latitude' => 13.9530,
            'longitude' => 121.1500,
            'phone' => '09236667788',
            'health_category' => '100% Plant-Based & Vegan',
            'business_permit_no' => 'BP-LIPA-2026-5512',
            'health_certificate' => 'HC-BATANGAS-3320',
            'gcash_name' => 'Sarah Plant',
            'gcash_number' => '09236667788',
            'status' => 'approved',
            'commission_percent' => 10.00,
            'opening_time' => '08:30:00',
            'closing_time' => '20:00:00',
            'is_open' => true,
            'rating' => 4.90,
            'total_reviews' => 27,
        ]);

        // Pending Store for Admin Vetting Demo
        Store::create([
            'user_id' => $pendingStoreUser->id,
            'store_name' => 'Greasy Double Burger & Fries (Pending Approval)',
            'slug' => 'greasy-double-burger',
            'description' => 'Deep fried double bacon cheese burgers and sugared donuts.',
            'logo' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80',
            'banner' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=1200&q=80',
            'barangay' => 'Balintawak',
            'address_line' => 'National Road, Brgy. Balintawak, Lipa City',
            'latitude' => 13.9556,
            'longitude' => 121.1550,
            'phone' => '09247778899',
            'health_category' => 'Fast Food',
            'business_permit_no' => 'BP-LIPA-2026-9090',
            'status' => 'pending', // Pending Admin review
            'commission_percent' => 10.00,
        ]);

        // 5. Riders in Lipa City
        $rider1 = Rider::create([
            'user_id' => $riderUser1->id,
            'vehicle_type' => 'Motorcycle (Honda Beat 125)',
            'plate_number' => 'LPA-1234',
            'license_number' => 'N02-21-987654',
            'phone' => '09258889900',
            'barangay' => 'Sabang',
            'current_latitude' => 13.9340,
            'current_longitude' => 121.1570,
            'status' => 'approved',
            'is_online' => true,
            'active_orders_count' => 0,
            'total_deliveries' => 142,
            'rating' => 4.97,
        ]);

        $rider2 = Rider::create([
            'user_id' => $riderUser2->id,
            'vehicle_type' => 'Motorcycle (Yamaha Mio 125)',
            'plate_number' => 'BAT-5678',
            'license_number' => 'N02-23-456789',
            'phone' => '09269990011',
            'barangay' => 'Tambo',
            'current_latitude' => 13.9530,
            'current_longitude' => 121.1500,
            'status' => 'approved',
            'is_online' => true,
            'active_orders_count' => 0,
            'total_deliveries' => 98,
            'rating' => 4.91,
        ]);

        // Pending Rider for Admin Vetting Demo
        Rider::create([
            'user_id' => $riderUser3->id,
            'vehicle_type' => 'Electric Scooter',
            'plate_number' => 'PENDING-99',
            'license_number' => 'N02-25-112233',
            'phone' => '09270001122',
            'barangay' => 'Lodlod',
            'status' => 'pending',
            'is_online' => false,
        ]);

        // Create Rider Wallets
        $wallet1 = RiderWallet::create([
            'rider_id' => $rider1->id,
            'balance' => 840.00,
            'total_earnings' => 9240.00,
            'pending_payout' => 0.00,
        ]);

        $wallet2 = RiderWallet::create([
            'rider_id' => $rider2->id,
            'balance' => 495.00,
            'total_earnings' => 6370.00,
            'pending_payout' => 0.00,
        ]);

        // 6. Healthy Products with Calories, Macros, Badges, and Smart Healthy Swaps
        // Store 1: Green Bites Lipa
        $p1 = Product::create([
            'store_id' => $store1->id,
            'category_id' => $catSalads->id,
            'name' => 'Avocado Quinoa Power Salad',
            'slug' => 'avocado-quinoa-power-salad',
            'description' => 'Fresh Batangas hydroponic lettuce, hass avocado slices, organic tri-color quinoa, cherry tomatoes, and lemon-tahini dressing.',
            'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&q=80',
            'price' => 240.00,
            'original_price' => 280.00,
            'calories' => 380,
            'protein_g' => 14.5,
            'carbs_g' => 32.0,
            'fat_g' => 18.0,
            'dietary_tags' => ['Vegan', 'Gluten-Free', 'High-Fiber', 'Heart-Healthy'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'healthy_alternative_notes' => 'Swaps traditional oily dressing for nutrient-rich omega fats and whole grains.',
            'stock' => 50,
        ]);

        $p2 = Product::create([
            'store_id' => $store1->id,
            'category_id' => $catSalads->id,
            'name' => 'Mediterranean Chicken Harvest Bowl',
            'slug' => 'mediterranean-chicken-harvest-bowl',
            'description' => 'Herb-grilled chicken breast, roasted pumpkin, cucumbers, feta cheese, and cold-pressed extra virgin olive oil.',
            'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
            'price' => 275.00,
            'calories' => 420,
            'protein_g' => 38.0,
            'carbs_g' => 26.0,
            'fat_g' => 12.0,
            'dietary_tags' => ['High-Protein', 'Low-Glycemic', 'Diabetic-Friendly'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'stock' => 45,
        ]);

        $p3 = Product::create([
            'store_id' => $store1->id,
            'category_id' => $catJuices->id,
            'name' => 'Pure Green Detox Elixir (500ml)',
            'slug' => 'pure-green-detox-elixir',
            'description' => '100% cold-pressed kale, cucumber, green apple, ginger, and Lipa calamansi. Zero added sugar or preservatives.',
            'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=600&q=80',
            'price' => 160.00,
            'calories' => 110,
            'protein_g' => 2.5,
            'carbs_g' => 24.0,
            'fat_g' => 0.5,
            'dietary_tags' => ['Detox', 'No-Added-Sugar', 'Immunity-Boost', 'Vegan'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'stock' => 60,
        ]);

        // Store 2: Barako Fit Kitchen
        $p4 = Product::create([
            'store_id' => $store2->id,
            'category_id' => $catProtein->id,
            'name' => 'Barako High-Protein Steak & Cauliflower Mash',
            'slug' => 'barako-high-protein-steak-cauliflower-mash',
            'description' => 'Lean sirloin beef steak grilled to perfection, paired with buttery garlic cauliflower mash and steamed broccoli florets.',
            'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&q=80',
            'price' => 320.00,
            'calories' => 460,
            'protein_g' => 48.0,
            'carbs_g' => 11.0,
            'fat_g' => 16.0,
            'dietary_tags' => ['Keto', 'High-Protein', 'Low-Carb', 'Diabetic-Friendly'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'alternative_to_id' => $p2->id,
            'healthy_alternative_notes' => 'Replaces mashed potatoes with high-fiber cauliflower mash to reduce carbs by 75%.',
            'stock' => 40,
        ]);

        $p5 = Product::create([
            'store_id' => $store2->id,
            'category_id' => $catProtein->id,
            'name' => 'Citrus Pepper Grilled Salmon Bowl',
            'slug' => 'citrus-pepper-grilled-salmon-bowl',
            'description' => 'Omega-3 rich wild salmon fillet over organic brown rice, edamame beans, and roasted sesame greens.',
            'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&q=80',
            'price' => 345.00,
            'calories' => 490,
            'protein_g' => 42.0,
            'carbs_g' => 34.0,
            'fat_g' => 14.5,
            'dietary_tags' => ['Omega-3', 'High-Protein', 'Heart-Healthy'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'stock' => 35,
        ]);

        $p6 = Product::create([
            'store_id' => $store2->id,
            'category_id' => $catKeto->id,
            'name' => 'Keto Chicken Adobo Cauliflower Rice',
            'slug' => 'keto-chicken-adobo-cauliflower-rice',
            'description' => 'Classic Batangas-style adobo prepared with coconut aminos (low sodium, zero sugar) paired with warm cauliflower rice.',
            'image' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&q=80',
            'price' => 260.00,
            'calories' => 390,
            'protein_g' => 36.0,
            'carbs_g' => 8.0,
            'fat_g' => 18.0,
            'dietary_tags' => ['Keto', 'Low-Sodium', 'Gluten-Free'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'stock' => 40,
        ]);

        // Store 3: NutriFit Supplements Lipa
        $p7 = Product::create([
            'store_id' => $store3->id,
            'category_id' => $catSupplements->id,
            'name' => 'Pure Whey Isolate 100% (Vanilla Cream 2lbs)',
            'slug' => 'pure-whey-isolate-vanilla-2lbs',
            'description' => 'Ultra-filtered whey isolate delivering 27g protein and 0g sugar per scoop. Supports lean muscle recovery and satiety.',
            'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&q=80',
            'price' => 1850.00,
            'original_price' => 2100.00,
            'calories' => 120,
            'protein_g' => 27.0,
            'carbs_g' => 1.0,
            'fat_g' => 0.5,
            'dietary_tags' => ['High-Protein', 'Zero-Sugar', 'Supplement', 'FDA-Approved'],
            'is_healthy_choice' => true,
            'is_supplement' => true,
            'stock' => 25,
        ]);

        $p8 = Product::create([
            'store_id' => $store3->id,
            'category_id' => $catSupplements->id,
            'name' => 'Hydrolyzed Marine Collagen Peptides (300g)',
            'slug' => 'marine-collagen-peptides-300g',
            'description' => 'Grass-fed type 1 & 3 marine collagen with Vitamin C and Hyaluronic Acid for radiant skin and joint recovery.',
            'image' => 'https://images.unsplash.com/photo-1579722821273-0f6c7d44362f?w=600&q=80',
            'price' => 1250.00,
            'calories' => 45,
            'protein_g' => 10.0,
            'carbs_g' => 0.0,
            'fat_g' => 0.0,
            'dietary_tags' => ['Anti-Aging', 'Skin-Health', 'Supplement'],
            'is_healthy_choice' => true,
            'is_supplement' => true,
            'stock' => 30,
        ]);

        // Store 4: Pure Organics & Vegan Deli
        $p9 = Product::create([
            'store_id' => $store4->id,
            'category_id' => $catVegan->id,
            'name' => 'Crispy Tofu & Shiitake Mushroom Sisig',
            'slug' => 'tofu-shiitake-mushroom-sisig',
            'description' => 'Batangas sizzling sisig reimagined with organic firm tofu, King oyster mushrooms, calamansi, and chili over red rice.',
            'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&q=80',
            'price' => 235.00,
            'calories' => 340,
            'protein_g' => 22.0,
            'carbs_g' => 28.0,
            'fat_g' => 12.0,
            'dietary_tags' => ['100% Vegan', 'Cholesterol-Free', 'High-Fiber'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'stock' => 50,
        ]);

        $p10 = Product::create([
            'store_id' => $store4->id,
            'category_id' => $catVegan->id,
            'name' => 'Dragonfruit Berry Super Acai Parfait',
            'slug' => 'dragonfruit-berry-super-acai-parfait',
            'description' => 'Organic organic acai blended with frozen bananas, topped with Lipa pitaya cubes, chia seeds, and gluten-free oats.',
            'image' => 'https://images.unsplash.com/photo-1511690656952-34342bb7c2f2?w=600&q=80',
            'price' => 220.00,
            'calories' => 280,
            'protein_g' => 8.5,
            'carbs_g' => 46.0,
            'fat_g' => 6.0,
            'dietary_tags' => ['Vegan', 'Antioxidant-Rich', 'Gluten-Free'],
            'is_healthy_choice' => true,
            'is_supplement' => false,
            'stock' => 45,
        ]);

        // 7. Content Creators & Cooking Vlogs
        $creator1 = Creator::create([
            'name' => 'Coach Mika Santos',
            'channel_name' => 'Mika Fits & Eats',
            'bio' => 'Certified Sports Nutritionist & Lipa-based fitness content creator. Teaching busy Filipinos how to cook high-protein, calorie-friendly meals in under 15 minutes!',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&q=80',
            'specialties' => 'High-Protein Prep, Calorie Counting, Post-Workout Fuel',
            'youtube_url' => 'https://youtube.com',
            'tiktok_url' => 'https://tiktok.com',
            'instagram_url' => 'https://instagram.com',
            'is_verified' => true,
        ]);

        $creator2 = Creator::create([
            'name' => 'Chef Carlo Plant-Power',
            'channel_name' => 'Plant-Based Batangas',
            'bio' => 'Culinary chef specializing in converting Pinoy comfort foods into 100% wholesome vegan powerhouses with zero cholesterol.',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&q=80',
            'specialties' => 'Vegan Pinoy Classics, Anti-Inflammatory, Whole Foods',
            'youtube_url' => 'https://youtube.com',
            'tiktok_url' => 'https://tiktok.com',
            'instagram_url' => 'https://instagram.com',
            'is_verified' => true,
        ]);

        // Recipe Vlogs
        RecipeAndVlog::create([
            'creator_id' => $creator1->id,
            'store_id' => $store2->id,
            'title' => '5-Minute Post-Workout High-Protein Garlic Chicken Plate',
            'slug' => '5-minute-high-protein-garlic-chicken',
            'description' => 'Learn how to whip up a 48g protein dinner in 10 minutes without sacrificing flavor. Pairs perfectly with Barako Fit Kitchen ingredients!',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_embed' => '<iframe width="100%" height="315" src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" title="Cooking Video" frameborder="0" allowfullscreen></iframe>',
            'thumbnail' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80',
            'prep_time_mins' => 12,
            'calories' => 450,
            'protein_g' => 48.0,
            'carbs_g' => 22.0,
            'fat_g' => 11.0,
            'ingredients' => [
                '200g Lean Chicken Breast',
                '1 cup Steamed Broccoli',
                '1 cup Cauliflower Rice',
                '2 cloves Batangas Garlic',
                '1 tbsp Cold-Pressed Olive Oil',
            ],
            'instructions' => "1. Season chicken with salt, black pepper, and minced garlic.\n2. Sear on a non-stick skillet for 4-5 minutes each side until golden.\n3. Flash-fry cauliflower rice with herbs.\n4. Plate and enjoy your macro-packed post-workout meal!",
            'is_premium_only' => false,
            'linked_product_ids' => [$p2->id, $p4->id],
            'views_count' => 1240,
        ]);

        RecipeAndVlog::create([
            'creator_id' => $creator2->id,
            'store_id' => $store4->id,
            'title' => 'Crispy Tofu Sisig with Calamansi & Shiitake (Vegan Masterclass)',
            'slug' => 'crispy-tofu-sisig-vegan-masterclass',
            'description' => 'Exclusive VIP Cooking Guide: How to extract maximum umami from Batangas mushrooms and achieve ultra-crispy tofu without deep-frying.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_embed' => '<iframe width="100%" height="315" src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" title="Cooking Video" frameborder="0" allowfullscreen></iframe>',
            'thumbnail' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&q=80',
            'prep_time_mins' => 18,
            'calories' => 320,
            'protein_g' => 24.0,
            'carbs_g' => 20.0,
            'fat_g' => 10.0,
            'ingredients' => [
                '300g Organic Firm Tofu',
                '100g Fresh Shiitake Mushrooms',
                '3 pcs Lipa Calamansi',
                '1 Red Onion (diced)',
                '1 pc Red Chili (siling labuyo)',
            ],
            'instructions' => "1. Press and cube tofu. Air-fry at 200C for 12 minutes.\n2. Sauté mushrooms and red onions until caramelized.\n3. Toss in crispy tofu, fresh calamansi juice, and chili.\n4. Serve piping hot!",
            'is_premium_only' => true, // Premium VIP content
            'linked_product_ids' => [$p9->id, $p10->id],
            'views_count' => 890,
        ]);

        // 8. Subscription Plans
        $planMonthly = SubscriptionPlan::create([
            'name' => 'NutriGo VIP Monthly',
            'slug' => 'nutrigo-vip-monthly',
            'price' => 299.00,
            'billing_period' => 'monthly',
            'badge' => 'Most Popular',
            'description' => 'Perfect for health-conscious foodies wanting weekly meal plans and delivery discounts.',
            'perks' => [
                'Free delivery on 5 orders per month (Save up to ₱350)',
                'Full access to Exclusive Vlogger Cooking Masterclasses',
                'Interactive Personalized Calorie & Macro Meal Planner',
                '10% Discount on all Partner Supplement Stores',
                'Priority Rider Dispatch & VIP Customer Support',
            ],
            'is_featured' => true,
        ]);

        $planAnnual = SubscriptionPlan::create([
            'name' => 'NutriGo VIP Annual',
            'slug' => 'nutrigo-vip-annual',
            'price' => 2499.00,
            'billing_period' => 'annual',
            'badge' => 'Best Value (Save 30%)',
            'description' => 'Complete full-year healthy lifestyle upgrade with customized nutritionist meal guidance.',
            'perks' => [
                'Unlimited Free Delivery on orders above ₱300',
                '1-on-1 Monthly Consultation with NutriGo Nutrition Coach',
                'All VIP Masterclasses & High-Protein Meal Prep Series',
                '15% Discount on all Partner Health Supplements',
                'Exclusive Invitations to Lipa Fitness & Wellness Workshops',
            ],
            'is_featured' => false,
        ]);

        // Subscribe Customer Maria Clara
        UserSubscription::create([
            'user_id' => $customerUser->id,
            'plan_id' => $planMonthly->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'payment_reference' => 'GCASH-SUB-998811',
        ]);

        // 9. Sample Active Orders with Point-to-Point Distance, GCash Proof, and Live Tracking
        $storeLat = $store1->latitude;
        $storeLng = $store1->longitude;
        $destLat = 13.9340; // Sabang
        $destLng = 13.1570;
        $distanceKm = LipaLocationService::calculateDistance($storeLat, $storeLng, 13.9340, 121.1570);
        $feeData = LipaLocationService::calculateDeliveryFee($distanceKm);

        $order1 = Order::create([
            'order_number' => 'NTR-2026-1001',
            'user_id' => $customerUser->id,
            'store_id' => $store1->id,
            'rider_id' => $rider1->id,
            'status' => 'on_the_way',
            'subtotal' => 435.00,
            'delivery_fee' => $feeData['delivery_fee'],
            'platform_fee' => 10.00,
            'discount_amount' => 0.00,
            'total_amount' => 435.00 + $feeData['delivery_fee'] + 10.00,
            'payment_method' => 'gcash',
            'payment_status' => 'verified',
            'payment_proof_image' => 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?w=600&q=80',
            'payment_reference_no' => '1002938475819',
            'recipient_name' => 'Maria Clara Lipa',
            'recipient_phone' => '09181112233',
            'delivery_barangay' => 'Sabang',
            'delivery_address' => 'House 18, Emerald St., Villa Sto. Tomas, Brgy. Sabang, Lipa City',
            'delivery_landmark' => 'Beside yellow gate, in front of San Sebastian sari-sari store',
            'delivery_notes' => 'Please ring doorbell 2x. Food is for lunch.',
            'delivery_latitude' => 13.9340,
            'delivery_longitude' => 121.1570,
            'distance_km' => $distanceKm,
            'delivery_type' => 'immediate',
            'estimated_prep_time_mins' => 15,
            'estimated_delivery_time_mins' => $feeData['estimated_time_mins'],
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'price' => $p1->price,
            'quantity' => 1,
            'calories' => $p1->calories,
            'subtotal' => $p1->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p3->id,
            'product_name' => $p3->name,
            'price' => $p3->price,
            'quantity' => 1,
            'calories' => $p3->calories,
            'subtotal' => $p3->price,
        ]);

        // Order Trackings for Order 1
        OrderTracking::create([
            'order_id' => $order1->id,
            'status' => 'pending_store',
            'title' => 'Order Placed & GCash Verified',
            'description' => 'Customer uploaded GCash proof with Reference #1002938475819.',
            'latitude' => $storeLat,
            'longitude' => $storeLng,
            'created_at' => now()->subMinutes(25),
        ]);

        OrderTracking::create([
            'order_id' => $order1->id,
            'status' => 'store_accepted_preparing',
            'title' => 'Store Accepted & Preparing Food',
            'description' => 'Green Bites Lipa verified payment and started healthy food prep.',
            'latitude' => $storeLat,
            'longitude' => $storeLng,
            'created_at' => now()->subMinutes(18),
        ]);

        OrderTracking::create([
            'order_id' => $order1->id,
            'status' => 'rider_picked_up',
            'title' => 'Rider Picked Up Fresh Order',
            'description' => 'Rider Juan Dela Cruz (Honda Beat LPA-1234) collected your meal.',
            'latitude' => $storeLat,
            'longitude' => $storeLng,
            'created_at' => now()->subMinutes(8),
        ]);

        OrderTracking::create([
            'order_id' => $order1->id,
            'status' => 'on_the_way',
            'title' => 'On The Way to Your Address',
            'description' => 'Rider is cruising along B. Morada Ave towards Brgy. Sabang. ETA: ~6 mins.',
            'latitude' => 13.9450,
            'longitude' => 121.1590,
            'created_at' => now()->subMinutes(2),
        ]);

        // Sample Completed Order with Review
        $order2 = Order::create([
            'order_number' => 'NTR-2026-1002',
            'user_id' => $customerUser->id,
            'store_id' => $store2->id,
            'rider_id' => $rider1->id,
            'status' => 'delivered',
            'subtotal' => 580.00,
            'delivery_fee' => 50.00,
            'platform_fee' => 10.00,
            'total_amount' => 640.00,
            'payment_method' => 'gcash',
            'payment_status' => 'verified',
            'payment_reference_no' => '1009988776655',
            'recipient_name' => 'Maria Clara Lipa',
            'recipient_phone' => '09181112233',
            'delivery_barangay' => 'Marauoy',
            'delivery_address' => 'Aura Heights, Brgy. Marauoy, Lipa City',
            'delivery_latitude' => 13.9575,
            'delivery_longitude' => 121.1612,
            'distance_km' => 2.4,
            'delivery_type' => 'immediate',
            'rider_delivered_at' => now()->subDays(1),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p4->id,
            'product_name' => $p4->name,
            'price' => $p4->price,
            'quantity' => 1,
            'calories' => $p4->calories,
            'subtotal' => $p4->price,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p6->id,
            'product_name' => $p6->name,
            'price' => $p6->price,
            'quantity' => 1,
            'calories' => $p6->calories,
            'subtotal' => $p6->price,
        ]);

        Review::create([
            'order_id' => $order2->id,
            'user_id' => $customerUser->id,
            'store_id' => $store2->id,
            'rider_id' => $rider1->id,
            'store_rating' => 5,
            'store_comment' => 'Ang sarap ng steak at cauliflower mash! Very accurate ang calorie and macro counts!',
            'rider_rating' => 5,
            'rider_comment' => 'Mabilis at maingat si kuya rider, mainit pa yung pagkain pagdating!',
            'health_satisfaction' => 'Fresh & Macro-Accurate',
        ]);

        RiderWalletTransaction::create([
            'rider_id' => $rider1->id,
            'order_id' => $order2->id,
            'type' => 'delivery_fee',
            'amount' => 50.00,
            'description' => 'Delivery Fee for Order #NTR-2026-1002 (Marauoy)',
        ]);
    }
}
