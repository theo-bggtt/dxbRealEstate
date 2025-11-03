<?php
// db.php (in /include/config/)

// Use absolute path to vendor/autoload.php
$autoloadPath = __DIR__ . '/../../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Error: Composer autoload file not found at: $autoloadPath. Ensure the 'vendor' directory exists and run 'composer install' in the project root.");
}
require_once $autoloadPath;

// Load .env with error handling
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
try {
    $dotenv->load();
    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET'])->notEmpty();
} catch (Exception $e) {
    die("Error loading .env file: " . $e->getMessage());
}

// Function to get PDO connection
function getPDO() {
    try {
        $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>