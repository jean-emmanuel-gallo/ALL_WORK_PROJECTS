document.addEventListener('DOMContentLoaded', function () {
    const calendar = document.querySelector('.calendar');
    const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const daysOfWeek = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

    let currentDate = new Date(2024, 0, 19); // La date actuelle (19/01/2024)

    function generateCalendar(year, month) {
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const numDaysInMonth = lastDayOfMonth.getDate();
        const startingDay = firstDayOfMonth.getDay();

        let html = `<h2>${monthNames[month]} ${year}</h2>`; // Affichage du mois et de l'année dans le titre
        html += '<table class="table">';
        html += '<thead><tr>';
        for (let day of daysOfWeek) {
            html += `<th>${day}</th>`;
        }
        html += '</tr></thead><tbody>';

        let dayCount = 1;
        for (let i = 0; i < 6; i++) {
            html += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < startingDay) {
                    html += '<td></td>';
                } else if (dayCount > numDaysInMonth) {
                    break;
                } else {
                    const currentDateCell = new Date(year, month, dayCount); // Création de la date correspondante
                    const currentDay = currentDateCell.getDate();
                    const currentMonth = currentDateCell.getMonth();
                    const currentYear = currentDateCell.getFullYear();

                    let className = '';

                    if (currentMonth === month && currentYear === year) {
                        className = 'current-month';
                    } else {
                        className = 'other-month';
                    }

                    if (
                        currentDay === currentDate.getDate() &&
                        currentMonth === currentDate.getMonth() &&
                        currentYear === currentDate.getFullYear()
                    ) {
                        // Ajouter une classe spécifique pour le style autour de la date actuelle
                        className += ' current-date';
                    }

                    html += `<td class="${className}">${currentDay}</td>`;
                    dayCount++;
                }
            }
            html += '</tr>';
            if (dayCount > numDaysInMonth) {
                break;
            }
        }

        html += '</tbody></table>';
        calendar.innerHTML = html;
    }

    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

    // Fonction pour changer de mois
    function changeMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    }

    // Boutons pour naviguer entre les mois
    const prevButton = document.getElementById('prevMonthBtn');
    prevButton.addEventListener('click', function () {
        changeMonth(-1);
    });

    const nextButton = document.getElementById('nextMonthBtn');
    nextButton.addEventListener('click', function () {
        changeMonth(1);
        currentDate.setDate(currentDate.getDate() + 1); // Déplacer la date d'un jour pour afficher la date de demain
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });

    // Écouter le clic sur une date et afficher la modale d'ajout d'événement
    calendar.addEventListener('click', function (event) {
        if (event.target.tagName === 'TD' && event.target.classList.contains('current-month')) {
            $('#eventModal').modal('show');
        }
    });

    // Soumettre le formulaire pour ajouter un événement
    $('#eventForm').submit(function (event) {
        event.preventDefault();
        const selectedFormateur = $('#formateurSelect').val();
        // Récupérer d'autres détails de l'événement ici

        $('#eventModal').modal('hide');
        // Code pour sauvegarder l'événement avec le formateur et autres détails
    });
});
