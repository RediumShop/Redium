<?php

require_once "../config.php";

requireAdmin();


$editProduct = null;


if (isset($_GET["edit"])) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM products
        WHERE id = ?
    ");

    $stmt->execute([
        (int)$_GET["edit"]
    ]);

    $editProduct =
        $stmt->fetch();

}


$stmt = $pdo->query("
    SELECT *
    FROM products
    ORDER BY id DESC
");

$products =
    $stmt->fetchAll();

?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Redium Admin Panel</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    background: #080a10;

    color: white;

    font-family: Arial;
}

.navbar {

    height: 70px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 0 5%;

    background: #10131c;

    border-bottom: 1px solid #282e3c;
}

.navbar a {

    color: #ff526b;

    text-decoration: none;
}

.container {

    width: min(1200px, 92%);

    margin: 30px auto;

    display: grid;

    grid-template-columns: 380px 1fr;

    gap: 20px;
}

.panel {

    background: #10131c;

    border: 1px solid #292f3d;

    border-radius: 15px;

    padding: 22px;
}

label {

    display: block;

    color: #a5adbd;

    font-size: 13px;

    margin: 14px 0 7px;
}

input,
textarea,
select {

    width: 100%;

    padding: 12px;

    background: #080a10;

    color: white;

    border: 1px solid #303747;

    border-radius: 8px;

}

textarea {

    resize: vertical;
}

button {

    padding: 11px 15px;

    border: 0;

    border-radius: 8px;

    background: #ff304f;

    color: white;

    font-weight: bold;

    cursor: pointer;
}

.submit {

    width: 100%;

    margin-top: 18px;
}

.preview {

    width: 100%;

    max-height: 200px;

    object-fit: cover;

    border-radius: 10px;

    margin-top: 10px;
}

table {

    width: 100%;

    border-collapse: collapse;
}

th,
td {

    text-align: left;

    padding: 12px;

    border-bottom: 1px solid #282e3c;

    font-size: 13px;
}

.thumb {

    width: 65px;

    height: 50px;

    object-fit: cover;

    border-radius: 7px;
}

.description {

    color: #8f97a8;

    display: block;

    margin-top: 5px;

    max-width: 300px;
}

.actions {

    display: flex;

    gap: 7px;
}

.edit {

    background: #252c3a;

    text-decoration: none;

    color: white;

    padding: 8px;

    border-radius: 7px;
}

.delete {

    background: #451520;
}

@media(max-width:900px) {

    .container {

        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>


<header class="navbar">

<strong>

🔴 Redium Admin

</strong>

<div>

Hoş geldin,
<?= htmlspecialchars(
    $_SESSION["admin_username"]
) ?>

&nbsp; | &nbsp;

<a href="logout.php">

Çıkış Yap

</a>

</div>

</header>


<main class="container">


<!-- ÜRÜN FORMU -->

<section class="panel">

<h2>

<?= $editProduct
    ? "Ürünü Düzenle"
    : "Yeni Ürün Ekle"
?>

</h2>


<form
    action="save_product.php"
    method="POST"
    enctype="multipart/form-data"
>


<input
    type="hidden"
    name="id"
    value="<?= (int)(
        $editProduct["id"] ?? 0
    ) ?>"
>


<label>

Ürün Başlığı

</label>

<input
    name="title"
    maxlength="150"
    required
    value="<?= htmlspecialchars(
        $editProduct["title"] ?? ""
    ) ?>"
>


<label>

Ürün Açıklaması

</label>

<textarea
    name="description"
    rows="6"
    required
><?= htmlspecialchars(
    $editProduct["description"] ?? ""
)?></textarea>


<label>

Fiyat

</label>

<input
    name="price"
    type="number"
    min="0"
    step="0.01"
    required
    value="<?= htmlspecialchars(
        $editProduct["price"] ?? ""
    ) ?>"
>


<label>

Kategori

</label>

<select
    name="category"
    required
>

<?php

$categories = [
    "Oyun",
    "Hesap",
    "Kod",
    "Eşya",
    "Diğer"
];

foreach ($categories as $category):

?>

<option
    value="<?= $category ?>"
    <?= (
        ($editProduct["category"] ?? "")
        === $category
    )
        ? "selected"
        : ""
    ?>
>

<?= $category ?>

</option>

<?php endforeach; ?>

</select>


<label>

Ürün Fotoğrafı

</label>

<input
    name="image"
    type="file"
    accept=".jpg,.jpeg,.png,.webp"
>


<?php if (
    !empty($editProduct["image"])
): ?>

<img
    class="preview"
    src="../<?= htmlspecialchars(
        $editProduct["image"]
    ) ?>"
>

<?php endif; ?>


<button
    class="submit"
    type="submit"
>

<?= $editProduct
    ? "Değişiklikleri Kaydet"
    : "Ürünü Ekle"
?>

</button>


<?php if ($editProduct): ?>

<p>

<a
    href="index.php"
    style="color:#ff536b"
>

Yeni ürün ekle

</a>

</p>

<?php endif; ?>


</form>

</section>


<!-- ÜRÜNLER -->

<section class="panel">

<h2>

Ürünler

</h2>


<div style="overflow:auto">

<table>

<thead>

<tr>

<th>Fotoğraf</th>

<th>Ürün</th>

<th>Kategori</th>

<th>Fiyat</th>

<th>İşlem</th>

</tr>

</thead>


<tbody>

<?php foreach ($products as $product): ?>

<tr>

<td>

<?php if (
    !empty($product["image"])
): ?>

<img
    class="thumb"
    src="../<?= htmlspecialchars(
        $product["image"]
    ) ?>"
>

<?php else: ?>

🛍️

<?php endif; ?>

</td>


<td>

<strong>

<?= htmlspecialchars(
    $product["title"]
) ?>

</strong>


<span class="description">

<?= htmlspecialchars(
    $product["description"]
) ?>

</span>

</td>


<td>

<?= htmlspecialchars(
    $product["category"]
) ?>

</td>


<td>

<?= number_format(
    (float)$product["price"],
    2,
    ",",
    "."
) ?>

₺

</td>


<td>

<div class="actions">

<a
    class="edit"
    href="?edit=<?= (int)$product["id"] ?>"
>

Düzenle

</a>


<form
    action="delete_product.php"
    method="POST"
    onsubmit="
        return confirm(
            'Bu ürünü silmek istediğine emin misin?'
        );
    "
>

<input
    type="hidden"
    name="id"
    value="<?= (int)$product["id"] ?>"
>

<button
    class="delete"
>

Sil

</button>

</form>

</div>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</section>

</main>

</body>

</html>