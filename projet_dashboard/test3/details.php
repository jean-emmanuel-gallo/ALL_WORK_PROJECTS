<!-- details.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du thème</title>
    <link rel="stylesheet" href="details.css">
</head>
<body>

<?php
$host = 'localhost';
$dbname = 'thagr';
$user = 'root';
$password = '';

// Fonction pour récupérer le nombre total de formateurs associés au thème
function getTotalFormateursCount($pdo, $themeId) {
    $queryCount = "SELECT COUNT(*) FROM formateurs_themes WHERE theme_id = :theme_id";
    $stmtCount = $pdo->prepare($queryCount);
    $stmtCount->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
    $stmtCount->execute();
    return $stmtCount->fetchColumn();
}

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
            echo '<header>';
            echo '    <nav>';
            echo '        <img class="logo" src="img/logo.png" alt="">';
            echo '    </nav>';
            echo '</header>';

            echo '<main>';
            echo '    <a href="index.php" class="back-button">Revenir aux thèmes</a>';
            echo '    <a href="add_formateur.php?theme_id=' . $themeId . '" class="add-button">Ajouter un formateur</a>';
            echo '    <h2 class="theme-title" style="background-color: ' . $theme['color'] . ';">' . $theme['nom'] . '</h2>';
            echo '    <input type="text" id="searchInput" onkeyup="searchFormateurs()" placeholder=" Rechercher des formateurs...">';

            // Récupérer les lettres initiales des noms des formateurs
            $queryInitials = "SELECT DISTINCT LEFT(UPPER(f.nom), 1) as initial
                FROM formateurs_themes ft
                JOIN formateurs f ON ft.formateur_id = f.id
                WHERE ft.theme_id = :theme_id
                ORDER BY initial";
            $stmtInitials = $pdo->prepare($queryInitials);
            $stmtInitials->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
            $stmtInitials->execute();
            $initials = $stmtInitials->fetchAll(PDO::FETCH_COLUMN);

            // Afficher les liens pour chaque lettre
            echo '<div class="pagination">';
            foreach ($initials as $initial) {
                echo '<a class="pagination" href="?theme_id=' . $themeId . '&initial=' . $initial . '">' . $initial . '</a>';
            }
            echo '</div>';

            // Filtrer les formateurs en fonction de la lettre sélectionnée
            $selectedInitial = isset($_GET['initial']) ? $_GET['initial'] : null;

            $queryFormateurs = "SELECT f.id as formateur_id, f.nom as formateur_nom, f.prenom as formateur_prenom
            FROM formateurs_themes ft
            JOIN formateurs f ON ft.formateur_id = f.id
            WHERE ft.theme_id = :theme_id";

            if ($selectedInitial) {
                $queryFormateurs .= " AND LEFT(UPPER(f.nom), 1) = :initial";
            }

            $queryFormateurs .= " ORDER BY f.nom, f.prenom";

            $stmtFormateurs = $pdo->prepare($queryFormateurs);

            if ($selectedInitial) {
                $stmtFormateurs->bindParam(':initial', $selectedInitial, PDO::PARAM_STR);
            }

            $stmtFormateurs->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
            $stmtFormateurs->execute();
            $formateurs = $stmtFormateurs->fetchAll(PDO::FETCH_ASSOC);

            echo '    <div class="ftop">';
            foreach ($formateurs as $formateur) {
                echo '        <div class="formateur-item">';
                echo '            <span>' . $formateur['formateur_nom'] . ' ' . $formateur['formateur_prenom'] . '</span>';
                echo '            <button class="delete-button" onclick="deleteFormateur(' . $formateur['formateur_id'] . ', ' . $themeId . ')">Supprimer</button>';
                echo '        </div>';
            }
            echo '    </div>';

            echo '    <script>';
            echo '        function deleteFormateur(formateurId, themeId) {';
            echo '            if (confirm("Voulez-vous vraiment supprimer ce formateur ?")) {';
            echo '                window.location.href = "delete.php?formateur_id=" + formateurId + "&theme_id=" + themeId;';
            echo '            }';
            echo '        }';
    
            echo '        function searchFormateurs() {';
            echo '            var input, filter, ft, span, i, txtValue;';
            echo '            input = document.getElementById("searchInput");';
            echo '            filter = input.value.toUpperCase();';
            echo '            ft = document.getElementsByClassName("formateur-item");';
            echo '            for (i = 0; i < ft.length; i++) {';
            echo '                span = ft[i].getElementsByTagName("span")[0];';
            echo '                txtValue = span.textContent || span.innerText;';
            echo '                if (txtValue.toUpperCase().indexOf(filter) > -1) {';
            echo '                    ft[i].style.display = "";';
            echo '                } else {';
            echo '                    ft[i].style.display = "none";';
            echo '                }';
            echo '            }';
            echo '        }';
            echo '    </script>';
            
            echo '</main>';
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
