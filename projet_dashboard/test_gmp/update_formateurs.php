<?php
$host = 'localhost';
$dbname = 'thagr';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['theme_id']) && isset($_POST['formateurs']) && is_array($_POST['formateurs'])) {
            $themeId = $_POST['theme_id'];
            $formateurs = $_POST['formateurs'];

            $queryInsert = "INSERT INTO formateurs_themes (formateur_id, theme_id) VALUES (:formateur_id, :theme_id)";
            $stmtInsert = $pdo->prepare($queryInsert);

            foreach ($formateurs as $formateurId) {
                $stmtInsert->bindParam(':formateur_id', $formateurId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':theme_id', $themeId, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            header("Location: details.php?theme_id=$themeId");
            exit();
        } else {
            echo 'Paramètres du formulaire manquants.';
        }
    } else {
        echo 'Méthode non autorisée.';
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
