//ajout d'un utilisateur via ajax et modal
$("#submit").click(function AddAdmin() {

    var username = $('#username').val();
    var pattern = new RegExp ("^[<>/&$]$","g");
    if(!pattern.test(username) && username!=""){
        $.ajax({
        url: "/Admin/NouveauAdmins",
        type: "POST",
        data:{
            username: $('#username').val(),
            _token: $('#_token').val()
        },
        async:      true,
        success: function(data, status) 
        {
            var html = '<h2>Identifiant : ' + data.login + '</h2><br><h2>Mot de passe : ' + data.pwd + '</h2>';
                
            $('#ajax-modal').html(html);
        },
        error: function (xhr, ajaxOptions, thrownError)
        {
            $('#ajax-modal').html('Error: ' + xhr.status);
            console.log(thrownError);
        }
    });
    }
    else{
        $('#modalLabel2').html('La création a échoué');
        var html = '<h5>Veuillez entrer un identifiant valide. Les caractères "<, >, &, $ et /" ne sont pas autorisés."</h5>';
                
        $('#ajax-modal').html(html);
    }
});