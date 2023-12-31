<!DOCTYPE html>
<html lang="en">

<head>
    <title>Computer Shop Management</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="assets/images/X-ComShop Logo.svg">
    <!-- link mystyle.css -->
    <link rel="stylesheet" href="assets/css/mystyle.css">

    <!-- jQuery-->
    <script defer src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="assets/js/jquery-3.7.0.min.js"></script>

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link id="theme-style" rel="stylesheet" href="assets/font/kantumruy/kantumruy_font.css">
    <link id="theme-style" rel="stylesheet" href="assets/font/kohsantepheap/kohsantepheap_font.css">
</head>

<body class="app">
    <header class="app-header fixed-top" id="menu-container">
        <div class="app-header-inner">
            <div class="container-fluid py-2">
                <div class="app-header-content">
                    <div class="row justify-content-between align-items-center">

                        <div class="col-auto">
                            <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                                    <title>Menu</title>
                                    <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                                </svg>
                            </a>
                        </div><!--//col-->
                        <div class="search-mobile-trigger d-sm-none col">
                            <i class="search-mobile-trigger-icon fa-solid fa-magnifying-glass"></i>
                        </div><!--//col-->

                        <div class="app-utilities col-auto">
                            <div class="app-utility-item app-notifications-dropdown dropdown">
                                <a href="../index.php">GO <i class="fa-solid fa-arrow-right"></i></a>

                                <div class="dropdown-menu p-0" aria-labelledby="notifications-dropdown-toggle">
                                    <div class="dropdown-menu-header p-3">
                                        <h5 class="dropdown-menu-title mb-0">Notifications</h5>
                                    </div><!--//dropdown-menu-title-->
                                    <div class="dropdown-menu-content">
                                        <div class="item p-3">
                                            <div class="row gx-2 justify-content-between align-items-center">
                                                <div class="col-auto">
                                                    <img class="profile-image" src="assets/images/profiles/profile-1.png" alt="">
                                                </div><!--//col-->
                                                <div class="col">
                                                    <div class="info">
                                                        <div class="desc">Amy shared a file with you. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
                                                        <div class="meta"> 2 hrs ago</div>
                                                    </div>
                                                </div><!--//col-->
                                            </div><!--//row-->
                                            <a class="link-mask" href="index.php?pg=notifications"></a>
                                        </div><!--//item-->
                                        <div class="item p-3">
                                            <div class="row gx-2 justify-content-between align-items-center">
                                                <div class="col-auto">
                                                    <div class="app-icon-holder">
                                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                                            <path fill-rule="evenodd" d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                                                        </svg>
                                                    </div>
                                                </div><!--//col-->
                                                <div class="col">
                                                    <div class="info">
                                                        <div class="desc">You have a new invoice. Proin venenatis interdum est.</div>
                                                        <div class="meta"> 1 day ago</div>
                                                    </div>
                                                </div><!--//col-->
                                            </div><!--//row-->
                                            <a class="link-mask" href="index.php?pg=notifications"></a>
                                        </div><!--//item-->
                                        <div class="item p-3">
                                            <div class="row gx-2 justify-content-between align-items-center">
                                                <div class="col-auto">
                                                    <div class="app-icon-holder icon-holder-mono">
                                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bar-chart-line" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z" />
                                                        </svg>
                                                    </div>
                                                </div><!--//col-->
                                                <div class="col">
                                                    <div class="info">
                                                        <div class="desc">Your report is ready. Proin venenatis interdum est.</div>
                                                        <div class="meta"> 3 days ago</div>
                                                    </div>
                                                </div><!--//col-->
                                            </div><!--//row-->
                                            <a class="link-mask" href="index.php?pg=notifications"></a>
                                        </div><!--//item-->
                                        <div class="item p-3">
                                            <div class="row gx-2 justify-content-between align-items-center">
                                                <div class="col-auto">
                                                    <img class="profile-image" src="assets/images/profiles/profile-2.png" alt="">
                                                </div><!--//col-->
                                                <div class="col">
                                                    <div class="info">
                                                        <div class="desc">James sent you a new message.</div>
                                                        <div class="meta"> 7 days ago</div>
                                                    </div>
                                                </div><!--//col-->
                                            </div><!--//row-->
                                            <a class="link-mask" href="index.php?pg=notifications"></a>
                                        </div><!--//item-->
                                    </div><!--//dropdown-menu-content-->

                                    <div class="dropdown-menu-footer p-2 text-center">
                                        <a href="index.php?pg=notifications">View all</a>
                                    </div>

                                </div><!--//dropdown-menu-->
                            </div><!--//app-utility-item-->
                            <div class="app-utility-item">
                                <a href="index.php?pg=settings" title="Settings">
                                    <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z" />
                                        <path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z" />
                                    </svg>
                                </a>
                            </div><!--//app-utility-item-->

                        </div><!--//app-utilities-->
                    </div><!--//row-->
                </div><!--//app-header-content-->
            </div><!--//container-fluid-->
        </div><!--//app-header-inner-->
        <div id="app-sidepanel" class="app-sidepanel">
            <div id="sidepanel-drop" class="sidepanel-drop"></div>
            <div class="sidepanel-inner d-flex flex-column">
                <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
                <div class="app-branding">
                    <a class="app-logo" href="index.php?pg=homepage"><img class="logo-icon me-2" src="assets/images/X-ComShop Logo.svg" alt="logo"><span class="logo-text">X-ComShop</span></a>

                </div><!--//app-branding-->

                <!-- Menu -->
                <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                    <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                        <li class="nav-item">
                            <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                            <a class="nav-link active" href="index.php?pg=homepage">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-gauge fa-xl"></i>
                                </span>
                                <span class="nav-link-text">ផ្ទាំង Dashboard</span>
                            </a><!--//nav-link-->
                        </li><!--//nav-item-->

                        <!-- Cashier -->
                        <li class="nav-item" id="cashier-menu">
                            <a class="nav-link" href="index.php?ch=cashier">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-cart-shopping fa-xl"></i>
                                </span>
                                <span class="nav-link-text">ផ្ទាំងលក់</span>
                            </a>
                        </li>

                        <!-- Manage Product -->
                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-11" aria-expanded="false" aria-controls="submenu-11">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                                </span>
                                <span class="nav-link-text">គ្រប់គ្រងផលិតផល</span>
                                <span class="submenu-arrow">
                                    <i class="fa-solid fa-arrow-turn-down"></i>
                                </span><!--//submenu-arrow-->
                            </a><!--//nav-link-->
                            <div id="submenu-11" class="collapse submenu submenu-11" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?br=brand"><i class="fa-solid fa-copyright"></i> ប្រេន</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?um=unit_measurement"><i class="fa-solid fa-weight-scale"></i> ខ្នាត</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?pt=category"><i class="fa-solid fa-layer-group"></i> ប្រភេទ</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?p=insert_product"><i class="fa-solid fa-plus"></i> បង្កើតផលិតផល</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?p=product"><i class="fa-brands fa-product-hunt"></i> ព័ត៌មានផលិតផល</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Manage Inventory -->
                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-12" aria-expanded="false" aria-controls="submenu-12">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-warehouse fa-lg"></i>
                                </span>
                                <span class="nav-link-text">គ្រប់គ្រង ស្តុកផលិតផល</span>
                                <span class="submenu-arrow">
                                    <i class="fa-solid fa-arrow-turn-down"></i>
                                </span><!--//submenu-arrow-->
                            </a><!--//nav-link-->
                            <div id="submenu-12" class="collapse submenu submenu-12" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?im=import_stock"><i class="fa-solid fa-file-import"></i> នាំចូល</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?st=stock"><i class="fa-solid fa-clipboard"></i> ស្តុក</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?imh=import_history"><i class="fa-solid fa-layer-group"></i> របាយការណ៏ នាចូល</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Manage Expense -->
                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-13" aria-expanded="false" aria-controls="submenu-13">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-file-invoice-dollar fa-xl"></i>
                                </span>
                                <span class="nav-link-text">គ្រប់គ្រងចំណាយ</span>
                                <span class="submenu-arrow">
                                    <i class="fa-solid fa-arrow-turn-down"></i>
                                </span>
                            </a>
                            <div id="submenu-13" class="collapse submenu submenu-13" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?expt=expense_type"><i class="fa-solid fa-file-pen"></i> ប្រភេទ ចំណាយ</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?cexp=create_expense"><i class="fa-solid fa-file-circle-plus"></i> បង្កើត ការចំណាយ</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?exp=expense"><i class="fa-solid fa-file"></i> របាយការណ៍ ការចំណាយ</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Customer -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?c=customer">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-address-card fa-lg"></i>
                                </span>
                                <span class="nav-link-text">អតិថិជន</span>
                            </a>
                        </li>

                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-1" aria-expanded="false" aria-controls="submenu-1">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-clipboard-user fa-xl"></i>
                                </span>
                                <span class="nav-link-text">បុគ្គលិក</span>
                                <span class="submenu-arrow">
                                    <i class="fa-solid fa-arrow-turn-down"></i>
                                </span>
                            </a>
                            <div id="submenu-1" class="collapse submenu submenu-1" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?pe=staff"><i class="fa-solid fa-users"></i> បុគ្គលិក</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?pe=user"><i class="fa-solid fa-user"></i> អ្នកប្រើប្រាស់</a></li>
                                    <!-- <li class="submenu-item"><a class="submenu-link" href="index.php?pg=settings">Settings</a></li> -->
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item has-submenu">
                            <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-14" aria-expanded="false" aria-controls="submenu-13">
                                <span class="nav-icon">
                                    <i class="fa-solid fa-file-invoice-dollar fa-xl"></i>
                                </span>
                                <span class="nav-link-text">របាយការណ៍</span>
                                <span class="submenu-arrow">
                                    <i class="fa-solid fa-arrow-turn-down"></i>
                                </span>
                            </a>
                            <div id="submenu-14" class="collapse submenu submenu-14" data-bs-parent="#menu-accordion">
                                <ul class="submenu-list list-unstyled">
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?rep=income"><i class="fa-solid fa-file"></i> របាយការណ៍ ចំណូល</a></li>
                                    <li class="submenu-item"><a class="submenu-link" href="index.php?rep=income_details"><i class="fa-solid fa-file"></i> របាយការណ៍ ចំណូលលម្អិត</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="app-sidepanel-footer">
                    <nav class="app-nav app-nav-footer">
                        <ul class="app-menu footer-menu list-unstyled">
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="index.php?pg=settings">
                                    <span class="nav-icon">
                                        <i class="fa-solid fa-gear fa-lg"></i>
                                    </span>
                                    <span class="nav-link-text">Settings</span>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/logout.php" class="btn btn-danger text-white px-2">
                                    <span class="nav-icon">
                                        <i class="fa-solid fa-right-from-bracket fa-xl"></i>
                                    </span>
                                    <span class="nav-link-text">ចាក់ចេញ</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </header>