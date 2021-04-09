function lockButton(button, newText) {
    button.attr("disabled", "true").addClass("disabled_button");
    if (newText.trim() !== "" && typeof newText !== "undefined") {
        button.val(newText);
    }
}

function unlockButton(button, newText) {
    button.removeAttr("disabled", "true").removeClass("disabled_button");
    if (newText.trim() !== "" && typeof newText !== "undefined") {
        button.val(newText);
    }
}

function setMessage(type, text) {
    switch (type) {
        case "error":
            $(".message_container").prepend(`
                <div class="error_message_box message_box">` + text + `<svg version="1.1" onclick="removeMessage(this)" class="cross_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="20px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                <path class="cross" d="M29.5,27.9c0.4,0.3,0.5,0.9,0.2,1.3c-0.1,0.1-0.1,0.2-0.2,0.2c-0.3,0.4-0.9,0.5-1.3,0.2
                c-0.1-0.1-0.1-0.1-0.2-0.2L15,16.4L2,29.4c-0.4,0.4-1.1,0.4-1.5,0s-0.4-1.1,0-1.5l13.1-13L0.5,1.9C0,1.6,0,1,0.2,0.6
                c0.1-0.1,0.1-0.1,0.2-0.2C0.7,0,1.3-0.1,1.7,0.2C1.8,0.2,1.9,0.3,2,0.4l13,13.1l13-13c0.3-0.4,0.9-0.5,1.3-0.2
                c0.1,0.1,0.2,0.1,0.2,0.2C30,0.7,30,1.3,29.8,1.7c-0.1,0.1-0.1,0.1-0.2,0.2l-13.1,13L29.5,27.9z"></path>
                </svg></div>`);

            break;
        case "success":
            $(".message_container").prepend(`
                <div class="success_message_box message_box">` + text + `<svg version="1.1" onclick="removeMessage(this)" class="cross_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="20px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                <path class="cross" d="M29.5,27.9c0.4,0.3,0.5,0.9,0.2,1.3c-0.1,0.1-0.1,0.2-0.2,0.2c-0.3,0.4-0.9,0.5-1.3,0.2
                  c-0.1-0.1-0.1-0.1-0.2-0.2L15,16.4L2,29.4c-0.4,0.4-1.1,0.4-1.5,0s-0.4-1.1,0-1.5l13.1-13L0.5,1.9C0,1.6,0,1,0.2,0.6
                  c0.1-0.1,0.1-0.1,0.2-0.2C0.7,0,1.3-0.1,1.7,0.2C1.8,0.2,1.9,0.3,2,0.4l13,13.1l13-13c0.3-0.4,0.9-0.5,1.3-0.2
                  c0.1,0.1,0.2,0.1,0.2,0.2C30,0.7,30,1.3,29.8,1.7c-0.1,0.1-0.1,0.1-0.2,0.2l-13.1,13L29.5,27.9z"></path>
                </svg></div>`);
            break;

        case "warning":
            $(".message_container").prepend(`
                <div class="warn_message_box message_box">` + text + `<svg version="1.1" onclick="removeMessage(this)" class="cross_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="20px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                <path class="cross" d="M29.5,27.9c0.4,0.3,0.5,0.9,0.2,1.3c-0.1,0.1-0.1,0.2-0.2,0.2c-0.3,0.4-0.9,0.5-1.3,0.2
                    c-0.1-0.1-0.1-0.1-0.2-0.2L15,16.4L2,29.4c-0.4,0.4-1.1,0.4-1.5,0s-0.4-1.1,0-1.5l13.1-13L0.5,1.9C0,1.6,0,1,0.2,0.6
                    c0.1-0.1,0.1-0.1,0.2-0.2C0.7,0,1.3-0.1,1.7,0.2C1.8,0.2,1.9,0.3,2,0.4l13,13.1l13-13c0.3-0.4,0.9-0.5,1.3-0.2
                    c0.1,0.1,0.2,0.1,0.2,0.2C30,0.7,30,1.3,29.8,1.7c-0.1,0.1-0.1,0.1-0.2,0.2l-13.1,13L29.5,27.9z"></path>
                </svg></div>`);
            break;

        default:
            break;
    }
}

function removeMessage(elem) {
    $(elem).parent().remove();
}

function closePopupWindow() {
    $(".grey_background").addClass("hidden");
    $(".popup_window").html("").addClass("hidden");
}

function showPopup() {
    $(".grey_background").removeClass("hidden");
    $(".popup_window").removeClass("hidden").html(`<div class="popup_loading">Ожидайте...</div>`);
}