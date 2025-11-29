<?php
// FORCE UPDATE SCRIPT
// Updated with reliable image URLs to fix the "absent pictures" issue.

require_once 'db_connect.php';

// --- THE 100 PRODUCTS DATA ---
$products_db = [];

// Improved Helper: Now accepts a specific Image ID for reliability
function create_item($id, $cat, $en, $tr, $price, $stock, $img_id, $seller, $dist, $co2) {
    // Unsplash w=500 optimization for speed
    $image_url = "https://images.unsplash.com/photo-$img_id?auto=format&fit=crop&w=500&q=80";
    
    return [
        'id' => $id,
        'category' => $cat,
        'title' => $en,
        'title_tr' => $tr,
        'price' => $price,
        'stock' => $stock,
        'desc' => "Fresh and sustainable $en sourced locally.",
        'image' => $image_url,
        'delivery_mode' => ($dist < 3) ? 'walk' : (($dist < 10) ? 'bike' : 'cargo'),
        'packaging_type' => ($cat == 'beverages') ? 'glass' : 'plastic_free',
        'location' => 'Istanbul',
        'seller_name' => $seller,
        'seller_id' => rand(101, 120),
        'distance' => "$dist km",
        'co2_saved' => $co2
    ];
}

// 1. FRESH PRODUCE (Reliable IDs)
$produce = [
    [1, 'Red Apples', 'Kırmızı Elma', 25.00, '1560806887-1e4cd0b6cbd6'],
    [2, 'Organic Carrots', 'Organik Havuç', 15.00, '1598170845058-32b9d6a5da37'],
    [3, 'Fresh Spinach', 'Taze Ispanak', 20.00, '1576045057995-568f588f82fb'],
    [4, 'Ripe Avocados', 'Olgun Avokado', 45.00, '1523049673856-38866ea6c0d1'],
    [5, 'Cherry Tomatoes', 'Çeri Domates', 18.00, '1561136594-7f68413baa99'],
    [6, 'Broccoli', 'Brokoli', 30.00, '1459411621-694a4906f408'],
    [7, 'Garlic Bulbs', 'Sarımsak', 40.00, '1615477264539-743217822987'],
    [8, 'Red Onions', 'Kırmızı Soğan', 12.00, '1618512496248-a07fe83aa8cb'],
    [9, 'Potatoes', 'Patates', 10.00, '1518977676058-2e8289093b25'],
    [10, 'Cucumbers', 'Salatalık', 15.00, '1449300079323-02e209d9d3a6'],
    [11, 'Green Peppers', 'Yeşil Biber', 20.00, '1563565375-f3fdf5ec3e95'],
    [12, 'Eggplant', 'Patlıcan', 22.00, '1615485925696-22a7063469e6'],
    [13, 'Zucchini', 'Kabak', 18.00, '1604543179294-4b537e492c6b'],
    [14, 'Lemons', 'Limon', 25.00, '1592596414341-38327954d782'],
    [15, 'Oranges', 'Portakal', 20.00, '1611080626919-7cf5a9dbab5b'],
    [16, 'Bananas', 'Muz', 35.00, '1603569283847-aa295f0d016a'],
    [17, 'Strawberries', 'Çilek', 50.00, '1464965911861-746f5169c967'],
    [18, 'Blueberries', 'Yaban Mersini', 70.00, '1498557853362-50d2d0f3b574'],
    [19, 'Watermelon', 'Karpuz', 80.00, '1587049352851-8d4e5de24658'],
    [20, 'Parsley', 'Maydanoz', 5.00, '1626282243682-97274028684d']
];
foreach ($produce as $p) $products_db[] = create_item($p[0], 'fresh_produce', $p[1], $p[2], $p[3], 50, $p[4], 'Green Valley Farm', rand(2, 20), 0.5);

