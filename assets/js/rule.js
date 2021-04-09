$("document").ready(function() {
    $(".open_rules_picker").on("click", function() {
        openRulesPicker();
    });
});

function openRulesPicker() {
    showPopup();
    var info_div = JSON.parse($(".info_div").html());
    var uid = info_div.uid;

    $.ajax({
            method: "POST",
            url: "../../app/controllers/accountController.php",
            data: {
                cmd: "get_all_accounts_by_uid"
            }
        })
        .done(function(cbData) {
            var allAccounts = JSON.parse(cbData);
            var options = `<option value="null">Выберите...</option>`;
            for (let i = 0; i < allAccounts.length; i++) {
                const element = allAccounts[i];
                options = options + `<option value=${JSON.stringify({
                    "id": element.id
                })}>${element.name}</option>`;
                if (i == allAccounts.length - 1) {
                    var popup_body = `
                    <div class="rules_picker_select_container">
                    Выберите аккаунт: 
                    <select onChange="getRulesFromFacebook(this)">` + options + `</select>
                    </div>
                    <div class="rules_template_container"></div>
                    `;
                    $(".popup_window").html(popup_body);
                }
            }
        })
        .fail(function(err) {
            console.log(err);
            setMessage("error", "<b>Не удалось загрузить все ваши аккаунты : </b> " + err);
        });

}

async function getRulesFromFacebook(elem) {
    var data = JSON.parse($(elem).val());
    var accountData = await getAccountData(data.id);

    var proxy_data = JSON.parse(accountData.proxy_data);
    var local_url = "../../app/controllers/proxyController.php";
    var request_url = "https://graph.facebook.com/v10.0/" + accountData.facebook_id + "/adaccounts?access_token=" + accountData.facebook_token;

    $.ajax({
            method: "POST",
            url: local_url,
            data: {
                url: request_url,
                usernamePassword: proxy_data.proxyUsername + ":" + proxy_data.proxyPassword,
                proxyIp: proxy_data.proxyIp,
                proxyPort: proxy_data.proxyPort,
                userAgent: proxy_data.proxyUserAgent,
                requestType: "GET"
            }
        })
        .done(function(cbDataRaw) {
            try {
                var cbData = JSON.parse(cbDataRaw);
                allAdAccounts = cbData.data;
                getRulesMiddleware(allAdAccounts, accountData);

            } catch (error) {
                setMessage("error", "<b>#31 Не удалось загрузить аккаунт : </b> " + error);
            }
        })
        .fail(function(err) {
            console.log(err);
            var errorMessage = err.responseJSON.error.message;
            setMessage("error", "<b>#32 Не удалось загрузить аккаунт : </b> " + errorMessage);
        });


}

async function getRulesMiddleware(allAdAccounts, accountInfo) {
    $(".popup_window").find(".rules_template_container").html(``);

    for (let r = 0; r < allAdAccounts.length; r++) {

        const adAccount = allAdAccounts[r];
        console.log(adAccount);
        var resp = await getRuleByAdAccount({
            "adAccount": adAccount.id,
            "accountInfo": accountInfo
        });
        console.log(resp);
        for (let t = 0; t < resp.data.length; t++) {
            const rule = resp.data[t];
            $(".popup_window").find(".rules_template_container").append(`
            <div class="rule_picker_element">
            ${rule.name} ( ${rule.created_time.substring(0, 10)} ) 
            <input type="button" class="default_button" onclick="adRule(this)" value="Добавить" data-id="${rule.id}" data-account_id="${accountInfo.id}"/>
            </div>
        `);
        }
    }
}

function getRuleByAdAccount(data) {
    var proxy_data = JSON.parse(data.accountInfo.proxy_data);
    return new Promise((resolve, reject) => {

        var local_url = "../../app/controllers/proxyController.php";
        var request_url = "https://graph.facebook.com/v10.0/" + data.adAccount + "/adrules_library?fields=name,account_id,created_time&access_token=" + data.accountInfo.facebook_token;
        console.log(request_url);
        $.ajax({
                method: "POST",
                url: local_url,
                data: {
                    url: request_url,
                    usernamePassword: proxy_data.proxyUsername + ":" + proxy_data.proxyPassword,
                    proxyIp: proxy_data.proxyIp,
                    proxyPort: proxy_data.proxyPort,
                    userAgent: proxy_data.proxyUserAgent,
                    requestType: "GET"
                }
            })
            .done(function(cbDataRaw) {
                var cbData = JSON.parse(cbDataRaw);
                resolve(cbData);
            })
            .fail(function(err) {
                console.log(err);
                var errorMessage = err.responseJSON.error.message;
                setMessage("error", "<b>Не удалось загрузить Адсэты : </b> " + errorMessage);
                reject(err);
            });
    });
}

