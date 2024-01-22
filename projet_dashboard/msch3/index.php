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
        <h1 id="currentDateHeading"></h1>
        <button id="prevMonthBtn">Mois précédent</button>
        <button id="nextMonthBtn">Mois suivant</button>
        <div class="current-date-info"></div>

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
                                // Code PHP pour récupérer les formateurs depuis la base de données
                                $conn = new mysqli("localhost", "root", "", "schtf");
                                $formateursQuery = $conn->query("SELECT * FROM formateurs");
                                while ($formateur = $formateursQuery->fetch_assoc()) {
                                    echo "<option value='{$formateur['id']}'>{$formateur['nom']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="themeSelect">Sélectionner un thème :</label>
                            <select class="form-control" id="themeSelect" required>
                                <?php
                                // Code PHP pour récupérer les thèmes depuis la base de données
                                $themesQuery = $conn->query("SELECT * FROM themes");
                                while ($theme = $themesQuery->fetch_assoc()) {
                                    echo "<option value='{$theme['id']}'>{$theme['nom']}</option>";
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

    <!-- Inclure jQuery en premier -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Ensuite, inclure Bootstrap et jQuery UI -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Enfin, inclure votre script.js -->
    <script src="script.js"></script>

    <!-- Script pour afficher la date actuelle dans le h1 -->
    <script>
    </script>

</body>
</html>
