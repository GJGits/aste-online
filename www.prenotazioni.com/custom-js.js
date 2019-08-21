$(document).ready(function () {
    // Handler for cell hover
    $("td").hover(
        // handler in
        function () {
            const email = $(this).attr("data-email");
            const timestamp = $(this).attr("data-timestamp");
            if (email && email !== "free") {
                $(this).html("<small>" + email + "</small><br><small>" + timestamp + "</small>");
            }
        },
        // handler out
        function () {
            const email = $(this).attr("data-email");
            if (email && email !== "free") {
                $(this).html("");
            }
        }
    );
    // Handler for cell click
    $("td").click(function () {
        const email = $(this).attr("data-email");
        if (email && email === "free") {
            oldClass = $(this).attr("class");
            newClass = oldClass === "table-success" ? "table-warning" : "table-success";
            $(this).attr("class", newClass);
            name = $(this).attr("data-giorno") + "-" + $(this).attr("data-ora");
            const selector = "#" + name;
            console.log("name:",$(selector).attr("name"));
            $(selector).length ? $(selector).remove() : $("#pre-form").append("<input type='text' id='" + name +"' name='" + name + "'></input>");
        }
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