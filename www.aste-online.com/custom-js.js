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
        url = $("#table").data("personal") ? "prenotazione.php?table=true&personal=true" : "prenotazione.php?table=true";
        console.log("url:", url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                if (response === "error-db") {
                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                } else {
                    $("#table").html(response);
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
    $(document).on('mouseenter', '#table tr', function () {
        // handler hover in
        element = $(this);
        id = element.data("id");
        $.ajax({
            type: "GET",
            url: "prenotazione.php?id=" + id,
            success: function (response) {
                if (response === "scaduta") {
                    showErrorMessage("sessione");
                } else if (response === "error-db") {
                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                } else {
                    user = response;
                    element.attr("data-toggle", "tooltip");
                    element.attr("data-placement", "left");
                    element.attr("title", user);
                }
            }
        });
    });
    // Handler prenota
    $("#prenota").click(function () {
        $("#pre-error").html("");
        hmin_rgx = /^\d{1,2}$/;
        pers_rgx = /^\d{1,3}$/;
        ini_hh = +$("#ini-hh").val();
        ini_min = +$("#ini-mm").val();
        fin_hh = +$("#fin-hh").val();
        fin_min = +$("#fin-mm").val();
        npers = $("#npers").val();
        console.log("finhh - inihh:", (ini_hh <= fin_hh));
        if (hmin_rgx.test(ini_hh) && hmin_rgx.test(ini_min)
            && hmin_rgx.test(fin_hh) && hmin_rgx.test(fin_min)
            && ini_hh < 24 && ini_hh >= 0
            && fin_hh < 24 && fin_hh >= 0
            && (ini_hh * 60 + ini_min) < (fin_hh * 60 + fin_min)
            && pers_rgx.test(npers) && npers > 0) {
            $.ajax({
                type: "POST",
                url: "prenotazione.php",
                data: { offri: true, hhi: ini_hh, mmi: ini_min, hhf: fin_hh, mmf: fin_min, pers: npers },
                success: function (response) {
                    if (response === "scaduta") {
                        showErrorMessage("sessione");
                    } else if (response === "error-db") {
                        $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                    } else if (response === "too-much") {
                        $("#pre-error").html("superato limite massimo posti");
                    } else if (response === "err-time") {
                        $("#pre-error").html("errore orari");
                    } else {
                        if ($("#table").length) {
                            $.ajax({
                                type: "GET",
                                url: "prenotazione.php?table=true",
                                success: function (response) {
                                    if (response === "error-db") {
                                        $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                                    } else {
                                        $("#table").html(response);
                                    }

                                }
                            });
                        }
                    }
                }
            });
        } else {
            $("#pre-error").html("prenotazione non valida");
        }

    });

    // handler elimina
    $(document).on("click", "td > button", function () {
        id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: "prenotazione.php?id=" + id,
            success: function (response) {
                if (response === "scaduta") {
                    showErrorMessage("sessione");
                } else if (response === "db-error") {
                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                } else if (response === "invalid") {
                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Non puoi eliminare questa prenotazione</div>');
                } else {
                    if ($("#table").length) {
                        $.ajax({
                            type: "GET",
                            url: "prenotazione.php?table=true",
                            success: function (response) {
                                if (response === "error-db") {
                                    $("#err-cont").html('<div class="alert alert-danger" role="alert">Errore di connessione al DB</div>');
                                } else {
                                    $("#table").html(response);
                                }

                            }
                        });
                    }
                }
            }
        });
    });

    // Validate signup form
    $("#supf").click(function (event) {
        //event.preventDefault();
        user = $("#user").val();
        pass1 = $("#pass").val();
        pass2 = $("#pass2").val();
        if (pass1 !== pass2) {
            //event.preventDefault();
            $("#pass2")[0].setCustomValidity("le due password non coincidono");
        }

        $.ajax({
            type: "POST",
            url: "auth.php",
            data: { user: user, pass: pass1, pass2: pass2 },
            success: function (response) {
                err_code = response.split("-");
                if (err_code[0] === "user") {
                    $("#user")[0].setCustomValidity(err_code[1]);
                } else if (err_code[0] === "pass") {
                    $("#pass")[0].setCustomValidity(err_code[1]);
                } else if (err_code[0] === "pass2") {
                    $("#pass2")[0].setCustomValidity(err_code[1]);
                } else {
                    prev_path = window.location.pathname;
                    tokens = prev_path.split("/");
                    tokens[tokens.length - 1] = "index.php";
                    window.location.pathname = tokens.join("/");
                }
            }
        });

    });

    // login
    $("#signin").click(function () {
        user = $("#user").val();
        pass = $("#pass").val();
        if (user && pass) {
            if (user === "" && pass === "") {
                $("#sign_err").html("Inserire credenziali");
                return;
            }
            if (user === "") {
                $("#user")[0].setCustomValidity("inserire username");
                return;
            }
            if (pass === "") {
                $("#pass")[0].setCustomValidity("inserire password");
                return;
            }

            $.ajax({
                type: "POST",
                url: "auth.php",
                data: { user: user, password: pass },
                success: function (response) {
                    err_code = response.split("-");
                    if (err_code[0] === "user") {
                        $("#user")[0].setCustomValidity(err_code[1]);
                    } else if (err_code[0] === "pass") {
                        $("#pass")[0].setCustomValidity(err_code[1]);
                    } else if (err_code[0] === "cred") {
                        $("#sign_err").html(err_code[1]);
                    } else {
                        prev_path = window.location.pathname;
                        tokens = prev_path.split("/");
                        tokens[tokens.length - 1] = "index.php";
                        window.location.pathname = tokens.join("/");
                    }
                }
            });

        } else {

            $("#sign_err").html("Inserire credenziali");
            return;

        }
    });

});