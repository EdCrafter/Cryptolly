
function validateName(field) {
    if (field == "") return "No Name was entered.\n";
    else if (field.length < 3)
        return "Name must be at least 3 characters.\n";
    else if (!(/[^a-zA-Z]/.test(field) ^ /[^а-яА-Я]/.test(field)))
        return "Only a-z, A-Z or а-я , А-Я allowed in names.\n";
    return "";
}

function validateSurname(field) {
    if (field == "") return "No Surname was entered.\n";
    else if (field.length < 3)
        return "Surname must be at least 3 characters.\n";
    else if (!(/[^a-zA-Z]/.test(field) ^ /[^а-яА-Я]/.test(field)))
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


$(document).ready(function () {
    $("#form").submit(function (e) {
        e.preventDefault();
        console.log("submit");
        var submitForm = true;
        var fail = "";
        fail += validateName($("#name").val());
        if (fail != "") {
            submitForm = false;
            $("input[name='name']").css("border", "1px solid red");
            $("input[name='name']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
        }
        fail = "";
        fail += validateSurname($("#surname").val());
        if (fail != "") {
            submitForm = false;
            $("input[name='surname']").css("border", "1px solid red");
            $("input[name='surname']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
        }
        fail = "";
        fail += validateUsername($("#username").val());
        if (fail != "") {
            submitForm = false;
            $("input[name='username']").
                css("border", "1px solid red");
            $("input[name='username']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
        }
        fail = "";
        fail += validatePassword($("#password").val(), $("#password2").val());
        if (fail != "") {
            submitForm = false;
            $("input[name='password']").css("border", "1px solid red");
            $("input[name='password2']").css("border", "1px solid red");
            $("input[name='password']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
            $("input[name='password2']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
        }
        fail = "";
        if ($("#adminPass").val() !== undefined) {
            fail += validateAdmin($("#adminPass").val());
            if (fail != "") {
                submitForm = false;
                $("input[name='adminPass']").css("border", "1px solid red");
                $("input[name='adminPass']").next().not("p").before(
                    $("<p>").css("color", "red").text(fail)
                );
            }
            fail = "";
        }
        fail += validateAge($("#age").val());
        if (fail != "") {
            submitForm = false;
            $("input[name='age']").css("border", "1px solid red");
            $("input[name='age']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
        }
        fail = "";
        fail += validateEmail($("#email").val());
        if (fail != "") {
            submitForm = false;
            $("input[name='email']").css("border", "1px solid red");
            $("input[name='email']").next().not("p").before(
                $("<p>").css("color", "red").text(fail)
            );
        }
        //submitForm = true;
        
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
        $(this).next("p").remove();
    });
});
