<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start(); // for Auth sessions

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// --- Twig setup ---
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

// --- Define features for Landing page ---
$features = [
    [
        'icon' => '/icons/ticket.svg',
        'title' => 'Smart Ticket Management',
        'description' => 'Organize and track all your support tickets in one centralized platform.'
    ],
    [
        'icon' => '/icons/check-circle.svg',
        'title' => 'Status Tracking',
        'description' => 'Monitor ticket progress with visual status indicators and real-time updates.'
    ],
    [
        'icon' => '/icons/trending-up.svg',
        'title' => 'Analytics Dashboard',
        'description' => 'Get insights into your support performance with comprehensive analytics.'
    ],
    [
        'icon' => '/icons/users.svg',
        'title' => 'Team Collaboration',
        'description' => 'Work together seamlessly with your team to resolve tickets faster.'
    ]
];

// --- Helper function to check if user is logged in ---
function isLoggedIn()
{
    return isset($_SESSION['user']);
}

// --- Routing ---
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Handle GET routes
if ($uri === '/' || $uri === '/index') {
    echo $twig->render('landing.html.twig', ['features' => $features]);
}

elseif ($uri === '/login') {
    echo $twig->render('login.html.twig');
}

elseif ($uri === '/signup') {
    echo $twig->render('signup.html.twig');
}

elseif ($uri === '/dashboard') {
    if (isLoggedIn()) {
        echo $twig->render('dashboard.html.twig', ['user' => $_SESSION['user']]);
    } else {
        header("Location: /login");
        exit;
    }
}

elseif ($uri === '/tickets') {
    if (isLoggedIn()) {
        echo $twig->render('tickets.html.twig', ['user' => $_SESSION['user']]);
    } else {
        header("Location: /login");
        exit;
    }
}

// Handle POST routes (form submissions)
elseif ($uri === '/login-process' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple dummy login (replace with database logic later)
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $_SESSION['user'] = $email;
        header("Location: /dashboard");
        exit;
    } else {
        echo $twig->render('login.html.twig', [
            'error_message' => 'Please enter your email and password.'
        ]);
    }
}

elseif ($uri === '/signup-process' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple dummy signup (replace with real signup logic later)
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($email && $password && $confirmPassword && $password === $confirmPassword) {
        $_SESSION['user'] = $email;
        header("Location: /dashboard");
        exit;
    } else {
        echo $twig->render('signup.html.twig', [
            'error_message' => 'Passwords do not match or fields are incomplete.'
        ]);
    }
}

elseif ($uri === '/logout') {
    session_destroy();
    header("Location: /");
    exit;
}

// Fallback route for unknown URLs
else {
    echo $twig->render('notfound.html.twig');
}
