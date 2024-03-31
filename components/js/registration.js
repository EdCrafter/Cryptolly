
function validateName(field) {
    if (field == "") return "No Name was entered.\n";
    else if (field.length < 3)
        return "Name must be at least 3 characters.\n";
    else if (!(/[^a-zA-Z]/.test(field) ^ /[^\u0400-\u04FF]/.test(field)))
        return "Only a-z, A-Z or а-я , А-Я allowed in names.\n";
    return "";
}

function validateSurname(field) {
    if (field == "") return "No Surname was entered.\n";
    else if (field.length < 3)
        return "Surname must be at least 3 characters.\n";
    else if (!(/[^a-zA-Z]/.test(field) ^ /[^\u0400-\u04FF]/.test(field)))
        return "Only a-z, A-Z or а-я , А-Я allowed in surnames.\n";
    return "";
}

function validateUsername(field) {
    if (field == "") return "No Username was entered.\n";
    else if (field.length < 3)
        return "Usernames must be at least 3 characters.\n";
    else if (/\W/.test(field))
        return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n";
    return "";
}

function validatePassword(field, field2) {
    if (field == "") return "No Password was entered.\n";
    else if (field.length < 6)
        return "Passwords must be at least 6 characters.\n";
    else if (
        !/[a-z]/.test(field) ||
        !/[A-Z]/.test(field) ||
        !/\d/.test(field)
    )
        return "Passwords require one each of a-z, A-Z and 0-9.\n";
    else if (field != field2) return "Passwords do not match.\n";
    return "";
}

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

function validateAge(field) {
    if (isNaN(field)) return "No Age was entered.\n";
    else if (field < 18 || field > 110)
        return "Age must be between 18 and 110.\n";
    return "";
}

function validateEmail(field) {
    if (field == "") return "No Email was entered.\n";
    else if (
        !((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) ||
        !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(field)
    )
        return "The Email address is invalid.\n";
    return "";
}

function failAlert(fail, field) {
    if (fail != "") {
        field.css("border", "1px solid #ff0000");
        field.next().not("p").before(
            $("<p>").css("color", "red").text(fail)
        );
    }
}


$(document).ready(function () {
    $("#form").submit(function (e) {
        e.preventDefault();
        
        failAlert(validateName($("#name").val()), $("input[name='name']"));
        failAlert(validateSurname($("#surname").val()), $("input[name='surname']"));
        failAlert(validateUsername($("#username").val()), $("input[name='username']"));
        failAlert(validatePassword($("#password").val(), $("#password2").val()), $("input[name='password1']"));
        failAlert(validatePassword($("#password").val(), $("#password2").val()), $("input[name='password2']"));
        if ($("#adminPass").val() !== undefined) {
            failAlert(validateAdmin($("#adminPass").val()), $("input[name='adminPass']"));
        }
        failAlert(validateAge($("#age").val()), $("input[name='age']"));
        failAlert(validateEmail($("#email").val()), $("input[name='email']"));
        if(!$("#form p").length){
            $.ajax({
                type: "POST",
                url: "validate.php",
                data: $("#form").serialize(),
                success: function (data) {
                    console.log(data);
                    var dataP = JSON.parse(data);
                    if(dataP["success"] == "true"){
                        $(".registration").html("<h1>Registration Successful</h1><h2 style=\"font-size:x-large\">Thank you for registering with us. You can now <a href='../authorization/authorization.php'>login</a> to your account.</h2>");
                    }
                    else{
                        console.log(dataP);
                        failAlert(dataP["name"], $("input[name='name']"));
                        failAlert(dataP["surname"], $("input[name='surname']"));
                        failAlert(dataP["username"], $("input[name='username']"));
                        failAlert(dataP["password"], $("input[name='password1']"));
                        failAlert(dataP["password"], $("input[name='password2']"));
                        if ($("#adminPass").val() !== undefined) {
                            failAlert(dataP["adminPass"], $("input[name='adminPass']"));
                        }
                        failAlert(dataP["age"], $("input[name='age']"));
                        failAlert(dataP["email"], $("input[name='email']"));
                    }


        
                },
                error: function (data) {
                    console.log("error");
                    console.log(data);
                }
            });
        }
        
    });

    $(".registration__top button").mouseover(function () {
        $(this).css("transform", "scale(1.1)");
    });
    $(".registration__top button").mouseout(function () {
        $(this).css("transform", "scale(1)");
    });
    toggle = true;
    $(".registration__top").on("click", "button:not(.active)", function () {
        $(".registration__top button").removeClass("active");
        $(this).addClass("active");
        if (toggle) {
            $(this).parent().next().find("input[type='submit']").before(
                $("<label>").css("margin-top", "30px").attr({ "for": "adminPass" }).text("Admin Password:")
            );
            $(this).parent().next().find("input[type='submit']").before(
                $("<input>").attr({ "type": "password", "name": "adminPass", "autocomplete": "off", "required": "required", "id": "adminPass" })
            );
        }
        else {
            $(this).parent().next().find("label[for ='adminPass' ]").remove();
            $(this).parent().next().find("input[name='adminPass']").remove();
        }
        toggle = !toggle;
    }
    );

    $(".registration form input").focusin(function () {
        $(this).css("border", "1px solid #3b5998");
        $(this).next("p , span").remove();
    });

    $("#username").blur(function () {
        checkUsername(this);
    });
    $("#email").blur(function () {
        checkEmail(this);
    });
});


function checkUsername(user) {
    if (user.value == "") {
        $("#used").html("&nbsp;");
        return;
    }
    $.ajax({
        type: "POST",
        url: "check.php",
        data: "user=" + user.value,
        caches: false,
        success: function (data) {
            $("#username").next().not("span").before(
            $("<span>").html(data));
        }
    });
}

function checkEmail(email) {
    if (email.value == "") {
        $("#used").html("&nbsp;");
        return;
    }
    $.ajax({
        type: "POST",
        url: "check.php",
        data: "email=" + email.value,
        caches: false,
        success: function (data) {
            $("#email").next().not("span").before(
            $("<span>").html(data));
        }
    });
}