<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3307', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS larabookmarks');
    echo "Database 'larabookmarks' created successfully.\n";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
    exit(1);
}
