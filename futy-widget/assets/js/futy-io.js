window.Futy = {
  key: data["widget_code"],
  meta: "wp: " + data["plugin_version"]
};

(function (e, t) {
  var n = e.createElement(t);
  n.async = true;
  var f = window.Promise && window.fetch ? "modern.js" : "legacy.js";
  n.src = "https://v1.widget.futy.io/js/futy-widget-" + f;
  var r = e.getElementsByTagName(t)[0];
  r.parentNode.insertBefore(n, r);
})(document, "script");
