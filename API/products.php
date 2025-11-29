<?php
// 1. Include Config & Helpers
require_once '../config/db.php';
require_once '../includes/functions.php';

// Set Header to JSON
header('Content-Type: application/json');

// 2. Get Parameters
$category_id = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$search = isset($_GET['q']) ? sanitize($_GET['q']) : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

try {
    // 3. Build Query
    $sql = "SELECT g.*, c.name as category_name 
            FROM games g 
            LEFT JOIN categories c ON g.category_id = c.id 
            WHERE 1=1";
    
    $params = [];

    // Filter by Category
    if ($category_id) {
        $sql .= " AND category_id = ?";
        $params[] = $category_id;
    }

    // Filter by Search Term
    if ($search) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    // Add Limit
    $sql .= " ORDER BY created_at DESC LIMIT " . $limit;

    // 4. Execute
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. Return JSON
    echo json_encode([
        'success' => true,
        'count' => count($games),
        'data' => $games
    ]);

} catch (PDOException $e) {
    // Return Error JSON
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>