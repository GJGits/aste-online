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
    $("td").click(function(){
        const email = $(this).attr("data-email");
        if(email && email === "free") {
            oldClass = $(this).attr("class");
            newClass = oldClass === "table-success" ? "table-warning" : "table-success";
            $(this).attr("class",newClass);
        }
    });
});