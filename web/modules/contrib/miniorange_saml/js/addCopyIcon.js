jQuery(document).ready( function($){
    $(".mo_copy").click(function () {
        copyToClipboard('#'+$(this).prev().attr("id"));
    });
});
function copyToClipboard(element) {
    jQuery(".selected-text").removeClass("selected-text");
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    jQuery(element).addClass("selected-text");
    temp.val(jQuery(element).text().trim()).select();
    document.execCommand("copy");
    temp.remove();
}
jQuery(window).click(function(e) {
    if( e.target.className == undefined || e.target.className.indexOf("mo_copy") == -1)
        jQuery(".selected-text").removeClass("selected-text");

});