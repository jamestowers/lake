(function() {
  this.YouTubePlayer = (function() {
    function YouTubePlayer() {
      console.log('[Dropshop][YouTube] Initialising...');
      this.player = null;
      this.ready = false;
      this.done = false;
      this.init();
    }

    YouTubePlayer.prototype.init = function() {
      var firstScriptTag, tag;
      window.onYouTubeIframeAPIReady = function() {
        return dropshop.youTube.scanForYouTubePlayers();
      };
      tag = document.createElement('script');
      tag.src = 'https://www.youtube.com/iframe_api';
      firstScriptTag = document.getElementsByTagName('script')[0];
      return firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    };

    YouTubePlayer.prototype.scanForYouTubePlayers = function() {
      var $player, youtubeId;
      console.log('[Dropshop][YouTube] Scanning for YouTube players');
      $player = $('#youtube-player');
      if (!$player.length) {
        return;
      }
      youtubeId = $player.data('video-id');
      return this.player = new YT.Player('youtube-player', {
        height: '562',
        width: '1000',
        videoId: youtubeId,
        events: {
          'onReady': this.onPlayerReady,
          'onStateChange': this.onPlayerStateChange
        }
      });
    };

    YouTubePlayer.prototype.onPlayerReady = function(event) {
      console.log('[Dropshop][YouTube] Player ready');
      dropshop.youTube.ready = true;
      event.target.mute();
    };

    YouTubePlayer.prototype.onPlayerStateChange = function(event) {};

    YouTubePlayer.prototype.playVideo = function() {
      return this.player.playVideo();
    };

    YouTubePlayer.prototype.pauseVideo = function() {
      return this.player.pauseVideo();
    };

    YouTubePlayer.prototype.stopVideo = function() {
      return this.player.stopVideo();
    };

    YouTubePlayer.prototype.playPause = function(btn) {
      if (this.player.getPlayerState() === YT.PlayerState.PLAYING) {
        this.pauseVideo();
        return btn.removeClass('icon-pause').addClass('icon-play');
      } else {
        this.playVideo();
        return btn.removeClass('icon-play').addClass('icon-pause');
      }
    };

    YouTubePlayer.prototype.toggleVolume = function(btn) {
      if (this.player.isMuted()) {
        this.player.unMute();
        return btn.removeClass('icon-volume').addClass('icon-mute');
      } else {
        this.player.mute();
        return btn.removeClass('icon-mute').addClass('icon-volume');
      }
    };

    return YouTubePlayer;

  })();

}).call(this);

//# sourceMappingURL=../sourcemaps/youtube.js.map

