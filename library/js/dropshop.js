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
      return this.onPageLoad();
    };

    Dropshop.prototype.onFullLoad = function() {
      console.log('[Dropshop] Page fully loaded');
      window.fullyLoaded = true;
      return this.onScroll();
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

    Dropshop.prototype.setEventListeners = function() {
      console.log('whaaaaat');
      console.log('[Dropshop] setting event listeners');
      this.$doc.on('click', 'a#menu-toggle', function() {
        dropshop.$body.toggleClass('slide-from-right');
        console.log('sdf');
        return false;
      });
      this.$doc.on('click', '#nav-header a', function() {
        var pageId, target;
        pageId = $(this).data('page-id');
        dropshop.$body.removeClass('slide-from-right');
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
      return dropshop.$allMods.each(function(i, el) {
        el = $(el);
        if (el.visible(true)) {
          return el.addClass('animate');
        }
      });
    };

    return Dropshop;

  })();

}).call(this);

//# sourceMappingURL=../sourcemaps/dropshop.js.map
