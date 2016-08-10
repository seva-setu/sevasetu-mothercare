jQuery(document).ready(function(){
    
    if (!Date.now) {
        Date.now = function() {
            return new Date().valueOf();
        }
    }

    if(countdown_expiration > 0){
        var timestamp = countdown_expiration - Date.now() / 1000;
        //timestamp /= 1000;
        function component(x, v) {
            return Math.floor(x / v);
        }
        var $div = jQuery('#tile_timer .time');
        setInterval(function() {
            timestamp--;
            var days    = component(timestamp, 24 * 60 * 60),
            hours   = component(timestamp, 60 * 60) % 24,
            minutes = component(timestamp, 60) % 60,
            seconds = component(timestamp, 1) % 60;
            refreshCountdown([Math.abs(days), Math.abs(hours), Math.abs(minutes), Math.abs(seconds)]);
        }, 1000);
    }
});
    
function refreshCountdown(periods) {
    var i, j = 0;
    var elements_arr = ['.days', '.hours', '.minutes', '.seconds'];

    for(i = 0; i < periods.length; i++){
        periods[i] = String(periods[i].toString()).slice((i==0 ? -3 : -2));
        j = periods[i].length - 1;
        jQuery('#tile_timer ' + elements_arr[i]).contents().filter(function() {
            return this.nodeType == 3; //Node.TEXT_NODE
        }).remove();
        while( j >= 0) {
            var digit = periods[i].charAt(j);
            jQuery('#tile_timer ' + elements_arr[i]).prepend(digit);
            j--;
        }
    }
}
    
function numPadding (number, paddingChar,i) {
    //var padding = new Array(number + 1).join(paddingChar);
    //return padding.substr(0, padding.length - (Math.floor(i).toString().length)) + Math.floor(i );
    return number;
}