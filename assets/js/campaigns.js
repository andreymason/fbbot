$(document).ready(function() {
    var infoRaw = $(".info_div").html();
    var info = JSON.parse(infoRaw);
    $(".adAccountSelect").on("change", function() {
        var selected_value = $(this).val();
        if (selected_value == "all") {
            var optionValues = [];
            var all_options = $('.adAccountSelect option');
            for (let o = 0; o < all_options.length; o++) {
                const option = all_options[o];
                var option_value = $(option).val();
                if (option_value !== "emtpy" && option_value !== "all") {
                    optionValues.push($(option).val());
                }

                if (o == all_options.length - 1) {
                    refresh_account_page({
                        "accountId": "all",
                        "facebookId": info.facebook_id,
                        "facebookToken": info.facebook_token,
                        "info": infoRaw,
                        "allAccountIds": optionValues
                    });
                }
            }

        } else {
            refresh_account_page({
                "accountId": $(this).val(),
                "facebookId": info.facebook_id,
                "facebookToken": info.facebook_token,
                "info": infoRaw

            });
        }

    });

    getAdAccounts({
        "id": info.facebook_id,
        "token": info.facebook_token,
        "info": infoRaw
    });
    // getCampaigns(info.facebook_id, info.facebook_token);
});

function getAdAccounts(data) {
    var info = JSON.parse(data.info);
    var proxy_data = JSON.parse(info.proxy_data);
    var local_url = "../../app/controllers/proxyController.php";
    var request_url = "https://graph.facebook.com/v10.0/" + data.id + "/adaccounts?access_token=" + data.token;

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
                var adAccountsAmount = cbData.data.length;
                if (adAccountsAmount > 0) {

                    $(".adAccountSelect").append(`
                <option value='empty'>Выберите...</option>
                <option value='all'>Все</option>
                `);
                    for (let i = 0; i < adAccountsAmount; i++) {
                        let element = cbData.data[i];

                        $(".adAccountSelect").append(`<option value='${element.id}'>${element.id.substring(4)}</option>`);
                    }
                    $(".download_accounts_container").addClass("download_trigger");
                } else {
                    $(".adAccountSelect").append(`<option value='empty'>Нет аккаунтов</option>`);
                    setMessage("warning", "<b>Нет рекламных аккаунтов</b>");
                }
            } catch (error) {
                setMessage("error", "<b>Не удалось загрузить аккаунт : </b> Возможно закончился срок токена, " + error);
            }
        })
        .fail(function(err) {
            console.log(err);
            var errorMessage = err.responseJSON.error.message;
            setMessage("error", "<b>Не удалось загрузить аккаунт : </b> " + errorMessage);

        });
}


function getCampaigns(id, token, proxy_data) {
    return new Promise((resolve, reject) => {
        var local_url = "../../app/controllers/proxyController.php";
        var request_url = "https://graph.facebook.com/v10.0/" + id + "/campaigns?effective_status=['ACTIVE','PAUSED','ARCHIVED','IN_PROCESS','WITH_ISSUES']&fields=name,objective,effective_status,status&access_token=" + token;
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
                try {
                    var cbData = JSON.parse(cbDataRaw);
                    resolve(cbData);
                } catch (error) {
                    setMessage("error", "<b>Не удалось загрузить РК : </b> " + error);
                    reject(error);
                }
            })
            .fail(function(err) {
                var errorMessage = err.responseJSON.error.message;
                setMessage("error", "<b>Не удалось загрузить РК : </b> " + errorMessage);
                console.log(err);
                reject(err);
            });
    });
}

function getAdsets(id, token, proxy_data) {
    console.log(proxy_data);
    return new Promise((resolve, reject) => {

        var local_url = "../../app/controllers/proxyController.php";
        var request_url = "https://graph.facebook.com/v10.0/" + id + "/adsets?fields=name,start_time,end_time,daily_budget,lifetime_budget,adrules_governed,account_id&access_token=" + token;
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
                try {
                    var cbData = JSON.parse(cbDataRaw);
                    resolve(cbData);
                } catch (error) {
                    setMessage("error", "<b>Не удалось загрузить Адсэты : </b> " + error);
                    reject(error);
                }
            })
            .fail(function(err) {
                var errorMessage = err.responseJSON.error.message;
                setMessage("error", "<b>Не удалось загрузить Адсэты : </b> " + errorMessage);
                console.log(err);
            });
    });
}


