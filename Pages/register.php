<?php
if (isLoggedIn()) {
    redirect('index.php?page=dashboard');
}
?>

<div class="container">
    <div class="auth-box">
        <h2 style="text-align: center; margin-bottom: 20px;">Create Account</h2>

        <?php if(isset($_GET['error'])): ?>
            <div style="background: var(--danger-color); color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="api/auth.php" method="POST">
            <input type="hidden" name="action" value="register">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="user@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Create a password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn-submit">Sign Up</button>
        </form>

        <p style="text-align: center; margin-top: 15px; color: var(--text-muted);">
            Already have an account? <a href="index.php?page=login" style="color: var(--primary-color);">Login</a>
        </p>
    </div>
</div>