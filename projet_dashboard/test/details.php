<!-- details.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du thème</title>
    <link rel="stylesheet" href="details.css">
    <!-- <style>
        .formateur-item {
            display: inline-block;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 5px;
            margin-right: 20px;
            margin-left: 20px;
        }

        .delete-button {
            background-color: #ff6666;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #2ecc71;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        
    </style> -->
</head>
<body>

<?php
$host = 'localhost';
$dbname = 'thagr';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['theme_id'])) {
        $themeId = $_GET['theme_id'];

        // Récupérer le nom du thème
        $queryTheme = "SELECT nom FROM themes WHERE id ORDER BY nom= :theme_id";
        $stmtTheme = $pdo->prepare($queryTheme);
        $stmtTheme->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
        $stmtTheme->execute();
        $theme = $stmtTheme->fetch(PDO::FETCH_ASSOC);

        if ($theme) {
            echo '<a href="index.php" class="back-button">Revenir aux thèmes</a>';
            echo '<a href="add_formateur.php?theme_id=' . $themeId . '" class="add-button">Ajouter un formateur</a>';
            echo '<h2>' . $theme['nom'] . '</h2>';

            // Récupérer les formateurs associés au thème
            $queryFormateurs = "SELECT f.id as formateur_id, f.nom as formateur_nom, f.prenom as formateur_prenom
            FROM formateurs_themes ft
            JOIN formateurs f ON ft.formateur_id = f.id
            WHERE ft.theme_id = :theme_id
            ORDER BY f.nom, f.prenom";
            $stmtFormateurs = $pdo->prepare($queryFormateurs);
            $stmtFormateurs->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
            $stmtFormateurs->execute();
            $formateurs = $stmtFormateurs->fetchAll(PDO::FETCH_ASSOC);

            echo '<div class="ftop">';
            foreach ($formateurs as $formateur) {
                echo '<div class="formateur-item">';
                echo '<span>' . $formateur['formateur_nom'] . ' ' . $formateur['formateur_prenom'] . '</span>';
                echo '<button class="delete-button" onclick="deleteFormateur(' . $formateur['formateur_id'] . ', ' . $themeId . ')">Supprimer</button>';
                echo '</div>';
            }
            echo '</div>';

            echo '<script>';
            echo 'function deleteFormateur(formateurId, themeId) {';
            echo 'if (confirm("Voulez-vous vraiment supprimer ce formateur ?")) {';
            echo 'window.location.href = "delete.php?formateur_id=" + formateurId + "&theme_id=" + themeId;';
            echo '}';
            echo '}';
            echo '</script>';
        } else {
            echo 'Thème non trouvé.';
        }
    } else {
        echo 'ID du thème non spécifié.';
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

</body>
</html>
