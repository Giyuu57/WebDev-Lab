<?php
// Security Check: Only Admins Allowed
if (!isAdmin()) {
    echo "<div class='container'><p class='error'>Access Denied. Admins only.</p></div>";
    return;
}

// Handle Form Submissions
$message = "";

// 1. Add Game
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_game') {
    $title = sanitize($_POST['title']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $image_url = sanitize($_POST['image_url']);
    $description = sanitize($_POST['description']);
    $rating = (float)$_POST['rating'];

    try {
        $stmt = $pdo->prepare("INSERT INTO games (title, description, price, category_id, image_url, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $category_id, $image_url, $rating]);
        $message = "Game added successfully!";
    } catch (PDOException $e) {
        $message = "Error adding game: " . $e->getMessage();
    }
}

// 2. Delete Game
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_game') {
    $game_id = (int)$_POST['game_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
        $stmt->execute([$game_id]);
        $message = "Game deleted successfully!";
    } catch (PDOException $e) {
        $message = "Error deleting game: " . $e->getMessage();
    }
}

// Fetch Data for View
// Categories for dropdown
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
// All Games for list
$all_games = $pdo->query("SELECT g.*, c.name as cat_name FROM games g LEFT JOIN categories c ON g.category_id = c.id ORDER BY g.id DESC")->fetchAll();
?>

<div class="container">
    <h2 class="section-title">Admin Panel</h2>

    <?php if ($message): ?>
        <div style="background: #00b894; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <div class="auth-box" style="margin: 0; max-width: 100%;">
                <h3 style="margin-bottom: 20px;">Add New Game</h3>
                
                <form action="index.php?page=admin" method="POST">
                    <input type="hidden" name="action" value="add_game">
                    
                    <div class="form-group">
                        <label>Game Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Rating (0-5)</label>
                            <input type="number" step="0.1" max="5" name="rating" class="form-control" value="4.5">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <?php foreach ($cats as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Image Filename (e.g., game1.jpg)</label>
                        <input type="text" name="image_url" class="form-control" value="placeholder.jpg">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Add Game</button>
                </form>
            </div>
        </div>

        <div style="flex: 1.5; min-width: 300px;">
            <div style="background: var(--card-bg); padding: 20px; border-radius: 10px;">
                <h3 style="margin-bottom: 20px;">Manage Games</h3>
                
                <div style="max-height: 600px; overflow-y: auto;">
                    <table class="cart-table" style="font-size: 0.9rem;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_games as $g): ?>
                            <tr>
                                <td><?php echo $g['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($g['title']); ?><br>
                                    <small style="color: var(--text-muted);"><?php echo htmlspecialchars($g['cat_name']); ?></small>
                                </td>
                                <td>$<?php echo $g['price']; ?></td>
                                <td>
                                    <form action="index.php?page=admin" method="POST" onsubmit="return confirm('Are you sure you want to delete this game?');">
                                        <input type="hidden" name="action" value="delete_game">
                                        <input type="hidden" name="game_id" value="<?php echo $g['id']; ?>">
                                        <button type="submit" style="background: var(--danger-color); color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>