const sessioneMessage = 'Sessione scaduta, procedere al <a href="signin.php" class="alert-link">login</a>';
const occupatoMessage = 'Alcuni degli slot selezionati sono stati prenotati da altri utenti';

// Show error messages
function showErrorMessage(type) {
    errorModal = $('#exampleModalCenter');
    title = type === "sessione" ? "Sessione scaduta" : "Slot occupato";
    body = type === "sessione" ? sessioneMessage : occupatoMessage;
    $(".modal-title").text(title);
    $(".modal-body").html(body);
    errorModal.modal("show");
}

$(document).ready(function () {
    //$("#table-owner").load("prenotazioni.php", { load: true });
    // Cookie watcher
    setInterval(function () {
        if (!$.cookie("PHPSESSID") && !$(".jumbotron").length) {
            $("body").html("");
            $("body").load("nocookie.html");
        }
    }, 2000);
    // handler hover offerta
    $("#off-value").hover(function(){
        // handler hover in
        $(this).attr("title", "email@example.com");
    }
    , function(){
        // handler hover out
        $(this).attr("title", "");
    });
    // Handler prenota
    $("#prenota").click(function () {
        $.ajax({
            type: "POST",
            url: "prenotazioni.php",
            data: { prenota: true, prenotazioni: prenotazioni },
            success: function (response) {
                if (response === "scaduta") {
                    showErrorMessage("sessione");
                } else if (response === "occupato") {
                    showErrorMessage("slot");
                    $("#table-owner").load("prenotazioni.php", { load: true });
                } else if (response === "error-db") {
                    $("#table-owner").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                } else {
                    for (pre of prenotazioni) {
                        tokens = pre.split("-");
                        tokensResp = response.split(",");
                        giorno = tokens[0];
                        ora = tokens[1] + ":" + tokens[2];
                        el = $("[data-giorno='" + giorno + "'][data-ora='" + ora + "']");
                        el.attr("data-email", tokensResp[0]);
                        el.attr("data-timestamp", tokensResp[1]);
                        el.attr("class", "table-orange");
                    }
                }
                prenotazioni = []; // azzero prenotazioni in ogni caso
            }
        });
    });
    // Validate signup form
    $("#supf").submit(function (event) {
        rgx_robusta = /^(?=(.*\d)+)(?=(.*[!@#$%.-]){1})[0-9a-zA-Z!@#$%.-]{3,}$/;
        rgx_email = /^\w+@\w+\.\w{2,3}$/;
        email = $("#email").val();
        pass1 = $("#pass").val();
        pass2 = $("#pass2").val();
        console.log("email:", email);
        if (!email || email === "" || !rgx_email.test(email)) {
            event.preventDefault();
            $("#email")[0].setCustomValidity("Email non valida");
        }
        if (pass1 !== pass2) {
            event.preventDefault();
            $("#pass2")[0].setCustomValidity("le due password non coincidono");
        }
        if (!rgx_robusta.test(pass1)) {
            event.preventDefault();
            $("#pass")[0].setCustomValidity("password non valida");
        }
    });
});