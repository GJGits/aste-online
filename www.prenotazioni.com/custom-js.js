$(document).ready(function () {
    let prenotazioni = [];
    // Handler for cell hover
    $("#table-owner td").on({
        // handler in
        mouseenter: function () {
            const email = $(this).attr("data-email");
            const timestamp = $(this).attr("data-timestamp");
            if (email && email !== "free") {
                $(this).html("<small>" + email + "</small><br><small>" + timestamp + "</small>");
            }
        },
        // handler out
        mouseleave: function () {
            const email = $(this).attr("data-email");
            if (email && email !== "free") {
                $(this).html("");
            }
        }
    });
    // Handler for cell click
    $("td").click(function () {
        const email = $(this).attr("data-email");
        if (email && email === "free") {
            oldClass = $(this).attr("class");
            newClass = oldClass === "table-success" ? "table-warning" : "table-success";
            $(this).attr("class", newClass);
            name = $(this).attr("data-giorno") + "-" + $(this).attr("data-ora").replace(":", "-");
            if (prenotazioni.includes(name)) {
                index = prenotazioni.indexOf(name);
                prenotazioni.splice(index, 1);
            } else {
                prenotazioni.push(name);
            }
        }
    });
    // Handler prenota
    $("#prenota").click(function () {
        $.ajax({
            type: "POST",
            url: "prenotazioni.php",
            data: { prenota: true, prenotazioni: prenotazioni },
            success: function (response) {
                console.log("response", response);
                if (response === "occupato" || response === "scaduta") {
                    // todo: show alert
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
                    prenotazioni = []; // azzero perché prenotazione effettuata
                }
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
                console.log("response", response);
                if (response === "scaduta") {
                    // todo: show alert
                } else {
                    elements = $(".table-orange");
                    for (el of elements) {
                        el.attr("class", "table-success");
                        el.attr("data-email", "free");
                        el.attr("data-timestamp", "");
                    }
                }
            }
        });
    });
    // Handler for password security level
    $("#pass").on('keyup keypress blur change', function () {
        password = $(this).val();
        pass_level = $("#pass-level");
        rgx_robusta = /^(?=(.*\d)+)(?=(.*[!@#$%]){2})[0-9a-zA-Z!@#$%]{3,}$/;
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
        pass1 = $("#pass").val();
        pass2 = $("#pass2").val();
        if (pass1 !== pass2) {
            event.preventDefault();
            $("#pass2")[0].setCustomValidity("passwords are different");
        }
    });
});