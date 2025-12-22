<?php
// COMPREHENSIVE SEED SCRIPT: Creates seller accounts + products linked to them
// Run this ONCE to populate database with realistic demo data

require_once 'db_connect.php';

$db = getDBConnection();

if (!$db) {
    die("<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 600px; margin: 50px auto;'>
    <h1>Connection Failed</h1>
    <p>Could not connect to MongoDB. Check your drivers or internet connection.</p>
    </div>");
}

try {
    $usersCollection = $db->users;
    $productsCollection = $db->products;

    // STEP 1: Find Existing Seller Accounts by Email (matching exact emails from database)
    $sellerEmails = [
        'ahmet\'sorchard@leafmarket.com' => 'Ahmet\'s Orchard',
        'greenrootsfarm@leafmarket.com' => 'Green Roots Farm',
        'happyhencoop@leafmarket.com' => 'Happy Hen Coop',
        'uskudargardens@leafmarket.com' => 'Uskudar Gardens',
        'perabakery@leafmarket.com' => 'Pera Bakery',
        'beenatural@leafmarket.com' => 'Bee Natural',
        'milkyway@leafmarket.com' => 'Milky Way',
        'citrus&co@leafmarket.com' => 'Citrus & Co',
        'citygreen@leafmarket.com' => 'City Green',
        'sunnygroves@leafmarket.com' => 'Sunny Groves',
        'citygreenhouse@leafmarket.com' => 'City Greenhouse',
        'aegeandairy@leafmarket.com' => 'Aegean Dairy',
        'yogurtmama@leafmarket.com' => 'Yogurt Mama',
        'highlandfarms@leafmarket.com' => 'Highland Farms',
        'unclesimit@leafmarket.com' => 'Uncle Simit',
        'grain&co@leafmarket.com' => 'Grain & Co',
        'frenchtouch@leafmarket.com' => 'French Touch',
        'grandma\'soven@leafmarket.com' => 'Grandma\'s Oven',
        'olivebranch@leafmarket.com' => 'Olive Branch',
        'sundry@leafmarket.com' => 'Sun Dry',
        'anatoliagrains@leafmarket.com' => 'Anatolia Grains',
        'nuthouse@leafmarket.com' => 'Nut House',
        'fermentlab@leafmarket.com' => 'Ferment Lab',
        'plantmom@leafmarket.com' => 'Plant Mom',
        'puresoap@leafmarket.com' => 'Pure Soap',
    ];

    // Clear existing products only
    $productsCollection->deleteMany([]);

    // Get existing seller IDs from database
    $sellerIds = [];
    foreach ($sellerEmails as $email => $name) {
        $seller = $usersCollection->findOne(['email' => $email]);
        if ($seller) {
            $sellerIds[$name] = (string)$seller['_id'];
        }
    }
    
    // If sellers don't exist, show error
    if (count($sellerIds) < 25) {
        throw new Exception("Not all seller accounts found. Please ensure all 25 sellers are registered first.");
    }

    // STEP 2: Create Products with Real Seller IDs
    $products = [
        // --- FRESH PRODUCE (1-5) ---
        [
            'id' => 1, 'category' => 'fresh_produce',
            'title'=>'Red Apples', 'title_tr'=>'Kırmızı Elma',
            'price'=>25.00, 'stock'=>50, 
            'desc'=>'Crisp, sweet, and locally grown red apples.', 
            'image'=>'https://picsum.photos/400/400?random=1',
            'delivery_mode'=>'bike', 'packaging_type'=>'plastic_free', 'location'=>'Kadikoy',
            'seller_name'=>'Ahmet\'s Orchard', 'seller_id'=>$sellerIds['Ahmet\'s Orchard'], 'distance'=>'3.2 km', 'co2_saved'=>'0.5'
        ],
        [
            'id' => 2, 'category' => 'fresh_produce',
            'title'=>'Organic Carrots', 'title_tr'=>'Organik Havuç',
            'price'=>15.00, 'stock'=>30, 
            'desc'=>'Crunchy organic carrots harvested this morning.',    
            'image'=>'https://picsum.photos/400/400?random=2',
            'delivery_mode'=>'bike', 'packaging_type'=>'recycled', 'location'=>'Besiktas',
            'seller_name'=>'Green Roots Farm', 'seller_id'=>$sellerIds['Green Roots Farm'], 'distance'=>'5.1 km', 'co2_saved'=>'0.3'
        ],
        [
            'id' => 3, 'category' => 'fresh_produce',
            'title'=>'Fresh Spinach', 'title_tr'=>'Taze Ispanak',
            'price'=>20.00, 'stock'=>15, 
            'desc'=>'Leafy green spinach, washed and ready to eat.',                         
            'image'=>'https://picsum.photos/400/400?random=3',
            'delivery_mode'=>'public', 'packaging_type'=>'plastic_free', 'location'=>'Uskudar',
            'seller_name'=>'Uskudar Gardens', 'seller_id'=>$sellerIds['Uskudar Gardens'], 'distance'=>'4.5 km', 'co2_saved'=>'0.4'
        ],
        [
            'id' => 4, 'category' => 'fresh_produce',
            'title'=>'Ripe Avocados', 'title_tr'=>'Olgun Avokado',
            'price'=>45.00, 'stock'=>10, 
            'desc'=>'Creamy, perfectly ripe avocados from the Mediterranean coast.',
            'image'=>'https://picsum.photos/400/400?random=4',
            'delivery_mode'=>'cargo', 'packaging_type'=>'recycled', 'location'=>'Antalya',
            'seller_name'=>'Sunny Groves', 'seller_id'=>$sellerIds['Sunny Groves'], 'distance'=>'500 km', 'co2_saved'=>'0.1'
        ],
        [
            'id' => 5, 'category' => 'fresh_produce',
            'title'=>'Cherry Tomatoes', 'title_tr'=>'Çeri Domates',
            'price'=>18.00, 'stock'=>40, 
            'desc'=>'Sweet and juicy cherry tomatoes, perfect for salads.',
            'image'=>'https://picsum.photos/400/400?random=5',
            'delivery_mode'=>'bike', 'packaging_type'=>'plastic_free', 'location'=>'Sariyer',
            'seller_name'=>'City Greenhouse', 'seller_id'=>$sellerIds['City Greenhouse'], 'distance'=>'12 km', 'co2_saved'=>'0.4'
        ],

        // --- DAIRY & EGGS (6-10) ---
        [
            'id' => 6, 'category' => 'dairy_eggs',
            'title'=>'Free Range Eggs', 'title_tr'=>'Gezen Tavuk Yumurtası',
            'price'=>45.00, 'stock'=>20, 
            'desc'=>'Farm-fresh eggs from happy, free-roaming chickens.',                   
            'image'=>'https://picsum.photos/400/400?random=6',
            'delivery_mode'=>'walk', 'packaging_type'=>'recycled', 'location'=>'Moda',
            'seller_name'=>'Happy Hen Coop', 'seller_id'=>$sellerIds['Happy Hen Coop'], 'distance'=>'1.2 km', 'co2_saved'=>'0.8'
        ],
        [
            'id' => 7, 'category' => 'dairy_eggs',
            'title'=>'Fresh Farm Milk', 'title_tr'=>'Taze Çiftlik Sütü',
            'price'=>30.00, 'stock'=>12, 
            'desc'=>'Raw, unpasteurized milk from local grass-fed cows.',
            'image'=>'https://picsum.photos/400/400?random=7',
            'delivery_mode'=>'bike', 'packaging_type'=>'glass', 'location'=>'Kemerburgaz',
            'seller_name'=>'Milky Way', 'seller_id'=>$sellerIds['Milky Way'], 'distance'=>'15 km', 'co2_saved'=>'0.4'
        ],
        [
            'id' => 8, 'category' => 'dairy_eggs',
            'title'=>'Goat Cheese', 'title_tr'=>'Keçi Peyniri',
            'price'=>85.00, 'stock'=>8, 
            'desc'=>'Artisanal aged goat cheese with herbs.',
            'image'=>'https://picsum.photos/400/400?random=8',
            'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Izmir',
            'seller_name'=>'Aegean Dairy', 'seller_id'=>$sellerIds['Aegean Dairy'], 'distance'=>'350 km', 'co2_saved'=>'0.2'
        ],
        [
            'id' => 9, 'category' => 'dairy_eggs',
            'title'=>'Greek Yogurt', 'title_tr'=>'Süzme Yoğurt',
            'price'=>35.00, 'stock'=>15, 
            'desc'=>'Thick and creamy traditional yogurt.',
            'image'=>'https://picsum.photos/400/400?random=9',
            'delivery_mode'=>'bike', 'packaging_type'=>'glass', 'location'=>'Besiktas',
            'seller_name'=>'Yogurt Mama', 'seller_id'=>$sellerIds['Yogurt Mama'], 'distance'=>'6 km', 'co2_saved'=>'0.5'
        ],
        [
            'id' => 10, 'category' => 'dairy_eggs',
            'title'=>'Salted Butter', 'title_tr'=>'Tuzlu Tereyağı',
            'price'=>60.00, 'stock'=>10, 
            'desc'=>'Hand-churned butter with sea salt.',
            'image'=>'https://picsum.photos/400/400?random=10',
            'delivery_mode'=>'cargo', 'packaging_type'=>'recycled', 'location'=>'Trabzon',
            'seller_name'=>'Highland Farms', 'seller_id'=>$sellerIds['Highland Farms'], 'distance'=>'900 km', 'co2_saved'=>'0.1'
        ],

        // --- BAKERY (11-15) ---
        [
            'id' => 11, 'category' => 'bakery',
            'title'=>'Sourdough Bread', 'title_tr'=>'Ekşi Mayalı Ekmek',
            'price'=>35.00, 'stock'=>5,  
            'desc'=>'Artisanal sourdough bread with a perfect crust.',                       
            'image'=>'https://picsum.photos/400/400?random=11',
            'delivery_mode'=>'cargo', 'packaging_type'=>'standard', 'location'=>'Beyoglu',
            'seller_name'=>'Pera Bakery', 'seller_id'=>$sellerIds['Pera Bakery'], 'distance'=>'8.0 km', 'co2_saved'=>'0.1'
        ],
        [
            'id' => 12, 'category' => 'bakery',
            'title'=>'Simit (Sesame Ring)', 'title_tr'=>'Simit',
            'price'=>10.00, 'stock'=>50, 
            'desc'=>'Freshly baked Turkish sesame bagel.',
            'image'=>'https://picsum.photos/400/400?random=12',
            'delivery_mode'=>'walk', 'packaging_type'=>'paper', 'location'=>'Karakoy',
            'seller_name'=>'Uncle Simit', 'seller_id'=>$sellerIds['Uncle Simit'], 'distance'=>'2 km', 'co2_saved'=>'0.9'
        ],
        [
            'id' => 13, 'category' => 'bakery',
            'title'=>'Whole Wheat Loaf', 'title_tr'=>'Tam Buğday Ekmeği',
            'price'=>25.00, 'stock'=>10, 
            'desc'=>'Healthy whole wheat bread rich in fiber.',
            'image'=>'https://picsum.photos/400/400?random=13',
            'delivery_mode'=>'bike', 'packaging_type'=>'paper', 'location'=>'Nisantasi',
            'seller_name'=>'Grain & Co', 'seller_id'=>$sellerIds['Grain & Co'], 'distance'=>'4 km', 'co2_saved'=>'0.6'
        ],
        [
            'id' => 14, 'category' => 'bakery',
            'title'=>'Croissants', 'title_tr'=>'Kruvasan',
            'price'=>40.00, 'stock'=>12, 
            'desc'=>'Buttery, flaky croissants baked daily.',
            'image'=>'https://picsum.photos/400/400?random=14',
            'delivery_mode'=>'bike', 'packaging_type'=>'paper', 'location'=>'Cihangir',
            'seller_name'=>'French Touch', 'seller_id'=>$sellerIds['French Touch'], 'distance'=>'3 km', 'co2_saved'=>'0.5'
        ],
        [
            'id' => 15, 'category' => 'bakery',
            'title'=>'Oatmeal Cookies', 'title_tr'=>'Yulaflı Kurabiye',
            'price'=>30.00, 'stock'=>20, 
            'desc'=>'Homemade cookies with oats and raisins.',
            'image'=>'https://picsum.photos/400/400?random=15',
            'delivery_mode'=>'cargo', 'packaging_type'=>'recycled', 'location'=>'Kadikoy',
            'seller_name'=>'Grandma\'s Oven', 'seller_id'=>$sellerIds['Grandma\'s Oven'], 'distance'=>'3 km', 'co2_saved'=>'0.4'
        ],

        // --- PANTRY (16-20) ---
        [
            'id' => 16, 'category' => 'pantry',
            'title'=>'Honey Jar', 'title_tr'=>'Bal Kavanozu',
            'price'=>85.00, 'stock'=>12, 
            'desc'=>'Pure, raw honey from local wildflowers.',                               
            'image'=>'https://picsum.photos/400/400?random=16',
            'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Sariyer',
            'seller_name'=>'Bee Natural', 'seller_id'=>$sellerIds['Bee Natural'], 'distance'=>'12.5 km', 'co2_saved'=>'0.2'
        ],
        [
            'id' => 17, 'category' => 'pantry',
            'title'=>'Olive Oil', 'title_tr'=>'Zeytinyağı',
            'price'=>150.00, 'stock'=>8, 
            'desc'=>'Cold-pressed extra virgin olive oil.',
            'image'=>'https://picsum.photos/400/400?random=17',
            'delivery_mode'=>'cargo', 'packaging_type'=>'glass', 'location'=>'Ayvalik',
            'seller_name'=>'Olive Branch', 'seller_id'=>$sellerIds['Olive Branch'], 'distance'=>'400 km', 'co2_saved'=>'0.1'
        ],
        [
            'id' => 18, 'category' => 'pantry',
            'title'=>'Dried Apricots', 'title_tr'=>'Kuru Kayısı',
            'price'=>60.00, 'stock'=>25, 
            'desc'=>'Sun-dried apricots with no added sugar.',
            'image'=>'https://picsum.photos/400/400?random=18',
            'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Malatya',
            'seller_name'=>'Sun Dry', 'seller_id'=>$sellerIds['Sun Dry'], 'distance'=>'800 km', 'co2_saved'=>'0.1'
        ],
        [
            'id' => 19, 'category' => 'pantry',
            'title'=>'Red Lentils', 'title_tr'=>'Kırmızı Mercimek',
            'price'=>25.00, 'stock'=>40, 
            'desc'=>'Organic red lentils, great for soups.',
            'image'=>'https://picsum.photos/400/400?random=19',
            'delivery_mode'=>'cargo', 'packaging_type'=>'paper', 'location'=>'Gaziantep',
            'seller_name'=>'Anatolia Grains', 'seller_id'=>$sellerIds['Anatolia Grains'], 'distance'=>'900 km', 'co2_saved'=>'0.1'
        ],
        [
            'id' => 20, 'category' => 'pantry',
            'title'=>'Walnuts', 'title_tr'=>'Ceviz',
            'price'=>120.00, 'stock'=>15, 
            'desc'=>'Freshly shelled walnuts.',
            'image'=>'https://picsum.photos/400/400?random=20',
            'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Bursa',
            'seller_name'=>'Nut House', 'seller_id'=>$sellerIds['Nut House'], 'distance'=>'150 km', 'co2_saved'=>'0.2'
        ],

        // --- BEVERAGES & HOME (21-25) ---
        [
            'id' => 21, 'category' => 'beverages',
            'title'=>'Organic Lemonade', 'title_tr'=>'Organik Limonata',
            'price'=>25.00, 'stock'=>20, 
            'desc'=>'Homemade lemonade with organic lemons and mint.',
            'image'=>'https://picsum.photos/400/400?random=21',
            'delivery_mode'=>'walk', 'packaging_type'=>'glass', 'location'=>'Kadikoy',
            'seller_name'=>'Citrus & Co', 'seller_id'=>$sellerIds['Citrus & Co'], 'distance'=>'1 km', 'co2_saved'=>'0.2'
        ],
        [
            'id' => 22, 'category' => 'beverages',
            'title'=>'Kombucha', 'title_tr'=>'Kombu Çayı',
            'price'=>45.00, 'stock'=>10, 
            'desc'=>'Fermented tea drink, rich in probiotics.',
            'image'=>'https://picsum.photos/400/400?random=22',
            'delivery_mode'=>'bike', 'packaging_type'=>'glass', 'location'=>'Moda',
            'seller_name'=>'Ferment Lab', 'seller_id'=>$sellerIds['Ferment Lab'], 'distance'=>'2 km', 'co2_saved'=>'0.6'
        ],
        [
            'id' => 23, 'category' => 'home_garden',
            'title'=>'Basil Plant', 'title_tr'=>'Fesleğen Bitkisi',
            'price'=>40.00, 'stock'=>5, 
            'desc'=>'Potted basil plant for your kitchen window.',
            'image'=>'https://picsum.photos/400/400?random=23',
            'delivery_mode'=>'public', 'packaging_type'=>'plastic_free', 'location'=>'Besiktas',
            'seller_name'=>'City Green', 'seller_id'=>$sellerIds['City Green'], 'distance'=>'4 km', 'co2_saved'=>'0.6'
        ],
        [
            'id' => 24, 'category' => 'home_garden',
            'title'=>'Aloe Vera', 'title_tr'=>'Aloe Vera',
            'price'=>55.00, 'stock'=>8, 
            'desc'=>'Healing succulent plant, easy to care for.',
            'image'=>'https://picsum.photos/400/400?random=24',
            'delivery_mode'=>'cargo', 'packaging_type'=>'plastic_free', 'location'=>'Uskudar',
            'seller_name'=>'Plant Mom', 'seller_id'=>$sellerIds['Plant Mom'], 'distance'=>'5 km', 'co2_saved'=>'0.4'
        ],
        [
            'id' => 25, 'category' => 'home_garden',
            'title'=>'Lavender Soap', 'title_tr'=>'Lavanta Sabunu',
            'price'=>20.00, 'stock'=>30, 
            'desc'=>'Handmade natural soap with lavender oil.',
            'image'=>'https://picsum.photos/400/400?random=25',
            'delivery_mode'=>'cargo', 'packaging_type'=>'paper', 'location'=>'Izmir',
            'seller_name'=>'Pure Soap', 'seller_id'=>$sellerIds['Pure Soap'], 'distance'=>'350 km', 'co2_saved'=>'0.3'
        ]
    ];

    // Insert all products
    $insertResult = $productsCollection->insertMany($products);

    echo "<div style='font-family: sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>✅ Products Linked Successfully!</h1>";
    echo "<p>Linked <strong>" . $insertResult->getInsertedCount() . "</strong> products to your existing " . count($sellerIds) . " seller accounts.</p>";
    echo "<h3>Your Seller Credentials:</h3>";
    echo "<ul style='text-align: left;'>";
    echo "<li><strong>Email:</strong> Any @leafmarket.com email (e.g., ahmetsorchard@leafmarket.com)</li>";
    echo "<li><strong>Password:</strong> seller123</li>";
    echo "</ul>";
    echo "<p style='margin-top: 20px;'>Now sellers can log in and see their products!</p>";
    echo "<a href='index.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>Go to Home</a> ";
    echo "<a href='login.php' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#1a4d2e; color:white; text-decoration:none; border-radius:5px;'>Login as Seller</a>";
    echo "</div>";

} catch (Exception $e) {
    error_log('Seeding error: ' . $e->getMessage());
    echo "<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 10px; max-width: 600px; margin: 50px auto;'>";
    echo "<h1>Error During Seeding</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>
