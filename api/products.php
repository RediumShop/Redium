<?php

require_once "../config.php";

header(
    "Content-Type: application/json; charset=utf-8"
);

$stmt = $pdo->query("
    SELECT
        id,
        title,
        description,
        price,
        category,
        image
    FROM products
    ORDER BY id DESC
");

$products = $stmt->fetchAll();

foreach ($products as &$product) {

    if (!empty($product["image"])) {

        $product["image"] =
            $product["image"];

    }

    $product["price"] =
        (float)$product["price"];
}

echo json_encode(
    $products,
    JSON_UNESCAPED_UNICODE
);