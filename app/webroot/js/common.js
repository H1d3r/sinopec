function HelpBase() { };

HelpBase.prototype.AutoScroll = function () {
    var scrollHeight = "";
    if (location.href.indexOf("?") == -1 || location.href.indexOf('sc=') == -1) {
        return;
    }

    var queryString = location.href.substring(location.href.indexOf("?") + 1);

    var parameters = queryString.split("&");

    var pos, paraName, paraValue;
    for (var i = 0; i < parameters.length; i++) {
        pos = parameters[i].indexOf('=');
        if (pos == -1) { continue; }

        paraName = parameters[i].substring(0, pos);
        paraValue = parameters[i].substring(pos + 1);

        if (paraName == 'sc') {
            scrollHeight = unescape(paraValue.replace(/\+/g, " "));
            break;
        }
    }

    if (scrollHeight != "") {
        window.scroll(0, scrollHeight);
    }
};