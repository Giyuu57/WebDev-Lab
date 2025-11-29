<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/**
 * Sanitize user input to prevent XSS
 */
function sanitize($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

/**
 * Redirect to a specific page
 */
function redirect($url)
{
  header("Location: $url");
  exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
  return isset($_SESSION['user_id']);
}

/**
 * Check if the logged-in user is an admin
 */
function isAdmin()
{
  return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Format price properly (e.g., $19.99)
 */
function formatPrice($price)
{
  return '$' . number_format($price, 2);
}

/**
 * JSON Response helper for API
 */
function jsonResponse($success, $message, $data = [])
{
  header('Content-Type: application/json');
  echo json_encode([
    'success' => $success,
    'message' => $message,
    'data' => $data
  ]);
  exit();
}
?>