<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Calendrier</h1>
        <button id="prevMonthBtn">Mois précédent</button>
        <button id="nextMonthBtn">Mois suivant</button>
        <div class="calendar"></div>
    </div>

    <!-- Modal pour ajouter un événement -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div style="background-color: #ff8000;" class="modal-header">
                    <h5 class="modal-title" style="color: white;">Ajouter un événement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="form-group">
                            <label for="eventName">Nom de l'événement :</label>
                            <input type="text" class="form-control" id="eventName" required>
                        </div>
                        <div class="form-group">
                            <label for="formateurSelect">Sélectionner un formateur :</label>
                            <select class="form-control" id="formateurSelect" required>
                                <?php
                                    // Connexion à la base de données avec PDO
                                    try {
                                        $pdo = new PDO("mysql:host=localhost;dbname=schtf", "root");
                                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                        // Exemple de requête SQL pour récupérer les formateurs
                                        $stmtFormateurs = $pdo->query("SELECT * FROM formateurs ORDER BY nom");

                                        // Boucle à travers les résultats
                                        while ($formateur = $stmtFormateurs->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$formateur['id']}'>{$formateur['nom']} {$formateur['prenom']}</option>";
                                        }

                                        // Fermer la requête
                                        $stmtFormateurs = null;
                                        // Fermer la connexion à la base de données
                                        $pdo = null;
                                    } catch (PDOException $e) {
                                        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="themeSelect">Sélectionner un thème :</label>
                            <select class="form-control" id="themeSelect" required>
                                <?php
                                    // Connexion à la base de données avec PDO
                                    try {
                                        $pdo = new PDO("mysql:host=localhost;dbname=schtf", "root");
                                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                        // Exemple de requête SQL pour récupérer les thèmes
                                        $stmtThemes = $pdo->query("SELECT * FROM themes");

                                        // Boucle à travers les résultats
                                        while ($theme = $stmtThemes->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$theme['id']}'>{$theme['nom']}</option>";
                                        }

                                        // Fermer la requête
                                        $stmtThemes = null;
                                        // Fermer la connexion à la base de données
                                        $pdo = null;
                                    } catch (PDOException $e) {
                                        echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="startDate">Date de début :</label>
                            <input type="datetime-local" class="form-control" id="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">Date de fin :</label>
                            <input type="datetime-local" class="form-control" id="endDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: #ff8000; border: none;">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
