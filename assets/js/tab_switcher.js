$(document).ready(function() {
    $(".tab").on("click", function() {
        $(".tab").removeClass("selected_tab");
        $(this).addClass("selected_tab");
        var target = $(this).data("for");
        switchTab(target);
    });
});

function switchTab(target) {
    var wrapper = $(".section_wrapper");
    wrapper.removeClass("wrapper_position_0 wrapper_position_1 wrapper_position_2");

    switch (target) {
        case "campaign":
            wrapper.addClass("wrapper_position_0");
            break;
        case "adsets":
            wrapper.addClass("wrapper_position_1");
            break;
        case "ads":
            wrapper.addClass("wrapper_position_2");
            break;

        default:
            break;
    }
}