function getRuleDataById(rule_id, accountData) {
    var proxy_data = JSON.parse(accountData.proxy_data);

    return new Promise((resolve, reject) => {

        var local_url = "../../app/controllers/proxyController.php";
        var request_url = "https://graph.facebook.com/v10.0/" + rule_id + "?fields=name,account_id,created_by,created_time,evaluation_spec,execution_spec,schedule_spec,status,updated_time&access_token=" + accountData.facebook_token;
        console.log(request_url);
        $.ajax({
                method: "POST",
                url: local_url,
                data: {
                    url: request_url,
                    usernamePassword: proxy_data.proxyUsername + ":" + proxy_data.proxyPassword,
                    proxyIp: proxy_data.proxyIp,
                    proxyPort: proxy_data.proxyPort,
                    userAgent: proxy_data.proxyUserAgent,
                    requestType: "GET"
                }
            })
            .done(function(cbDataRaw) {
                console.log(cbDataRaw);
                var cbData = JSON.parse(cbDataRaw);
                resolve(cbData);
            })
            .fail(function(err) {
                console.log(err);
                var errorMessage = err.responseJSON.error.message;
                setMessage("error", "<b>Не удалось загрузить Адсэты : </b> " + errorMessage);
                reject(err);
            });
    });
}

function getAccountData(accountId) {
    return new Promise((resolve, reject) => {

        $.ajax({
                method: "POST",
                url: "../../app/controllers/accountController.php",
                data: {
                    cmd: "get_account_info_by_id",
                    id: accountId
                }
            })
            .done(function(accountDataRaw) {
                var accountData = JSON.parse(accountDataRaw);
                resolve(accountData);
            });
    });
}

async function adRule(element) {
    lockButton($(element), "Добавляем..");
    var rule_id = $(element).data("id");
    var account_id = $(element).data("account_id");
    console.log(account_id);
    var accountData = await getAccountData(account_id);
    var rule_res = await getRuleDataById(rule_id, accountData);
    console.log(rule_res);
    $.ajax({
            method: "POST",
            url: "../../app/controllers/ruleController.php",
            data: {
                cmd: "create",
                data: JSON.stringify(rule_res),
                token: accountData.facebook_token
            }
        })
        .done(function(cbData) {
            console.log(cbData);
            if (JSON.parse(cbData)) {
                var resp = JSON.parse(cbData);
                if (resp["resp"]) {
                    $(element).addClass("ruleAdded").val("Добавлено!");
                } else {
                    setMessage("error", "<b>Не удалось сохранить правило : </b> " + resp["msg"]);
                }
            } else {
                setMessage("error", "<b>Не удалось сохранить правило : </b> " + resp["msg"]);
            }
        })
        .fail(function(err) {
            console.log(err);
            setMessage("error", "<b>Не удалось загрузить все ваши аккаунты : </b> " + err);
        });

    // var token = $(element).data("token");
}

// rule view =======================================

async function getAllAdAccounts(element) {
    $(".loadingCampaigns").removeClass("hidden");
    var accountId = $(element).val();
    var accountData = await getAccountData(accountId);
    var proxy_data = JSON.parse(accountData.proxy_data);

    // console.log(data);

    var local_url = "../../app/controllers/proxyController.php";
    var request_url = "https://graph.facebook.com/v10.0/" + accountData.facebook_id + "/adaccounts?access_token=" + accountData.facebook_token;
    $.ajax({
            method: "POST",
            url: local_url,
            data: {
                url: request_url,
                usernamePassword: proxy_data.proxyUsername + ":" + proxy_data.proxyPassword,
                proxyIp: proxy_data.proxyIp,
                proxyPort: proxy_data.proxyPort,
                userAgent: proxy_data.proxyUserAgent,
                requestType: "GET"
            }
        })
        .done(function(cbDataRaw) {
            console.log(cbDataRaw);
            var cbData = JSON.parse(cbDataRaw);
            console.log(cbData);
            accountData["allAdAccounts"] = cbData.data;
            getAllAdSets(accountData);
        })
        .fail(function(err) {
            console.log(err);
            var errorMessage = err.responseJSON.error.message;
            setMessage("error", "<b>Не удалось загрузить аккаунт : </b> " + errorMessage);
        });
}

function toggleAllCheckboxes(elem) {
    activateSubmitButton(this);
    if ($(elem).is(":checked")) {
        $("input[type='checkbox']").prop('checked', true);
    } else {
        $("input[type='checkbox']").prop('checked', false);
    }
}

