<div class="container">
    <h2 class="section-title">Game Catalog</h2>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <aside style="flex: 1; min-width: 250px;">
            <div style="background: var(--card-bg); padding: 20px; border-radius: 10px;">
                <h3 style="margin-bottom: 15px; border-bottom: 1px solid #444; padding-bottom: 10px;">Categories</h3>
                <ul class="category-list">
                    <li style="margin-bottom: 10px;">
                        <a href="index.php?page=catalog" style="<?php echo !isset($_GET['cat']) ? 'color: var(--primary-color); font-weight:bold;' : ''; ?>">All Games</a>
                    </li>
                    <?php
                    // Fetch Categories for Sidebar
                    $cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
                    while ($cat = $cat_stmt->fetch()):
                        $is_active = isset($_GET['cat']) && $_GET['cat'] == $cat['id'];
                    ?>
                    <li style="margin-bottom: 10px;">
                        <a href="index.php?page=catalog&cat=<?php echo $cat['id']; ?>" 
                           style="<?php echo $is_active ? 'color: var(--primary-color); font-weight:bold;' : ''; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </aside>

        <section style="flex: 3;">
            <?php
            // Build Query based on Filters
            $sql = "SELECT g.*, c.name as category_name FROM games g LEFT JOIN categories c ON g.category_id = c.id WHERE 1=1";
            $params = [];

            // Filter by Category
            if (isset($_GET['cat']) && is_numeric($_GET['cat'])) {
                $sql .= " AND category_id = ?";
                $params[] = $_GET['cat'];
            }

            // Filter by Search Query
            if (isset($_GET['q'])) {
                $sql .= " AND (title LIKE ? OR description LIKE ?)";
                $search_term = "%" . $_GET['q'] . "%";
                $params[] = $search_term;
                $params[] = $search_term;
            }

            $sql .= " ORDER BY created_at DESC";

            // Execute Query
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $games = $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

            <?php if (isset($_GET['q'])): ?>
                <p style="margin-bottom: 20px; color: var(--text-muted);">
                    Showing results for "<strong><?php echo htmlspecialchars($_GET['q']); ?></strong>"
                </p>
            <?php endif; ?>

            <div class="game-grid">
                <?php if (count($games) > 0): ?>
                    <?php foreach ($games as $game): ?>
                        <div class="game-card">
                            <img src="assets/images/<?php echo htmlspecialchars($game['image_url']); ?>" 
                                 onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'"
                                 alt="<?php echo htmlspecialchars($game['title']); ?>" 
                                 class="game-image">
                            
                            <div class="game-info">
                                <div class="game-category"><?php echo htmlspecialchars($game['category_name'] ?? 'General'); ?></div>
                                <h3 class="game-title"><?php echo htmlspecialchars($game['title']); ?></h3>
                                
                                <div class="game-meta">
                                    <span class="rating"><i class="fa fa-star"></i> <?php echo $game['rating']; ?></span>
                                    <span class="price"><?php echo formatPrice($game['price']); ?></span>
                                </div>
                                
                                <a href="index.php?page=product&id=<?php echo $game['id']; ?>" class="btn-add-cart">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No games found matching your criteria.</p>
                <?php endif; ?>
            </div>
        </section>

    </div>
</div>