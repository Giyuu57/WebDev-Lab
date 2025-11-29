<?php
// Check if cart is empty
$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    // Convert cart array keys (game IDs) to a comma-separated string for SQL
    // We assume $_SESSION['cart'] stores IDs as values or keys. 
    // For this implementation, let's assume standard array of IDs.
    $ids = implode(',', $_SESSION['cart']);
    
    // Sanity check to ensure IDs are integers (prevent SQL injection via session manipulation)
    // In a real app, prepared statements with dynamic placeholders are better, 
    // but IN clause with PDO is tricky. We'll sanitize explicitly here since it comes from internal session.
    $ids_array = array_map('intval', $_SESSION['cart']);
    $ids_clean = implode(',', $ids_array);

    if (!empty($ids_clean)) {
        try {
            $sql = "SELECT * FROM games WHERE id IN ($ids_clean)";
            $stmt = $pdo->query($sql);
            $cart_items = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error fetching cart: " . $e->getMessage();
        }
    }
}
?>

<div class="container">
    <h2 class="section-title">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div style="text-align: center; padding: 50px; background: var(--card-bg); border-radius: 10px;">
            <i class="fa fa-shopping-cart" style="font-size: 4rem; color: #444; margin-bottom: 20px;"></i>
            <h3>Your cart is empty</h3>
            <p style="margin-bottom: 20px; color: var(--text-muted);">Looks like you haven't added any games yet.</p>
            <a href="index.php?page=catalog" class="btn-register">Browse Games</a>
        </div>
    <?php else: ?>
        
        <div style="background: var(--card-bg); padding: 20px; border-radius: 10px;">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Game</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): 
                        $total_price += $item['price'];
                    ?>
                    <tr>
                        <td style="display: flex; align-items: center; gap: 15px;">
                            <img src="assets/images/<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 onerror="this.src='https://via.placeholder.com/50'"
                                 alt="cover" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            <div>
                                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                            </div>
                        </td>
                        <td><?php echo formatPrice($item['price']); ?></td>
                        <td>
                            <form action="api/cart_api.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="game_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" style="background: none; border: none; color: var(--danger-color); cursor: pointer;">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; border-top: 1px solid #444; padding-top: 20px;">
                <a href="index.php?page=catalog" style="color: var(--text-muted);"><i class="fa fa-arrow-left"></i> Continue Shopping</a>
                
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; margin-bottom: 10px;">
                        Total: <span style="color: var(--accent-color); font-weight: bold;"><?php echo formatPrice($total_price); ?></span>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <a href="index.php?page=checkout" class="btn-register" style="font-size: 1.2rem; padding: 10px 30px;">
                            Checkout <i class="fa fa-arrow-right"></i>
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=login&error=Please login to checkout" class="btn-login">Login to Checkout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>