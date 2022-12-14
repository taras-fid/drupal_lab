/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/
(function (_ref) {
  var offCanvas = _ref.offCanvas;
  var originalResetSize = offCanvas.resetSize;

  offCanvas.resetSize = function (event) {
    originalResetSize(event);
    event.data.$element.attr('data-resize-done', 'true');
  };
})(Drupal);