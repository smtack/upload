<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('img/favicon/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('img/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('img/favicon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= asset('img/favicon/site.webmanifest') ?>">

    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="<?= asset('js/main.js') ?>" defer></script>

    <title><?= $page_title ?? 'Upload' ?></title>
</head>
<body>
    <div class="header">
        <div class="left-header">
            <span class="toggle-side-menu">&#9776;</span>
            <h1 id="logo"><a href="<?php echo BASE_URL; ?>">Upload</a></h1>
        </div>

        <div class="search">
            <form action="<?php echo BASE_URL; ?>/search" method="GET">
                <input type="text" name="s" placeholder="Search" value="<?php echo isset($keywords) ? str_replace('%', '', $keywords) : ''; ?>">
            </form>
        </div>

        <div class="right-header">
            <?php if($user->loggedIn()): ?>
                <span class="toggle-menu"><img src="uploads/profile-pictures/<?php echo escape($user->data()->user_profile_picture); ?>" alt="Toggle Menu"></span>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/signup"><button>Sign Up</button></a>
                <a href="<?php echo BASE_URL; ?>/login"><button>Log In</button></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="menu">
        <ul>
            <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo escape($user->data()->user_username); ?>"><li>Your Profile</li></a>
            <a href="<?php echo BASE_URL; ?>/update-profile"><li>Update Profile</li></a>
            <a href="<?php echo BASE_URL; ?>/logout"><li>Log Out</li></a>
        </ul>
    </div>

    <div class="side-menu">
        <ul>
            <a href="<?php echo BASE_URL; ?>"><li><img src="<?= asset('img/home.svg') ?>" alt="Home">Home</li></a>
            <a href="<?php echo BASE_URL; ?>/latest"><li><img src="<?= asset('img/all.svg') ?>" alt="Latest Uploads">Latest Uploads</li></a>
            <a href="<?php echo BASE_URL; ?>/popular"><li><img src="<?= asset('img/popular.svg') ?>" alt="Popular Uploads">Most Viewed</li></a>
            <a href="<?php echo BASE_URL; ?>/top"><li><img src="<?= asset('img/star.svg') ?>" alt="Top Uploads">Top Rated</li></a>

            <?php if($user->loggedIn()): ?>
                <hr class="h-rule">
                <a href="<?php echo BASE_URL; ?>/upload"><li><img src="<?= asset('img/upload.svg') ?>" alt="Upload">New Upload</li></a>
                <a href="<?php echo BASE_URL; ?>/your-uploads"><li><img src="<?= asset('img/your-uploads.svg') ?>" alt="Your Uploads">Your Uploads</li></a>
                <a href="<?php echo BASE_URL; ?>/favorites"><li><img src="<?= asset('img/favorite.svg') ?>" alt="Favorites">Favorites</li></a>
                <a href="<?php echo BASE_URL; ?>/follows"><li><img src="<?= asset('img/follows.svg') ?>" alt="Follows">Following</li></a>
            <?php endif; ?>
        </ul>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Upload</p>
        </div>
    </div>

    <div class="container">
