/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal) {
  Drupal.behaviors.menuUiChangeParentItems = {
    attach: function attach(context, settings) {
      var menu = once('menu-parent', '#edit-menu');
      if (menu.length) {
        var $menu = $(menu);
        Drupal.menuUiUpdateParentList();

        $menu.on('change', 'input', Drupal.menuUiUpdateParentList);
      }
    }
  };

  Drupal.menuUiUpdateParentList = function () {
    var $menu = $('#edit-menu');
    var values = [];
    $menu.find('input:checked').each(function () {
      values.push(Drupal.checkPlain(this.value));
    });
    $.ajax({
      url: "".concat(window.location.protocol, "//").concat(window.location.host).concat(Drupal.url('admin/structure/menu/parents')),
      type: 'POST',
      data: {
        'menus[]': values
      },
      dataType: 'json',
      success: function success(options) {
        var $select = $('#edit-menu-parent');
        var selected = $select[0].value;
        $select.children().remove();
        var totalOptions = 0;
        Object.keys(options || {}).forEach(function (machineName) {
          var selectContents = document.createElement('option');
          selectContents.selected = machineName === selected;
          selectContents.value = machineName;
          selectContents.textContent = options[machineName];
          $select.append(selectContents);
          totalOptions++;
        });

        $select.closest('div').toggle(totalOptions > 0).attr('hidden', totalOptions === 0);
      }
    });
  };
})(jQuery, Drupal);