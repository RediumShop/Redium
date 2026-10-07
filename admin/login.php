<?php

require_once "../config.php";

if (!empty($_SESSION["admin_id"])) {

    header("Location: index.php");

    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username =
        trim($_POST["username"] ?? "");

    $password =
        $_POST["password"] ?? "";


    $stmt = $pdo->prepare("
        SELECT *
        FROM admins
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->execute([
        $username
    ]);

    $admin =
        $stmt->fetch();


    if (
        $admin &&
        password_verify(
            $password,
            $admin["password_hash"]
        )
    ) {

        session_regenerate_id(true);

        $_SESSION["admin_id"] =
            $admin["id"];

        $_SESSION["admin_username"] =
            $admin["username"];


        header("Location: index.php");

        exit;

    }


    $error =
        "Kullanıcı adı veya şifre yanlış.";

}

?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Redium Admin Giriş</title>

<style>

body {

    margin: 0;

    min-height: 100vh;

    display: grid;

    place-items: center;

    background: #080a10;

    color: white;

    font-family: Arial;
}

.login {

    width: 360px;

    padding: 30px;

    background: #10131c;

    border: 1px solid #292f3d;

    border-radius: 15px;
}

h1 {

    margin-top: 0;
}

label {

    display: block;

    margin-top: 15px;

    margin-bottom: 7px;

    color: #9aa2b3;
}

input {

    width: 100%;

    padding: 12px;

    box-sizing: border-box;

    background: #080a10;

    border: 1px solid #303747;

    color: white;

    border-radius: 8px;
}

button {

    width: 100%;

    margin-top: 20px;

    padding: 12px;

    border: 0;

    border-radius: 8px;

    background: #ff304f;

    color: white;

    font-weight: bold;

    cursor: pointer;
}

.error {

    padding: 10px;

    background: #35131b;

    color: #ff7286;

    border-radius: 8px;
}

</style>

</head>

<body>

<form
    class="login"
    method="POST"
>

<h1>🔴 Redium Admin</h1>

<p>Yönetim paneline giriş yap</p>


<?php if ($error): ?>

<div class="error">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>


<label>

Kullanıcı adı

</label>

<input
    name="username"
    required
>


<label>

Şifre

</label>

<input
    name="password"
    type="password"
    required
>


<button>

Giriş Yap

</button>

</form>

</body>

</html>