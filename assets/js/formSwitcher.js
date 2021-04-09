$(document).ready(function() {
    var current_slide_pos = $(".selected_slide_pos").val();

    $(".form_toggle_button").on("click", function() {
        
        var select_class = "form_toggle_button_selected";
        var slide_1 = $(".form_slide_1");
        var slide_2 = $(".form_slide_2");

        if ( !$(this).hasClass(select_class) ) {
            
            $(".form_toggle_button").removeClass(select_class); 
            $(this).addClass(select_class);

            if ( current_slide_pos === "1" ) {                
                slide_1.addClass("form_slide_1_off");
                slide_2.addClass("form_slide_2_on");
                current_slide_pos = "2";
            }else if ( current_slide_pos === "2" ) {
                slide_1.removeClass("form_slide_1_off");
                slide_2.removeClass("form_slide_2_on");
                current_slide_pos = "1";
            }
        }
    });
});