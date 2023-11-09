NumberOfLiveEasys = 0;
EasyAlert = {};
EasyAlert.Easy = function (data) {
    if ($("body .Easy.go")) { // if an Easy already exists on page
        $("body .Easy.go").each(function () {
            var currentBottomPixels = $(this).css("bottom"); // get bottom margin
            var currentBottom = currentBottomPixels.split('p')[0]; // remove the px
            var EasyHeight = $(this).height() + Number(currentBottom) + 30; // the height of the Easy + current bottom margin
            $(this).css("bottom", EasyHeight); // Apply new margin amount
        });
    }
    NumberOfLiveEasys = NumberOfLiveEasys + 1;
    var CurrentEasy = NumberOfLiveEasys + 1;
    var title = data.title;
    var text = data.text;
    var removeTime = data.time;
    var bkgrndColour = data.bkgrndColour;
    var textColour = data.textColour;
    $("body").append("<div class='Easy Easy-" + CurrentEasy + "' style='background-color: " + bkgrndColour + "'><p style='color:" + textColour + "'>"+title+"</p><p style='color:" + textColour + "'>" + text + "</p></div>");
    setTimeout(function () {
        $(".Easy-" + CurrentEasy + "").addClass("go"); // animation to display
    }, 250);
    setTimeout(function () {
        $(".Easy-" + CurrentEasy + "").addClass("stop"); // animation to remove
        setTimeout(function () {
            $(".Easy-" + CurrentEasy + "").remove(); // remove EasyAlert from DOM
        }, 2000);
    }, removeTime);
}

function jAlertWorning(title,text){
    EasyAlert.Easy({
        title: title,text:text,time:"5000",
        bkgrndColour:"blue",textColour:"#FFFFFF"
    });
}
function jAlertError(title,text){
    EasyAlert.Easy({
        title: title, text: text, time: "5000",
        bkgrndColour: "red", textColour: "#FFFFFF"
    });
}
function jAlertMessage(title,text){
    EasyAlert.Easy({
            title: title, text: text,
            time: "5000", bkgrndColour: "#000000",
            textColour: "#FFFFFF"
    });
}

