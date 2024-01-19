<?php
// Remplacez les paramètres de connexion par les vôtres
$servername = "localhost";
$username = "localhost";
$password = "";
$dbname = "schtf";

try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}
?>
