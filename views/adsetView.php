<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>TraffBraza - аккаунт</title>
    <!-- place logo here  -->
    <link id="dynamic-favicon" rel="shortcut icon" href="../assets/imgs/toco_logo.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="../assets/js/tab_switcher.js"></script>
    <script src="../assets/js/helper.js"></script>
    <script src="../assets/js/adsets.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../assets/css/stylesheet-main.css" />

</head>

<body>
    <?php include("headerView.php");

    $userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
    $userEmail    =  (isset($_SESSION["UserEmail"]) ? $_SESSION["UserEmail"] : $_COOKIE["UserEmail"]);
    include '../app/classes/MysqlConnection.php';
    include '../app/classes/Adset.php';
    include '../app/classes/Rule.php';
    include '../app/classes/Account.php';
    include '../app/classes/Proxy.php';


    $adset = new Adset($userId);
    $rule = new Rule($userId);
    $account = new Account($userId);

    $adsetId = $_GET["adset_id"];
    $accountId = $_GET["uid"];
    $accountData = $account->getAccountSafe("id", $accountId);

    $facebookToken = $accountData["facebook_token"];
    // get adset from facebook
    $rawAdsetResponse = $adset->getAdsetById($adsetId, $accountData);
    $AdsetResponse = json_decode($rawAdsetResponse);

    if (isset($AdsetResponse->adrules_governed)) {
        // get ad rules from facebook by their id
        $adsetRulesIds = $AdsetResponse->adrules_governed->data;
        $rules_from_facebook = [];

        for ($i = 0; $i < count($adsetRulesIds); $i++) {
            $ruleId = $adsetRulesIds[$i]->id;
            $RuleInfoFromFacebookCallback = $rule->getRuleInfoByIdFromFacebook($ruleId, $accountData);
            $rules_from_facebook[] = json_decode($RuleInfoFromFacebookCallback);
        }

        $all_rule_statuses = $rule->getAllStatuses();
        $all_filters_evaluation_operators = $rule->getAllEvaluationOperators();
        $all_execution_types = $rule->getAllExecutionTypes();
        $all_schedule_types = $rule->getAllScheduleTypes();
        $all_evaluation_types = $rule->getAllEvaluationTypes();
    } else {
        $adsetRulesIds = "empty";
    }

    ?>

    <div class="body_container">
        <div class="body_inner">
            <div class="page_nav_back">
                <svg class="nav_back_svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="18px" height="18px">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                </svg>
                <a href="/home">На главную</a>
            </div>
            <div class="page_header">
                <?= $AdsetResponse->name ?> (<?= $AdsetResponse->id ?>)
            </div>
            <div class="rules_container">

                <?php 
                if ( isset($rules_from_facebook) ) {
                    foreach ($rules_from_facebook as $key => $value) { ?>

                    <div class="rule_element">
                        <div class="rule_name"><?= $value->name ?> (<?= $value->id ?>)</div>
                        <div class="rule_status">
                            <select class="rules_status_select" data-id="<?= $value->id; ?>">
                                <?php foreach ($all_rule_statuses as $key => $rule_statuses) {
                                    $selected = "";
                                    if ($rule_statuses == $value->status) {
                                        $selected = "selected";
                                    }
                                    echo "<option value='$rule_statuses' $selected>$rule_statuses</option>";
                                } ?>
                            </select>
                        </div>
                        <hr>
                        </hr>
                        <div class="filters_container">
                            <div class="evaluation_spec">
                                <div class="rules_section_title">Evaluation specs</div>
                                Evaluation type :
                                <select class="evaluation_type_select" data-id="<?= $value->id; ?>">
                                    <?php
                                    foreach ($all_evaluation_types as $key => $evaluation_type) {
                                        $selected = "";
                                        if ($evaluation_type === $value->evaluation_spec->evaluation_type) {
                                            $selected = "selected";
                                        }
                                        echo "<option value='$evaluation_type' $selected>$evaluation_type</option>";
                                    } ?>
                                </select>

                                <?php foreach ($value->evaluation_spec->filters as $key => $evaluation_filter) {
                                    if (is_array($evaluation_filter->value)) { ?>
                                        <div class="filter_element filter_elements_from_facebook" data-id="<?= $value->id; ?>">
                                            <div><input type="text" class="rules_field" data-id="<?= $value->id; ?>" value="<?= $evaluation_filter->field ?>" /></div>
                                            <div>
                                                <select class="rules_filter_operator" data-id="<?= $value->id; ?>">
                                                    <?php foreach ($all_filters_evaluation_operators as $key => $filters_evaluation_operator) {
                                                        $selected = "";
                                                        if ($evaluation_filter->operator === $filters_evaluation_operator) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option value='$filters_evaluation_operator' $selected>$filters_evaluation_operator</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="rules_array_value_container" data-id="<?= $value->id ?>">
                                                <?php for ($p = 0; $p < count($evaluation_filter->value); $p++) {
                                                    echo "<div class='value_array_elements' data-id='" . $value->id . "' data-value='" . $evaluation_filter->value[$p] . "'>" . $evaluation_filter->value[$p] . "<div class='action_e'  onclick='removeValueArrayElement(this); return false;' data-id='" . $value->id . "'>+</div></div>";
                                                } ?>
                                                <div class='value_array_elements add_array_element'><input class="array_value_input" type="text" data-id="<?= $value->id; ?>" />
                                                    <div class='action_e add_e' onclick="addFilterArrayValue(this); return false;" data-id="<?= $value->id; ?>">+</div>
                                                </div>
                                                <div class="filterValueFieldToggle" data-id="<?= $value->id; ?>" onclick="toggleFilterValueContent(this); return false;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="20px" height="20px">
                                                        <path d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="action_e action_e_big" onclick="removeFilterElement(this); return false;">
                                                +
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="filter_element filter_elements_from_facebook" data-id="<?= $value->id; ?>">
                                            <div><input type="text" class="rules_field" data-id="<?= $value->id; ?>" value="<?= $evaluation_filter->field ?>" /></div>
                                            <div>

                                                <select class="rules_filter_operator" data-id="<?= $value->id; ?>">
                                                    <?php foreach ($all_filters_evaluation_operators as $key => $filters_evaluation_operator) {
                                                        $selected = "";
                                                        if ($evaluation_filter->operator === $filters_evaluation_operator) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option value='$filters_evaluation_operator' $selected>$filters_evaluation_operator</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="text" class="rules_filter_value" data-id="<?= $value->id; ?>" value="<?= $evaluation_filter->value ?>" />
                                                <div class="filterValueFieldToggle" data-id="<?= $value->id; ?>" onclick="toggleFilterValueContent(this); return false;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="20px" height="20px">
                                                        <path d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="action_e action_e_big" onclick="removeFilterElement(this); return false;">
                                                +
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                                <div class="filter_element add_filter_element" data-id="<?= $value->id; ?>" onclick="updateChangedFieldsAndUnlockActionButton('<?= $value->id; ?>', 'rules_field'); return false;">
                                    + Добавить ещё одни фильтр
                                </div>
                            </div>
                            <hr>
                            </hr>
                            <div class="execution_spec">
                                <div class="rules_section_title">Execution specs</div>
                                execution type :
                                <select class="execution_type_select" data-id="<?= $value->id; ?>">
                                    <?php
                                    foreach ($all_execution_types as $key => $execution_type) {
                                        $selected = "";
                                        if ($execution_type === $value->execution_spec->execution_type) {
                                            $selected = "selected";
                                        }
                                        echo "<option value='$execution_type' $selected>$execution_type</option>";
                                    } ?>
                                </select>
                            </div>
                            <hr>
                            </hr>
                            <div class="schedule_spec">
                                <div class="rules_section_title">schedule specs</div>
                                schedule type :
                                <select class="schedule_type_select" data-id="<?= $value->id; ?>">
                                    <?php
                                    foreach ($all_schedule_types as $key => $schedule_type) {
                                        $selected = "";
                                        if ($schedule_type === $value->schedule_spec->schedule_type) {
                                            $selected = "selected";
                                        }
                                        echo "<option value='$schedule_type' $selected>$schedule_type</option>";
                                    } ?>
                                </select>
                            </div>
                            <hr>
                            </hr>
                            <div class="rules_submit_button_container">
                                <input type="button" value="Нет изменений" data-id="<?= $value->id; ?>" class="default_button default_button_inactive rules_submit_button" disabled>
                            </div>
                        </div>
                    </div>

                <?php }
                } else { ?>

                    <h1 class="light_h1">У данного адсэта нет автоправил</h1>
               <?php  }
                 ?>
            </div>
        </div>
    </div>
    <div class="hidden info_div"><?= json_encode(["token" => $facebookToken]) ?></div>
</body>

</html>