(function($){
	var nomJours = ["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"];
	
	var nomMois = ["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"];
	
	var vacances = [[13, 7, 2009],[14, 7, 2009],[15, 7, 2009],[16, 7, 2009],[17, 7, 2009],[20, 7, 2009],[21, 7, 2009],[22, 7, 2009],[23, 7, 2009],[24, 7, 2009],[23, 12, 2009],[24, 12, 2009],[25, 12, 2009],[26, 12, 2009],[27, 12, 2009],[28, 12, 2009],[29, 12, 2009],[30, 12, 2009],[31, 12, 2009]];
	
	var reunions = [[9, 7, 2009],[12, 8, 2009],[17, 9, 2009],[22, 10, 2009],[26, 11, 2009],[22, 12, 2009]];
	
	var reserver = [[18, 8, 2009]];
			
	setDays = function(day, month, year) {
		var result = [true, '', ''];	//par défaut, on affiche la date
		var boolVacances = false;
		var boolReunion = false;
		var boolLundiMercredi = false;
		var rl = 0;
		var i = 0;
		
		/*
		* Les données peuvent contenir, par erreur, des chevauchements de dates !
		* Priorité des événements : Vacances > Reunion > LundiMercredi > Reserver !
		* Évite l'ajout de la "class" d'un événement non prioritaire !
		*/
		
		if (vacances != null) {
			rl = vacances.length;
		
			for (i = 0; i < rl; i++) {
				if ((day == vacances[i][0]) && (month == vacances[i][1] - 1) && (year == vacances[i][2])) {
					result = [false, '', "On est en vacances !"];
					boolVacances = true;
				}
			}
		}
	
		if ((!boolVacances) && (reunions != null)) {
			rl = reunions.length;
		
			for (i = 0; i < rl; i++) {
				if ((day == reunions[i][0]) && (month == reunions[i][1] - 1) && (year == reunions[i][2])) {
					result = [true, "important", "Réunion !"];
					boolReunion = true;
				}
			}
		}
						
		//getDay() retourne un entier correspondant au jour de la semaine
		// 0 (dimanche), 1 (lundi), 2 (mardi), 3 (mercredi), 4 (jeudi), 5 (vendredi), 6 (samedi)
		var jourSemaine = new Date(year, month, day).getDay();
		
		if ((!boolReunion) && (!boolVacances) && ((jourSemaine == 1) || (jourSemaine == 3))) {
			result = [true, "lundiMercredi", 'Loisirs studieux !'];
			boolLundiMercredi = true;
		}
	
		if ((!boolReunion) && (!boolVacances) && (!boolLundiMercredi) && (reserver != null)) {
			rl = reserver.length;
		
			for (i = 0; i < rl; i++) {
				if ((day == reserver[i][0]) && (month == reserver[i][1] - 1) && (year == reserver[i][2])) {
					result = [true, "reserver", "La salle des jeux est déjà réservée !"];
				}
			}
		}
									
		return result;
	}
	
	selectedDay = function(day, month, year) {
		var boolReunion = false;
		var boolLundiMercredi = false;
		var boolReserver = false;
		var rl = 0;
		var i = 0;
		
		/*
		* Les données peuvent contenir, par erreur, des chevauchements de dates !
		* Priorité des événements : Vacances > Reunion > LundiMercredi > Reserver !
		* Ici on ne s'occupe pas des jours de vacances puisque l'utilisateur ne peut pas les choisir !
		*/
		
		//getDay() retourne un entier correspondant au jour de la semaine
		// 0 (dimanche), 1 (lundi), 2 (mardi), 3 (mercredi), 4 (jeudi), 5 (vendredi), 6 (samedi)
		var jourSemaine = new Date(year, month, day).getDay();
		
		$("#affiche").html("<p>Vous avez sélectionné le " +
												nomJours[jourSemaine] + " " + day + " " + nomMois[month] + " " + year + "</p>");
		
		if (reunions != null) {
			rl = reunions.length;
			
			for (i = 0; i < rl; i++) {
				if ((day == reunions[i][0]) && (month == reunions[i][1] - 1) && (year == reunions[i][2])) {
					$("#important").dialog("open");
					boolReunion = true;
				}
			}
		}
		
		// Il ne faut pas afficher le dialogue lundiMercredi s'il s'agit d'un jour de réunion !
		if ((!boolReunion) && ((jourSemaine == 1) || (jourSemaine == 3))) {
			$("#lundiMercredi").dialog("open");
			boolLundiMercredi = true;
		}
	
		if ((!boolReunion) && (!boolLundiMercredi) && (reserver != null)) {
			rl = reserver.length;
		
			for (i = 0; i < rl; i++) {
				if ((day == reserver[i][0]) && (month == reserver[i][1] - 1) && (year == reserver[i][2])) {
					boolReserver = true;
				}
			}
		}
		
		/*
		* Ce n'est pas un jour de réunion !
		* Ce n'est ni un lundi ni un mercredi !
		* La salle des jeux n'est pas encore réservée !
		* La date n'est pas dépassée !
		*/
		if ((!boolReunion) && (!boolLundiMercredi) && (!boolReserver) && (new Date(year,month,day) >= new Date())) {
			reserver.push([day, month+1, year]);
			$("#affiche").append("<p style='margin-top:12px;'>Votre réservation de la salle des jeux est enregistrée pour la date ci-dessus.</p>");
		} else {
			$("#affiche").append("<p style='margin-top:12px;'>Désolé, la salle des jeux est déjà réservée<br />ou le jour choisi n'est pas disponible (lundi,mercredi et les jours de réunions)<br />ou la date choisie est dépassée.</p>");
		}
		
		//$("#choixDate").mouseover();
	}
			
	$(document).ready(function(){
		$("#lundiMercredi").dialog({
			autoOpen: false,
			height: 240,
			width: 400,
			modal: true,
			overlay: {
				backgroundColor: '#000000',
				opacity: 0.5
			},
			buttons: {
				'Fermer': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				// pour colorer les dates choisies chaque fois que le datepicker se redessine !
				$("#choixDate").mouseover();
			}
		});	
		
		$("#important").dialog({
			autoOpen: false,
			height: 240,
			width: 400,
			modal: true,
			overlay: {
				backgroundColor: '#000000',
				opacity: 0.5
			},
			buttons: {
				'Fermer': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				// pour colorer les dates choisies chaque fois que le datepicker se redessine !
				$("#choixDate").mouseover();
			}
		});	

		$("#choixDate").datepicker({
			numberOfMonths: 3,
			stepMonths: 3,
			showButtonPanel: true,
			currentText: "Aujourd'hui",
			nextText: "Suivant",
			prevText: "Précédent",
			minDate: new Date(2009,7-1,1), //du 1 juillet 2009
			maxDate: new Date(2009,12-1,31), //au 31 décembre 2009
			beforeShowDay: function(date) {
				var noWeekend = $.datepicker.noWeekends(date); //samedi et dimanche non sélectionable !
				//var noWeekend = [true, '', '']; // on garde le samedi et le dimanche !
				
				if (noWeekend[0]) {
					return setDays(date.getDate(), date.getMonth(), date.getFullYear());
				} else {
					return noWeekend;
				}
				
				/*
				* Si parmi les dates à colorer il y a des samedis ou des dimanches alors
				* mettez en commentaire tout ce qui précède et remplacez-le par :
				* return setDays(date.getDate(), date.getMonth(), date.getFullYear());
				*/
			},
			onSelect: function(dateText) {
				//le format de dateText est donné par l'option dateFormat
				//transforme la date donnée au format texte (08082009) en day (8), month (7), year (2009)
				selectedDay(
					parseInt(dateText.slice(0,2),10),
					parseInt(dateText.slice(3,5),10)-1,
					parseInt(dateText.slice(6),10)
				);
			}
		});
	});	//fin document ready
	
	$(window).load(function(){
		/*
		* Bricolage indispensable pour colorer les dates choisies
		* chaque fois que le datepicker se redessine !
		*/
		$("#choixDate").bind("mouseover", function(){
			if ($("td.important > a", this).eq(0).css("color") != "#FF0000") {
				$("td.important > a", this).css("color","#FF0000");
				$("td.lundiMercredi > a", this).css("color","#33CC00");
				$("td.reserver > a", this).css("color","#0033CC");
			}
		});
		$("#choixDate").mouseover();
	});	//fin window load
})(jQuery);