<!-- index.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des thèmes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .theme-button {
            display: inline-block;
            color: white;
            padding: 10px;
            margin: 5px;
            text-decoration: none;
            text-align: center;
            width: 150px;
        }
    </style>
</head>
<body>
<header>
<nav id="v-nav">
        <img class="logo" src="img/logo.png" alt="">
        <div id="navlist">
            <ul class="tl">
                <li><a href="">Thèmes</a></li>
                <li><a href="">Formateurs</a></li>
                <li><a href="">Planning</a></li>
                <li></li>
            </ul>
        </div>
</nav>
</header>

<?php
$host = 'localhost';
$dbname = 'thagr';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT id, nom, color FROM themes ORDER BY nom";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($themes as $theme) {
        echo '<a href="details.php?theme_id=' . $theme['id'] . '" class="theme-button" style="background-color: ' . $theme['color'] . '" target="_blank">' . $theme['nom'] . '</a>';
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

</body>
</html>
