<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelStore - Online Game Shop</title>
    
    <link rel="stylesheet" href="Assets/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="index.php?page=home" class="logo">
            <i class="fa-solid fa-gamepad"></i> PixelStore
        </a>

        <div class="nav-links">
            <a href="index.php?page=home">Home</a>
            <a href="index.php?page=catalog">All Games</a>
        </div>

        <div class="nav-actions">
            <form action="index.php" method="GET" class="search-form">
                <input type="hidden" name="page" value="catalog">
                <input type="text" name="q" placeholder="Search games...">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <a href="index.php?page=cart" class="cart-icon">
                <i class="fa fa-shopping-cart"></i>
                <span id="cart-count">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                </span>
            </a>

            <?php if (isLoggedIn()): ?>
                <div class="user-menu">
                    <a href="index.php?page=dashboard" class="user-link">
                        <i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <a href="api/auth.php?action=logout" class="btn-logout">Logout</a>
                </div>
            <?php else: ?>
                <a href="index.php?page=login" class="btn-login">Login</a>
                <a href="index.php?page=register" class="btn-register">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="main-content"></div>