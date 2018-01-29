$(document).ready(function(){
    // récupère les tickets
    var $listeTickets = $('.tickets');

    console.log($listeTickets);
    // compte  de nombre de tickets existant
    var index = $listeTickets.length;

    // création du bouton d'ajout
    var btnAdd = $('<button id="add-ticket" class="btn btn-default">Ajouter un ticket</button>');

    // ajout du bouton d'ajout juste avant le bouton suivant du formulaire
    $('.commande-submit:first').before(btnAdd);

    // modifie le comportement du bouton d'ajout
    btnAdd.click(function(e){
        e.preventDefault();
        addTicket(index);
    });

    // modifie l'attribut id du ticket pour y accéder plus facilement et renumérote le texte
    for(var i = 0; i < index; i++){
        $listeTickets[i].id = i + 1;
        $listeTickets[i].querySelector('.num-ticket').textContent = i + 1;
    }

    // ajoute les boutons de suppression d'un ticket
    for(var j = 1; j <= index; j++){
        addBtnDelTicket($('#' + j));
    }

    function addTicket(){
        // création d'un template de ticket
        var template = $('#ticket-container').attr('data-template')
            .replace(/__name__/g, index)
        ;

        var $ticket = $(template);

        $ticket.attr('id', index + 1);
        $ticket.find('.num-ticket').text(index + 1);

        // ajoute le bouton supprimer
        addBtnDelTicket($ticket);

        // ajout du ticket au DOM
        $('.tickets:last').after($ticket);

        // ajoute le bouton supprimer s'il n'y avait qu'un ticket au départ
        if(index === 1){
            addBtnDelTicket($('.tickets:first'));
        }

        // mets à jour le nb de tickets
        index ++;
    }

    function addBtnDelTicket($element){
        // création du bouton
        var $btnDelTicket = $('<a href="#" class="btn btn-danger del-ticket">Supprimer ce ticket</a>');

        // ajout du bouton supprimer à la fin de l'élément
        $element.append($btnDelTicket);

        $btnDelTicket.click(function(e){
            e.preventDefault();

            $element.remove();

            // mets à jour le nombre de tickets
            index--;

            // suite au retrait, on réindex les id et mets à jour le texte
            var $listeTickets = $('.tickets');
            for(var i = 0; i < index; i++){
                $listeTickets[i].id = i + 1;
                $listeTickets[i].querySelector('.num-ticket').textContent = i + 1;
            }

            // s'il ne reste plus qu'un ticket, retire le bouton supprimer
            if(index === 1){
                $('.tickets').find('.del-ticket').remove();
            }
        })
    }

});