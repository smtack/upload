<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="apple-touch-icon" sizes="180x180" href="public/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="public/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="public/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="public/img/favicon/site.webmanifest">
  <link href="<?php echo BASE_URL; ?>/public/css/style.css" rel="stylesheet">
  <script src="<?php echo BASE_URL; ?>/public/js/main.js" defer></script>
  <title><?php echo isset($page_title) ? $page_title : 'Upload'; ?></title>
</head>
<body>
  <div class="header">    
    <h1><a href="<?php echo BASE_URL; ?>">Upload</a></h1>

    <ul>
      <li><a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>/public/img/home.svg" alt="Home"></a></li>
      <li class="toggle-search"><img src="<?php echo BASE_URL; ?>/public/img/search.svg" alt="Search"></li>
      <li><a href="<?php echo BASE_URL; ?>/all"><img src="<?php echo BASE_URL; ?>/public/img/all.svg" alt="All Uploads"></a></li>

      <?php if($user->loggedIn()): ?>
        <li><a href="<?php echo BASE_URL; ?>/upload"><img src="<?php echo BASE_URL; ?>/public/img/upload.svg" alt="Upload"></a></li>
        <li><a href="<?php echo BASE_URL; ?>/favorites"><img src="<?php echo BASE_URL; ?>/public/img/favorite.svg" alt="Favorites"></a></li>
        <li><a href="<?php echo BASE_URL; ?>/follows"><img src="<?php echo BASE_URL; ?>/public/img/follows.svg" alt="Follows"></a></li>
        <li class="toggle-menu"><img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $user->data()->user_profile_picture; ?>" alt="Toggle Menu"></li>
      <?php else: ?>
        <li><a href="<?php echo BASE_URL; ?>/signup"><button>Sign Up</button></a></li>
        <li><a href="<?php echo BASE_URL; ?>/login"><button>Log In</button></a></li>
      <?php endif; ?>
    </ul>

    <div class="search">
      <h1>Search...</h1>
      
      <span class="close-search">&times;</span>

      <form action="<?php echo BASE_URL; ?>/search" method="GET">
        <input type="text" name="s" placeholder="Search" value="<?php echo isset($keywords) ? str_replace('%', '', $keywords) : ''; ?>">
      </form>
    </div>

    <div class="menu">
      <ul>
        <a href="<?php echo BASE_URL; ?>/profile?u=<?php echo $user->data()->user_username; ?>"><li>Your Profile</li></a>
        <a href="<?php echo BASE_URL; ?>/update-profile"><li>Update Profile</li></a>
        <a href="<?php echo BASE_URL; ?>/logout"><li>Log Out</li></a>
      </ul>
    </div>
  </div>
  <div class="container">