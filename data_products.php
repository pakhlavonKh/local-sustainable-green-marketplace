<?php
// CENTRAL PRODUCT DATABASE (25 Realistic Items)
// Each item now has 'title' (English) and 'title_tr' (Turkish)
// Images are selected for reliability and speed (Unsplash w=500 optimization)

$products_db = [
    // --- FRESH PRODUCE (1-5) ---
    1 => [
        'id' => 1, 'category' => 'fresh_produce',
        'title'=>'Red Apples', 'title_tr'=>'Kırmızı Elma',
        'price'=>25.00, 'stock'=>50, 
        'desc'=>'Crisp, sweet, and locally grown red apples.', 
        'image'=>'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=500',
        'delivery_mode'=>'bike', 'packaging_type'=>'plastic_free', 'location'=>'Kadikoy',
        'seller_name'=>'Ahmet\'s Orchard', 'seller_id'=>101, 'distance'=>'3.2 km', 'co2_saved'=>'0.5'
    ],
    2 => [
        'id' => 2, 'category' => 'fresh_produce',
        'title'=>'Organic Carrots', 'title_tr'=>'Organik Havuç',
        'price'=>15.00, 'stock'=>30, 
        'desc'=>'Crunchy organic carrots harvested this morning.',    
        'image'=>'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=500',
        'delivery_mode'=>'bike', 'packaging_type'=>'recycled', 'location'=>'Besiktas',
        'seller_name'=>'Green Roots Farm', 'seller_id'=>102, 'distance'=>'5.1 km', 'co2_saved'=>'0.3'
    ],
    3 => [
        'id' => 3, 'category' => 'fresh_produce',
        'title'=>'Fresh Spinach', 'title_tr'=>'Taze Ispanak',
        'price'=>20.00, 'stock'=>15, 
        'desc'=>'Leafy green spinach, washed and ready to eat.',                         
        'image'=>'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=500',
        'delivery_mode'=>'public', 'packaging_type'=>'plastic_free', 'location'=>'Uskudar',
        'seller_name'=>'Uskudar Gardens', 'seller_id'=>104, 'distance'=>'4.5 km', 'co2_saved'=>'0.4'
    ],
    4 => [
        'id' => 4, 'category' => 'fresh_produce',
        'title'=>'Ripe Avocados', 'title_tr'=>'Olgun Avokado',
        'price'=>45.00, 'stock'=>10, 
        'desc'=>'Creamy, perfectly ripe avocados from the Mediterranean coast.',
        'image'=>'https://i.pinimg.com/736x/a8/32/dc/a832dc4c3c955171786001198ce539f5.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'recycled', 'location'=>'Antalya',
        'seller_name'=>'Sunny Groves', 'seller_id'=>110, 'distance'=>'500 km', 'co2_saved'=>'0.1'
    ],
    5 => [
        'id' => 5, 'category' => 'fresh_produce',
        'title'=>'Cherry Tomatoes', 'title_tr'=>'Çeri Domates',
        'price'=>18.00, 'stock'=>40, 
        'desc'=>'Sweet and juicy cherry tomatoes, perfect for salads.',
        'image'=>'https://i.pinimg.com/736x/59/51/13/595113f5bf672c3288bb5eac92e6bcb7.jpg',
        'delivery_mode'=>'bike', 'packaging_type'=>'plastic_free', 'location'=>'Sariyer',
        'seller_name'=>'City Greenhouse', 'seller_id'=>111, 'distance'=>'12 km', 'co2_saved'=>'0.4'
    ],

    // --- DAIRY & EGGS (6-10) ---
    6 => [
        'id' => 6, 'category' => 'dairy_eggs',
        'title'=>'Free Range Eggs', 'title_tr'=>'Gezen Tavuk Yumurtası',
        'price'=>45.00, 'stock'=>20, 
        'desc'=>'Farm-fresh eggs from happy, free-roaming chickens.',                   
        'image'=>'https://i.pinimg.com/1200x/20/e5/35/20e5351bb7012cd0f28b660fdfeb9e7f.jpg',
        'delivery_mode'=>'walk', 'packaging_type'=>'recycled', 'location'=>'Moda',
        'seller_name'=>'Happy Hen Coop', 'seller_id'=>103, 'distance'=>'1.2 km', 'co2_saved'=>'0.8'
    ],
    7 => [
        'id' => 7, 'category' => 'dairy_eggs',
        'title'=>'Fresh Farm Milk', 'title_tr'=>'Taze Çiftlik Sütü',
        'price'=>30.00, 'stock'=>12, 
        'desc'=>'Raw, unpasteurized milk from local grass-fed cows.',
        'image'=>'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=500',
        'delivery_mode'=>'bike', 'packaging_type'=>'glass', 'location'=>'Kemerburgaz',
        'seller_name'=>'Milky Way', 'seller_id'=>107, 'distance'=>'15 km', 'co2_saved'=>'0.4'
    ],
    8 => [
        'id' => 8, 'category' => 'dairy_eggs',
        'title'=>'Goat Cheese', 'title_tr'=>'Keçi Peyniri',
        'price'=>85.00, 'stock'=>8, 
        'desc'=>'Artisanal aged goat cheese with herbs.',
        'image'=>'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=500',
        'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Izmir',
        'seller_name'=>'Aegean Dairy', 'seller_id'=>112, 'distance'=>'350 km', 'co2_saved'=>'0.2'
    ],
    9 => [
        'id' => 9, 'category' => 'dairy_eggs',
        'title'=>'Greek Yogurt', 'title_tr'=>'Süzme Yoğurt',
        'price'=>35.00, 'stock'=>15, 
        'desc'=>'Thick and creamy traditional yogurt.',
        'image'=>'https://i.pinimg.com/736x/6c/1e/9a/6c1e9a24ad599212c76b3bd1de0c1fa9.jpg',
        'delivery_mode'=>'bike', 'packaging_type'=>'glass', 'location'=>'Besiktas',
        'seller_name'=>'Yogurt Mama', 'seller_id'=>113, 'distance'=>'6 km', 'co2_saved'=>'0.5'
    ],
    10 => [
        'id' => 10, 'category' => 'dairy_eggs',
        'title'=>'Salted Butter', 'title_tr'=>'Tuzlu Tereyağı',
        'price'=>60.00, 'stock'=>10, 
        'desc'=>'Hand-churned butter with sea salt.',
        'image'=>'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=500',
        'delivery_mode'=>'cargo', 'packaging_type'=>'recycled', 'location'=>'Trabzon',
        'seller_name'=>'Highland Farms', 'seller_id'=>114, 'distance'=>'900 km', 'co2_saved'=>'0.1'
    ],

    // --- BAKERY (11-15) ---
    11 => [
        'id' => 11, 'category' => 'bakery',
        'title'=>'Sourdough Bread', 'title_tr'=>'Ekşi Mayalı Ekmek',
        'price'=>35.00, 'stock'=>5,  
        'desc'=>'Artisanal sourdough bread with a perfect crust.',                       
        'image'=>'https://i.pinimg.com/1200x/e1/b9/c6/e1b9c6aabac12aa71f5260a449468eb9.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'standard', 'location'=>'Beyoglu',
        'seller_name'=>'Pera Bakery', 'seller_id'=>105, 'distance'=>'8.0 km', 'co2_saved'=>'0.1'
    ],
    12 => [
        'id' => 12, 'category' => 'bakery',
        'title'=>'Simit (Sesame Ring)', 'title_tr'=>'Simit',
        'price'=>10.00, 'stock'=>50, 
        'desc'=>'Freshly baked Turkish sesame bagel.',
        'image'=>'https://i.pinimg.com/736x/2d/52/7f/2d527f0863a5aeec0bad3df8be32def5.jpg',
        'delivery_mode'=>'walk', 'packaging_type'=>'paper', 'location'=>'Karakoy',
        'seller_name'=>'Uncle Simit', 'seller_id'=>115, 'distance'=>'2 km', 'co2_saved'=>'0.9'
    ],
    13 => [
        'id' => 13, 'category' => 'bakery',
        'title'=>'Whole Wheat Loaf', 'title_tr'=>'Tam Buğday Ekmeği',
        'price'=>25.00, 'stock'=>10, 
        'desc'=>'Healthy whole wheat bread rich in fiber.',
        'image'=>'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500',
        'delivery_mode'=>'bike', 'packaging_type'=>'paper', 'location'=>'Nisantasi',
        'seller_name'=>'Grain & Co', 'seller_id'=>116, 'distance'=>'4 km', 'co2_saved'=>'0.6'
    ],
    14 => [
        'id' => 14, 'category' => 'bakery',
        'title'=>'Croissants', 'title_tr'=>'Kruvasan',
        'price'=>40.00, 'stock'=>12, 
        'desc'=>'Buttery, flaky croissants baked daily.',
        'image'=>'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500',
        'delivery_mode'=>'bike', 'packaging_type'=>'paper', 'location'=>'Cihangir',
        'seller_name'=>'French Touch', 'seller_id'=>117, 'distance'=>'3 km', 'co2_saved'=>'0.5'
    ],
    15 => [
        'id' => 15, 'category' => 'bakery',
        'title'=>'Oatmeal Cookies', 'title_tr'=>'Yulaflı Kurabiye',
        'price'=>30.00, 'stock'=>20, 
        'desc'=>'Homemade cookies with oats and raisins.',
        'image'=>'https://i.pinimg.com/736x/78/6f/9f/786f9fe9f5fe6b06269c72c4ac2d56fa.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'recycled', 'location'=>'Kadikoy',
        'seller_name'=>'Grandma\'s Oven', 'seller_id'=>118, 'distance'=>'3 km', 'co2_saved'=>'0.4'
    ],

    // --- PANTRY (16-20) ---
    16 => [
        'id' => 16, 'category' => 'pantry',
        'title'=>'Honey Jar', 'title_tr'=>'Bal Kavanozu',
        'price'=>85.00, 'stock'=>12, 
        'desc'=>'Pure, raw honey from local wildflowers.',                               
        'image'=>'https://i.pinimg.com/736x/d5/d2/26/d5d226d0eff9708d5dcc324888daa6be.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Sariyer',
        'seller_name'=>'Bee Natural', 'seller_id'=>106, 'distance'=>'12.5 km', 'co2_saved'=>'0.2'
    ],
    17 => [
        'id' => 17, 'category' => 'pantry',
        'title'=>'Olive Oil', 'title_tr'=>'Zeytinyağı',
        'price'=>150.00, 'stock'=>8, 
        'desc'=>'Cold-pressed extra virgin olive oil.',
        'image'=>'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500',
        'delivery_mode'=>'cargo', 'packaging_type'=>'glass', 'location'=>'Ayvalik',
        'seller_name'=>'Olive Branch', 'seller_id'=>119, 'distance'=>'400 km', 'co2_saved'=>'0.1'
    ],
    18 => [
        'id' => 18, 'category' => 'pantry',
        'title'=>'Dried Apricots', 'title_tr'=>'Kuru Kayısı',
        'price'=>60.00, 'stock'=>25, 
        'desc'=>'Sun-dried apricots with no added sugar.',
        'image'=>'https://i.pinimg.com/736x/a0/56/b9/a056b936e98b3504d039b74c5b82fa15.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Malatya',
        'seller_name'=>'Sun Dry', 'seller_id'=>120, 'distance'=>'800 km', 'co2_saved'=>'0.1'
    ],
    19 => [
        'id' => 19, 'category' => 'pantry',
        'title'=>'Red Lentils', 'title_tr'=>'Kırmızı Mercimek',
        'price'=>25.00, 'stock'=>40, 
        'desc'=>'Organic red lentils, great for soups.',
        'image'=>'https://i.pinimg.com/736x/e6/12/3a/e6123a2556c975587b39faab4d5566d1.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'paper', 'location'=>'Gaziantep',
        'seller_name'=>'Anatolia Grains', 'seller_id'=>121, 'distance'=>'900 km', 'co2_saved'=>'0.1'
    ],
    20 => [
        'id' => 20, 'category' => 'pantry',
        'title'=>'Walnuts', 'title_tr'=>'Ceviz',
        'price'=>120.00, 'stock'=>15, 
        'desc'=>'Freshly shelled walnuts.',
        'image'=>'https://i.pinimg.com/736x/9e/e0/89/9ee08915fc37dba82dffd83a8e0eb83d.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Bursa',
        'seller_name'=>'Nut House', 'seller_id'=>122, 'distance'=>'150 km', 'co2_saved'=>'0.2'
    ],

    // --- BEVERAGES & HOME (21-25) ---
    21 => [
        'id' => 21, 'category' => 'beverages',
        'title'=>'Organic Lemonade', 'title_tr'=>'Organik Limonata',
        'price'=>25.00, 'stock'=>20, 
        'desc'=>'Homemade lemonade with organic lemons and mint.',
        'image'=>'https://i.pinimg.com/736x/7b/58/33/7b5833756a2877c47417e33379e40143.jpg',
        'delivery_mode'=>'walk', 'packaging_type'=>'glass', 'location'=>'Kadikoy',
        'seller_name'=>'Citrus & Co', 'seller_id'=>108, 'distance'=>'1 km', 'co2_saved'=>'0.2'
    ],
    22 => [
        'id' => 22, 'category' => 'beverages',
        'title'=>'Kombucha', 'title_tr'=>'Kombu Çayı',
        'price'=>45.00, 'stock'=>10, 
        'desc'=>'Fermented tea drink, rich in probiotics.',
        'image'=>'https://i.pinimg.com/736x/50/71/35/507135eaecaf8b1a12a7421d9f29d9db.jpg',
        'delivery_mode'=>'bike', 'packaging_type'=>'glass', 'location'=>'Moda',
        'seller_name'=>'Ferment Lab', 'seller_id'=>123, 'distance'=>'2 km', 'co2_saved'=>'0.6'
    ],
    23 => [
        'id' => 23, 'category' => 'home_garden',
        'title'=>'Basil Plant', 'title_tr'=>'Fesleğen Bitkisi',
        'price'=>40.00, 'stock'=>5, 
        'desc'=>'Potted basil plant for your kitchen window.',
        'image'=>'https://i.pinimg.com/1200x/78/4a/8f/784a8fa2f9e55826c4432428be208d16.jpg',
        'delivery_mode'=>'public', 'packaging_type'=>'plastic_free', 'location'=>'Besiktas',
        'seller_name'=>'City Green', 'seller_id'=>109, 'distance'=>'4 km', 'co2_saved'=>'0.6'
    ],
    24 => [
        'id' => 24, 'category' => 'home_garden',
        'title'=>'Aloe Vera', 'title_tr'=>'Aloe Vera',
        'price'=>55.00, 'stock'=>8, 
        'desc'=>'Healing succulent plant, easy to care for.',
        'image'=>'https://i.pinimg.com/736x/05/63/eb/0563eb8ec49c67915b279ce013bbe0a7.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Uskudar',
        'seller_name'=>'Plant Mom', 'seller_id'=>124, 'distance'=>'5 km', 'co2_saved'=>'0.4'
    ],
    25 => [
        'id' => 25, 'category' => 'home_garden',
        'title'=>'Lavender Soap', 'title_tr'=>'Lavanta Sabunu',
        'price'=>20.00, 'stock'=>30, 
        'desc'=>'Handmade natural soap with lavender oil.',
        'image'=>'https://i.pinimg.com/1200x/db/4e/12/db4e123d312a8694921b5e38e1c30c51.jpg',
        'delivery_mode'=>'cargo', 'packaging_type'=>'paper', 'location'=>'Izmir',
        'seller_name'=>'Pure Soap', 'seller_id'=>125, 'distance'=>'350 km', 'co2_saved'=>'0.3'
    ],
    26 => [
        'id' => 26, 'category' => 'beverages',
        'title'=>'Freshly Squeezed Orange Juice', 'title_tr'=>'Taze Sıkılmış Portakal Suyu',
        'price'=>30.00, 'stock'=>25, 
        'desc'=>'Daily squuezed,100% natural orange juice from local groves',
        'image'=>'https://i.pinimg.com/736x/ee/8d/7d/ee8d7d23a124c326f4f05936a6d12d3c.jpg',
        'delivery_mode'=>'bike', 'packaging_type'=>'glass_bottle', 'location'=>'Kadikoy',
        'seller_name'=>'Citrus Garden', 'seller_id'=>108, 'distance'=>'1.5 km', 'co2_saved'=>'1.2'
    ]
    
];

// 2. TRY MONGODB CONNECTION
if (file_exists('db_connect.php')) {
    // Suppress errors with @ so the site doesn't crash if drivers are missing
    @include_once 'db_connect.php';
    
    if (function_exists('getDBConnection')) {
        $db = getDBConnection();
        
        if ($db) {
            try {
                $collection = $db->products;
                // Fetch all documents as arrays
                $cursor = $collection->find();
                $mongo_products = [];
                
                foreach ($cursor as $doc) {
                    $item = (array)$doc;
                    if (isset($item['id'])) {
                        $mongo_products[$item['id']] = $item;
                    }
                }
                
                // If we successfully got data from MongoDB, use it!
                if (!empty($mongo_products)) {
                    $products_db = $mongo_products;
                }
            } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
                // MongoDB connection timeout - silently fall back to local array
                error_log('MongoDB timeout in data_products.php: ' . $e->getMessage());
            } catch (Exception $e) {
                // Other MongoDB errors - silently fall back to local array
                error_log('MongoDB error in data_products.php: ' . $e->getMessage());
            }
        }
    }
}
?>