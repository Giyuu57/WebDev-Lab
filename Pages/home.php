<div class="hero-section" style="
    background: linear-gradient(rgba(11, 12, 16, 0.8), rgba(11, 12, 16, 0.9)), url('https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');
    background-size: cover;
    background-position: center;
    padding: 150px 0;
    text-align: center;
    position: relative;
    overflow: hidden;">
    
    <div class="container hero-content">
        <h1 style="font-size: 4rem; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 5px; text-shadow: 0 0 20px rgba(102, 252, 241, 0.6);">
            Level Up <span style="color: var(--primary-color);">Your Game</span>
        </h1>
        <p style="font-size: 1.5rem; color: #ddd; margin-bottom: 40px; max-width: 700px; margin-left: auto; margin-right: auto;">
            Explore the multiverse of digital entertainment. Instant delivery. Unbeatable prices.
        </p>
        <div style="display: flex; gap: 20px; justify-content: center;">
            <a href="index.php?page=catalog" class="btn-register" style="padding: 15px 40px; font-size: 1.2rem;">Browse Store</a>
            <a href="index.php?page=login" class="btn-login" style="padding: 15px 40px; font-size: 1.2rem; background: rgba(0,0,0,0.5);">Join Now</a>
        </div>
    </div>

    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 50px; background: linear-gradient(to top, var(--bg-color), transparent);"></div>
</div>

<div class="container" style="margin-top: 80px; padding-bottom: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h2 class="section-title" style="margin-bottom: 0;">Trending Now</h2>
        <a href="index.php?page=catalog" style="color: var(--primary-color); font-weight: bold; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
            View All <i class="fa fa-arrow-right"></i>
        </a>
    </div>

    <div class="game-grid">
        <?php
        try {
            $sql = "SELECT g.*, c.name as category_name 
                    FROM games g 
                    LEFT JOIN categories c ON g.category_id = c.id 
                    ORDER BY RAND() LIMIT 4";
            $stmt = $pdo->query($sql);
            $featured_games = $stmt->fetchAll();

            if (count($featured_games) > 0):
                foreach ($featured_games as $game):
        ?>
                <div class="game-card">
                    <img src="assets/images/<?php echo htmlspecialchars($game['image_url']); ?>" 
                         onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'" 
                         alt="<?php echo htmlspecialchars($game['title']); ?>" 
                         class="game-image">
                    
                    <div class="game-info">
                        <div class="game-category"><?php echo htmlspecialchars($game['category_name'] ?? 'General'); ?></div>
                        <h3 class="game-title"><?php echo htmlspecialchars($game['title']); ?></h3>
                        
                        <div class="game-meta">
                            <span class="rating">
                                <i class="fa fa-star"></i> <?php echo $game['rating']; ?>
                            </span>
                            <span class="price"><?php echo formatPrice($game['price']); ?></span>
                        </div>
                        
                        <a href="index.php?page=product&id=<?php echo $game['id']; ?>" class="btn-add-cart">
                            View Details
                        </a>
                    </div>
                </div>
        <?php 
                endforeach; 
            endif; 
        } catch (PDOException $e) {
            echo "<p class='error'>Error loading games.</p>";
        }
        ?>
    </div>
</div>