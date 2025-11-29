<?php
// Ensure user is logged in
if (!isLoggedIn()) {
    redirect('index.php?page=login');
}

$user_id = $_SESSION['user_id'];

// Fetch User Orders
try {
    // Get Orders
    $stmt = $pdo->prepare("
        SELECT id, total_amount, created_at, status 
        FROM orders 
        WHERE user_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container">
    <h2 class="section-title">My Dashboard</h2>

    <div style="background: var(--card-bg); padding: 30px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
        <p style="color: var(--text-muted); margin-top: 10px;">
            Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?><br>
            Role: <span style="text-transform: capitalize;"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
        </p>
        
        <?php if(isAdmin()): ?>
            <a href="index.php?page=admin" class="btn-register" style="display: inline-block; margin-top: 15px; background: var(--accent-color);">
                <i class="fa fa-cogs"></i> Go to Admin Panel
            </a>
        <?php endif; ?>
    </div>

    <h3 class="section-title">Order History</h3>

    <?php if (count($orders) > 0): ?>
        <div style="background: var(--card-bg); padding: 20px; border-radius: 10px; overflow-x: auto;">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date("M d, Y", strtotime($order['created_at'])); ?></td>
                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                            <td>
                                <span style="
                                    padding: 5px 10px; 
                                    border-radius: 5px; 
                                    font-size: 0.8rem;
                                    background: <?php echo $order['status'] == 'completed' ? '#00b894' : '#e17055'; ?>;
                                    color: white;
                                ">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                // Fetch items for this specific order
                                $item_stmt = $pdo->prepare("
                                    SELECT g.title 
                                    FROM order_items oi
                                    JOIN games g ON oi.game_id = g.id
                                    WHERE oi.order_id = ?
                                ");
                                $item_stmt->execute([$order['id']]);
                                $items = $item_stmt->fetchAll(PDO::FETCH_COLUMN);
                                echo implode(", ", $items);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 30px; background: var(--card-bg); border-radius: 10px;">
            <p>You haven't placed any orders yet.</p>
            <a href="index.php?page=catalog" style="color: var(--primary-color);">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>