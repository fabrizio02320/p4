var dt = new Date();

// Display the month, day, and year. getMonth() returns a 0-based number.
var month = dt.getMonth()+1;
var day = dt.getDate();
var year = dt.getFullYear();

$("#choixDate").datepicker({
    closeText: 'Fermer',
    prevText: '<Préc',
    nextText: 'Suiv>',
    currentText: 'Courant',
    monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
    'Jul','Aoû','Sep','Oct','Nov','Déc'],
    dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
    dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
    dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
    dateFormat: 'dd/mm/yy', firstDay: 1,
    isRTL: false,
    beforeShowDay: function (date) {
        if(date.getDay() === 0 || date.getDay() === 2) { // la semaine commence le dimanche
            return [false, ''];
        } else {
            return [true, ''];
        }
    }, 
    minDate: new Date(2017,12-1,7), 
});