document.addEventListener('DOMContentLoaded', function () {
    const calendar = document.querySelector('.calendar');
    const prevButton = document.getElementById('prevMonthBtn');
    const nextButton = document.getElementById('nextMonthBtn');
    const currentDateElement = document.getElementById('currentDateHeading');

    const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    const daysOfWeek = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

    let currentDate = new Date();
    let originalDate = new Date(currentDate);

    function generateCalendar(year, month) {
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const numDaysInMonth = lastDayOfMonth.getDate();
        const startingDay = firstDayOfMonth.getDay();

        let html = `<h2>${monthNames[month]} ${year}</h2>`;
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
                    const currentDateCell = new Date(year, month, dayCount);
                    currentDateCell.setHours(0, 0, 0, 0);

                    let className = '';
                    const currentMonth = currentDateCell.getMonth();
                    const currentYear = currentDateCell.getFullYear();

                    if (currentMonth === month && currentYear === year) {
                        className = 'current-month';
                    } else {
                        className = 'other-month';
                    }

                    if (currentDateCell.getTime() === currentDate.getTime()) {
                        className += ' current-date';
                        currentDateElement.textContent = `Nous sommes le ${currentDateCell.getDate()} ${monthNames[currentMonth]}`;
                    }

                    html += `<td class="${className}">${dayCount}</td>`;
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

    function changeMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    }

    function updateModalTitle(selectedDate) {
        const modalTitle = document.querySelector('.modal-title');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = selectedDate.toLocaleDateString('fr-FR', options);
        modalTitle.textContent = `Ajouter un événement le ${formattedDate}`;
    }

    function updateCurrentDateElement() {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = currentDate.toLocaleDateString('fr-FR', options);
        currentDateElement.textContent = `Bienvenue ! Nous sommes le ${formattedDate}`;
    }

    function handleEventFormSubmission() {
        const selectedFormateur = $('#formateurSelect').val();
        const selectedTheme = $('#themeSelect').val();
        const eventName = $('#eventName').val();
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        const eventData = {
            eventName: eventName,
            formateurId: selectedFormateur,
            themeId: selectedTheme,
            startDate: startDate,
            endDate: endDate
        };

        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: eventData,
            success: function (response) {
                console.log(response);
                generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            },
            error: function (error) {
                console.error(error);
            }
        });

        $('#eventModal').modal('hide');
    }

    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

    prevButton.addEventListener('click', function () {
        changeMonth(-1);
    });

    nextButton.addEventListener('click', function () {
        changeMonth(1);
    });

    calendar.addEventListener('click', function (event) {
        if (event.target.tagName === 'TD' && event.target.classList.contains('current-month')) {
            const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(event.target.textContent));
            updateModalTitle(selectedDate);
            $('#eventModal').modal('show');
        }
    });

    $('#eventForm').submit(function (event) {
        event.preventDefault();
        handleEventFormSubmission();
    });

    updateCurrentDateElement();
});
