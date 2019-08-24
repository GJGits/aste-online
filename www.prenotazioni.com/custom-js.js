let prenotazioni = [];
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
    $("#table-owner").load("prenotazioni.php", { load: true });
    // Cookie watcher
    setInterval(function(){ 
        if(!$.cookie("PHPSESSID") && !$(".jumbotron").length)  {
            console.log("cookies disabilitati");
            $("body").html("");
            $("body").load("nocookie.html");
        }
    }, 2000);
    // Cell handlers
    $("#table-owner").on({
        mouseenter: function () {
            // Handle mouseenter...
            cell = $(this);
            giorno = cell.attr("data-giorno");
            ora = cell.attr("data-ora");
            $.ajax({
                type: "POST",
                url: "prenotazioni.php",
                data: { info: true, giorno: giorno, ora: ora },
                success: function (response) {
                    console.log("response:", response);
                    tokens = response.split(",");
                    email = tokens[0];
                    timestamp = tokens[1];
                    if (email && email !== "free" && !cell.hasClass("table-success")) {
                        cell.html("<small>" + email + "</small><br><small>" + timestamp + "</small>");
                    }
                }
            });

        },
        mouseleave: function () {
            // Handle mouseleave...
            cell = $(this);
            email = cell.attr("data-email");
            if (email && email !== "free")
                cell.html("");
        },
        click: function () {
            // Handle click...
            const email = $(this).attr("data-email");
            if (email && email === "free") {
                oldClass = $(this).attr("class");
                newClass = oldClass === "table-success" ? "table-warning" : "table-success";
                $(this).attr("class", newClass);
                name = $(this).attr("data-giorno") + "-" + $(this).attr("data-ora").replace(":", "-");
                if (prenotazioni.includes(name)) {
                    index = prenotazioni.indexOf(name);
                    prenotazioni.splice(index, 1);
                    if (prenotazioni.length === 0)
                        $("#prenota").attr("disabled", true);
                } else {
                    $("#prenota").attr("disabled", false);
                    prenotazioni.push(name);
                }
            }
        }
    }, "td");
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
    // Handler elimina
    $("#elimina").click(function () {
        $.ajax({
            type: "POST",
            url: "prenotazioni.php",
            data: { elimina: true },
            success: function (response) {
                if (response === "scaduta") {
                    $('#exampleModalCenter').modal('show');
                    $(".modal-body").append('<div class="alert alert-primary" role="alert">Sessione scaduta, procedere al <a href="signin.php" class="alert-link">login</a></div>');
                } else {
                    elements = $(".table-orange");
                    elements.attr("class", "table-success");
                    elements.attr("data-email", "free");
                    elements.attr("data-timestamp", "");
                }
                prenotazioni = []; // in ogni caso azzero buffer prenotazioni
            }
        });
    });
    // Handler for password security level
    $("#pass").on('keyup keypress blur change', function () {
        password = $(this).val();
        pass_level = $("#pass-level");
        rgx_robusta = /^(?=(.*\d)+)(?=(.*[!@#$%.-]){2})[0-9a-zA-Z!@#$%.-]{3,}$/;
        rgx_media = /.{3,}/;
        rgx_debole = /.{1,2}/;
        if (!password || password === "" || rgx_debole.test(password)) {
            pass_level.text("debole");
            pass_level.attr("class", "text-danger");
        }
        if (rgx_media.test(password)) {
            pass_level.text("media");
            pass_level.attr("class", "text-primary");
        }
        if (rgx_robusta.test(password)) {
            pass_level.text("robusta");
            pass_level.attr("class", "text-success");
        }
    });
    // Validate signup form
    $("#supf").submit(function (event) {
        rgx_robusta = /^(?=(.*\d)+)(?=(.*[!@#$%.-]){2})[0-9a-zA-Z!@#$%.-]{3,}$/;
        pass1 = $("#pass").val();
        pass2 = $("#pass2").val();
        console.log("pass1:", pass1, "pass2:", pass2);
        if (pass1 !== pass2) {
            event.preventDefault();
            $("#pass2")[0].setCustomValidity("le due password non coincidono");
        }
        if (!rgx_robusta.test(pass1)) {
            event.preventDefault();
            $("#pass1")[0].setCustomValidity("il livello di sicurezza deve essere: robusta");
        }
    });
});