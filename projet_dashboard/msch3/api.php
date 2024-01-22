<?php
// api.php

// Connexion à la base de données
$host = "localhost";
$username = "root";
$password = "";
$database = "schtf";

$conn = new mysqli($host, $username, $database);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Ajout d'un événement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = $_POST["eventName"];
    $formateurId = $_POST["formateurId"];
    $themeId = $_POST["themeId"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];

    // Utilisation de requêtes préparées pour éviter l'injection SQL
    $sql = "INSERT INTO event (nom, id_formateurs, id_themes, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        // Liaison des paramètres et exécution de la requête
        $stmt->bind_param("siiii", $eventName, $formateurId, $themeId, $startDate, $endDate);
        $stmt->execute();

        // Vérification de l'exécution de la requête
        if ($stmt->affected_rows > 0) {
            echo "Événement ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout de l'événement : " . $stmt->error;
        }

        // Fermer la requête préparée
        $stmt->close();
    } else {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
