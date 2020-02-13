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
    $("#text-danger").html("");
    err_cont = $("#err-cont");
    if (err_cont) {
        err_cont.html("");
    }
    if ($("#table").length) {
        console.log("entro in table");
        $.ajax({
            type: "GET",
            url: "offerta.php?table=true",
            success: function (response) {
                if (response === "scaduta") {
                    showErrorMessage("sessione");
                } else if (response === "error-db") {
                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                } else {
                    tokens = response.split(";");
                    if (tokens.length === 2) {
                        if (tokens[0] === "best") {
                            $("#best-offer").html("Attualmente sei il miglior offerente");
                        }
                        $("#table").html(tokens[1]);
                    }
                }

            }
        });
    }
    if ($("#off-value").length) {
        $.ajax({
            type: "GET",
            url: "offerta.php",
            success: function (response) {
                if (response) {
                    if (response === "error-db") {
                        $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                    } else {
                        tokens = response.split("-");
                        user = tokens[0];
                        amount = tokens[1];
                        timestamp = tokens[2];
                        $("#off-value").html("" + amount + "&euro;");
                    }
                }
            }
        });
    }
    // Cookie watcher
    setInterval(function () {
        if (!$.cookie("PHPSESSID") && !$(".jumbotron").length) {
            $("body").html("");
            $("body").load("nocookie.html");
        }
    }, 2000);
    // handler hover offerta
    $("#off-value").hover(function () {
        // handler hover in
        $.ajax({
            type: "GET",
            url: "offerta.php",
            success: function (response) {
                if (response === "scaduta") {
                    showErrorMessage("sessione");
                } else if (response === "error-db") {
                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                } else {
                    tokens = response.split("-");
                    user = tokens[0];
                    amount = tokens[1];
                    timestamp = tokens[2];
                    $("#off-value").html("" + amount + "&euro;");
                    $("#off-value").attr("title", user);
                }
            }
        });
    }
        , function () {
            // handler hover out
            $(this).attr("title", "");
        });
    // Handler offri
    $("#offri").click(function () {
        $("#off-error").html("");
        off_rgx = /^\d{1,9}\.\d{2}$/;
        off_txt = $("#off-value").text();
        off_value = off_txt.substring(0, off_txt.length - 1);
        offer = $("#offerta").val();
        if (off_value && off_rgx.test(offer) && offer > +off_value) {
            $.ajax({
                type: "POST",
                url: "offerta.php",
                data: { offri: true, value: offer },
                success: function (response) {
                    if (response === "scaduta") {
                        showErrorMessage("sessione");
                    } else if (response === "error-db") {
                        $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                    } else {
                        if (response === "1") {
                            $("#off-value").html(offer + "&euro;");
                        } else {
                            $("#off-error").html("offerta non valida o minore del massimo attuale (ricaricare massimo in tal caso)");
                        }
                    }
                }
            });
        } else {
            // offerta non valida o minore del massimo attuale (ricaricare massimo)
            $("#off-error").html("offerta non valida o minore del massimo attuale (ricaricare massimo in tal caso)");
        }

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