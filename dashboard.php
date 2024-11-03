<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Connexion à la BDD
require_once 'bdd.php';

// Récupération des informations de l'utilisateur connecté
$sql = "SELECT id, is_admin FROM users WHERE username = :username";
$stmt = $connexion->prepare($sql);
$stmt->execute([':username' => $_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['id'];
$is_admin = $user['is_admin'];

// Affichage de tous les articles
$sql = "SELECT articles.id, articles.title, articles.content, articles.created_at, users.username, users.id AS author_id
        FROM articles
        JOIN users ON articles.user_id = users.id
        ORDER BY articles.created_at DESC";
$stmt = $connexion->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour ajouter un article
function ajouterArticle($connexion, $user_id, $title, $content) {
    $sql = "INSERT INTO articles (user_id, title, content) VALUES (:user_id, :title, :content)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':user_id' => $user_id, ':title' => htmlspecialchars($title), ':content' => htmlspecialchars($content)]);
}

// Fonction pour supprimer un article
function supprimerArticle($connexion, $user_id, $article_id, $is_admin) {
    $sql = "SELECT * FROM articles WHERE id = :article_id AND (user_id = :user_id OR :is_admin = 1)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':article_id' => $article_id, ':user_id' => $user_id, ':is_admin' => $is_admin]);

    if ($stmt->rowCount() === 1) {
        $sql = "DELETE FROM articles WHERE id = :article_id";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
    } else {
        echo "<p>Erreur : Vous ne pouvez supprimer que vos propres articles.</p>";
    }
}

// Fonction pour modifier un article
function modifierArticle($connexion, $user_id, $article_id, $title, $content, $is_admin) {
    $sql = "SELECT * FROM articles WHERE id = :article_id AND (user_id = :user_id OR :is_admin = 1)";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':article_id' => $article_id, ':user_id' => $user_id, ':is_admin' => $is_admin]);

    if ($stmt->rowCount() === 1) {
        $sql = "UPDATE articles SET title = :title, content = :content WHERE id = :article_id";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([':title' => htmlspecialchars($title), ':content' => htmlspecialchars($content), ':article_id' => $article_id]);
    } else {
        echo "<p>Erreur : Vous ne pouvez modifier que vos propres articles.</p>";
    }
}

// Gestion des actions de formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_article' && !empty($_POST['title']) && !empty($_POST['content'])) {
        ajouterArticle($connexion, $user_id, $_POST['title'], $_POST['content']);
        header('Location: dashboard.php');
        exit;

    } elseif ($action === 'delete_article' && isset($_POST['article_id'])) {
        supprimerArticle($connexion, $user_id, (int)$_POST['article_id'], $is_admin);
        header('Location: dashboard.php');
        exit;

    } elseif ($action === 'edit_article' && isset($_POST['article_id']) && !empty($_POST['title']) && !empty($_POST['content'])) {
        modifierArticle($connexion, $user_id, (int)$_POST['article_id'], $_POST['title'], $_POST['content'], $is_admin);
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard</title>
    <style>
        .edit-form { display: none; }
    </style>
    <script>
        function toggleEditForm(articleId) {
            const form = document.getElementById('edit-form-' + articleId);
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <header>
        <h1>Home</h1>
        <button><a href="logout.php">Déconnexion</a></button>
    </header>

    <!-- Formulaire pour ajouter un nouvel article -->
    <h2>Poster un nouvel article</h2>
    <form action="dashboard.php" method="POST">
        <input type="hidden" name="action" value="add_article">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="content">Contenu :</label>
        <textarea id="content" name="content" required></textarea>
        <br>
        <button type="submit">Publier</button>
    </form>

    <!-- Affichage de tous les articles -->
    <h2>Tous les articles</h2>
    <?php if ($articles): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <p class="nom"><?php echo htmlspecialchars($article['username']); ?></p>
                    <h3 class="titre"><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p class="contenue"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                    <p><?php echo $article['created_at']; ?></p>

                    <?php if ($is_admin || $article['author_id'] === $user_id): ?>
                        <form action="dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_article">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                        <button onclick="toggleEditForm(<?php echo $article['id']; ?>)">Modifier</button>
                        <form id="edit-form-<?php echo $article['id']; ?>" action="dashboard.php" method="POST" class="edit-form">
                            <input type="hidden" name="action" value="edit_article">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <label for="edit-title-<?php echo $article['id']; ?>">Titre :</label>
                            <input type="text" id="edit-title-<?php echo $article['id']; ?>" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                            <br>
                            <label for="edit-content-<?php echo $article['id']; ?>">Contenu :</label>
                            <textarea id="edit-content-<?php echo $article['id']; ?>" name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                            <br>
                            <button type="submit">Valider</button>
                            <button type="button" onclick="toggleEditForm(<?php echo $article['id']; ?>)">Annuler</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun article trouvé.</p>
    <?php endif; ?>

</body>
</html>
