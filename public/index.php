<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start(); // for Auth

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

// Define features array for Landing page
$features = [
    [
        'icon' => '/icons/ticket.svg', // place your SVGs in public/icons/
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

// Simple routing
$uri = $_SERVER['REQUEST_URI'];

function isLoggedIn() {
    return isset($_SESSION['user']);
}

switch ($uri) {
    case '/':
        echo $twig->render('landing.html.twig', ['features' => $features]);
        break;

    case '/auth/login':
        echo $twig->render('login.html.twig');
        break;

    case '/auth/signup':
        echo $twig->render('signup.html.twig');
        break;

    case '/dashboard':
        if (isLoggedIn()) {
            echo $twig->render('dashboard.html.twig');
        } else {
            header("Location: /auth/login");
            exit;
        }
        break;

    case '/tickets':
        if (isLoggedIn()) {
            echo $twig->render('tickets.html.twig');
        } else {
            header("Location: /auth/login");
            exit;
        }
        break;

    default:
        echo $twig->render('notfound.html.twig');
        break;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri === '/auth/login') {
    // Simple dummy login for demo
    $_SESSION['user'] = $_POST['email'];
    header("Location: /dashboard");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri === '/auth/signup') {
    // Simple dummy signup
    $_SESSION['user'] = $_POST['email'];
    header("Location: /dashboard");
    exit;
}
