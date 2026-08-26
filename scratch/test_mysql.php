<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `db_dkupp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "MYSQL DATABASE `db_dkupp` VERIFIED/CREATED SUCCESS!\n";
} catch (Exception $e) {
    echo "MYSQL ERROR: " . $e->getMessage() . "\n";
}
