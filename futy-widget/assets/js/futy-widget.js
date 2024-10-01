window.Widget = {
    key: data['widget_code'],
    wp: data['plugin_version'],
};

(function (e, t) {
    var n = e.createElement(t);
    n.async = true;
    n.src = 'https://static.futy-widget.com/js/widget.js';
    var r = e.getElementsByTagName(t)[0];
    r.parentNode.insertBefore(n, r);
})(document, 'script');