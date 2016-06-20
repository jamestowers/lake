(function() {
  var animEndEventNames, lastTime, repeatOften, requestTick, transEndEventNames, vendors, x;

  if (!window.console) {
    window.console = {};
  }

  if (!window.console.log) {
    window.console.log = function() {};
  }

  window.latestKnownScrollY = 0;

  transEndEventNames = {
    'WebkitTransition': 'webkitTransitionEnd',
    'MozTransition': 'transitionend',
    'OTransition': 'oTransitionEnd',
    'msTransition': 'MSTransitionEnd',
    'transition': 'transitionend'
  };

  window.transEndEventName = transEndEventNames[Modernizr.prefixed('transition')];

  animEndEventNames = {
    'WebkitAnimation': 'webkitAnimationEnd',
    'OAnimation': 'oAnimationEnd',
    'msAnimation': 'MSAnimationEnd',
    'animation': 'animationend'
  };

  window.animEndEventName = animEndEventNames[Modernizr.prefixed('animation')];

  $.fn.visible = function(partial) {
    var $t, $w, _bottom, _top, compareBottom, compareTop, viewBottom, viewTop;
    $t = $(this);
    $w = $(window);
    viewTop = latestKnownScrollY;
    viewBottom = viewTop + $w.height();
    _top = $t.offset().top;
    _bottom = _top + $t.height();
    compareTop = (partial === true ? _bottom : _top);
    compareBottom = (partial === true ? _top : _bottom);
    return (compareBottom <= viewBottom) && (compareTop >= viewTop);
  };

  $.fn.renderEmbeds = function() {
    var $el, postId;
    $el = $(this);
    $el.attr('data-status', 'loading');
    postId = $el.data('post-id');
    console.log('[Dropshop] Loading embeds on page id: ' + postId);
    return $.when(dropshop.fetchEmbeds(postId).then(function(embedCodes) {
      $el.html('');
      return $.each(embedCodes, function(i, code) {
        $el.append(code);
        return $el.attr('data-status', 'loaded');
      });
    }));
  };

  window.fullyLoaded = false;

  window.dropshop = new Dropshop();

  lastTime = 0;

  vendors = ["ms", "moz", "webkit", "o"];

  x = 0;

  while (x < vendors.length && !window.requestAnimationFrame) {
    window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"];
    window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"] || window[vendors[x] + "CancelRequestAnimationFrame"];
    ++x;
  }

  if (!window.requestAnimationFrame) {
    window.requestAnimationFrame = function(callback, element) {
      var currTime, id, timeToCall;
      currTime = new Date().getTime();
      timeToCall = Math.max(0, 16 - (currTime - lastTime));
      id = window.setTimeout(function() {
        callback(currTime + timeToCall);
      }, timeToCall);
      lastTime = currTime + timeToCall;
      return id;
    };
  }

  if (!window.cancelAnimationFrame) {
    window.cancelAnimationFrame = function(id) {
      clearTimeout(id);
    };
  }

  repeatOften = function() {
    window.globalID = requestAnimationFrame(repeatOften);
  };

  requestTick = function() {
    if (!window.ticking) {
      requestAnimationFrame(dropshop.onScroll);
    }
    window.ticking = true;
  };

  $(window).scroll(function() {
    window.latestKnownScrollY = window.scrollY;
    return requestTick();
  });

  $(window).resize(function() {
    return dropshop.setWidths();
  });

  $(window).load(function() {
    return dropshop.onFullLoad();
  });

  (function(w) {
    var aig, checkTilt, disableZoom, disabledZoom, doc, enabled, enabledZoom, initialContent, meta, restoreZoom, y, z;
    restoreZoom = function() {
      var enabled;
      meta.setAttribute("content", enabledZoom);
      enabled = true;
    };
    disableZoom = function() {
      var enabled;
      meta.setAttribute("content", disabledZoom);
      enabled = false;
    };
    checkTilt = function(e) {
      var aig, y, z;
      aig = e.accelerationIncludingGravity;
      x = Math.abs(aig.x);
      y = Math.abs(aig.y);
      z = Math.abs(aig.z);
      if (!w.orientation && (x > 7 || ((z > 6 && y < 8 || z < 8 && y > 6) && x > 5))) {
        if (enabled) {
          disableZoom();
        }
      } else {
        if (!enabled) {
          restoreZoom();
        }
      }
    };
    if (!(/iPhone|iPad|iPod/.test(navigator.platform) && navigator.userAgent.indexOf("AppleWebKit") > -1)) {
      return;
    }
    doc = w.document;
    if (!doc.querySelector) {
      return;
    }
    meta = doc.querySelector("meta[name=viewport]");
    initialContent = meta && meta.getAttribute("content");
    disabledZoom = initialContent + ",maximum-scale=1";
    enabledZoom = initialContent + ",maximum-scale=10";
    enabled = true;
    x = void 0;
    y = void 0;
    z = void 0;
    aig = void 0;
    if (!meta) {
      return;
    }
    w.addEventListener("orientationchange", restoreZoom, false);
    w.addEventListener("devicemotion", checkTilt, false);
  })(this);

}).call(this);

//# sourceMappingURL=../sourcemaps/scripts.js.map
