(function () {
  var cachedItems = [];
  var dropdownOpen = false;
  var unreadCount = 0;

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
    unreadCount = count;
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

  function formatRelativeTime(dateStr) {
    if (!dateStr) {
      return '';
    }
    var normalized = String(dateStr).replace(' ', 'T');
    var date = new Date(normalized);
    if (isNaN(date.getTime())) {
      return '';
    }
    var diffSec = Math.floor((Date.now() - date.getTime()) / 1000);
    if (diffSec < 60) {
      return 'たった今';
    }
    if (diffSec < 3600) {
      return '約' + Math.floor(diffSec / 60) + '分前';
    }
    if (diffSec < 86400) {
      return '約' + Math.floor(diffSec / 3600) + '時間前';
    }
    if (diffSec < 86400 * 2) {
      return '1日前';
    }
    if (diffSec < 86400 * 7) {
      return Math.floor(diffSec / 86400) + '日前';
    }
    var month = date.getMonth() + 1;
    var day = date.getDate();
    return month + '/' + day;
  }

  function escapeHtml(text) {
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function renderDropdownItems(items) {
    var dropdown = getDropdown();
    if (!dropdown) {
      return;
    }

    var html = '<div class="bukken-alert-list">';
    if (items.length === 0) {
      html += '<div class="bukken-alert-empty">お知らせはありません</div>';
    } else {
      items.forEach(function (item) {
        var name = item.BukkenName;
        if (name === false || name === null || name === undefined) {
          name = '物件CD:' + item.BukkenCD;
        }
        var label = item.ActivityLabel || (item.ActivityType == 2 ? '更新' : '予約');
        var activeClass = item.IsUnread ? ' bukken-alert-item--active' : ' bukken-alert-item--read';
        var timeLabel = formatRelativeTime(item.LastWebActivityAt);
        html += '<a href="#" class="bukken-alert-item' + activeClass + '" data-bukkencd="' + item.BukkenCD + '">';
        html += '<div class="bukken-alert-item-head">';
        html += '<span class="bukken-alert-item-label">' + escapeHtml(label) + '</span>';
        html += '<span class="bukken-alert-item-time">' + escapeHtml(timeLabel) + '</span>';
        html += '</div>';
        html += '<div class="bukken-alert-item-body">' + escapeHtml(name) + '</div>';
        html += '</a>';
      });
    }
    html += '</div>';
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

        var wasUnread = el.classList.contains('bukken-alert-item--active');
        if (!wasUnread) {
          window.location.href = url;
          return;
        }

        fetch('mark_bukken_alert_read.php?rKey=' + encodeURIComponent(rKey) + '&editBukkenCD=' + encodeURIComponent(bukkenCD))
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (!data || !data.ok) {
              return;
            }
            cachedItems = cachedItems.map(function (item) {
              if (String(item.BukkenCD) === String(bukkenCD)) {
                return Object.assign({}, item, { IsUnread: false });
              }
              return item;
            });
            updateBadge(Math.max(0, unreadCount - 1));
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

    var count = (data && data.count !== undefined) ? data.count : 0;
    var items = (data && data.items) ? data.items : [];
    cachedItems = items.map(function (item) {
      return {
        BukkenCD: item.BukkenCD,
        BukkenName: item.BukkenName,
        LastWebActivityAt: item.LastWebActivityAt,
        ActivityType: item.ActivityType,
        ActivityLabel: item.ActivityLabel || (item.ActivityType == 2 ? '更新' : '予約'),
        IsUnread: !!item.IsUnread,
      };
    });

    bell.style.display = 'inline-flex';
    updateBadge(count);
    if (dropdownOpen) {
      renderDropdownItems(cachedItems);
    }
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