async function getAllAdSets(data) {

    $(".rule_adset_table").html(`
    <tr>
        <td class="td_header">
        <input type="checkbox" onchange="toggleAllCheckboxes(this)"/>ID</td>
        <td class="td_header">Название</td>
        <td class="td_header">Количество автоправил</td>
        <td class="td_header">Момент создания</td>
    </tr>
    `);
    for (let r = 0; r < data.allAdAccounts.length; r++) {

        const adAccount = data.allAdAccounts[r];
        data["adAccount"] = adAccount.id;
        var resp = await getAdsetsByAdAccount(data);
        for (let t = 0; t < resp.adsets.length; t++) {
            adset_gov = 0;
            const adset = resp.adsets[t];
            if (typeof adset.adrules_governed !== "undefined") {
                if (adset.adrules_governed.data.length !== 0) {
                    adset_gov = adset.adrules_governed.data.length;
                }
            }
            $(".rule_adset_table").append(`
            <tr>
            <td><input class="adsetOptionCheckbox" onChange="activateSubmitButton(this)" type="checkbox" data-data=${JSON.stringify({
                facebook_id: resp.facebook_id,
                facebook_token: resp.facebook_token,
                adset_id: adset.id,
                adAccount: resp.adAccount,
                account_id: data.id
            })} />${adset.id}</td>
            <td>${adset.name}</td>
            <td>${adset_gov}</td>
            <td>${adset.created_time}</td>
            </tr>
            `);

        }
        if (r == data.allAdAccounts.length - 1) {
            $(".rule_adset_table").find(".firstTd").prepend(`
            <input type="checkbox" class="check_all />"`);
            $(".loadingCampaigns").addClass("hidden");
        }
    }
}

function activateSubmitButton() {
    $(".rules_adjust_submit_button").removeClass("default_button_inactive");
}

function getAdsetsByAdAccount(data) {
    console.log(data);
    var proxy_data = JSON.parse(data.proxy_data);
    return new Promise((resolve, reject) => {

        var local_url = "../../app/controllers/proxyController.php";
        var request_url = "https://graph.facebook.com/v10.0/" + data.adAccount + "/adsets?fields=account_id,ad_count,created_time,name,adrules_governed&access_token=" + data.facebook_token;
        // console.log(request_url);
        $.ajax({
                method: "POST",
                url: local_url,
                data: {
                    url: request_url,
                    usernamePassword: proxy_data.proxyUsername + ":" + proxy_data.proxyPassword,
                    proxyIp: proxy_data.proxyIp,
                    proxyPort: proxy_data.proxyPort,
                    userAgent: proxy_data.proxyUserAgent,
                    requestType: "GET"
                }
            })
            .done(function(adsetsRaw) {
                var adsets = JSON.parse(adsetsRaw);
                data["adsets"] = adsets.data;
                resolve(data);
            })
            .fail(function(err) {
                console.log(err);
                var errorMessage = err.responseJSON.error.message;
                setMessage("error", "<b>Не удалось загрузить Адсэты : </b> " + errorMessage);
                reject(err);
            });
    });
}


async function submitRuleAdjust() {
    lockButton($(".rules_adjust_submit_button"), "Ожидайте...");

    var all_checked_checkboxes = $(".rule_adset_table").find(".adsetOptionCheckbox:checked");
    var current_rule = JSON.parse($(".rule_data_container").html());
    // console.log(all_checked_checkboxes.length);
    // console.log(current_rule);
    var succeed_calls = 0;
    const checked_checkboxes_length = all_checked_checkboxes.length;
    for (let b = 0; b < checked_checkboxes_length; b++) {
        const checkbox_element = all_checked_checkboxes[b];
        var checkboxData = $(checkbox_element).data("data");
        checkboxData["currentRule"] = current_rule;
        var accountData = await getAccountData(checkboxData["account_id"]);
        checkboxData["account_info"] = accountData;
        var applyRuleCallback = await createAndApplyRuleToAdset(checkboxData);
        if (applyRuleCallback.resp == "success") {
            succeed_calls = succeed_calls + 1;
        }
        var adsets_left = (checked_checkboxes_length - 1) - b;
        lockButton($(".rules_adjust_submit_button"), "Осталось " + adsets_left + " адсэтов, Ожидайте...");

        // console.log(applyRuleCallback);
        if (b == checked_checkboxes_length - 1) {
            unlockButton($(".rules_adjust_submit_button"), "применить");
            setMessage("success", "<b>Правила добавлены успешно!</b> Количество успешно обработанных адсэтов : " + succeed_calls);
        }
    }
}

