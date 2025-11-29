<?php
// 1. Include Config & Helpers
require_once '../config/db.php';
require_once '../includes/functions.php';

// 2. Determine Action
// check $_POST for forms, $_GET for links (like logout)
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'register':
        handleRegister($pdo);
        break;
    case 'login':
        handleLogin($pdo);
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        redirect('../index.php');
        break;
}

// --- Functions ---

function handleRegister($pdo) {
    // 1. Get Input
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 2. Validation
    if (empty($name) || empty($email) || empty($password)) {
        redirect('../index.php?page=register&error=All fields are required');
    }

    if ($password !== $confirm_password) {
        redirect('../index.php?page=register&error=Passwords do not match');
    }

    if (strlen($password) < 6) {
        redirect('../index.php?page=register&error=Password must be at least 6 characters');
    }

    try {
        // 3. Check if Email Exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            redirect('../index.php?page=register&error=Email already exists');
        }

        // 4. Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 5. Insert User
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password]);

        // 6. Success
        redirect('../index.php?page=login&success=Account created successfully! Please login.');

    } catch (PDOException $e) {
        redirect('../index.php?page=register&error=Database error: ' . $e->getMessage());
    }
}

function handleLogin($pdo) {
    // 1. Get Input
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    try {
        // 2. Fetch User
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 3. Verify Password
        if ($user && password_verify($password, $user['password'])) {
            // Login Success: Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect to Dashboard
            redirect('../index.php?page=dashboard');
        } else {
            // Login Failed
            redirect('../index.php?page=login&error=Invalid email or password');
        }

    } catch (PDOException $e) {
        redirect('../index.php?page=login&error=Database error');
    }
}

function handleLogout() {
    // Destroy Session
    session_unset();
    session_destroy();
    redirect('../index.php?page=login&success=Logged out successfully');
}
?>