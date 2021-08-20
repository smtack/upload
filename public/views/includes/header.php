<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="public/css/style.css" rel="stylesheet">
  <script src="public/js/main.js" defer></script>
  <title><?php echo isset($page_title) ? $page_title : 'Upload'; ?></title>
</head>
<body>
  <div class="header">
    <h1><a href="<?php echo BASE_URL; ?>">Upload</a></h1>

    <ul>
      <li class="toggle-search"><img src="<?php echo BASE_URL; ?>/public/img/search.svg" alt="Search"></li>

      <?php if($user->loggedIn()): ?>
        <li><a href="upload"><img src="<?php echo BASE_URL; ?>/public/img/upload.svg" alt="Upload"></a></li>
        <li class="toggle-menu"><img src="<?php echo BASE_URL; ?>/public/img/menu.svg" alt="Toggle Menu"></li>
      <?php else: ?>
        <li><a href="signup">Sign Up</a></li>
        <li><a href="login">Log In</a></li>
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
        <a href="profile?user=<?php echo $user->data()->user_username; ?>"><li>Your Profile</li></a>
        <a href="update-profile"><li>Update Profile</li></a>
        <a href="logout"><li>Log Out</li></a>
      </ul>
    </div>
  </div>
  <div class="container">