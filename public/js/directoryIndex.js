$(document).ready(function() {
    $(".delete").click(function(e){
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");
        if (confirmation == true) {
            return;
        } else {
            e.preventDefault();
        }
    });
});