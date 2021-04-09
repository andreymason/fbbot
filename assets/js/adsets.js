var changedFields = [];

$("document").ready(function() {
    $(`
    .rules_status_select,
    .rules_field, 
    .rules_filter_operator,
    .rules_filter_value,
    .evaluation_type_select,
    .execution_type_select,
    .schedule_type_select
    `).on("change", function(event) {
        updateChangedFieldsAndUnlockActionButton(this, event.target.className);
    });

    $(".rules_submit_button").on("click", function() {
        updateRule(this, changedFields);
    });
    $(".add_filter_element").on("click", function() {
        var id = $(this).data("id");
        $(this).before(`<div class="filter_element filter_elements_from_facebook" data-id="` + id + `">
        <div>
            <input type="text" class="rules_field" data-id="` + id + `" />
        </div>
        <div>
            <select class="rules_filter_operator" data-id="` + id + `">
                <option value="LESS_THAN">LESS_THAN</option>
                <option value="GREATER_THAN">GREATER_THAN</option>
                <option value="EQUAL">EQUAL</option><option value="NOT_EQUAL">NOT_EQUAL</option><option value="IN_RANGE">IN_RANGE</option><option value="NOT_IN_RANGE">NOT_IN_RANGE</option><option value="IN">IN</option><option value="NOT_IN">NOT_IN</option><option value="CONTAIN">CONTAIN</option><option value="NOT_CONTAIN">NOT_CONTAIN</option><option value="ANY">ANY</option><option value="ALL">ALL</option><option value="NONE">NONE</option>                                            
            </select>
        </div>
        <div>
            <input type="text" class="rules_filter_value" data-id="` + id + `" />
            <div class="filterValueFieldToggle" data-id="` + id + `" onclick="toggleFilterValueContent(this); return false;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="20px" height="20px">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                </svg>
            </div>
        </div>
        <div class="action_e action_e_big" onclick="removeFilterElement(this); return false;">
            +
        </div>
        </div>`);
    });

    // $(".add_e").on("click", function() {
    // var id = $(this).data("id");
    // updateChangedFieldsAndUnlockActionButton(this, "rules_filter_value");

    // var text = $(".array_value_input[data-id='" + id + "']").val();
    // // clear textfield
    // $(".array_value_input[data-id='" + id + "']").val("");
    // $(this).parent().before(`<div class="value_array_elements" data-id="${id}" data-value="${text}">${text}<div class="action_e" onclick="removeValueArrayElement(this); return false;" data-id="${id}">+</div></div>`);
    // });
});

function addFilterArrayValue(elem) {
    var id = $(elem).data("id");
    var inputField = $(elem).parent().find(".array_value_input[data-id='" + id + "']");
    updateChangedFieldsAndUnlockActionButton(elem, "rules_filter_value");
    var text = inputField.val();
    // clear textfield
    inputField.val("");
    $(elem).parent().before(`<div class="value_array_elements" data-id="${id}" data-value="${text}">${text}<div class="action_e" onclick="removeValueArrayElement(this); return false;" data-id="${id}">+</div></div>`);
}

function toggleFilterValueContent(elem) {
    var id = $(elem).data("id");
    var valueContainer = $(elem).parent();
    console.log(valueContainer);
    console.log(valueContainer.find(".rules_filter_value"));
    if (valueContainer.find(".rules_filter_value").length !== 0) {
        valueContainer.find(".rules_filter_value").remove();
        valueContainer.prepend(`
        <div class="value_array_elements add_array_element">
        <input class="array_value_input" type="text" data-id="` + id + `">
        <div class="action_e add_e" onclick="addFilterArrayValue(this); return false;" data-id="` + id + `">+</div>
        </div>
        `);
    } else {
        valueContainer.find(".value_array_elements").remove();
        valueContainer.prepend(`
        <input type="text" class="rules_filter_value" data-id="` + id + `" value="">
        `);
    }
}

function removeFilterElement(elem) {
    $(elem).parent().remove();
}

function updateChangedFieldsAndUnlockActionButton(elem, field) {
    if (!changedFields.includes(field)) {
        changedFields.push(field);
    }
    if (typeof elem == "string") {
        unlockRulesSubmitButton($(".rules_submit_button[data-id='" + elem + "']"));
    } else {
        unlockRulesSubmitButton(elem);
    }
}

