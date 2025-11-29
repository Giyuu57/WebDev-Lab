<?php
// Ensure user is logged in
if (!isLoggedIn()) {
    redirect('index.php?page=login&error=You must be logged in to checkout');
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    redirect('index.php?page=cart');
}

// Calculate Total again for security
$total_price = 0;
$cart_items = [];
// Safe way to handle array for SQL IN clause
$ids_array = array_map('intval', $_SESSION['cart']);
$ids = implode(',', $ids_array);

if (!empty($ids)) {
    try {
        $stmt = $pdo->query("SELECT * FROM games WHERE id IN ($ids)");
        $cart_items = $stmt->fetchAll();
        foreach ($cart_items as $item) {
            $total_price += $item['price'];
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<div class="container">
    <h2 class="section-title fade-in">Checkout</h2>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 2; min-width: 300px;" class="fade-in">
            <div class="auth-box" style="margin: 0; max-width: 100%; box-shadow: none; border: 1px solid rgba(255,255,255,0.1);">
                <h3 style="margin-bottom: 20px; color: var(--primary-color);">Billing Details</h3>
                
                <form action="api/cart_api.php" method="POST">
                    <input type="hidden" name="action" value="checkout">
                    <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly style="background: rgba(0,0,0,0.5); cursor: not-allowed; color: #888;">
                    </div>

                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19" required
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(.{4})/g, '$1 ').trim().slice(0, 19)">
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Expiry Date (MM/YY)</label>
                            <input type="text" class="form-control" id="expiry-date" placeholder="MM/YY" maxlength="5" required>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label>CVV</label>
                            <input type="text" class="form-control" placeholder="123" maxlength="3" required
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" style="margin-top: 20px; width: 100%; font-size: 1.1rem;">
                        <i class="fa fa-lock"></i> Pay <?php echo formatPrice($total_price); ?>
                    </button>
                </form>
            </div>
        </div>

        <div style="flex: 1; min-width: 250px;" class="fade-in">
            <div style="background: var(--card-bg); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <h3 style="margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">Order Summary</h3>
                
                <ul style="margin-bottom: 20px;">
                    <?php foreach ($cart_items as $item): ?>
                    <li style="display: flex; justify-content: space-between; margin-bottom: 15px; color: var(--text-muted);">
                        <span><?php echo htmlspecialchars($item['title']); ?></span>
                        <span style="color: #fff;"><?php echo formatPrice($item['price']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px; display: flex; justify-content: space-between; font-weight: bold; font-size: 1.4rem;">
                    <span>Total</span>
                    <span style="color: var(--primary-color); text-shadow: 0 0 10px rgba(102, 252, 241, 0.4);"><?php echo formatPrice($total_price); ?></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('expiry-date').addEventListener('input', function(e) {
    // 1. Remove non-numeric characters
    var input = this.value.replace(/\D/g, '');
    
    // 2. Add slash after 2nd char
    if (input.length > 2) {
        input = input.substring(0, 2) + '/' + input.substring(2, 4);
    }
    
    // 3. Update field
    this.value = input;
});
</script>