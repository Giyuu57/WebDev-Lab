<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// Initialize Cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'add':
        addToCart();
        break;
    case 'remove':
        removeFromCart();
        break;
    case 'checkout':
        processCheckout($pdo);
        break;
    default:
        redirect('../index.php?page=cart');
}

// --- Functions ---

function addToCart() {
    $game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : 0;
    $redirect_page = isset($_POST['redirect']) ? $_POST['redirect'] : 'catalog';

    if ($game_id > 0) {
        // Check if item already in cart
        if (!in_array($game_id, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $game_id;
        }
    }
    
    // Redirect back to where the user was
    if ($redirect_page == 'product') {
        redirect("../index.php?page=product&id=$game_id");
    } else {
        redirect("../index.php?page=cart");
    }
}

function removeFromCart() {
    $game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : 0;

    if (($key = array_search($game_id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
        // Re-index array to prevent gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    redirect('../index.php?page=cart');
}

function processCheckout($pdo) {
    // 1. Security Checks
    if (!isLoggedIn()) {
        redirect('../index.php?page=login&error=Please login to checkout');
    }

    if (empty($_SESSION['cart'])) {
        redirect('../index.php?page=cart');
    }

    $user_id = $_SESSION['user_id'];
    
    // 2. Recalculate Total (Server-side security)
    // Never trust the total sent from the frontend form
    $ids = implode(',', array_map('intval', $_SESSION['cart']));
    
    try {
        // Start Transaction
        $pdo->beginTransaction();

        // Fetch prices again
        $stmt = $pdo->query("SELECT id, price FROM games WHERE id IN ($ids)");
        $games = $stmt->fetchAll();

        $total_amount = 0;
        foreach ($games as $game) {
            $total_amount += $game['price'];
        }

        // 3. Create Order Record
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'completed')");
        $stmt->execute([$user_id, $total_amount]);
        $order_id = $pdo->lastInsertId();

        // 4. Create Order Items
        $item_sql = "INSERT INTO order_items (order_id, game_id, price_at_purchase) VALUES (?, ?, ?)";
        $item_stmt = $pdo->prepare($item_sql);

        foreach ($games as $game) {
            $item_stmt->execute([$order_id, $game['id'], $game['price']]);
        }

        // 5. Commit Transaction
        $pdo->commit();

        // 6. Clear Cart
        $_SESSION['cart'] = [];

        // 7. Redirect to Dashboard
        redirect('../index.php?page=dashboard&success=Order placed successfully!');

    } catch (Exception $e) {
        // Rollback changes if something went wrong
        $pdo->rollBack();
        redirect('../index.php?page=checkout&error=Transaction failed: ' . $e->getMessage());
    }
}
?>