function removeValueArrayElement(elem) {
    $(elem).parent().remove();
    updateChangedFieldsAndUnlockActionButton(elem, "rules_filter_value");
}

function setLoadingStatusSubmitButton(button) {
    $(button).addClass("default_button_loading").val("Ожидайте...").attr("disabled", "");
}

function setDefaultStatusSubmitButton(button) {
    $(button).removeClass("default_button_loading").addClass("default_button default_button_inactive").val("Нет изменений").attr("disabled", "");
}

function unlockRulesSubmitButton(elem) {
    var rule_id = $(elem).data("id");
    $(".rules_submit_button[data-id='" + rule_id + "']").removeClass("default_button_inactive default_button_loading").val("Применить").removeAttr("disabled");
}

function updateRule(button, changedFields) {
    setLoadingStatusSubmitButton(button);
    var rule_id = $(button).data("id");
    var info_div = $(".info_div").html();
    var postData = {};
    var access_token = JSON.parse(info_div).token;
    console.log(access_token);

    console.log(changedFields);

    for (let i = 0; i < changedFields.length; i++) {
        const element = changedFields[i];
        if (element == "rules_status_select") {
            postData.status = $(".rules_status_select[data-id='" + rule_id + "']").val();
        }

        if (element == "evaluation_type_select" ||
            element == "rules_field" ||
            element == "rules_filter_operator" ||
            element == "rules_filter_value"
        ) {
            var evaluation_filters = $(".filter_elements_from_facebook[data-id='" + rule_id + "']");
            var evaluation_type = $(".evaluation_type_select[data-id='" + rule_id + "']").val();
            var evaluation_filters_data = [];

            for (let o = 0; o < evaluation_filters.length; o++) {
                const element = evaluation_filters[o];
                console.log(element);
                var field = $(element).find(".rules_field").val();
                var operator = $(element).find(".rules_filter_operator").val();
                var value = $(element).find(".rules_filter_value").val();

                if (typeof value !== "undefined") {
                    evaluation_filters_data.push({
                        "field": field,
                        "operator": operator,
                        "value": value
                    });
                } else {
                    var all_array_values = [];
                    all_array_values_elements = $(".rules_array_value_container[data-id='" + rule_id + "']").find(".value_array_elements[data-id='" + rule_id + "']");
                    for (let l = 0; l < all_array_values_elements.length; l++) {
                        const array_value_element = all_array_values_elements[l];
                        all_array_values.push($(array_value_element).data("value").toString());
                        if (l == all_array_values_elements.length - 1) {
                            evaluation_filters_data.push({
                                "field": field,
                                "operator": operator,
                                "value": all_array_values
                            });
                        }
                    }

                }
                if (o == evaluation_filters.length - 1) {
                    postData.evaluation_spec = {
                        "evaluation_type": evaluation_type,
                        "filters": evaluation_filters_data
                    }
                }
            }
        }

        if (element == "execution_type_select") {

        }
        if (element == "schedule_type_select") {
            var schedule_type = $(".schedule_type_select[data-id='" + rule_id + "']").val();

            postData.schedule_spec = {
                "schedule_type": schedule_type
            }
        }

        if (i == changedFields.length - 1) {
            console.log("done");
        }
    }

    var request_url = "https://graph.facebook.com/v10.0/" + rule_id + "?access_token=" + access_token;

    console.log(request_url);
    console.log(postData);
    $.ajax({
            method: "POST",
            url: request_url,
            contentType: 'application/json',
            data: JSON.stringify(postData)
        })
        .done(function(callback) {
            console.log(callback);
            if (callback.success) {
                setMessage("success", "<b>Правило " + rule_id + " успешно обновлено!</b>");
                setDefaultStatusSubmitButton($(".rules_submit_button[data-id='" + rule_id + "']"));

            } else {
                console.log(callback);
            }
            window.scrollTo(0, 0);
        })
        .fail(function(err) {
            var errorMessage = err.responseJSON.error.message;
            setMessage("error", "<b>Не удалось обновить правило : </b> " + errorMessage);
            console.log(err);
            unlockRulesSubmitButton($(".rules_submit_button[data-id='" + rule_id + "']"));
            window.scrollTo(0, 0);
        });
}