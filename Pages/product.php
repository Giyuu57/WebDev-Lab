<?php
// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container'><p class='error'>Invalid Product ID.</p></div>";
    return; // Stop loading this include
}

$game_id = $_GET['id'];

// Fetch Game Details
try {
    $stmt = $pdo->prepare("SELECT g.*, c.name as category_name FROM games g LEFT JOIN categories c ON g.category_id = c.id WHERE g.id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch();

    if (!$game) {
        echo "<div class='container'><p class='error'>Game not found.</p></div>";
        return;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container">
    <a href="index.php?page=catalog" style="display:inline-block; margin-bottom: 20px; color: var(--text-muted);">
        <i class="fa fa-arrow-left"></i> Back to Catalog
    </a>

    <div style="display: flex; gap: 40px; flex-wrap: wrap; background: var(--card-bg); padding: 30px; border-radius: 10px;">
        
        <div style="flex: 1; min-width: 300px;">
            <img src="assets/images/<?php echo htmlspecialchars($game['image_url']); ?>" 
                 onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'"
                 alt="<?php echo htmlspecialchars($game['title']); ?>" 
                 style="width: 100%; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        </div>

        <div style="flex: 1.5; min-width: 300px;">
            <span style="background: var(--primary-color); padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; text-transform: uppercase;">
                <?php echo htmlspecialchars($game['category_name']); ?>
            </span>

            <h1 style="margin: 15px 0; font-size: 2.5rem;"><?php echo htmlspecialchars($game['title']); ?></h1>
            
            <div style="margin-bottom: 20px; font-size: 1.2rem; color: #fdcb6e;">
                <i class="fa fa-star"></i> <?php echo $game['rating']; ?> / 5.0
            </div>

            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 30px;">
                <?php echo nl2br(htmlspecialchars($game['description'])); ?>
            </p>

            <div style="display: flex; align-items: center; gap: 20px; border-top: 1px solid #444; padding-top: 20px;">
                <h2 style="color: var(--accent-color); margin: 0;"><?php echo formatPrice($game['price']); ?></h2>
                
                <form action="api/cart_api.php" method="POST" style="flex: 1;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                    <input type="hidden" name="redirect" value="product"> <button type="submit" class="btn-add-cart" style="font-size: 1.2rem; padding: 15px;">
                        <i class="fa fa-shopping-cart"></i> Add to Cart
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>