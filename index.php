<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("
    SELECT p.*, 
    (SELECT image_path FROM product_images 
     WHERE product_id = p.id 
     ORDER BY id ASC LIMIT 1) AS main_image
    FROM products p
    ORDER BY p.id DESC
");

$products = $stmt->fetchAll();


$stmt = $pdo->query("
    SELECT c.*, 
    (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as total_products
    FROM categories c
    ORDER BY id DESC
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html class="no-js" lang="zxx">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Auto Market</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.css">
    <!-- Fontawesome Star -->
    <link rel="stylesheet" href="assets/css/vendor/fontawesome-stars.css">
    <!-- Ion Icon -->
    <link rel="stylesheet" href="assets/css/vendor/ion-fonts.css">
    <!-- Slick CSS -->
    <link rel="stylesheet" href="assets/css/plugins/slick.css">
    <!-- Animation -->
    <link rel="stylesheet" href="assets/css/plugins/animate.css">
    <!-- jQuery Ui -->
    <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
    <!-- Lightgallery -->
    <link rel="stylesheet" href="assets/css/plugins/lightgallery.min.css">
    <!-- Nice Select -->
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">

    <!-- Vendor & Plugins CSS (Please remove the comment from below vendor.min.css & plugins.min.css for better website load performance and remove css files from the above) -->
    <!--
    <script src="assets/js/vendor/vendor.min.js"></script>
    <script src="assets/js/plugins/plugins.min.js"></script>
    -->

    <!-- Main Style CSS (Please use minify version for better website load performance) -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!--<link rel="stylesheet" href="assets/css/style.min.css">-->

</head>

<body class="template-color-1">

    <div class="main-wrapper">

        <!-- Begin Uren's Header Main Area -->
        <header class="header-main_area header-main_area-2 header-main_area-3">
            <div class="header-middle_area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-3 col-lg-2 col-md-3 col-sm-5">
                            <div class="header-logo_area">
                                <a href="index.php">
                                    <img src="assets/images/menu/logo/2.png" alt="Uren's Logo">
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6 d-none d-lg-block">
                            <!-- <div class="hm-form_area">
                                <form action="#" class="hm-searchbox">
                                    <select class="nice-select select-search-category">
                                        <option value="0">All Categories</option>
                                        <option value="10">Laptops</option>
                                        <option value="17">Prime Video</option>
                                        <option value="20">All Videos</option>
                                       
                                       
                                     
                                    </select>
                                    <input type="text" placeholder="Enter your search key ...">
                                    <button class="header-search_btn" type="submit"><i
                                        class="ion-ios-search-strong"><span>Search</span></i></button>
                                </form>
                            </div> -->
                        </div>
                        <div class="col-lg-4 col-md-9 col-sm-7">
                            <div class="header-right_area">
                                <ul>
                                    <li class="mobile-menu_wrap d-flex d-lg-none">
                                        <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn color--white">
                                            <i class="ion-navicon"></i>
                                        </a>
                                    </li>
                                    <!-- <li class="minicart-wrap">
                                        <a href="#miniCart" class="minicart-btn toolbar-btn">
                                            <div class="minicart-count_area">
                                                <span class="item-count">3</span>
                                                <i class="ion-bag"></i>
                                            </div>
                                            <div class="minicart-front_text">
                                                <span>Cart:</span>
                                                <span class="total-price">462.4</span>
                                            </div>
                                        </a>
                                    </li> -->
                                    <li class="contact-us_wrap">
                                        <a href="tel://+221 76 648 74 20"><i class="ion-android-call"></i>+221 76 648 74 20</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-top_area bg--primary">
                <div class="container-fluid">
                    <div class="row">
                        <div class="custom-category_col col-12">
                            <div class="category-menu category-menu-hidden">
                                <div class="category-heading">
                                    <h2 class="categories-toggle">
                                        <span></span>
                                        <span>Categories</span>
                                    </h2>
                                </div>
                                <div id="cate-toggle" class="category-menu-list">
                                    <ul>
                                        <li><a href="shop-left-sidebar">Body Parts</a></li>
                                        <li><a href="shop-left-sidebar">Interior</a></li>
                                        <li><a href="shop-left-sidebar">Audio</a></li>
                                        <li><a href="shop-left-sidebar">End Tables</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="custom-menu_col col-12 d-none d-lg-block">
                            <div class="main-menu_area position-relative">
                                <nav class="main-nav">
                                    <ul>
                                        <li class="dropdown-holder active"><a href="index.php">Accueil</a>
                                            
                                        </li>
                                        <li class=""><a href="javascript:void(0)">Compte <i
                                                class="ion-ios-arrow-down"></i></a>
                                            <ul class="hm-dropdown">

                                            <?php if (isset($_SESSION['user_id'])): ?>

                                                <li>
                                                    <a href="dashboard.php">👤 Mon compte</a>
                                                </li>

                                                <li>
                                                    <a href="logout.php">🚪 Déconnexion</a>
                                                </li>

                                            <?php else: ?>

                                                <li>
                                                    <a href="login.php">Login | Register</a>
                                                </li>

                                            <?php endif; ?>

                                            </ul>
                                        </li>                                       
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="custom-setting_col col-12 d-none d-lg-block">
                            <div class="ht-right_area">
                               

                                    <div class="ht-menu">
                                        <ul>

                                            <li>
                                                <a href="#">
                                                    <span class="fa fa-user"></span> 
                                                    <span>
                                                        <?= isset($_SESSION['user_id']) 
                                                            ? htmlspecialchars($_SESSION['username'] ?? 'Mon compte') 
                                                            : 'My Account' ?>
                                                    </span>
                                                    <i class="fa fa-chevron-down"></i>
                                                </a>

                                                <ul class="ht-dropdown ht-my_account">

                                                    <?php if (isset($_SESSION['user_id'])): ?>

                                                        <li>
                                                            <a href="dashboard.php">Dashboard</a>
                                                        </li>

                                                        <li>
                                                            <a href="logout.php">Déconnexion</a>
                                                        </li>

                                                    <?php else: ?>

                                                        <li>
                                                            <a href="register.php">Register</a>
                                                        </li>

                                                        <li class="active">
                                                            <a href="login.php">Login</a>
                                                        </li>

                                                    <?php endif; ?>

                                                </ul>
                                            </li>

                                        </ul>
                                    </div>
                            </div>
                        </div>
                        <div class="custom-search_col col-12 d-none d-md-block d-lg-none">
                            <div class="hm-form_area">
                                <form action="#" class="hm-searchbox">
                                    <select class="nice-select select-search-category">
                                        <option value="0">All Categories</option>
                                        <option value="10">Laptops</option>
                                        <option value="17">Prime Video</option>
                                        
                                   
                                      
                                    </select>
                                    <input type="text" placeholder="Enter your search key ...">
                                    <button class="header-search_btn" type="submit"><i
                                        class="ion-ios-search-strong"><span>Search</span></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-top_area header-sticky bg--primary">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-8 col-lg-7 d-lg-block d-none">
                            <div class="main-menu_area position-relative">
                                <nav class="main-nav">
                                     <ul>
                                        <li class="dropdown-holder active"><a href="index.php">Accueil</a>
                                            
                                        </li>
                                        <li class=""><a href="javascript:void(0)">Compte <i
                                                class="ion-ios-arrow-down"></i></a>
                                            <ul class="hm-dropdown">

                                                <?php if (isset($_SESSION['user_id'])): ?>

                                                    <li>
                                                        <a href="dashboard.php">👤 Mon compte</a>
                                                    </li>

                                                    <li>
                                                        <a href="logout.php">🚪 Déconnexion</a>
                                                    </li>

                                                <?php else: ?>

                                                    <li>
                                                        <a href="login.php">Login | Register</a>
                                                    </li>

                                                <?php endif; ?>

                                                </ul>
                                        </li>
                                       
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-sm-3 d-block d-lg-none">
                            <div class="header-logo_area header-sticky_logo">
                                <a href="index.php">
                                    <img src="assets/images/menu/logo/3.png" alt="Uren's Logo">
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5 col-sm-9">
                            <div class="header-right_area">
                                <ul>
                                    <li class="mobile-menu_wrap d-flex d-lg-none">
                                        <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn color--white">
                                            <i class="ion-navicon"></i>
                                        </a>
                                    </li>
                                    
                                    <li class="contact-us_wrap">
                                        <a href="tel://+221 76 648 74 20"><i class="ion-android-call"></i>+221 76 648 74 20</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-minicart_wrapper" id="miniCart">
                <div class="offcanvas-menu-inner">
                    <a href="#" class="btn-close"><i class="ion-android-close"></i></a>
                    <div class="minicart-content">
                        <div class="minicart-heading">
                            <h4>Shopping Cart</h4>
                        </div>
                        <ul class="minicart-list">
                           
                        </ul>
                    </div>
                   
                </div>
            </div>
            <div class="mobile-menu_wrapper" id="mobileMenu">
                <div class="offcanvas-menu-inner">
                    <div class="container">
                        <a href="#" class="btn-close"><i class="ion-android-close"></i></a>
                        <div class="offcanvas-inner_search">
                            <form action="#" class="inner-searchbox">
                                <input type="text" placeholder="Search for item...">
                                <button class="search_btn" type="submit"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                        <nav class="offcanvas-navigation">
                            <ul class="mobile-menu">
                               
                                        <li class="dropdown-holder active"><a href="index.php">Accueil</a>
                                            
                                        </li>
                                        <li class=""><a href="javascript:void(0)">Compte <i
                                                class="ion-ios-arrow-down"></i></a>
                                            <ul class="hm-dropdown">

                                                <?php if (isset($_SESSION['user_id'])): ?>

                                                    <li>
                                                        <a href="dashboard.php">👤 Mon compte</a>
                                                    </li>

                                                    <li>
                                                        <a href="logout.php">🚪 Déconnexion</a>
                                                    </li>

                                                <?php else: ?>

                                                    <li>
                                                        <a href="login.php">Login | Register</a>
                                                    </li>

                                                <?php endif; ?>

                                                </ul>
                                        </li>                                       
                                    
                            </ul>
                        </nav>
                      

                        <nav class="offcanvas-navigation user-setting_area">
                            <ul class="mobile-menu">
                                <li class="menu-item-has-children active">
                                    <a href="javascript:void(0)">
                                        <span class="mm-text">
                                            <?= isset($_SESSION['user_id']) ? 'Mon compte' : 'User Setting' ?>
                                        </span>
                                    </a>

                                    <ul class="sub-menu">

                                        <?php if (isset($_SESSION['user_id'])): ?>

                                            <li>
                                                <a href="dashboard.php">
                                                    <span class="mm-text">Dashboard</span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="logout.php">
                                                    <span class="mm-text">Déconnexion</span>
                                                </a>
                                            </li>

                                        <?php else: ?>

                                            <li>
                                                <a href="login.php">
                                                    <span class="mm-text">Login</span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="register.php">
                                                    <span class="mm-text">Register</span>
                                                </a>
                                            </li>

                                        <?php endif; ?>

                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- Uren's Header Main Area End Here -->

        <div class="uren-slider_area uren-slider_area-3">
            <div class="main-slider slider-navigation_style-2">
                <!-- Begin Single Slide Area -->
                <div class="single-slide animation-style-01 bg-5">
                    <div class="slider-content">
                        <span class="carlet-text_color">Save $120 when you buy</span>
                        <h3>Wheels &amp; Tires</h3>
                        <p class="short-desc">Explore and immerse in exciting 360 content withFulldive’s all-in-one virtual reality platform</p>
                        <div class="uren-btn-ps_center slide-btn">
                            <a class="uren-btn" href="shop-left-sidebar">Read More</a>
                        </div>
                    </div>
                </div>
                <!-- Single Slide Area End Here -->
                <!-- Begin Single Slide Area -->
                <div class="single-slide animation-style-02 bg-6">
                    <div class="slider-content slider-content-2">
                        <span class="carlet-text_color">We have the part you need</span>
                        <h3>20% off Auto part</h3>
                        <p class="short-desc">Explore and immerse in exciting 360 content withFulldive’s all-in-one virtual reality platform</p>
                        <div class="uren-btn-ps_center slide-btn">
                            <a class="uren-btn" href="shop-left-sidebar">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Begin Uren's Banner Two Area -->
        <!-- <div class="uren-banner_area uren-banner_area-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-item img-hover_effect">
                            <a href="shop-left-sidebar">
                                <img class="img-full" src="assets/images/banner/3-1.jpg" alt="Uren's Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-item img-hover_effect">
                            <a href="shop-left-sidebar">
                                <img class="img-full" src="assets/images/banner/3-2.jpg" alt="Uren's Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-item img-hover_effect">
                            <a href="shop-left-sidebar">
                                <img class="img-full" src="assets/images/banner/3-3.jpg" alt="Uren's Banner">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Uren's Banner Two Area End Here -->

        <!-- Begin Uren's Shipping Area 
        <div class="uren-shipping_area">
            <div class="container-fluid">
                <div class="shipping-nav">
                    <div class="row no-gutters">
                        <div class="shipping-grid">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <i class="ion-ios-paperplane-outline"></i>
                                </div>
                                <div class="shipping-content">
                                    <h6>Free Shipping</h6>
                                    <p>Free shipping on all US order</p>
                                </div>
                            </div>
                        </div>
                        <div class="shipping-grid">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <i class="ion-ios-help-outline"></i>
                                </div>
                                <div class="shipping-content">
                                    <h6>Support 24/7</h6>
                                    <p>Contact us 24 hours a day</p>
                                </div>
                            </div>
                        </div>
                        <div class="shipping-grid">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <i class="ion-ios-refresh-empty"></i>
                                </div>
                                <div class="shipping-content">
                                    <h6>100% Money Back</h6>
                                    <p>You have 30 days to Return</p>
                                </div>
                            </div>
                        </div>
                        <div class="shipping-grid">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <i class="ion-ios-undo-outline"></i>
                                </div>
                                <div class="shipping-content">
                                    <h6>90 Days Return</h6>
                                    <p>If goods have problems</p>
                                </div>
                            </div>
                        </div>
                        <div class="shipping-grid">
                            <div class="shipping-item">
                                <div class="shipping-icon">
                                    <i class="ion-ios-locked-outline"></i>
                                </div>
                                <div class="shipping-content last-child">
                                    <h6>Payment Secure</h6>
                                    <p>We ensure secure payment</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         Uren's Shipping Area End Here -->

        <!-- Begin Featured Categories Area -->
        <div class="featured-categories_area featured-categories_area-2">
            <div class="container-fluid">
                <div class="section-title_area">
                    <span></span>
                    <h3>Categories</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="featured-categories_slider-2 uren-slick-slider slider-navigation_style-1 img-hover-effect_area" data-slick-options='{
                        "slidesToShow": 6,
                        "arrows" : true
                       }' data-slick-responsive='[
                                             {"breakpoint":1501, "settings": {"slidesToShow": 5}},
                                             {"breakpoint":1200, "settings": {"slidesToShow": 4}},
                                             {"breakpoint":768, "settings": {"slidesToShow": 3}},
                                             {"breakpoint":576, "settings": {"slidesToShow": 2}},
                                             {"breakpoint":480, "settings": {"slidesToShow": 1}}
                                         ]'>
                            <?php foreach ($categories as $cat): ?>
<div class="slide-item">
    <div class="slide-inner">
        <div class="single-product">

            <div class="slide-image_area">
                <a href="shop.php?category=<?= $cat['id'] ?>">

                    <?php if (!empty($cat['image'])): ?>
                        <img src="uploads/categories/<?= $cat['image'] ?>" 
                             alt="<?= htmlspecialchars($cat['name']) ?>">
                    <?php else: ?>
                        <img src="assets/images/no-image.png" alt="No image">
                    <?php endif; ?>

                </a>
            </div>

            <div class="slide-content_area">
                <h3>
                    <a href="shop.php?category=<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </h3>

                <span>(<?= $cat['total_products'] ?> Produits)</span>
            </div>

        </div>
    </div>
</div>
<?php endforeach; ?>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featured Categories Area End Here -->

        <!-- Begin Multiple Section Area -->
        <div class="multiple-section_area bg--white_smoke">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
    <div class="special-product_wrap img-hover-effect_area-2">

        <div class="section-title_area bg--white">
            <span>Nouveautés</span>
            <h3>Produits récents</h3>
        </div>

        <div class="special-product_slider-2 uren-slick-slider slider-navigation_style-1 img-hover-effect_area"
            data-slick-options='{
                "slidesToShow": 4,
                "arrows" : true
            }'
            data-slick-responsive='[
                {"breakpoint":1200, "settings": {"slidesToShow": 3}},
                {"breakpoint":992, "settings": {"slidesToShow": 2}},
                {"breakpoint":768, "settings": {"slidesToShow": 1}}
            ]'>

            <?php foreach ($products as $p): ?>

            <div class="slide-item">
                <div class="inner-slide">
                    <div class="single-product">

                        <div class="product-img">
                            <a href="product_details.php?id=<?= $p['id'] ?>">

                                <?php if (!empty($p['main_image'])): ?>
                                    <img class="primary-img"
                                         src="<?= $p['main_image'] ?>"
                                         alt="<?= htmlspecialchars($p['name']) ?>">
                                <?php else: ?>
                                    <img class="primary-img"
                                         src="assets/images/no-image.png">
                                <?php endif; ?>

                            </a>
                        </div>

                        <div class="product-content">
                            <div class="product-desc_info">

                                <h6 class="product-name">
                                    <a href="product_details.php?id=<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['name']) ?>
                                    </a>
                                </h6>

                                <div class="price-box">
                                    <span class="new-price">
                                        <?= number_format($p['price'], 0, ',', ' ') ?> FCFA
                                    </span>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</div>
                    <!-- <div class="col-xl-9">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="list-product_wrap img-hover-effect_area-2 bg--white">
                                    <div class="section-title_area bg--white">
                                        <span>Top Featured On This Week</span>
                                        <h3>Featured Products</h3>
                                    </div>
                                    <div class="list-product_slider uren-slick-slider slider-navigation_style-1 section-space_mn-30 img-hover-effect_area" data-slick-options='{
                                "slidesToShow": 1,
                                "arrows" : true,
                                "rows": 4
                               }' data-slick-responsive='[
                                    {"breakpoint":1501, "settings": {"slidesToShow": 1}},
                                    {"breakpoint":1200, "settings": {"slidesToShow": 2}},
                                    {"breakpoint":768, "settings": {"slidesToShow": 1}}
                               ]'>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/1-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Nam vitae autem quo
                                                                perspiciatis magni</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$122.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/2-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quasi maxime pariatur
                                                                nisi non</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$150.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/3-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quos iure similique
                                                                qui beatae</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$180.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/4-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Rem eveniet eum rerum
                                                                est veniam</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$116.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/1-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Nam vitae autem quo
                                                                perspiciatis magni</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$122.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/2-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quasi maxime pariatur
                                                                nisi non</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$150.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/3-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quos iure similique
                                                                qui beatae</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$180.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/4-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Accusamus dicta odio
                                                                magni cumque</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$116.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/1-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Nam vitae autem quo
                                                                perspiciatis magni</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$122.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/2-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quasi maxime pariatur
                                                                nisi non</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$150.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="list-product_wrap img-hover-effect_area-2 bg--white">
                                    <div class="section-title_area bg--white">
                                        <span>On-Sale On This Week</span>
                                        <h3>On-Sale Products</h3>
                                    </div>
                                    <div class="list-product_slider uren-slick-slider slider-navigation_style-1 img-hover-effect_area" data-slick-options='{
                                "slidesToShow": 1,
                                "arrows" : true,
                                "rows": 4
                               }' data-slick-responsive='[
                                    {"breakpoint":1501, "settings": {"slidesToShow": 1}},
                                    {"breakpoint":1200, "settings": {"slidesToShow": 2}},
                                    {"breakpoint":768, "settings": {"slidesToShow": 1}}
                                                 ]'>
                                        <?php foreach ($products as $p): ?>

                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">

                                                    <div class="product-img">
                                                        <a href="product_details.php?id=<?= $p['id'] ?>">

                                                            <?php if($p['main_image']): ?>
                                                                <img class="primary-img"
                                                                    src="<?= $p['main_image'] ?>"
                                                                    style="height:200px;object-fit:cover;">
                                                            <?php else: ?>
                                                                <img class="primary-img"
                                                                    src="assets/images/no-image.png">
                                                            <?php endif; ?>

                                                        </a>

                                                        <div class="sticker">
                                                            <span class="sticker">New</span>
                                                        </div>
                                                    </div>

                                                    <div class="product-content">
                                                        <div class="product-desc_info">

                                                            <h6>
                                                                <a class="product-name"
                                                                href="product_details.php?id=<?= $p['id'] ?>">
                                                                <?= htmlspecialchars($p['title']) ?>
                                                                </a>
                                                            </h6>

                                                            <div class="price-box">
                                                                <span class="new-price">
                                                                    <?= number_format($p['price'],0,',',' ') ?> FCFA
                                                                </span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php endforeach; ?>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/4-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Accusamus dicta odio
                                                                magni cumque</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$116.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/1-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Nam vitae autem quo
                                                                perspiciatis magni</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$122.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/2-2.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quasi maxime pariatur
                                                                nisi non</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$150.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/1-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Nam vitae autem quo
                                                                perspiciatis magni</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$122.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/2-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quasi maxime pariatur
                                                                nisi non</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$150.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/3-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quos iure similique
                                                                qui beatae</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$180.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/4-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Rem eveniet eum rerum
                                                                est veniam</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$116.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/2-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li class="silver-color"><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quasi maxime pariatur
                                                                nisi non</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$150.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slide-item">
                                            <div class="inner-slide">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="shop-left-sidebar">
                                                            <img src="assets/images/product/medium-size/3-1.jpg" alt="Uren's Product Image">
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="rating-box">
                                                            <ul>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                                <li><i class="ion-android-star"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="product-name">
                                                            <a href="shop-left-sidebar">Quos iure similique
                                                                qui beatae</a>
                                                        </h3>
                                                        <div class="price-box">
                                                            <span class="new-price">$180.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Multiple Section Area End Here -->

        <!-- Begin Uren's Banner Area -->
        <!-- <div class="uren-banner_area bg--white_smoke">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="banner-item img-hover_effect">
                            <div class="banner-img-1"></div>
                            <div class="banner-content">
                                <span class="offer">Get 20% off your order</span>
                                <h4>Car and Truck</h4>
                                <h3>Mercedes Benz</h3>
                                <p>Explore and immerse in exciting 360 content with
                                    Fulldive’s all-in-one virtual reality platform</p>
                                <div class="uren-btn-ps_left">
                                    <a class="uren-btn" href="shop-left-sidebar">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner-item img-hover_effect">
                            <div class="banner-img-1 banner-img-2"> </div>
                            <div class="banner-content">
                                <span class="offer">Save $120 when you buy</span>
                                <h4>Rotiform SFO </h4>
                                <h3>Custom Forged</h3>
                                <p>Explore and immerse in exciting 360 content with
                                    Fulldive’s all-in-one virtual reality platform</p>
                                <div class="uren-btn-ps_left">
                                    <a class="uren-btn" href="shop-left-sidebar">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Uren's Banner Area End Here -->

        <!-- Begin Uren's Product Area -->
        <div class="uren-product_area">
            <div class="container-fluid">
                <div class="row">
                    <!-- <div class="col-lg-12">
                        <div class="section-title_area">
                            <span>Top New On This Week</span>
                            <h3>New Arrivals Products</h3>
                        </div>
                        <div class="product-slider uren-slick-slider slider-navigation_style-1 img-hover-effect_area" data-slick-options='{
                        "slidesToShow": 6,
                        "arrows" : true
                        }' data-slick-responsive='[
                                                {"breakpoint":1501, "settings": {"slidesToShow": 4}},
                                                {"breakpoint":1200, "settings": {"slidesToShow": 3}},
                                                {"breakpoint":992, "settings": {"slidesToShow": 2}},
                                                {"breakpoint":767, "settings": {"slidesToShow": 1}},
                                                {"breakpoint":480, "settings": {"slidesToShow": 1}}
                                            ]'>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/1-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/1-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <div class="sticker">
                                                <span class="sticker">New</span>
                                            </div>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Veniam officiis voluptates</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price">$122.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/2-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/2-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <div class="sticker-area-2">
                                                <span class="sticker-2">-20%</span>
                                                <span class="sticker">New</span>
                                            </div>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Corporis sed excepturi</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price new-price-2">$194.00</span>
                                                    <span class="old-price">$241.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/3-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/3-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <span class="sticker">New</span>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Quidem iusto sapiente</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price">$175.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/4-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/4-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <div class="sticker-area-2">
                                                <span class="sticker-2">-5%</span>
                                                <span class="sticker">New</span>
                                            </div>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Ullam excepturi nesciunt</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price new-price-2">$145.00</span>
                                                    <span class="old-price">$190.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/5-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/5-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <span class="sticker">New</span>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Minus ipsam rerum</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price">$130.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/6-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/6-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <div class="sticker-area-2">
                                                <span class="sticker-2">-15%</span>
                                                <span class="sticker">New</span>
                                            </div>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Labore aliquid eos</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price new-price-2">$240.00</span>
                                                    <span class="old-price">$320.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/7-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/7-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <span class="sticker">New</span>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li class="silver-color"><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Enim nobis numquam</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price">$190.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-slide_item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="single-product">
                                                <img class="primary-img" src="assets/images/product/medium-size/8-1.jpg" alt="Uren's Product Image">
                                                <img class="secondary-img" src="assets/images/product/medium-size/1-2.jpg" alt="Uren's Product Image">
                                            </a>
                                            <span class="sticker">New</span>
                                            <div class="add-actions">
                                                <ul>
                                                    <li><a class="uren-add_cart" href="cart" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                    </li>
                                                    <li><a class="uren-wishlist" href="wishlist" data-toggle="tooltip" data-placement="top" title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                                    </li>
                                                    <li><a class="uren-add_compare" href="compare" data-toggle="tooltip" data-placement="top" title="Compare This Product"><i
                                                            class="ion-android-options"></i></a>
                                                    </li>
                                                    <li class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Quick View"><i
                                                            class="ion-android-open"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-desc_info">
                                                <div class="rating-box">
                                                    <ul>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                        <li><i class="ion-android-star"></i></li>
                                                    </ul>
                                                </div>
                                                <h6><a class="product-name" href="single-product">Dolorem voluptates aut</a></h6>
                                                <div class="price-box">
                                                    <span class="new-price">$250.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- Uren's Product Area End Here -->

        <!-- Begin Uren's Testimonial Area -->
        <div class="testimonial-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="testimonial-slider uren-slick-slider slider-navigation_style-1" data-slick-options='{
                        "slidesToShow": 1,
                        "arrows" : true
                       }' data-slick-responsive='[
                                             {"breakpoint":768, "settings": {"slidesToShow": 1}},
                                             {"breakpoint":577, "settings": {"slidesToShow": 1}},
                                             {"breakpoint":481, "settings": {"slidesToShow": 1}},
                                             {"breakpoint":321, "settings": {"slidesToShow": 1}}
                                         ]'>
                            
                            <!-- <div class="slide-item">
                                <div class="slide-inner">
                                    <div class="single-slide">
                                        <div class="slide-content">
                                            <span class="primary-text_color">What’s Client Says</span>
                                            <h3 class="user-name">Amber Laha</h3>
                                            <div class="comment-box">
                                                <p class="user-feedback">“ When a beautiful design is combined with powerful
                                                    technology,
                                                    it truly is an artwork. I love how my website operates and looks with this
                                                    theme. Thank you for the awesome product. ”</p>
                                            </div>
                                        </div>
                                        <div class="slide-image">
                                            <a href="javascript:void(0)">
                                                <img src="assets/images/testimonial/user/2.png" alt="Uren's Testimonial Image">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="slide-inner">
                                    <div class="single-slide">
                                        <div class="slide-content">
                                            <span class="primary-text_color">What’s Client Says</span>
                                            <h3 class="user-name">Lindsy Neloms</h3>
                                            <div class="comment-box">
                                                <p class="user-feedback">“ Perfect Themes and the best of all that you have many options to choose! Best Support team ever!Very fast responding and experts on their fields! Thank you very much! ”</p>
                                            </div>
                                        </div>
                                        <div class="slide-image">
                                            <a href="javascript:void(0)">
                                                <img src="assets/images/testimonial/user/3.png" alt="Uren's Testimonial Image">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Uren's Testimonial Area End Here -->

        <!-- Begin Uren's Brand Area -->
        <div class="uren-brand_area">
            <div class="container-fluid">
                <div class="row">
                    <!-- <div class="col-lg-12">
                        <div class="section-title_area">
                            <span>Top Quality Partner</span>
                            <h3>Shop By Brands</h3>
                        </div>
                        <div class="brand-slider uren-slick-slider img-hover-effect_area" data-slick-options='{
                        "slidesToShow": 6
                        }' data-slick-responsive='[
                                                {"breakpoint":1200, "settings": {"slidesToShow": 5}},
                                                {"breakpoint":992, "settings": {"slidesToShow": 3}},
                                                {"breakpoint":767, "settings": {"slidesToShow": 3}},
                                                {"breakpoint":577, "settings": {"slidesToShow": 2}},
                                                {"breakpoint":321, "settings": {"slidesToShow": 1}}
                                            ]'>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/1.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/2.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/3.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/4.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/5.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/6.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/1.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/7.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="single-product">
                                        <a href="javascript:void(0)">
                                            <img src="assets/images/brand/2.jpg" alt="Uren's Brand Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- Uren's Brand Area End Here -->

        <!-- Begin Uren's Blog Area 
        <div class="uren-blog_area bg--white_smoke">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title_area">
                            <span>Our Recent Posts</span>
                            <h3>From Our Blogs</h3>
                        </div>
                        <div class="blog-slider uren-slick-slider slider-navigation_style-1" data-slick-options='{
                        "slidesToShow": 4,
                        "spaceBetween": 30,
                        "arrows" : true
                        }' data-slick-responsive='[
                            {"breakpoint":1200, "settings": {"slidesToShow": 3}},
                            {"breakpoint":992, "settings": {"slidesToShow": 2}},
                            {"breakpoint":768, "settings": {"slidesToShow": 2}},
                            {"breakpoint":576, "settings": {"slidesToShow": 1}}
                        ]'>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="blog-img img-hover_effect">
                                        <a href="blog-details-left-sidebar">
                                            <img src="assets/images/blog/large-size/1.jpg" alt="Uren's Blog Image">
                                        </a>
                                        <span class="post-date">12-09-19</span>
                                    </div>
                                    <div class="blog-content">
                                        <h3><a href="blog-details-left-sidebar">Quaerat eligendi dolores autem omnis sed</a></h3>
                                        <p>Maiores accusamus unde nulla quaerat deserunt, beatae molestias blanditiis aut recusandae saepe, quis, culpa voluptatum?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="blog-img img-hover_effect">
                                        <a href="blog-details-left-sidebar">
                                            <img src="assets/images/blog/large-size/2.jpg" alt="Uren's Blog Image">
                                        </a>
                                        <span class="post-date">15-09-19</span>
                                    </div>
                                    <div class="blog-content">
                                        <h3><a href="blog-details-left-sidebar">Nulla voluptatum maiores dolorem nobis</a></h3>
                                        <p>Maiores accusamus unde nulla quaerat deserunt, beatae molestias blanditiis aut recusandae saepe, quis, culpa voluptatum?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="blog-img img-hover_effect">
                                        <a href="blog-details-left-sidebar">
                                            <img src="assets/images/blog/large-size/3.jpg" alt="Uren's Blog Image">
                                        </a>
                                        <span class="post-date">19-09-19</span>
                                    </div>
                                    <div class="blog-content">
                                        <h3><a href="blog-details-left-sidebar">Laudantium minus excepturi expedita dolore</a></h3>
                                        <p>Maiores accusamus unde nulla quaerat deserunt, beatae molestias blanditiis aut recusandae saepe, quis, culpa voluptatum?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="blog-img img-hover_effect">
                                        <a href="blog-details-left-sidebar">
                                            <img src="assets/images/blog/large-size/4.jpg" alt="Uren's Blog Image">
                                        </a>
                                        <span class="post-date">16-09-19</span>
                                    </div>
                                    <div class="blog-content">
                                        <h3><a href="blog-details-left-sidebar">Aliquam nihil dolorem beatae totam tempora</a></h3>
                                        <p>Maiores accusamus unde nulla quaerat deserunt, beatae molestias blanditiis aut recusandae saepe, quis, culpa voluptatum?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="blog-img img-hover_effect">
                                        <a href="blog-details-left-sidebar">
                                            <img src="assets/images/blog/large-size/5.jpg" alt="Uren's Blog Image">
                                        </a>
                                        <span class="post-date">20-09-19</span>
                                    </div>
                                    <div class="blog-content">
                                        <h3><a href="blog-details-left-sidebar">Reprehenderit illum iusto sit asperiores</a></h3>
                                        <p>Maiores accusamus unde nulla quaerat deserunt, beatae molestias blanditiis aut recusandae saepe, quis, culpa voluptatum?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-item">
                                <div class="inner-slide">
                                    <div class="blog-img img-hover_effect">
                                        <a href="blog-details-left-sidebar">
                                            <img src="assets/images/blog/large-size/6.jpg" alt="Uren's Blog Image">
                                        </a>
                                        <span class="post-date">25-09-19</span>
                                    </div>
                                    <div class="blog-content">
                                        <h3><a href="blog-details-left-sidebar">Corrupti, dolore tempore totam voluptate</a></h3>
                                        <p>Maiores accusamus unde nulla quaerat deserunt, beatae molestias blanditiis aut recusandae saepe, quis, culpa voluptatum?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        Uren's Blog Area End Here -->

        <!-- Begin Uren's Footer Area -->
        <div class="uren-footer_area">
            <div class="footer-top_area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- <div class="newsletter-area">
                                <h3 class="title">Join Our Newsletter Now</h3>
                                <p class="short-desc">Get E-mail updates about our latest shop and special offers.</p>
                                <div class="newsletter-form_wrap">
                                    <form action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="newsletters-form validate" target="_blank" novalidate>
                                        <div id="mc_embed_signup_scroll">
                                            <div id="mc-form" class="mc-form subscribe-form">
                                                <input id="mc-email" class="newsletter-input" type="email" autocomplete="off" placeholder="Enter your email" />
                                                <button class="newsletter-btn" id="mc-submit">Subscribe</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-middle_area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="footer-widgets_info">
                                <div class="footer-widgets_logo">
                                    <a href="#">
                                        <img src="assets/images/menu/logo/1.png" alt="Uren's Footer Logo">
                                    </a>
                                </div>
                                <div class="widget-short_desc">
                                    <p>AutoMarket est une marketplace automobile qui permet d’acheter et de vendre des véhicules et accessoires en ligne facilement. Parcourez les catégories, découvrez les meilleures offres et trouvez rapidement ce que vous cherchez.
                                    </p>
                                </div>
                                <div class="widgets-essential_stuff">
                                    <ul>
                                        <li class="uren-address"><span>Adresse:</span> 
                                            
                                            Rue 11 medina centenaire, Dakar</li>
                                        <li class="uren-phone"><span>+221 76 648 74 20
                                        SN:</span> <a href="tel://+221766487420">+221 76 648 74 20</a>
                                        </li>
                                        <!-- <li class="uren-email"><span>Email:</span> <a href="mailto://info@yourdomain.com">info@yourdomain.com</a></li> -->
                                    </ul>
                                </div>
                                <div class="uren-social_link">
                                    <ul>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank" title="Facebook">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-8">
                            <div class="footer-widgets_area">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="footer-widgets_title">
                                            <h3>Information</h3>
                                        </div>
                                        <div class="footer-widgets">
                                            <ul>
                                                <li><a href="javascript:void(0)">About Us</a></li>
                                                <li><a href="javascript:void(0)">Delivery Information</a></li>
                                                <li><a href="javascript:void(0)">Privacy Policy</a></li>
                                                <li><a href="javascript:void(0)">Terms & Conditions</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="footer-widgets_title">
                                            <h3>Customer Service</h3>
                                        </div>
                                        <div class="footer-widgets">
                                            <ul>
                                                <li><a href="javascript:void(0)">Contact Us</a></li>
                                                <li><a href="javascript:void(0)">Returns</a></li>
                                                <li><a href="javascript:void(0)">Site Map</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="footer-widgets_title">
                                            <h3>Extras</h3>
                                        </div>
                                        <div class="footer-widgets">
                                            <ul>
                                                <li><a href="javascript:void(0)">About Us</a></li>
                                                <li><a href="javascript:void(0)">Delivery Information</a></li>
                                                <li><a href="javascript:void(0)">Privacy Policy</a></li>
                                                <li><a href="javascript:void(0)">Terms & Conditions</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="footer-widgets_title">
                                            <h3>My Account</h3>
                                        </div>
                                        <div class="footer-widgets">
                                            <ul>
                                                <li><a href="javascript:void(0)">My Account</a></li>
                                                <li><a href="javascript:void(0)">Order History</a></li>
                                                <li><a href="javascript:void(0)">Wish List</a></li>
                                                <li><a href="javascript:void(0)">Newsletter</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="footer-bottom_area">
                <div class="container-fluid">
                    <div class="footer-bottom_nav">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="copyright">
                                    <span><a href=""></a></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Uren's Footer Area End Here -->
        <!-- Begin Uren's Modal Area 
        <div class="modal fade modal-wrapper" id="exampleModalCenter">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-inner-area sp-area row">
                            <div class="col-lg-5">
                                <div class="sp-img_area">
                                    <div class="sp-img_slider slick-img-slider uren-slick-slider" data-slick-options='{
                                    "slidesToShow": 1,
                                    "arrows": false,
                                    "fade": true,
                                    "draggable": false,
                                    "swipe": false,
                                    "asNavFor": ".sp-img_slider-nav"
                                    }'>
                                        <div class="single-slide red">
                                            <img src="assets/images/product/large-size/1.jpg" alt="Uren's Product Image">
                                        </div>
                                        <div class="single-slide orange">
                                            <img src="assets/images/product/large-size/2.jpg" alt="Uren's Product Image">
                                        </div>
                                        <div class="single-slide brown">
                                            <img src="assets/images/product/large-size/3.jpg" alt="Uren's Product Image">
                                        </div>
                                        <div class="single-slide umber">
                                            <img src="assets/images/product/large-size/4.jpg" alt="Uren's Product Image">
                                        </div>
                                        <div class="single-slide black">
                                            <img src="assets/images/product/large-size/5.jpg" alt="Uren's Product Image">
                                        </div>
                                        <div class="single-slide golden">
                                            <img src="assets/images/product/large-size/6.jpg" alt="Uren's Product Image">
                                        </div>
                                    </div>
                                    <div class="sp-img_slider-nav slick-slider-nav uren-slick-slider slider-navigation_style-3" data-slick-options='{
                                   "slidesToShow": 4,
                                    "asNavFor": ".sp-img_slider",
                                   "focusOnSelect": true,
                                   "arrows" : true,
                                   "spaceBetween": 30
                                  }' data-slick-responsive='[
                                    {"breakpoint":1501, "settings": {"slidesToShow": 3}},
                                    {"breakpoint":992, "settings": {"slidesToShow": 4}},
                                    {"breakpoint":768, "settings": {"slidesToShow": 3}},
                                    {"breakpoint":575, "settings": {"slidesToShow": 2}}
                                ]'>
                                        <div class="single-slide red">
                                            <img src="assets/images/product/small-size/1.jpg" alt="Uren's Product Thumnail">
                                        </div>
                                        <div class="single-slide orange">
                                            <img src="assets/images/product/small-size/2.jpg" alt="Uren's Product Thumnail">
                                        </div>
                                        <div class="single-slide brown">
                                            <img src="assets/images/product/small-size/3.jpg" alt="Uren's Product Thumnail">
                                        </div>
                                        <div class="single-slide umber">
                                            <img src="assets/images/product/small-size/4.jpg" alt="Uren's Product Thumnail">
                                        </div>
                                        <div class="single-slide black">
                                            <img src="assets/images/product/small-size/5.jpg" alt="Uren's Product Thumnail">
                                        </div>
                                        <div class="single-slide golden">
                                            <img src="assets/images/product/small-size/6.jpg" alt="Uren's Product Thumnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7 col-lg-6">
                                <div class="sp-content">
                                    <div class="sp-heading">
                                        <h5><a href="#">Dolorem odio provident ut nihil</a></h5>
                                    </div>
                                    <div class="rating-box">
                                        <ul>
                                            <li><i class="ion-android-star"></i></li>
                                            <li><i class="ion-android-star"></i></li>
                                            <li><i class="ion-android-star"></i></li>
                                            <li class="silver-color"><i class="ion-android-star"></i></li>
                                            <li class="silver-color"><i class="ion-android-star"></i></li>
                                        </ul>
                                    </div>
                                    <div class="price-box">
                                        <span class="new-price new-price-2">$194.00</span>
                                        <span class="old-price">$241.00</span>
                                    </div>
                                    <div class="sp-essential_stuff">
                                        <ul>
                                            <li>Brands <a href="javascript:void(0)">Buxton</a></li>
                                            <li>Product Code: <a href="javascript:void(0)">Product 16</a></li>
                                            <li>Reward Points: <a href="javascript:void(0)">100</a></li>
                                            <li>Availability: <a href="javascript:void(0)">In Stock</a></li>
                                            <li>EX Tax: <a href="javascript:void(0)"><span>$453.35</span></a></li>
                                            <li>Price in reward points: <a href="javascript:void(0)">400</a></li>
                                        </ul>
                                    </div>
                                    <div class="color-list_area">
                                        <div class="color-list_heading">
                                            <h4>Available Options</h4>
                                        </div>
                                        <span class="sub-title">Color</span>
                                        <div class="color-list">
                                            <a href="javascript:void(0)" class="single-color active" data-swatch-color="red">
                                                <span class="bg-red_color"></span>
                                                <span class="color-text">Red (+$150)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="orange">
                                                <span class="burnt-orange_color"></span>
                                                <span class="color-text">Orange (+$170)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="brown">
                                                <span class="brown_color"></span>
                                                <span class="color-text">Brown (+$120)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="umber">
                                                <span class="raw-umber_color"></span>
                                                <span class="color-text">Umber (+$125)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="black">
                                                <span class="black_color"></span>
                                                <span class="color-text">Black (+$125)</span>
                                            </a>
                                            <a href="javascript:void(0)" class="single-color" data-swatch-color="golden">
                                                <span class="golden_color"></span>
                                                <span class="color-text">Golden (+$125)</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="quantity">
                                        <label>Quantity</label>
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" value="1" type="text">
                                            <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                            <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="uren-group_btn">
                                        <ul>
                                            <li><a href="cart" class="add-to_cart">Cart To Cart</a></li>
                                            <li><a href="cart"><i class="ion-android-favorite-outline"></i></a></li>
                                            <li><a href="cart"><i class="ion-ios-shuffle-strong"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="uren-tag-line">
                                        <h6>Tags:</h6>
                                        <a href="javascript:void(0)">Ring</a>,
                                        <a href="javascript:void(0)">Necklaces</a>,
                                        <a href="javascript:void(0)">Braid</a>
                                    </div>
                                    <div class="uren-social_link">
                                        <ul>
                                            <li class="facebook">
                                                <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank" title="Facebook">
                                                    <i class="fab fa-facebook"></i>
                                                </a>
                                            </li>
                                            <li class="twitter">
                                                <a href="https://twitter.com/" data-toggle="tooltip" target="_blank" title="Twitter">
                                                    <i class="fab fa-twitter-square"></i>
                                                </a>
                                            </li>
                                            <li class="youtube">
                                                <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank" title="Youtube">
                                                    <i class="fab fa-youtube"></i>
                                                </a>
                                            </li>
                                            <li class="google-plus">
                                                <a href="https://www.plus.google.com/discover" data-toggle="tooltip" target="_blank" title="Google Plus">
                                                    <i class="fab fa-google-plus"></i>
                                                </a>
                                            </li>
                                            <li class="instagram">
                                                <a href="https://rss.com/" data-toggle="tooltip" target="_blank" title="Instagram">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         Uren's Modal Area End Here -->

    </div>

    <!-- JS