// 2. DAIRY
$dairy = [
    [21, 'Free Range Eggs', 'Gezen Tavuk Yumurtası', 45.00, '1511690656952-34342d5c2899'],
    [22, 'Fresh Farm Milk', 'Çiftlik Sütü', 30.00, '1563636619-e9143da7973b'],
    [23, 'Goat Cheese', 'Keçi Peyniri', 85.00, '1486297678162-eb2a19b0a32d'],
    [24, 'Greek Yogurt', 'Süzme Yoğurt', 35.00, '1488477181946-6428a029177b'],
    [25, 'Salted Butter', 'Tuzlu Tereyağı', 60.00, '1589985270826-4b7bb135bc9d'],
    [26, 'Cheddar Cheese', 'Çedar Peyniri', 90.00, '1618164966200-535c0b0566ac'],
    [27, 'Cream Cheese', 'Krem Peynir', 40.00, '1534483451922-84f96135b629'],
    [28, 'Heavy Cream', 'Süt Kreması', 50.00, '1624454002335-9080db057701'],
    [29, 'Quail Eggs', 'Bıldırcın Yumurtası', 60.00, '1516483638261-f4dbaf036963'],
    [30, 'Mozzarella', 'Mozzarella', 75.00, '1574860102733-c6446850a6f3'],
    [31, 'Feta Cheese', 'Beyaz Peynir', 55.00, '1559561010-3c193892708d'],
    [32, 'Kefir', 'Kefir', 25.00, '1584907797017-b0d508265546'],
    [33, 'Ayran', 'Yayık Ayranı', 15.00, '1625477924777-8c33477d2520'],
    [34, 'Duck Eggs', 'Ördek Yumurtası', 80.00, '1506976785307-8732e854ad03'],
    [35, 'Ricotta', 'Lor Peyniri', 45.00, '1628753423010-d3a82eb8436e']
];
foreach ($dairy as $p) $products_db[] = create_item($p[0], 'dairy_eggs', $p[1], $p[2], $p[3], 20, $p[4], 'Milky Way Dairy', rand(5, 50), 0.4);

// 3. BAKERY
$bakery = [
    [36, 'Sourdough Bread', 'Ekşi Mayalı Ekmek', 35.00, '1585476263060-b55d7612e69a'],
    [37, 'Simit', 'Simit', 10.00, '1621262659368-e67c88029272'],
    [38, 'Whole Wheat Loaf', 'Tam Buğday Ekmeği', 25.00, '1509440159596-0249088772ff'],
    [39, 'Croissants', 'Kruvasan', 40.00, '1555507036-ab1f4038808a'],
    [40, 'Oatmeal Cookies', 'Yulaflı Kurabiye', 30.00, '1499636138143-bd630f5cf38b'],
    [41, 'Baguette', 'Baget Ekmek', 15.00, '1586449480643-0154ed367da0'],
    [42, 'Rye Bread', 'Çavdar Ekmeği', 28.00, '1549931319-a545dcf3bc73'],
    [43, 'Brownies', 'Brownie', 45.00, '1514820717315-17b624247718'],
    [44, 'Muffins', 'Muffin', 35.00, '1558401367-156f41da5337'],
    [45, 'Pita Bread', 'Pide', 12.00, '1573140401552-3373a0480b5b'],
    [46, 'Focaccia', 'Focaccia', 50.00, '1573333178517-39145e21522c'],
    [47, 'Cinnamon Rolls', 'Tarçınlı Çörek', 40.00, '1509365914233-95265593120b'],
    [48, 'Scones', 'Çörek', 30.00, '1579306535978-9a42c433005d'],
    [49, 'Pretzels', 'Pretzel', 20.00, '1573105035706-6461787f0a63'],
    [50, 'Banana Bread', 'Muzlu Ekmek', 55.00, '1601733766923-5252b859999e']
];
foreach ($bakery as $p) $products_db[] = create_item($p[0], 'bakery', $p[1], $p[2], $p[3], 15, $p[4], 'Golden Oven', rand(1, 10), 0.2);

