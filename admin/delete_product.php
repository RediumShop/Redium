<?php

require_once "../config.php";

requireAdmin();


$id =
    (int)($_POST["id"] ?? 0);


$stmt = $pdo->prepare("
    SELECT image
    FROM products
    WHERE id = ?
");

$stmt->execute([
    $id
]);

$product =
    $stmt->fetch();


if ($product) {

    $stmt = $pdo->prepare("
        DELETE FROM products
        WHERE id = ?
    ");

    $stmt->execute([
        $id
    ]);


    if (
        !empty($product["image"])
    ) {

        $file =
            "../" .
            $product["image"];


        if (
            is_file($file)
        ) {

            unlink($file);

        }

    }

}


header(
    "Location: index.php"
);

exit;