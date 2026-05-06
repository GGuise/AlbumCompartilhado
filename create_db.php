<?php
$host = '127.0.0.1';
$port = '5432';
$user = 'postgres';
$pass = 'postgres';
$db = 'postgres'; // Connect to default database first

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'photogallery'");
    if (!$stmt->fetch()) {
        $pdo->exec('CREATE DATABASE photogallery');
        echo "Banco de dados 'photogallery' criado com sucesso!\n";
    } else {
        echo "O banco de dados 'photogallery' já existe.\n";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
