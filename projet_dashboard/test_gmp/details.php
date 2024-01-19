<!-- details.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du thème</title>
    <link rel="stylesheet" href="details.css">
</head>
<body>

<header>
    <nav>
        <img class="logo" src="img/logo.png" alt="">
    </nav>
</header>

<main>
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
    
            $queryTheme = "SELECT nom, color FROM themes WHERE id = :theme_id";
            $stmtTheme = $pdo->prepare($queryTheme);
            $stmtTheme->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
            $stmtTheme->execute();
            $theme = $stmtTheme->fetch(PDO::FETCH_ASSOC);
    
            if ($theme) {
                echo '<a href="index.php" class="back-button">Revenir aux thèmes</a>';
                echo '<a href="add_formateur.php?theme_id=' . $themeId . '" class="add-button">Ajouter un formateur</a>';
                echo '<h2 class="theme-title" style="background-color: ' . $theme['color'] . ';">' . $theme['nom'] . '</h2>';
    
                // Barre de recherche
                echo '<input type="text" id="searchInput" onkeyup="searchFormateurs()" placeholder=" Rechercher des formateurs...">';
    
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
    
                echo 'function searchFormateurs() {';
                echo 'var input, filter, ft, span, i, txtValue;';
                echo 'input = document.getElementById("searchInput");';
                echo 'filter = input.value.toUpperCase();';
                echo 'ft = document.getElementsByClassName("formateur-item");';
                echo 'for (i = 0; i < ft.length; i++) {';
                echo 'span = ft[i].getElementsByTagName("span")[0];';
                echo 'txtValue = span.textContent || span.innerText;';
                echo 'if (txtValue.toUpperCase().indexOf(filter) > -1) {';
                echo 'ft[i].style.display = "";';
                echo '} else {';
                echo 'ft[i].style.display = "none";';
                echo '}';
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
</main>

</body>
</html>
