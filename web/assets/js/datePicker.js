var dt = new Date();

// Display the month, day, and year. getMonth() returns a 0-based number.
var month = dt.getMonth()+1;
var day = dt.getDate();
var year = dt.getFullYear();

// désactivation des dates en fonction du jour de la semaine (ex: 0 pour dimanche)
var disabledDayNumber = [''];

// désactivation des dates en fonction du mois + jour (ex: 05/01 pour tous les 1er mai)
var disabledDateRecurring = [''];

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
        return activatedDate(date);

    }, 
    minDate: new Date(year, month -1, day),

    onSelect: function(dateText) {
        //le format de dateText est donné par l'option dateFormat
        selectedDay(
            parseInt(dateText.slice(0,2),10),
            parseInt(dateText.slice(3,5),10)-1,
            parseInt(dateText.slice(6),10)
        );
    },
});

var nomJours = ["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"];
var nomMois = ["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"];

// affiche la date sélectionnée dans la div "afficheChoixDate"
function selectedDay(day, month, year) {
    var jourSemaine = new Date(year, month, day).getDay();

    $("#afficheChoixDate").html("Vous avez sélectionné le " +
        nomJours[jourSemaine] + " " + day + " " + nomMois[month] + " " + year);
}

// fonction qui retourne un tableau [true, ''] ou [false, ''] si la date doit être activé ou non dans le datePicker
function activatedDate(date) {
    // partie concernant un jour dans la semaine (Dimanche commence à 0)
    var jourOff = [0, 2];
    if($.inArray(date.getDay(), jourOff) > -1) {
        return [false, ''];
    }

    // partie concernant une date récurrente tous les ans au format J/M
    var jourRecurrent = [
        '1/5',
        '1/11',
        '25/12',
    ];
    var dateJourRecurrent = date.getDate() + '/' + (date.getMonth() + 1);
    if($.inArray(dateJourRecurrent, jourRecurrent) > -1){
        return [false, ''];
    }

    // sinon, la date est activée
    return [true, ''];
}