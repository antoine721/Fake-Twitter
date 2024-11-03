<?php
session_start(); // Démarre la session

// Détruire toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers index.php
header('Location: index.php');
exit;
?>
