<?php
// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('index.php?page=dashboard');
}
?>

<div class="container">
    <div class="auth-box">
        <h2 style="text-align: center; margin-bottom: 20px;">Login to PixelStore</h2>
        
        <?php if(isset($_GET['error'])): ?>
            <div style="background: var(--danger-color); color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['success'])): ?>
            <div style="background: #00b894; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="api/auth.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="user@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p style="text-align: center; margin-top: 15px; color: var(--text-muted);">
            Don't have an account? <a href="index.php?page=register" style="color: var(--primary-color);">Sign Up</a>
        </p>
    </div>
</div>