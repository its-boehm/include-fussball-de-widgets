/* eslint-disable */

/*
 * fussball.de widgetAPI
 */

var egmWidget2 = {};

egmWidget2.url = "//www.fussball.de/widget2";
egmWidget2.referer = location.host
  ? encodeURIComponent(location.host)
  : "unknown";

var FussballdeWidgetAPI = function() {
  var widgetObj = {};

  widgetObj.showWidget = function(targetId, apiKey, fullWidth) {
    if (
      apiKey !== undefined &&
      apiKey !== null &&
      apiKey !== "" &&
      targetId !== undefined &&
      targetId !== null &&
      targetId !== ""
    ) {
      if (document.getElementById(targetId)) {
        if (apiKey !== "") {
          createIFrame(
            targetId,
            egmWidget2.url +
              "/-/schluessel/" +
              apiKey +
              "/target/" +
              targetId +
              "/caller/" +
              egmWidget2.referer,
            fullWidth
          );
        }
      } else {
        console.log(
          "Can't display the iframe. The DIV with the ID=\"" +
            targetId +
            '"is missing.'
        );
      }
    }
  };

  window.addEventListener(
    "message",
    function(event) {
      var currentIframe = document.querySelectorAll(
        "#" + event.data.container + " iframe"
      )[0];
      if (event.data.type === "setHeight") {
        currentIframe.setAttribute("height", event.data.value + "px");
      }
      if (
        currentIframe.getAttribute("width") !== "100%" &&
        event.data.type === "setWidth"
      ) {
        currentIframe.setAttribute("width", event.data.value + "px");
      }
    },
    false
  );

  return widgetObj;
};

function createIFrame(parentId, src, fullWidth) {
  var parent = document.getElementById(parentId);
  var iframe = document.createElement("iframe");

  iframe.frameBorder = 0;
  iframe.setAttribute("src", src);
  iframe.setAttribute("scrolling", "no");
  iframe.setAttribute("width", fullWidth ? "100%" : "900");
  iframe.setAttribute("height", "500");
  iframe.setAttribute("style", "border: 1px solid #CECECE;");

  parent.innerHTML = "";
  parent.appendChild(iframe);
}