(function() {
  this.Dropshop = (function() {
    function Dropshop() {
      console.log('[Dropshop] Initialising...');
      this.$doc = $(document);
      this.youTube = new YouTubePlayer();
      this.$body = $('body');
      this.$nav = $('#nav-header');
      this.windowHeightMargin = 0;
      this.init();
    }

    Dropshop.prototype.init = function() {
      FastClick.attach(document.body);
      this.setWidths();
      this.onPageLoad();
      this.$doc.on('click', 'a#menu-toggle', function() {
        dropshop.$body.toggleClass('slide-from-right');
        return false;
      });
      this.$doc.on('click', '#nav-header a', function() {
        var pageId, target;
        pageId = $(this).data('page-id');
        if (pageId) {
          target = $('section[data-page-id="' + pageId + '"]');
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 1000);
        }
        return false;
      });
      this.$doc.on('click', '.share-fb', function() {
        var url;
        url = this.href;
        if (!url) {
          url = $(this).data('share-url');
        }
        if (!url) {
          url = window.location.href;
        }
        FB.ui({
          method: 'share',
          href: url
        }, function(response) {
          return console.log(response);
        });
        return false;
      });
      this.$doc.on('click', '.share-email', function() {
        var emailBody, url;
        url = this.href;
        if (!url) {
          url = window.location.href;
        }
        emailBody = 'Read this: ' + url;
        return window.location = 'mailto:?subject=' + document.title + '&body=' + emailBody;
      });
      this.$doc.on('click', '.share-twitter', function() {
        var popUp, url;
        url = this.href;
        if (!url) {
          url = window.location.href;
        }
        popUp = window.open('http://twitter.com/intent/tweet?text=Read this: ' + url, 'popupwindow', 'scrollbars=yes,width=600,height=400,top=150,left=150');
        popUp.focus();
        return false;
      });
      this.$doc.on('click', '.video-controls a.play-pause', function() {
        var el;
        el = $(this);
        dropshop.youTube.playPause(el);
        return false;
      });
      this.$doc.on('click', '.video-controls a.mute', function() {
        var el;
        el = $(this);
        dropshop.youTube.toggleVolume(el);
        return false;
      });
      return this.$doc.on(animEndEventName, '#logo-svg path#R', function() {
        return $(this).parent().attr('class', 'animated');
      });
    };

    Dropshop.prototype.onFullLoad = function() {
      console.log('[Dropshop] Page fully loaded');
      window.fullyLoaded = true;
      this.onScroll();
      return this.$curtain = $('#curtain');
    };

    Dropshop.prototype.onPageFetch = function() {
      console.log('[Dropshop] fetching new page');
      return this.showLoadingAnimation();
    };

    Dropshop.prototype.onPageLoad = function() {
      console.log('[Dropshop] onPageLoad');
      this.setEventListeners();
      this.$body.removeClass('slide-from-right');
      $('.hero-container').css({
        'height': this.sizes.windowHeight + 'px'
      });
      this.$allMods = $("[data-animate]");
      this.$embeds = $(".embeds");
      this.videos = $('video');
      if (window.fullyLoaded) {
        this.onScroll();
      }
      this.hideLoadingAnimation();
      this.preloadBgImage();
      if (this.isMobile) {
        $('nav#nav-header:not(#top-banner nav#nav-header)').prependTo('body');
      }
      return setTimeout((function(_this) {
        return function() {
          return $.each(_this.$embeds, function(i, el) {
            el = $(el);
            if (el.attr('data-status') === 'pending') {
              return el.renderEmbeds();
            }
          });
        };
      })(this), 3000);
    };

    Dropshop.prototype.setWidths = function() {
      console.log('[Dropshop] setting page dims');
      this.sizes = {
        windowWidth: $(window).width(),
        windowHeight: $(window).height(),
        headerHeight: $('header.header').outerHeight() || 0,
        navTop: this.$nav.offset().top
      };
      return this.isMobile = this.sizes.windowWidth < 768;
    };

    Dropshop.prototype.showLoadingAnimation = function() {};

    Dropshop.prototype.hideLoadingAnimation = function() {};

    Dropshop.prototype.setWrapperHeight = function() {
      var $sheet, css, head;
      css = 'div#wrapper { min-height : ' + this.sizes.windowHeight + 'px }';
      $sheet = document.getElementById('styles');
      if (!$sheet) {
        head = document.head || document.getElementsByTagName('head')[0];
        $sheet = document.createElement('style');
        $sheet.appendChild(document.createTextNode(""));
        $sheet.type = 'text/css';
        $sheet.setAttribute("id", "styles");
        head.appendChild($sheet);
      }
      $sheet = document.styleSheets[document.styleSheets.length - 1];
      return this.addCSSRule($sheet, '#wrapper', 'min-height :' + this.sizes.windowHeight + 'px !important');
    };

    Dropshop.prototype.addCSSRule = function(sheet, selector, rules, index) {
      var i, myrules;
      myrules = sheet.cssRules ? sheet.cssRules : sheet.rules;
      if (myrules) {
        sheet.crossdelete = sheet.deleteRule ? sheet.deleteRule : sheet.removeRule;
        i = 0;
        while (i < myrules.length) {
          if (myrules[i].selectorText.toLowerCase().indexOf(selector) !== -1) {
            sheet.crossdelete(i);
            i--;
          }
          i++;
        }
        if ('insertRule' in sheet) {
          sheet.insertRule(selector + '{' + rules + '}', index);
        } else if ('addRule' in sheet) {
          sheet.addRule(selector, rules, index);
        }
      }
    };

    Dropshop.prototype.preloadBgImage = function() {
      var img;
      img = $('.hero-container img');
      if (!img.length) {
        return;
      }
      return img.each(function(i, el) {
        console.log('[App] loading background image');
        return $(el).parent().addClass('loaded');
      });
    };

    Dropshop.prototype.renderEmbeds = function() {
      return $.each(this.$embeds, (function(_this) {
        return function(i, el) {
          var postId;
          el = $(el);
          postId = el.data('post-id');
          return $.when(_this.fetchEmbeds(postId).then(function(embedCodes) {
            el.html('');
            return $.each(embedCodes, function(i, code) {
              return el.append(code);
            });
          }));
        };
      })(this));
    };

    Dropshop.prototype.fetchEmbeds = function(postId) {
      return $.ajax({
        url: ajax.ajax_url,
        dataType: 'json',
        type: 'POST',
        data: {
          'action': 'get_post_embeds',
          'post_id': postId
        },
        success: function(response) {
          if (response.embeds) {
            return response.embeds;
          } else {
            return console.error('There was a problem fetching the embed codes');
          }
        }
      });
    };

    Dropshop.prototype.onScroll = function() {
      var $youTubePlayer;
      window.ticking = false;
      if (window.latestKnownScrollY > 44) {
        dropshop.$body.addClass('scrolled');
      } else {
        dropshop.$body.removeClass('scrolled');
      }
      if (window.latestKnownScrollY > (dropshop.sizes.navTop + 48)) {
        if (!dropshop.$nav.hasClass('fixed')) {
          console.log('yes');
          dropshop.$nav.addClass('fixed');
        }
      } else {
        dropshop.$nav.removeClass('fixed');
      }
      dropshop.$embeds.each(function(i, el) {
        el = $(el);
        if (el.attr('data-status') === 'pending' && el.visible(true)) {
          return el.renderEmbeds();
        }
      });
      if (!dropshop.isMobile) {
        if (dropshop.youTube.ready) {
          $youTubePlayer = $(dropshop.youTube.player.c);
          if ($youTubePlayer.visible(true)) {
            dropshop.youTube.player.playVideo();
          } else {
            if (dropshop.youTube.player.isMuted() && !dropshop.youTube.player.getPlayerState() === YT.PlayerState.PLAYING) {
              dropshop.youTube.player.pauseVideo();
            }
          }
        }
        dropshop.videos.each(function(i, el) {
          el = $(el);
          if (el.visible(true)) {
            return el.get(0).play();
          } else {
            return el.get(0).pause();
          }
        });
      }
      dropshop.$allMods.each(function(i, el) {
        el = $(el);
        if (el.visible(true)) {
          return el.addClass('animate');
        }
      });
    };

    Dropshop.prototype.setEventListeners = function() {
      return console.log('[Dropshop] setting event listeners');
    };

    return Dropshop;

  })();

}).call(this);

//# sourceMappingURL=../sourcemaps/dropshop.js.map

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

  window.dropshop = new Dropshop();

  window.fullyLoaded = false;

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
