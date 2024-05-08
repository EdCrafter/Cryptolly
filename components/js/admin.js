$(document).ready(function(){
    $("#price-update").click(function(){
        $.ajax({
            url: "../../update/updateV2.php", 
            method: "POST", 
            
            success: function(response){
                var data = JSON.parse(response);
                $('#price-update').next().not("p").before($("<p>").css("color", "lightgreen").text(data["message"]));
            },
            error: function(xhr, status, error){
                var err = JSON.parse(xhr.responseText);
                $('#price-update').next().not("p").before($("<p>").css("color", "red").text(err["message"]));
            }
        });
    });
});
