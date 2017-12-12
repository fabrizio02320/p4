/* ***** SCRIPT POUR LA PAGE INDEX ***** */

/* ***** CHOIX DE LA DATE ***** */
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
        return activatedDate(date);

    }, 
    minDate: new Date(year, month -1, day),
    maxDate: new Date(year + 1, month -1, day),

    onSelect: function(dateText) {
        //le format de dateText est donné par l'option dateFormat
        var dateSelected = selectedDay(
            parseInt(dateText.slice(0,2),10),
            parseInt(dateText.slice(3,5),10)-1,
            parseInt(dateText.slice(6),10)
        );

        // suite du script, choix du type de billet
        choixTypeBillet(dateSelected);
    },
});

var nomJours = ["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"];
var nomMois = ["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"];

// affiche la date sélectionnée dans la div "afficheChoixDate"
function selectedDay(day, month, year) {
    var maDate = new Date(year, month, day);
    var jourSemaine = maDate.getDay();

    // affiche le jour sélectionné sous le datePicker
    $("#afficheChoixDate").html("Vous avez sélectionné le " +
        nomJours[jourSemaine] + " " + day + " " + nomMois[month] + " " + year);

    return maDate;
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
        '1/1',
        '1/5',
        '8/5',
        '14/7',
        '15/8',
        '1/11',
        '11/11',
        '25/12',
    ];
    var dateJourRecurrent = date.getDate() + '/' + (date.getMonth() + 1);
    if($.inArray(dateJourRecurrent, jourRecurrent) > -1){
        return [false, ''];
    }

    // partie concernant des dates fixés à l'avance (avec l'année)
    var jourFixe = [
        '2/4/2018',
        '10/5/2018',
        '21/5/2018',
    ];
    var dateJourFixe = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
    if($.inArray(dateJourFixe, jourFixe) > -1){
        return [false, ''];
    }

    // vérifie si le nombre de billet maximum sur la journée est atteint
    // TODO

    // sinon, la date est activée
    return [true, ''];
}

/* ***** CHOIX DU TYPE DE BILLET ***** */
function choixTypeBillet(dateSelected) {
    // Récupère le nombre de billet autorisé sur la journée
    // TODO
    var nbBilletMax = 6;

    // création de l'objet qui contiendra la liste déroulante
    if(nbBilletMax > 0){
    }
}