<?php
// Set the working directory to the project root so all relative includes work perfectly
chdir(dirname(__DIR__));

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Clean up the path (remove leading/trailing slashes)
$cleanPath = ltrim($path, '/');

if ($cleanPath === '' || $cleanPath === 'index.php') {
    require 'index.php';
} else {
    // Check if the requested file exists in the root folder and is a PHP file
    if (file_exists($cleanPath) && is_file($cleanPath) && pathinfo($cleanPath, PATHINFO_EXTENSION) === 'php') {
        require $cleanPath;
    } else {
        http_response_code(404);
        echo "404 Not Found: " . htmlspecialchars($cleanPath);
    }
}
?>
