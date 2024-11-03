<?php 
session_start();

if(!isset($_SESSION['csrf_article_add']) || empty($_SESSION['csrf_article_add']))
{
    $_SESSION['csrf_article_add'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Document</title>
</head>
<body>
    <h1>fils de discussion</h1>

    <h2>Log in</h2>
    <form action="login.php" method="post">

    <label for="username">Nom d'utilisateur: </label>
    <input type="text" name="username" id="username" placeholder="Entrez votre Username">
    <br>
    <label for="password">Mot de passe: </label>
    <input type="password" name="password" id="password" placeholder="Entrez votre mdp">
    <br>

    <br>
    <input type="hidden" name="token" value="<?= $_SESSION['csrf_article_add']; ?>">
    <input type="submit" name="login" value="Se connecter">
    <br>
    </form>
</body>
</html>