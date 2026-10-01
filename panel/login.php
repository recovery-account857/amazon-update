<?php
session_start();
error_reporting(0);
$settings = json_decode(file_get_contents("../config.json"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if ($_POST['glitchaccess'] == $settings['keypanel']) {
        $_SESSION['logged_in'] = true;
        header("Location: /glitch/home");
        exit();
    } else {
        header("Location: ?msg=error");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>Sign In | Glitch Zone</title>
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
        font-size: 2.5rem;
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
<body class="authentication-bg">
    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center">
                                <div class="mx-auto mb-4 text-center auth-logo">
                                    <a href="index.html" class="logo-dark">
                                        <p class="glitch" data-text="GLITCH ZONE">GLITCH ZONE</p>
                                    </a>
                                    <a href="index.html" class="logo-light">
                                        <p class="glitch" data-text="GLITCH ZONE">GLITCH ZONE</p>
                                    </a>
                                </div>
                            </div>
                            <?php
                            if ($_GET['msg'] == "error") {
                            echo '
                            <div class="alert alert-danger alert-icon mb-0" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded bg-danger d-flex justify-content-center align-items-center fs-18 me-2 flex-shrink-0">
                                        <iconify-icon icon="line-md:alert-loop"class="fs-24 align-middle"></iconify-icon>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>Sorry</strong>, your Glitch Access is incorrect.
                                    </div>
                                </div>
                            </div>';
                            }
                            ?>
                            <form method="POST" action="" class="mt-4">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label">GLITCH ACCESS</label>
                                    </div>
                                    <input type="text" class="form-control" id="password" name="glitchaccess" placeholder="Enter your glitch access">
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-dark btn-lg fw-medium" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor Javascript -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App Javascript -->
    <script src="assets/js/app.js"></script>
</body>
</html>