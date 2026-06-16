(function () {
  var cachedItems = [];
  var badgeCount = 0;
  var dropdownOpen = false;

  function getRKey() {
    var input = document.querySelector('input[name="rKey"]');
    if (input && input.value) {
      return input.value;
    }
    var params = new URLSearchParams(window.location.search);
    return params.get('rKey') || '';
  }

  function getMParam() {
    var input = document.querySelector('input[name="m"]');
    if (input && input.value) {
      return input.value;
    }
    var params = new URLSearchParams(window.location.search);
    return params.get('m') || '';
  }

  function getDropdown() {
    return document.querySelector('.bukken-alert-dropdown');
  }

  function closeDropdown() {
    var dropdown = getDropdown();
    if (dropdown) {
      dropdown.style.display = 'none';
    }
    dropdownOpen = false;
  }

  function updateBadge(count) {
    badgeCount = count;
    var badge = document.querySelector('.bukken-alert-badge');
    if (!badge) {
      return;
    }
    if (count > 0) {
      badge.textContent = count > 99 ? '99+' : String(count);
      badge.classList.add('bukken-alert-badge--visible');
    } else {
      badge.textContent = '';
      badge.classList.remove('bukken-alert-badge--visible');
    }
  }

  function renderDropdownItems(items) {
    var dropdown = getDropdown();
    if (!dropdown) {
      return;
    }

    var html = '<div class="bukken-alert-title">WEB予約・更新</div>';
    if (items.length === 0) {
      html += '<div class="bukken-alert-empty">未読はありません</div>';
    } else {
      items.forEach(function (item) {
        var name = item.BukkenName;
        if (name === false || name === null || name === undefined) {
          name = '物件CD:' + item.BukkenCD;
        }
        html += '<a href="#" class="bukken-alert-item" data-bukkencd="' + item.BukkenCD + '">';
        html += name;
        html += '</a>';
      });
    }
    dropdown.innerHTML = html;

    dropdown.querySelectorAll('.bukken-alert-item').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var bukkenCD = el.getAttribute('data-bukkencd');
        var rKey = getRKey();
        var m = getMParam();
        var url = 's_menu.php?rKey=' + encodeURIComponent(rKey) + '&editBukkenCD=' + encodeURIComponent(bukkenCD);
        if (m) {
          url += '&m=' + encodeURIComponent(m);
        }

        fetch('mark_bukken_alert_read.php?rKey=' + encodeURIComponent(rKey) + '&editBukkenCD=' + encodeURIComponent(bukkenCD))
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (!data || !data.ok) {
              return;
            }
            cachedItems = cachedItems.filter(function (item) {
              return String(item.BukkenCD) !== String(bukkenCD);
            });
            updateBadge(cachedItems.length);
            renderDropdownItems(cachedItems);
            window.location.href = url;
          })
          .catch(function () {});
      });
    });
  }

  function renderAlerts(data) {
    var bell = document.getElementById('bukken-alert-bell');
    if (!bell) {
      return;
    }

    var count = (data && data.count) ? data.count : 0;
    var items = (data && data.items) ? data.items : [];
    cachedItems = items.slice();

    bell.style.display = 'inline-flex';
    updateBadge(count);
    renderDropdownItems(cachedItems);
  }

  function toggleDropdown() {
    var dropdown = getDropdown();
    if (!dropdown) {
      return;
    }

    if (dropdownOpen) {
      closeDropdown();
      return;
    }

    renderDropdownItems(cachedItems);
    dropdown.style.display = 'block';
    dropdownOpen = true;
  }

  function loadAlerts() {
    if (dropdownOpen) {
      return;
    }

    var rKey = getRKey();
    if (!rKey) {
      return;
    }
    fetch('get_bukken_alerts.php?rKey=' + encodeURIComponent(rKey) + '&m=' + encodeURIComponent(getMParam()))
      .then(function (res) { return res.json(); })
      .then(renderAlerts)
      .catch(function () {});
  }

  document.addEventListener('DOMContentLoaded', function () {
    var bell = document.getElementById('bukken-alert-bell');
    var bellIcon = document.querySelector('.bukken-alert-icon');
    if (!bell || !bellIcon) {
      return;
    }

    var dropdown = getDropdown();
    if (dropdown) {
      dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
      });
    }

    bellIcon.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      toggleDropdown();
    });

    document.addEventListener('click', function () {
      closeDropdown();
    });

    loadAlerts();
    setInterval(loadAlerts, 60000);
  });
})();
