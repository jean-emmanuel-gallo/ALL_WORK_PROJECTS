<!-- add_formateur.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un formateur</title>
    <link rel="stylesheet" href="add.css">
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
        $queryTheme = "SELECT nom FROM themes WHERE id = :theme_id";
        $stmtTheme = $pdo->prepare($queryTheme);
        $stmtTheme->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
        $stmtTheme->execute();
        $theme = $stmtTheme->fetch(PDO::FETCH_ASSOC);

        if ($theme) {
            echo '<h2>Ajouter des formateurs au thème : ' . $theme['nom'] . '</h2>';

            // Récupérer tous les formateurs
            $queryFormateurs = "SELECT id, nom, prenom FROM formateurs";
            $stmtFormateurs = $pdo->prepare($queryFormateurs);
            $stmtFormateurs->execute();
            $formateurs = $stmtFormateurs->fetchAll(PDO::FETCH_ASSOC);

            echo '<form action="update_formateurs.php" method="post">';
            echo '<input type="hidden" name="theme_id" value="' . $themeId . '">';

            // Barre de recherche
            echo '<input type="text" id="searchInput" oninput="filterFormateurs()" placeholder="Rechercher un formateur...">';

            // Nouvelle structure pour envelopper les formateurs
            echo '<div id="formateurs-container">';
            
            foreach ($formateurs as $formateur) {
                // Vérifier si le formateur est déjà associé au thème
                $queryAssociation = "SELECT COUNT(*) as count FROM formateurs_themes WHERE formateur_id = :formateur_id AND theme_id = :theme_id";
                $stmtAssociation = $pdo->prepare($queryAssociation);
                $stmtAssociation->bindParam(':formateur_id', $formateur['id'], PDO::PARAM_INT);
                $stmtAssociation->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
                $stmtAssociation->execute();
                $association = $stmtAssociation->fetch(PDO::FETCH_ASSOC);

                $isAssigned = $association['count'] > 0;

                echo '<div class="formateur-item" id="formateur_' . $formateur['id'] . '">';
                echo '<span>';
                echo '<input type="checkbox" name="formateurs[]" value="' . $formateur['id'] . '" ' . ($isAssigned ? 'disabled' : '') . '>';
                echo ' ' . $formateur['nom'] . ' ' . $formateur['prenom'];
                echo '</span>';
                echo '<button class="add-button" type="submit" ' . ($isAssigned ? 'disabled' : '') . '>Ajouter</button>';
                echo '</div>';
            }

            echo '</div>';
            echo '</form>';
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

<script>
    function filterFormateurs() {
        var input, filter, container, formateurs, i, span, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        container = document.getElementById("formateurs-container");
        formateurs = container.getElementsByClassName("formateur-item");
        for (i = 0; i < formateurs.length; i++) {
            span = formateurs[i].getElementsByTagName("span")[0];
            txtValue = span.textContent || span.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                formateurs[i].style.display = "";
            } else {
                formateurs[i].style.display = "none";
            }
        }
    }
</script>

</body>
</html>