function getAds(id, token, proxy_data) {
    return new Promise((resolve, reject) => {

        var local_url = "../../app/controllers/proxyController.php";
        var request_url = "https://graph.facebook.com/v10.0/" + id + "/ads?fields=name,cost_per,cost_per_purchase_fb,cost_per_mobile_app_install,cost_per_link_click,cost_per_lead_fb,cost_per_initiate_checkout_fb,cost_per_complete_registration_fb,spent,stats&access_token=" + token;
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
                    resolve(cbData);
                } catch (error) {
                    setMessage("error", "<b>Не удалось загрузить Адсы : </b> " + error);
                    reject(error);
                }
            })
            .fail(function(err) {
                var errorMessage = err.responseJSON.error.message;
                setMessage("error", "<b>Не удалось загрузить Адсы : </b> " + errorMessage);
                console.log(err);
            });
    });
}

async function refresh_account_page(data) {
    switchTab("campaign");
    $(".tab").removeClass("selected_tab");
    $(".campaign_tab").addClass("selected_tab");
    $(".amount_of_campaigns, .amount_of_adsets, .amount_of_ads").html("");

    // clear tables
    $(".campaigns_table").html(
        `<tr>
            <td class="td_header">ID</td>
            <td class="td_header">Название</td>
            <td class="td_header">Тип</td>
        </tr>`
    );
    $(".adsets_table").html(
        `<tr>
            <td class="td_header">ID</td>
            <td class="td_header">Название</td>
            <td class="td_header">Количество правил</td>
            </tr>`
    );
    $(".ads_table").html(
        `<tr>
            <td class="td_header">ID</td>
            <td class="td_header">Название</td>
            <td class="td_header">spent</td>
            <td class="td_header">цена за регистрацию</td>
            <td class="td_header">количество регистраций</td>
            <td class="td_header">цена за установку</td>
            <td class="td_header">количество установок</td>
            <td class="td_header">цена за покупку</td>
        </tr>`
    );
    $(".loadingCampaigns").removeClass("hidden");
    var info = JSON.parse(data.info);
    var proxy_data = JSON.parse(info.proxy_data);
    if (data.accountId == "all") {
        var all_campaigns_raw = [];
        for (let p = 0; p < data.allAccountIds.length; p++) {
            const accountId = data.allAccountIds[p];
            var campaign_resp = await getCampaigns(accountId, data.facebookToken, proxy_data);
            all_campaigns_raw.push(campaign_resp);
            if (p == data.allAccountIds.length - 1) {
                for (let k = 0; k < all_campaigns_raw.length; k++) {
                    const campaign_element = all_campaigns_raw[k];

                    try {
                        if (campaign_element.data.length !== 0) {
                            // we have campaigns 
                            var all_campaigns = campaign_element.data;
                            $(".amount_of_campaigns").html(campaign_element.data.length);

                            for (let i = 0; i < all_campaigns.length; i++) {
                                const campaign = all_campaigns[i];
                                $(".campaigns_table").append(`
                                <tr>
                                <td class='campaign_id'>
                                    <input type='checkbox' data-id='${campaign.id}' data-data='${JSON.stringify(data)}' class='campaign_checkbox' onChange='refresh_ads_sections(); return false;'/>
                                    ${campaign.id}
                                </td>
                                <td>${campaign.name}</td>
                                <td>${campaign.objective}</td>
                                </tr>
                            `);
                                if (i == all_campaigns.length - 1) {
                                    $(".loadingCampaigns").addClass("hidden");
                                }
                            }
                        } else {
                            // there are no campaigns
                        }
                    } catch (error) {
                        setMessage("#22 warning", "<b>Рекламеные компании не загрузились</b> ");
                    }
                }
            }
        }
        console.log(data.allAccountIds);

    }
    var all_campaigns_raw = await getCampaigns(data.accountId, data.facebookToken, proxy_data);

    try {
        if (all_campaigns_raw.data.length !== 0) {
            // we have campaigns 
            var all_campaigns = all_campaigns_raw.data;
            $(".amount_of_campaigns").html(all_campaigns_raw.data.length);

            for (let i = 0; i < all_campaigns.length; i++) {
                const campaign = all_campaigns[i];
                $(".campaigns_table").append(`
                <tr>
                <td class='campaign_id'>
                    <input type='checkbox' data-id='${campaign.id}' data-data='${JSON.stringify(data)}' class='campaign_checkbox' onChange='refresh_ads_sections(); return false;'/>
                    ${campaign.id}
                </td>
                <td>${campaign.name}</td>
                <td>${campaign.objective}</td>
                </tr>
            `);
            }

        } else {
            // there are no campaigns
            setMessage("warning", "<b>Рекламеные компании не найденны</b> ");

        }
    } catch (error) {
        setMessage("#21 warning", "<b>Рекламеные компании не загрузились</b> ");
    }

}

