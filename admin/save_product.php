<?php

require_once "../config.php";

requireAdmin();


$id =
    (int)($_POST["id"] ?? 0);

$title =
    trim($_POST["title"] ?? "");

$description =
    trim($_POST["description"] ?? "");

$price =
    (float)($_POST["price"] ?? 0);

$category =
    trim($_POST["category"] ?? "");


if (
    $title === "" ||
    $description === "" ||
    $category === "" ||
    $price < 0
) {

    die("Geçersiz ürün bilgileri.");

}


$imagePath = null;


if (
    isset($_FILES["image"]) &&
    $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
) {

    if (
        $_FILES["image"]["error"]
        !== UPLOAD_ERR_OK
    ) {

        die("Fotoğraf yüklenemedi.");

    }


    if (
        $_FILES["image"]["size"]
        > 3 * 1024 * 1024
    ) {

        die(
            "Fotoğraf en fazla 3 MB olabilir."
        );

    }


    $finfo =
        new finfo(FILEINFO_MIME_TYPE);

    $mime =
        $finfo->file(
            $_FILES["image"]["tmp_name"]
        );


    $allowed = [

        "image/jpeg" => "jpg",

        "image/png" => "png",

        "image/webp" => "webp"

    ];


    if (
        !isset($allowed[$mime])
    ) {

        die(
            "Sadece JPG, PNG ve WEBP yüklenebilir."
        );

    }


    $filename =
        bin2hex(
            random_bytes(16)
        )
        . "."
        . $allowed[$mime];


    $uploadDirectory =
        "../uploads";


    if (
        !is_dir($uploadDirectory)
    ) {

        mkdir(
            $uploadDirectory,
            0755,
            true
        );

    }


    if (
        !move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $uploadDirectory .
            "/" .
            $filename
        )
    ) {

        die(
            "Fotoğraf kaydedilemedi."
        );

    }


    $imagePath =
        "uploads/" . $filename;

}


/* DÜZENLEME */

if ($id > 0) {

    if ($imagePath !== null) {

        $stmt = $pdo->prepare("
            UPDATE products

            SET
                title = ?,
                description = ?,
                price = ?,
                category = ?,
                image = ?

            WHERE id = ?
        ");

        $stmt->execute([

            $title,

            $description,

            $price,

            $category,

            $imagePath,

            $id

        ]);

    } else {

        $stmt = $pdo->prepare("
            UPDATE products

            SET
                title = ?,
                description = ?,
                price = ?,
                category = ?

            WHERE id = ?
        ");

        $stmt->execute([

            $title,

            $description,

            $price,

            $category,

            $id

        ]);

    }

}


/* YENİ ÜRÜN */

else {

    $stmt = $pdo->prepare("
        INSERT INTO products
        (
            title,
            description,
            price,
            category,
            image
        )

        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([

        $title,

        $description,

        $price,

        $category,

        $imagePath

    ]);

}


header(
    "Location: index.php"
);

exit;