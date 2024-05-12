function validateAdmin(field) {
    if (field == "") return "No admin Password was entered.\n";
    else if (field.length < 6)
        return "Passwords must be at least 6 characters.\n";
    else if (
        !/[a-z]/.test(field) ||
        !/[A-Z]/.test(field) ||
        !/\d/.test(field)
    )
        return "Passwords require one each of a-z, A-Z and 0-9.\n";
    return "";
}
function failAlert(fail, field) {
    if (fail != "") {
        console.log("fail");
        field.css("border", "1px solid #ff0000");
        field.next().not("p").before(
            $("<p>").css("color", "red").text(fail)
        );
    }
}
$(document).ready(function () {
    $("#form").submit(function (e) {
        e.preventDefault();
        failAlert(validateAdmin($("#adminPass").val()), $("input[name='adminPass']"));
        if (!$("#form p").length) {
            $.ajax({
                type: "POST",
                url: "validate.php",
                data: $("#form").serialize(),
                success: function (data) {
                    var dataP = JSON.parse(data);
                    if (dataP["success"] == "true") {
                        $(".registration").html("<h1>You admin now</h1><h2 style=\"font-size:x-large\"> Please <a href='profile.php'>Refresh</a>  .</h2>");
                    }
                    else {
                        failAlert(dataP["adminPass"], $("input[name='adminPass']"));
                    }
                },
                error: function (data) {
                    console.log("error");
                    console.log(data);
                }
            });
        }

    });
    $(".registration form input").focusin(function () {
        $(this).css("border", "1px solid #3b5998");
        $(this).next("p , span").remove();
    });
});