// 4. PANTRY
$pantry = [
    [51, 'Honey Jar', 'Bal Kavanozu', 85.00, '1587049352846-4a222e784d38'],
    [52, 'Olive Oil', 'Zeytinyağı', 150.00, '1474979266404-7eaacbcd87c5'],
    [53, 'Dried Apricots', 'Kuru Kayısı', 60.00, '1596558697693-4e4b77f9cd4d'],
    [54, 'Red Lentils', 'Kırmızı Mercimek', 25.00, '1515543904379-3d757afe72e3'],
    [55, 'Walnuts', 'Ceviz', 120.00, '1563519890692-a633633d74c3'],
    [56, 'Almonds', 'Badem', 130.00, '1508595165502-3e2652e5a405'],
    [57, 'Hazelnuts', 'Fındık', 110.00, '1533236897111-3e94666b2edf'],
    [58, 'Rice', 'Pirinç', 30.00, '1586201375761-83865001e31c'],
    [59, 'Bulgur', 'Bulgur', 20.00, '1586201375761-83865001e31c'], // Reusing grain
    [60, 'Pasta', 'Makarna', 15.00, '1612966879570-e0817943934d'],
    [61, 'Tomato Paste', 'Domates Salçası', 40.00, '1598346763291-79e313647b30'],
    [62, 'Pickles', 'Turşu', 35.00, '1622483767028-3f66f32aef97'],
    [63, 'Jam', 'Reçel', 50.00, '1584907797017-b0d508265546'], // Reusing Jar
    [64, 'Peanut Butter', 'Fıstık Ezmesi', 70.00, '1626074353765-517a6f322670'],
    [65, 'Oats', 'Yulaf', 25.00, '1517420704902-5fc5f1452508'],
    [66, 'Chickpeas', 'Nohut', 28.00, '1515543904379-3d757afe72e3'], // Reusing legumes
    [67, 'Beans', 'Kuru Fasulye', 30.00, '1633504581786-d0de5a2c3382'],
    [68, 'Salt', 'Deniz Tuzu', 10.00, '1518110925431-7e8440a36b53'],
    [69, 'Black Pepper', 'Karabiber', 40.00, '1596040033229-a9821ebd058d'],
    [70, 'Flour', 'Un', 20.00, '1586201375761-83865001e31c']
];
foreach ($pantry as $p) $products_db[] = create_item($p[0], 'pantry', $p[1], $p[2], $p[3], 40, $p[4], 'Natures Pantry', rand(100, 500), 0.1);

// 5. BEVERAGES
$beverages = [
    [71, 'Organic Lemonade', 'Organik Limonata', 25.00, '1513558161293-cdaf765ed2fd'],
    [72, 'Kombucha', 'Kombu Çayı', 45.00, '1556679343-c7306c1976bc'],
    [73, 'Apple Juice', 'Elma Suyu', 30.00, '1613478223719-2ab802602423'],
    [74, 'Orange Juice', 'Portakal Suyu', 35.00, '1614735063161-488dc73bb43c'],
    [75, 'Mineral Water', 'Maden Suyu', 10.00, '1604085572555-1849b887098e'],
    [76, 'Iced Tea', 'Soğuk Çay', 20.00, '1556679343-c7306c1976bc'],
    [77, 'Coffee Beans', 'Kahve Çekirdeği', 90.00, '1559056137-331577e9524e'],
    [78, 'Green Tea', 'Yeşil Çay', 40.00, '1627435601361-ec25f5b1d0e5'],
    [79, 'Herbal Tea', 'Bitki Çayı', 35.00, '1597318181312-b4b63f42953f'],
    [80, 'Smoothie', 'Smoothie', 50.00, '1505252585461-04db1eb84625'],
    [81, 'Almond Milk', 'Badem Sütü', 60.00, '1550583724-b2692b85b150'], // Using milk
    [82, 'Soy Milk', 'Soya Sütü', 55.00, '1563636619-e9143da7973b'],
    [83, 'Oat Milk', 'Yulaf Sütü', 58.00, '1550583724-b2692b85b150'],
    [84, 'Pomegranate Juice', 'Nar Suyu', 45.00, '1615485500704-3e3c54944c51'],
    [85, 'Cherry Juice', 'Vişne Suyu', 30.00, '1606757389772-23b0339c76cc']
];
foreach ($beverages as $p) $products_db[] = create_item($p[0], 'beverages', $p[1], $p[2], $p[3], 30, $p[4], 'Fresh Sips', rand(5, 50), 0.3);

