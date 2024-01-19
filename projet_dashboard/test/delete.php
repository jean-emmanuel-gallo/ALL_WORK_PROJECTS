<?php
$host = 'localhost';
$dbname = 'thagr';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['formateur_id']) && isset($_GET['theme_id'])) {
        $formateur_id = $_GET['formateur_id'];
        $theme_id = $_GET['theme_id'];
    
        $query = "DELETE FROM formateurs_themes WHERE formateur_id = :formateur_id AND theme_id = :theme_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':formateur_id', $formateur_id, PDO::PARAM_INT);
        $stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
        $stmt->execute();
    
        echo "L'association formateur-thème a été supprimée avec succès.";
    
        header("Location: details.php?theme_id=" . $theme_id);
        exit();
    } else {
        echo "Paramètres de l'URL manquants.";
    }

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
