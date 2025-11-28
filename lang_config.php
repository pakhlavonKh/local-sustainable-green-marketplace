<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// --- ENGLISH DICTIONARY ---
$lang_en = [
    // ... (Keep existing Navbar/Footer/Hero keys) ...
    'nav_home'       => 'Home',
    'nav_login'      => 'Log In',
    'nav_register'   => 'Register',
    'search_place'   => 'Search for fresh greens, dairy...',
    'basket'         => 'Basket',
    'nav_about'      => 'About Leaf Market',

    'cat_fresh'      => 'Fresh Produce',
    'cat_dairy'      => 'Dairy & Eggs',
    'cat_bakery'     => 'Bakery',
    'cat_pantry'     => 'Pantry',
    'cat_bev'        => 'Beverages',
    'cat_home'       => 'Home & Garden',

    'hero_title'     => 'Local. Sustainable. Green.',
    'hero_sub'       => 'Buy and sell eco-friendly products from your community.',
    'hero_btn'       => 'Shop Now',

    'slide1_title'   => 'Sustainable Future.',
    'slide1_text'    => 'We are committed to ecological balance and sustainable development. Our goal is to reduce carbon footprints by connecting you directly with local producers.',
    'slide1_quote'   => '"The Earth is what we all have in common."',
    'slide1_author'  => '— Wendell Berry',

    'slide2_title'   => 'Grow Together.',
    'slide2_text'    => 'Empowering local economies is at the heart of what we do. By supporting nearby farmers and artisans, we build a resilient community.',
    'slide2_quote'   => '"Alone we can do so little; together we can do so much."',
    'slide2_author'  => '— Helen Keller',

    'slide3_title'   => 'Fresh & Organic.',
    'slide3_sub1'    => 'Vegetables • Dairy • Pantry • Fruits',
    'slide3_sub2'    => 'Everything you need, ethically sourced from your neighbors.',
    'slide3_btn'     => 'CHOOSE A CATEGORY BELOW',

    'carousel_title' => 'You Might Be Interested In',
    'low_stock'      => 'Low Stock',
    'currency'       => 'TL',

    'banner_title'   => "Nature's Best, Locally Sourced",
    'banner_sub'     => 'Support your community • Eat fresh • Live sustainable',

    'contact_header' => 'Contact Us',
    'contact_desc'   => 'Have a question? Send us an email.',
    'help_header'    => 'Help',
    'about_header'   => 'We are LEAF MARKET',
    'you_like_header'=> 'You might like',
    'buy_online'     => 'Buy Online',
    'payment'        => 'Payment Methods',
    'shipping'       => 'Shipping & Tracking',
    'about_link'     => 'About Leaf Market',
    'sustainability' => 'Sustainability',
    'rights'         => 'All rights reserved.',
    'lang_label'     => 'Language:',

    // --- NEW: PRODUCT TRANSLATIONS (English Map) ---
    // The key must match the EXACT title in your JSON/Array
    'products' => [
        'Red Apples'      => 'Red Apples',
        'Organic Carrots' => 'Organic Carrots',
        'Free Range Eggs' => 'Free Range Eggs',
        'Fresh Spinach'   => 'Fresh Spinach',
        'Sourdough Bread' => 'Sourdough Bread',
        'Honey Jar'       => 'Honey Jar',
        // Add more products here as needed
    ]
];

// --- TURKISH DICTIONARY ---
$lang_tr = [
    // ... (Keep existing Navbar/Footer/Hero keys) ...
    'nav_home'       => 'Anasayfa',
    'nav_login'      => 'Giriş Yap',
    'nav_register'   => 'Kayıt Ol',
    'search_place'   => 'Taze yeşillik, süt ürünleri ara...',
    'basket'         => 'Sepet',
    'nav_about'      => 'Leaf Market Hakkında',

    'cat_fresh'      => 'Taze Ürünler',
    'cat_dairy'      => 'Süt ve Yumurta',
    'cat_bakery'     => 'Fırın',
    'cat_pantry'     => 'Kiler',
    'cat_bev'        => 'İçecekler',
    'cat_home'       => 'Ev ve Bahçe',

    'hero_title'     => 'Yerel. Sürdürülebilir. Yeşil.',
    'hero_sub'       => 'Topluluğunuzdan çevre dostu ürünler alın ve satın.',
    'hero_btn'       => 'Alışverişe Başla',

    'slide1_title'   => 'Sürdürülebilir Gelecek.',
    'slide1_text'    => 'Ekolojik dengeye ve sürdürülebilir kalkınmaya kararlıyız. Amacımız, sizi doğrudan yerel üreticilerle buluşturarak karbon ayak izini azaltmaktır.',
    'slide1_quote'   => '"Dünya hepimizin ortak noktasıdır."',
    'slide1_author'  => '— Wendell Berry',

    'slide2_title'   => 'Birlikte Büyüyelim.',
    'slide2_text'    => 'Yerel ekonomileri güçlendirmek işimizin kalbidir. Yakındaki çiftçileri ve zanaatkarları destekleyerek dayanıklı bir topluluk inşa ediyoruz.',
    'slide2_quote'   => '"Yalnızken çok az şey yapabiliriz; birlikte çok şey yapabiliriz."',
    'slide2_author'  => '— Helen Keller',

    'slide3_title'   => 'Taze ve Organik.',
    'slide3_sub1'    => 'Sebzeler • Süt Ürünleri • Kiler • Meyveler',
    'slide3_sub2'    => 'İhtiyacınız olan her şey, komşularınızdan etik olarak temin edildi.',
    'slide3_btn'     => 'AŞAĞIDAN BİR KATEGORİ SEÇİN',

    'carousel_title' => 'İlginizi Çekebilir',
    'low_stock'      => 'Az Stok',
    'currency'       => 'TL',

    'banner_title'   => "Doğanın En İyisi, Yerelden",
    'banner_sub'     => 'Topluluğunu destekle • Taze ye • Sürdürülebilir yaşa',

    'contact_header' => 'Bize Ulaşın',
    'contact_desc'   => 'Bir sorunuz mu var? Bize e-posta gönderin.',
    'help_header'    => 'Yardım',
    'about_header'   => 'Biz LEAF MARKET',
    'you_like_header'=> 'İlginizi Çekebilir',
    'buy_online'     => 'Online Satın Al',
    'payment'        => 'Ödeme Yöntemleri',
    'shipping'       => 'Kargo ve Takip',
    'about_link'     => 'Leaf Market Hakkında',
    'sustainability' => 'Sürdürülebilirlik',
    'rights'         => 'Tüm hakları saklıdır.',
    'lang_label'     => 'Dil:',

    // --- NEW: PRODUCT TRANSLATIONS (Turkish Map) ---
    'products' => [
        'Red Apples'      => 'Kırmızı Elma',
        'Organic Carrots' => 'Organik Havuç',
        'Free Range Eggs' => 'Gezen Tavuk Yumurtası',
        'Fresh Spinach'   => 'Taze Ispanak',
        'Sourdough Bread' => 'Ekşi Mayalı Ekmek',
        'Honey Jar'       => 'Bal Kavanozu',
    ]
];

$text = ($_SESSION['lang'] == 'tr') ? $lang_tr : $lang_en;
?>