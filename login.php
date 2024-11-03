<?php
session_start();

if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_article_add']) {
    die('<p>Token invalide</p>');
}

// Supprimer le token pour éviter la réutilisation
unset($_SESSION['csrf_article_add']);

// Vérification des données du formulaire
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
} else {
    die("<p>Le nom d'utilisateur est vide.</p>");
}

if (isset($_POST['password']) && !empty($_POST['password'])) {
    $password = htmlspecialchars($_POST['password']);
} else {
    die("<p>Le mot de passe est vide.</p>");
}

if (isset($username) && isset($password)) {
    // Connexion à la BDD
    require_once 'bdd.php';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    echo $hashedPassword;

    // Préparation de la requête pour vérifier l'utilisateur
    $sql = "SELECT password FROM users WHERE username = :username";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':username' => $username]);

    // Vérification de l'existence de l'utilisateur
    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $user['password'];

        // Vérification du mot de passe
        if (password_verify($password, $hashedPassword)) {

            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            echo "<p>Le mot de passe est incorrect.</p>";
        }
    } else {
        echo "<p>Nom d'utilisateur non trouvé.</p>";
    }
} else {
    echo "<p>Erreur de traitement des données.</p>";
}
?>