async function refresh_ads_sections() {

    var all_checked_campaigns = $(".campaigns_table").find(".campaign_checkbox:checked");
    $(".amount_of_adsets, amount_of_ads").html("");
    $(".adsets_table").html(
        `<tr>
            <td class="td_header">ID</td>
            <td class="td_header">Название</td>
            <td class="td_header">Количество правил</td>
            </tr>`
    );

    $(".ads_table").html(
        `<tr>
            <td class="td_header">ID</td>
            <td class="td_header">Название</td>
            <td class="td_header">spent</td>
            <td class="td_header">цена за регистрацию</td>
            <td class="td_header">количество регистраций</td>
            <td class="td_header">цена за установку</td>
            <td class="td_header">количество установок</td>
            <td class="td_header">цена за покупку</td>
        </tr>`
    );

    var infoRaw = $(".info_div").html();
    var info = JSON.parse(infoRaw);
    var proxy_data = JSON.parse(info.proxy_data);
    var loadingElement = `<div class="loadingCircle sCirlce"></div>`;
    $(".amount_of_adsets").html(loadingElement);
    $(".amount_of_ads").html(loadingElement);



    for (let y = 0; y < all_checked_campaigns.length; y++) {
        const checked_campaign = all_checked_campaigns[y];

        var campaignId = $(checked_campaign).data("id");
        var data = $(checked_campaign).data("data");

        var all_adsets_raw = await getAdsets(campaignId, data.facebookToken, proxy_data);
        var all_ads_raw = await getAds(campaignId, data.facebookToken, proxy_data);

        if (all_ads_raw.data.length !== 0) {
            // we have ads
            var all_ads = all_ads_raw.data;

            for (let i = 0; i < all_ads.length; i++) {
                const ads = all_ads[i];
                var omni_complete_registration = "";

                if (typeof ads.stats.actions.omni_complete_registration !== "undefined" && ads.stats.actions.omni_complete_registration !== null) {
                    omni_complete_registration = ads.stats.actions.omni_complete_registration;
                }
                $(".ads_table").append(`
                <tr>
                    <td class='ad_id'>${ads.id}</td>
                    <td>${ads.name}</td>
                    <td>${ads.spent}</td>
                    <td>${ads.cost_per_complete_registration_fb}</td>
                    <td>${omni_complete_registration}</td>
                    <td>${ads.cost_per_mobile_app_install}</td>
                    <td>${ads.stats.actions.omni_app_install}</td>
                    <td>${ads.cost_per_purchase_fb}</td>
                </tr>
            `);
                if (i == all_ads.length - 1) {

                    var amount_of_ads = $(".ads_table").children();
                    $(".amount_of_ads").html(amount_of_ads.length - 1);
                }
            }

        } else {
            // there are no campaigns
        }

        if (all_adsets_raw.data.length !== 0) {
            // we have adsets 
            var all_adsets = all_adsets_raw.data;

            for (let i = 0; i < all_adsets.length; i++) {
                const adset = all_adsets[i];
                var adset_gov = 0;
                if (typeof adset.adrules_governed !== "undefined") {
                    if (adset.adrules_governed.data.length !== 0) {
                        adset_gov = adset.adrules_governed.data.length;
                    }
                }

                $(".adsets_table").append(`
                <tr>
                    <td class='adset_id'>
                        <a href="/adset?adset_id=${adset.id}&uid=${info.id}" target="_blank">${adset.id}</a>
                    </td>
                    <td>${adset.name}</td>
                    <td>${adset_gov}</td>
                </tr>
            `);
                if (i == all_adsets.length - 1) {

                    var amount_of_adsets = $(".adsets_table").children();
                    $(".amount_of_adsets").html(amount_of_adsets.length - 1);
                }
            }

        } else {
            // there are no campaigns
        }
    }
}

function downloadAdAccounts() {


    var all_options = $(".adAccountSelect").children();
    var textFromOptions = [];
    var allInText = "";

    for (let o = 0; o < all_options.length; o++) {
        const option = all_options[o];
        var text = $(option).html().trim();

        if (text !== "Выберите..." && text !== "Все") {
            textFromOptions.push(text);
            allInText = allInText + text + "\n";
        } else {
            continue;
        }

        if (o == all_options.length - 1) {
            alert(allInText);
        }
    }
}