<?php
require_once __DIR__ . '/../vendor/autoload.php';

// VULNERABLE: Debug mode enabled in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// VULNERABLE: Insecure session configuration
ini_set('session.cookie_httponly', 0);
ini_set('session.cookie_secure', 0);

session_start();

use VulnerableApp\Controller\ActualVulnerableController;

// Simple routing
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Routes for dependency-specific vulnerabilities
if ($path === '/yaml-parse') {
    $controller = new ActualVulnerableController();
    $input = $_POST['yaml'] ?? '';
    echo $controller->symfonyYamlUnsafe($input);
    
} elseif ($path === '/monolog-path') {
    $controller = new ActualVulnerableController();
    $path = $_GET['path'] ?? '/tmp/log.txt';
    $message = $_GET['message'] ?? 'Test message';
    echo $controller->monologPathInjection($path, $message);
    
} elseif ($path === '/guzzle-ssrf') {
    $controller = new ActualVulnerableController();
    $url = $_GET['url'] ?? 'http://example.com';
    echo $controller->guzzleSSRF($url);
    
} elseif ($path === '/twig-sandbox') {
    $controller = new ActualVulnerableController();
    $template = $_POST['template'] ?? 'Hello {{ 7*7 }}';
    echo $controller->twigSandboxBypass($template);
    
} elseif ($path === '/doctrine-dql') {
    $controller = new ActualVulnerableController();
    $userId = $_GET['id'] ?? '1';
    echo $controller->doctrineDQLInjection($userId);
    
} else {
    // Default response
    echo "Vulnerable PHP Application Running\n";
    echo "Available endpoints:\n";
    echo "- POST /yaml-parse (CVE-2022-24894)\n";
    echo "- GET /monolog-path?path=... (CVE-2022-31090)\n";
    echo "- GET /guzzle-ssrf?url=... (CVE-2022-31091)\n";
    echo "- POST /twig-sandbox (CVE-2022-23614)\n";
    echo "- GET /doctrine-dql?id=... (CVE-2020-13246)\n";
}