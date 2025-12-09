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
    // Navbar
    'nav_home'       => 'Home',
    'nav_login'      => 'Log In',
    'nav_register'   => 'Register',
    'search_place'   => 'Search for fresh greens, dairy...',
    'basket'         => 'Basket',
    'nav_about'      => 'About Leaf Market',

    // Categories
    'cat_fresh'      => 'Fresh Produce',
    'cat_dairy'      => 'Dairy & Eggs',
    'cat_bakery'     => 'Bakery',
    'cat_pantry'     => 'Pantry',
    'cat_bev'        => 'Beverages',
    'cat_home'       => 'Home & Garden',
    'shop_all'       => 'All Products',

    // Hero
    'hero_title'     => 'Local. Sustainable. Green.',
    'hero_sub'       => 'Buy and sell eco-friendly products from your community.',
    'hero_btn'       => 'Shop Now',
    'slide1_title'   => 'Sustainable Future.',
    'slide1_text'    => 'We are committed to ecological balance and sustainable development.',
    'slide1_quote'   => '"The Earth is what we all have in common."',
    'slide1_author'  => '— Wendell Berry',
    'slide2_title'   => 'Grow Together.',
    'slide2_text'    => 'Empowering local economies is at the heart of what we do.',
    'slide2_quote'   => '"Alone we can do so little; together we can do so much."',
    'slide2_author'  => '— Helen Keller',
    'slide3_title'   => 'Fresh & Organic.',
    'slide3_sub1'    => 'Vegetables • Dairy • Pantry • Fruits',
    'slide3_sub2'    => 'Everything you need, ethically sourced from your neighbors.',
    'slide3_btn'     => 'CHOOSE A CATEGORY BELOW',

    // Components
    'carousel_title' => 'You Might Be Interested In',
    'low_stock'      => 'Low Stock',
    'currency'       => 'TL',
    'add_cart'       => 'Add to Basket',
    'banner_title'   => "Nature's Best, Locally Sourced",
    'banner_sub'     => 'Support your community • Eat fresh • Live sustainable',

    // Footer
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
    'learn_more'     => 'Learn More About Us',
    'about_teaser_title' => 'Supporting Local Communities & Sustainable Living',
    'about_teaser_text'  => 'Leaf Market connects local producers with conscious consumers. We believe in sustainable practices, reducing carbon footprints, and building stronger communities through local commerce.',

    // Eco Score
    'eco_score_label' => 'Sustainability Score',
    'impact_high'     => 'Excellent Eco-Impact',
    'impact_med'      => 'Good Eco-Impact',
    'impact_low'      => 'Standard Impact',
    'delivery_type'   => 'Green Delivery:',
    'packaging'       => 'Packaging:',
    'seller_loc'      => 'Seller Location:',
    
    // Eco Terms
    'del_bike'        => '🚲 Bike Courier (Zero Carbon)',
    'del_walk'        => '🚶 Walking Courier (Zero Carbon)',
    'del_public'      => '🚌 Public Transport',
    'del_cargo'       => '🚛 Standard Cargo',
    'pack_plastic_free' => 'Plastic-Free',
    'pack_recycled'     => 'Recycled Paper',
    'pack_standard'     => 'Standard',

    // Seller & Reviews
    'seller_profile'  => 'Seller Profile',
    'seller_bio'      => 'Bio',
    'seller_rating'   => 'Seller Rating',
    'other_products'  => 'Other products by this seller',
    'verified_seller' => 'Verified Local Producer',
    'eco_level'       => 'Eco Level',
    'reviews_title'   => 'Customer Reviews',
    'verified_buyer'  => 'Verified Buyer',
    'rating_label'    => 'Rating',

    // Cart
    'cart_title'      => 'Your Green Basket',
    'cart_empty'      => 'Your basket is empty',
    'cart_empty_sub'  => 'Start filling it with eco-friendly local goods!',
    'continue_shop'   => 'Continue Shopping',
    'start_shop'      => 'Start Shopping',
    'total_carbon'    => 'Total Carbon Saved',
    'total_price'     => 'Total Price',
    'clear_cart'      => 'Clear Cart',
    'checkout'        => 'Checkout',
    'quantity'        => 'Qty',
    'impact_fun_fact' => 'Equivalent to charging %s smartphones!',

    // About Page
    'about_eyebrow'   => 'ABOUT LEAF LEAF',
    'about_headline1' => 'CULTIVATING SUSTAINABILITY',
    'about_headline2' => 'IN YOUR NEIGHBORHOOD',
    'about_intro'     => 'Since 2025, Leaf Leaf Green Market has remained faithful to its artisanal model and its ecological values. The freedom to grow, the constant quest for sustainable materials, and the transmission of exceptional local produce forge the uniqueness of Leaf Leaf.',
    'about_section1_title' => 'A COMMUNITY SPIRIT',
    'about_section1_text'  => 'For the first generation of Leaf Leaf, we have been an independent, family-oriented platform. We believe that the best way to predict the future is to grow it yourself. Our entrepreneurial spirit drives us to connect local artisans with conscious consumers.',
    'about_section1_link'  => 'DISCOVER OUR MODEL',
    'about_section2_title' => 'CREATIVE FREEDOM',
    'about_section2_text'  => 'Sustainability is not just a goal; it is our canvas. The sixteen categories of our market create collections that combine freedom with inventiveness. From hand-woven baskets to heirloom tomatoes, every object tells a story.',
    'about_section2_link'  => 'VIEW OUR VALUES',
    'about_quote'     => '"The Earth is what we all have in common."',
    'about_quote_author' => '— Wendell Berry'
];

