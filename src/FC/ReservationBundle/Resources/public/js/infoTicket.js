$(document).ready(function(){
    // récupère les tickets
    var $listeTickets = $('.tickets');

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

    // todo bouton de suppression d'un ticket

    function addTicket(){
        // création d'un template de ticket
        var template = $('#ticket-container').attr('data-template')
            .replace(/__name__/g, index)
        ;

        var $ticket = $(template);

        $ticket.attr('id', index + 1);
        $ticket.find('.num-ticket').text(index + 1);

        // ajout du ticket au DOM
        $('#add-ticket').before($ticket);

        index ++;
    }


});