============================================ -->

    <!-- jQuery JS -->
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <!-- Modernizer JS -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- Popper JS -->
    <script src="assets/js/vendor/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="assets/js/vendor/bootstrap.min.js"></script>

    <!-- Slick Slider JS -->
    <script src="assets/js/plugins/slick.min.js"></script>
    <!-- Barrating JS -->
    <script src="assets/js/plugins/jquery.barrating.min.js"></script>
    <!-- Counterup JS -->
    <script src="assets/js/plugins/jquery.counterup.js"></script>
    <!-- Nice Select JS -->
    <script src="assets/js/plugins/jquery.nice-select.js"></script>
    <!-- Sticky Sidebar JS -->
    <script src="assets/js/plugins/jquery.sticky-sidebar.js"></script>
    <!-- Jquery-ui JS -->
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <script src="assets/js/plugins/jquery.ui.touch-punch.min.js"></script>
    <!-- Lightgallery JS -->
    <script src="assets/js/plugins/lightgallery.min.js"></script>
    <!-- Scroll Top JS -->
    <script src="assets/js/plugins/scroll-top.js"></script>
    <!-- Theia Sticky Sidebar JS -->
    <script src="assets/js/plugins/theia-sticky-sidebar.min.js"></script>
    <!-- Waypoints JS -->
    <script src="assets/js/plugins/waypoints.min.js"></script>
    <!-- jQuery Zoom JS -->
    <script src="assets/js/plugins/jquery.zoom.min.js"></script>

    <!-- Vendor & Plugins JS (Please remove the comment from below vendor.min.js & plugins.min.js for better website load performance and remove js files from avobe) -->
    <!--
<script src="assets/js/vendor/vendor.min.js"></script>
<script src="assets/js/plugins/plugins.min.js"></script>
-->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

</body>


</html>