function createAndApplyRuleToAdset(data) {
    console.log(data);
    // console.log(data);
    return new Promise((resolve, reject) => {

        var selectedRule = data["currentRule"];
        var rule = {};

        // console.log("check for adset.id field");
        // console.log(selectedRule.evaluation_spec.filters.length);
        const evaluation_length = selectedRule.evaluation_spec.filters.length;
        var adset_id_found = false;
        var user_id_found = false;
        for (let i = 0; i < evaluation_length; i++) {
            const element = selectedRule.evaluation_spec.filters[i];
            // console.log(element);
            if (element.field == "adset.id") {
                adset_id_found = true;
                var adset_id_value = selectedRule.evaluation_spec.filters[i].value;
                console.log(adset_id_value);
                if (adset_id_value.constructor === Array) {
                    console.log("is array");
                    selectedRule.evaluation_spec.filters[i].value = [data.adset_id];
                }
            }
            // console.log(i);
            // console.log(evaluation_length - 1);
            if (i === evaluation_length - 1) {
                // console.log("not found");
                if (!adset_id_found) {
                    selectedRule.evaluation_spec.filters.push({
                        "field": "adset.id",
                        "value": [data.adset_id],
                        "operator": "IN"
                    });
                }

                // console.log("done checking filters");
                // console.log(selectedRule.execution_spec.execution_options.length);
                const execution_length = selectedRule.execution_spec.execution_options.length;

                for (let j = 0; j < execution_length; j++) {
                    const execution_option = selectedRule.execution_spec.execution_options[j];
                    // console.log(execution_option);
                    if (execution_option.field == "user_ids") {
                        user_id_found = true;
                        var execution_option_value = selectedRule.execution_spec.execution_options[j].value;
                        if (execution_option_value.constructor === Array) {
                            // console.log("is array");
                            selectedRule.execution_spec.execution_options[j].value = [data.facebook_id];
                        }
                    }
                    // console.log(j);
                    // console.log(execution_length - 1);
                    if (j === execution_length - 1) {
                        // console.log("not found");
                        if (!user_id_found) {
                            selectedRule.execution_spec.execution_options.push({
                                "field": "user_ids",
                                "value": [data.facebook_id],
                                "operator": "EQUAL"
                            });
                        }

                        // console.log("done checking execution_options");
                        // console.log("do request");
                        rule.name = selectedRule.name;
                        rule.account_id = data.adAccount.substring(4); // substring to remove the ACT_
                        rule.status = "ENABLED";
                        rule.evaluation_spec = selectedRule.evaluation_spec;
                        rule.schedule_spec = selectedRule.schedule_spec;
                        rule.execution_spec = selectedRule.execution_spec;
                        // console.log(rule);

                        var local_url = "../../app/controllers/proxyController.php";
                        var request_url = "https://graph.facebook.com/v10.0/" + data.adAccount + "/adrules_library?access_token=" + data.facebook_token;
                        var proxy_data = JSON.parse(data.account_info.proxy_data);
                        console.log(request_url);
                        console.log(rule);
                        $.ajax({
                                method: "POST",
                                url: local_url,
                                data: {
                                    url: request_url,
                                    usernamePassword: proxy_data.proxyUsername + ":" + proxy_data.proxyPassword,
                                    proxyIp: proxy_data.proxyIp,
                                    proxyPort: proxy_data.proxyPort,
                                    userAgent: proxy_data.proxyUserAgent,
                                    postBody: JSON.stringify(rule),
                                    requestType: "POST"
                                }
                            })
                            .done(function(callbackRaw) {
                                var callback = JSON.parse(callbackRaw);
                                console.log(callback);
                                if (typeof callback.error !== "undefined") {
                                    // reject({ "resp": "error", "callback": callback.error.message });
                                    unlockButton($(".rules_adjust_submit_button"), "применить");
                                    setMessage("error", "<b>Не удалось добавить правило для адсета " + data.adset_id + ": </b> " + callback.error.error_user_msg);
                                    resolve({ "resp": "error", "callback": callback });

                                } else {
                                    resolve({ "resp": "success", "callback": callback });
                                }
                            })
                            .fail(function(err) {
                                reject({ "resp": "error", "callback": err });
                                var errorMessage = err.responseJSON.error.message;
                                unlockButton($(".rules_adjust_submit_button"), "применить");
                                setMessage("error", "<b>Не удалось добавить правило для адсета " + data.adset_id + ": </b> " + errorMessage);
                                console.log(err);
                            });

                        // console.log(request_url);
                        // $.ajax({
                        //         method: "POST",
                        //         url: request_url,
                        //         contentType: 'application/json',
                        //         data: JSON.stringify(rule)
                        //     })
                        //     .done(function(callback) {
                        //         resolve({ "resp": "success", "callback": callback });
                        //     })
                        //     .fail(function(err) {
                        //         reject({ "resp": "error", "callback": err });
                        //         var errorMessage = err.responseJSON.error.message;
                        //         unlockButton($(".rules_adjust_submit_button"), "применить");
                        //         setMessage("error", "<b>Не удалось добавить правило для адсета " + data.adset_id + ": </b> " + errorMessage);
                        //         console.log(err);
                        //     });
                    }
                }
            }
        }


    });

}