// --- TURKISH DICTIONARY ---
$lang_tr = [
    // Navbar
    'nav_home'       => 'Anasayfa',
    'nav_login'      => 'Giriş Yap',
    'nav_register'   => 'Kayıt Ol',
    'search_place'   => 'Taze yeşillik, süt ürünleri ara...',
    'basket'         => 'Sepet',
    'nav_about'      => 'Leaf Market Hakkında',

    // Categories
    'cat_fresh'      => 'Taze Ürünler',
    'cat_dairy'      => 'Süt ve Yumurta',
    'cat_bakery'     => 'Fırın',
    'cat_pantry'     => 'Kiler',
    'cat_bev'        => 'İçecekler',
    'cat_home'       => 'Ev ve Bahçe',
    'shop_all'       => 'Tüm Ürünler',

    // Hero
    'hero_title'     => 'Yerel. Sürdürülebilir. Yeşil.',
    'hero_sub'       => 'Topluluğunuzdan çevre dostu ürünler alın ve satın.',
    'hero_btn'       => 'Alışverişe Başla',
    'slide1_title'   => 'Sürdürülebilir Gelecek.',
    'slide1_text'    => 'Ekolojik dengeye ve sürdürülebilir kalkınmaya kararlıyız.',
    'slide1_quote'   => '"Dünya hepimizin ortak noktasıdır."',
    'slide1_author'  => '— Wendell Berry',
    'slide2_title'   => 'Birlikte Büyüyelim.',
    'slide2_text'    => 'Yerel ekonomileri güçlendirmek işimizin kalbidir.',
    'slide2_quote'   => '"Yalnızken çok az şey yapabiliriz; birlikte çok şey yapabiliriz."',
    'slide2_author'  => '— Helen Keller',
    'slide3_title'   => 'Taze ve Organik.',
    'slide3_sub1'    => 'Sebzeler • Süt Ürünleri • Kiler • Meyveler',
    'slide3_sub2'    => 'İhtiyacınız olan her şey, komşularınızdan etik olarak temin edildi.',
    'slide3_btn'     => 'AŞAĞIDAN BİR KATEGORİ SEÇİN',

    // Components
    'carousel_title' => 'İlginizi Çekebilir',
    'low_stock'      => 'Az Stok',
    'currency'       => 'TL',
    'add_cart'       => 'Sepete Ekle',
    'banner_title'   => "Doğanın En İyisi, Yerelden",
    'banner_sub'     => 'Topluluğunu destekle • Taze ye • Sürdürülebilir yaşa',

    // Footer
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
    'learn_more'     => 'Hakkımızda Daha Fazla Bilgi',
    'about_teaser_title' => 'Yerel Toplulukları ve Sürdürülebilir Yaşamı Destekleme',
    'about_teaser_text'  => 'Leaf Market, yerel üreticileri bilinçli tüketicilerle buluşturuyor. Sürdürülebilir uygulamalara, karbon ayak izini azaltmaya ve yerel ticaret yoluyla daha güçlü topluluklar oluşturmaya inanıyoruz.',

    // Eco Score
    'eco_score_label' => 'Sürdürülebilirlik Puanı',
    'impact_high'     => 'Mükemmel Eko-Etki',
    'impact_med'      => 'İyi Eko-Etki',
    'impact_low'      => 'Standart Etki',
    'delivery_type'   => 'Yeşil Teslimat:',
    'packaging'       => 'Paketleme:',
    'seller_loc'      => 'Satıcı Konumu:',

    // Eco Terms
    'del_bike'        => '🚲 Bisiklet Kurye (Sıfır Karbon)',
    'del_walk'        => '🚶 Yaya Kurye (Sıfır Karbon)',
    'del_public'      => '🚌 Toplu Taşıma',
    'del_cargo'       => '🚛 Standart Kargo',
    'pack_plastic_free' => 'Plastiksiz',
    'pack_recycled'     => 'Geri Dönüştürülmüş Kağıt',
    'pack_standard'     => 'Standart',

    // Seller & Reviews
    'seller_profile'  => 'Satıcı Profili',
    'seller_bio'      => 'Biyografi',
    'seller_rating'   => 'Satıcı Puanı',
    'other_products'  => 'Bu satıcının diğer ürünleri',
    'verified_seller' => 'Doğrulanmış Yerel Üretici',
    'eco_level'       => 'Eko Seviyesi',
    'reviews_title'   => 'Müşteri Yorumları',
    'verified_buyer'  => 'Doğrulanmış Alıcı',
    'rating_label'    => 'Puan',

    // Cart
    'cart_title'      => 'Yeşil Sepetiniz',
    'cart_empty'      => 'Sepetiniz boş',
    'cart_empty_sub'  => 'Çevre dostu yerel ürünlerle doldurmaya başlayın!',
    'continue_shop'   => 'Alışverişe Devam Et',
    'start_shop'      => 'Alışverişe Başla',
    'total_carbon'    => 'Toplam Karbon Tasarrufu',
    'total_price'     => 'Toplam Tutar',
    'clear_cart'      => 'Sepeti Temizle',
    'checkout'        => 'Ödeme Yap',
    'quantity'        => 'Adet',
    'impact_fun_fact' => '%s adet akıllı telefon şarjına eşdeğer!',

    // About Page
    'about_eyebrow'   => 'LEAF LEAF HAKKINDA',
    'about_headline1' => 'SÜRDÜRÜLEBİLİRLİĞİ',
    'about_headline2' => 'MAHALLENİZDE YETİŞTİRMEK',
    'about_intro'     => '2025\'ten beri Leaf Leaf Green Market, zanaatkarlık modeline ve ekolojik değerlerine sadık kalmıştır. Büyüme özgürlüğü, sürdürülebilir malzemeler arayışı ve istisnai yerel ürünlerin aktarımı, Leaf Leaf\'in benzersizliğini oluşturur.',
    'about_section1_title' => 'BİR TOPLULUK RUHU',
    'about_section1_text'  => 'Leaf Leaf\'in ilk nesli için bağımsız, aile odaklı bir platform olduk. Geleceği tahmin etmenin en iyi yolunun onu kendiniz yetiştirmek olduğuna inanıyoruz. Girişimcilik ruhumuz bizi yerel zanaatkarları bilinçli tüketicilerle buluşturmaya yönlendiriyor.',
    'about_section1_link'  => 'MODELİMİZİ KEŞFEDİN',
    'about_section2_title' => 'YARATICI ÖZGÜRLÜK',
    'about_section2_text'  => 'Sürdürülebilirlik sadece bir hedef değil; bizim tuvalimizdir. Pazarımızın on altı kategorisi, özgürlüğü yaratıcılıkla birleştiren koleksiyonlar oluşturur. El dokuması sepetlerden ata tohumlu domateslerine kadar her nesne bir hikaye anlatır.',
    'about_section2_link'  => 'DEĞERLERİMİZİ GÖRÜNTÜLE',
    'about_quote'     => '"Dünya hepimizin ortak noktasıdır."',
    'about_quote_author' => '— Wendell Berry'
];

$text = ($_SESSION['lang'] == 'tr') ? $lang_tr : $lang_en;
?>