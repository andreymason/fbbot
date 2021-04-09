$(document).ready(function() {


    $(".inputSubmit").on("click", function(e) {
        e.preventDefault();
        signUpAction();
    });

});

function signUpAction() {
    //get vars
    var name = $(".inputName").val();
    var email = $(".inputEmail").val();
    var password = $(".inputPassword").val();
    var password2 = $(".inputPassword2").val();

    $.ajax({
            method: "POST",
            url: "../app/controllers/signUpController.php",
            data: {
                signUpName: name,
                signUpEmail: email,
                signUpPassword: password,
                signUpPassword2: password2
            }
        })
        .done(function(msg) {


            if (msg == "signedUp") {
                $(".message-text").html("Signed up! <a href='http://localhost/signin'>Sign in</a>");
                $(".message-box").css({ "background": "#DEF4BE", "color": "#056338", "border": "1px solid #b4d0c3" }).fadeIn(1000);
                $(".cross-svg").css({ "fill": "#056338" });
            } else {
                $(".message-box").fadeIn(1000);
                $(".message-text").html(msg);
            }
        });
}