// 6. HOME
$home = [
    [86, 'Basil Plant', 'Fesleğen Bitkisi', 40.00, '1618331835717-801e976710b2'],
    [87, 'Aloe Vera', 'Aloe Vera', 55.00, '1596547609652-9cf5d8d71321'],
    [88, 'Lavender Soap', 'Lavanta Sabunu', 20.00, '1600857062241-98e5dba7f214'],
    [89, 'Beeswax Candle', 'Balmumu Mum', 60.00, '1603006905003-be475563bc59'],
    [90, 'Bamboo Toothbrush', 'Bambu Diş Fırçası', 25.00, '1607613009820-a29f7bb6dc32'],
    [91, 'Succulent', 'Sukulent', 35.00, '1459411621-694a4906f408'],
    [92, 'Mint Plant', 'Nane Bitkisi', 30.00, '1628583498176-77045328c34f'],
    [93, 'Flower Pot', 'Saksı', 45.00, '1485955937742-cdf9c17203b6'],
    [94, 'Seeds', 'Tohum Paketi', 15.00, '1466692476868-aef1dfb1e735'],
    [95, 'Gardening Gloves', 'Bahçe Eldiveni', 50.00, '1586201375761-83865001e31c'], // Generic
    [96, 'Watering Can', 'Sulama Kabı', 70.00, '1416879595882-3373a0480b5b'],
    [97, 'Tote Bag', 'Bez Çanta', 25.00, '1597484662317-c9253d302c95'],
    [98, 'Wooden Spoon', 'Tahta Kaşık', 30.00, '1584346133934-5b2f4c51c5e3'],
    [99, 'Ceramic Mug', 'Seramik Kupa', 80.00, '1514228742587-6b1558fcca3d'],
    [100, 'Hand Cream', 'El Kremi', 45.00, '1608248597279-f99d160bfbc8']
];
foreach ($home as $p) $products_db[] = create_item($p[0], 'home_garden', $p[1], $p[2], $p[3], 25, $p[4], 'Eco Home', rand(10, 100), 0.5);


// --- EXECUTE UPDATE ---
$db = getDBConnection();

if ($db) {
    $collection = $db->products;
    
    // 1. CLEAR OLD DATA (The 6 items)
    $collection->deleteMany([]);

    // 2. INSERT NEW DATA (The 100 items)
    $insertResult = $collection->insertMany($products_db);

    echo "<div style='font-family: sans-serif; padding: 20px; background: #dcfce7; color: #166534; border-radius: 10px; max-width: 600px; margin: 50px auto; text-align: center;'>";
    echo "<h1>SUCCESS!</h1>";
    echo "<p>We deleted the old 6 items.</p>";
    echo "<p>We inserted <strong>" . $insertResult->getInsertedCount() . "</strong> new products with reliable images.</p>";
    echo "<h2>You now have 100 products in MongoDB.</h2>";
    echo "<a href='index.php' style='display:inline-block; margin-top:20px; padding:12px 25px; background:#166534; color:white; text-decoration:none; border-radius:5px; font-weight:bold;'>Go to Homepage</a>";
    echo "</div>";
} else {
    echo "<h1>DB Connection Failed. Check drivers.</h1>";
}
?>