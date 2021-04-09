$(document).ready(function() {
    var addAccountSubmitBtn = $(".validate_account");

    addAccountSubmitBtn.on("click", function() {
        lockButton($(this), "Ожидайте...");
        var accountToken = $(".form_text_field[name='accountToken']").val();
        var proxyIp = $(".form_text_field[name='proxyIp']").val();
        var proxyPort = $(".form_text_field[name='proxyPort']").val();
        var proxyUsername = $(".form_text_field[name='proxyUsername']").val();
        var proxyPassword = $(".form_text_field[name='proxyPassword']").val();
        var proxyUserAgent = $(".form_text_field[name='proxyUserAgent']").val();
        validateUserToken({
            "accountToken": accountToken,
            "proxyIp": proxyIp,
            "proxyPort": proxyPort,
            "proxyUsername": proxyUsername,
            "proxyPassword": proxyPassword,
            "proxyUserAgent": proxyUserAgent,
        });
    });
});

function validateUserToken(data) {

    var local_url = "../../app/controllers/proxyController.php";
    var request_url = "https://graph.facebook.com/v10.0/me?access_token=" + data.accountToken;

    $.ajax({
            method: "POST",
            url: local_url,
            data: {
                url: request_url,
                usernamePassword: data.proxyUsername + ":" + data.proxyPassword,
                proxyIp: data.proxyIp,
                proxyPort: data.proxyPort,
                userAgent: data.proxyUserAgent,
                requestType: "GET"
            }
        })
        .done(function(cbDataRaw) {
			console.log(cbDataRaw);
            try {
                cbData = JSON.parse(cbDataRaw);
                console.log(cbData);
                if (typeof cbData.error !== "undefined") {
                    setMessage("error", "<b>#11 Не удалось добавить аккаунт : </b> " + cbData.error.message);
                } else {
                    cbData.facebook_token = data.accountToken;
                    cbData.proxy_data = data;
                    addUserToken(cbData);
                }

            } catch (error) {
                console.log(error); 
                unlockButton($(".validate_account"), "Добавить");
                setMessage("error", "<b>#12 Не удалось добавить аккаунт : </b> " + error);
            }

        })
        .fail(function(err) {
            var errorMessage = err.responseJSON.error.message;

            unlockButton($(".validate_account"), "Добавить");
            setMessage("error", "<b>#13 Не удалось добавить аккаунт : </b> " + errorMessage);
        });
}

function addUserToken(data) {
    var request_url = "../../app/controllers/accountController.php";
    data["user_id"] = $(".info_div").data("user_id");
    $.ajax({
            method: "POST",
            url: request_url,
            data: {
                "cmd": "create",
                "data": data
            },
            headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }
        })
        .done(function(cbData) {
            try {
                data = JSON.parse(cbData);
                unlockButton($(".validate_account"), "Добавить");

                if (data.resp == "warning") {
                    setMessage("warning", "<b>#14 Не удалось добавить аккаунт : </b> " + data.msg);
                }
                if (data.resp == "error") {
                    setMessage("error", "<b>#15 Не удалось добавить аккаунт : </b> " + data.msg);
                }
                if (data.resp == "success") {
                    setMessage("success", "<b>Аккаунт успешно добавлен!</b> Вы можете добавить ещё один аккаунт.");

                    $(".form_text_field[name='accountToken']").val("");
                }
                if (data.resp == "updated") {
                    setMessage("success", "<b>Аккаунт " + data.name + " успешно обновлён!</b>");

                    $(".form_text_field[name='accountToken']").val("");
                }
            } catch (error) {
                console.log(cbData);
                setMessage("error", "<b>#16 Не удалось добавить аккаунт : </b> " + error);
            }

        })
        .fail(function(err) {
            console.log(err);

            unlockButton($(".validate_account"), "Добавить");
            setMessage("error", "<b>#17 Не удалось добавить аккаунт : </b> " + err);
        });
}