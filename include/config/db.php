<?php
// db.php (place in your project root or a 'includes/config/' folder)

require_once __DIR__ . '/../../vendor/autoload.php';

// Load .env with error handling
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ ); // Adjust to parent directory if .env is in include/
try {
    $dotenv->load();
    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET'])->notEmpty();
} catch (Exception $e) {
    die("Error loading .env file: " . $e->getMessage());
}

// Function to get PDO connection
function getPDO() {
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// Initialize global $pdo on include
global $pdo;
$pdo = getPDO();
?>