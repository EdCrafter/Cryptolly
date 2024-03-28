
function validateForename(field) {
    return field == "" ? "No Forename was entered.\n" : "";
}

function validateSurname(field) {
    return field == "" ? "No Surname was entered.\n" : "";
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

function validateAge(field) {
    if (isNaN(field)) return "No Age was entered.\n";
    else if (field < 18 || field > 110)
        return "Age must be between 18 and 110.\n";
    return "";
}

function validateEmail(field) {
    if (field == "") return "No Email was entered.\n";
    else if (
        !((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) ||
        ! /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(field)
    )
        return "The Email address is invalid.\n";
    return "";
}

function validateForm(event) {
    console.log("validating");
    event.preventDefault(); // Prevent form submission
    var fail = "";
    fail += validateForename(event.target.forename.value);
    fail += validateSurname(event.target.surname.value);
    fail += validateUsername(event.target.username.value);
    fail += validatePassword(event.target.password.value, event.target.password2.value);
    fail += validateAge(event.target.age.value);
    fail += validateEmail(event.target.email.value);

    if (fail == "") {
        event.target.submit();
    } else {
        alert(fail);
    }
}


$(document).ready(function () {
    $(".registration__top button").mouseover(function () {
        $(this).css("transform", "scale(1.1)");
    });
    $(".registration__top button").mouseout(function () {
        $(this).css("transform", "scale(1)");
    });
});
