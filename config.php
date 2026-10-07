<?php

declare(strict_types=1);

session_start();

$dbHost = "localhost";
$dbName = "redium";
$dbUser = "root";
$dbPass = "";

try {

    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {

    die("Veritabanı bağlantısı başarısız.");

}

function requireAdmin(): void
{
    if (empty($_SESSION["admin_id"])) {

        header("Location: /Redium/admin/login.php");
        exit;

    }
}