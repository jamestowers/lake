(function() {
  this.Transitions = (function() {
    function Transitions() {
      console.log('[Dropshop][Transitions] Initialising...');
      this.$page = $('#wrapper');
      this.options = {
        debug: true,
        prefetch: true,
        cacheLength: 2,
        forms: 'form',
        loadingClass: 'loading',
        blacklist: '.no-ajax',
        onBefore: function($currentTarget, $container) {
          return console.log('[Dropshop][Transitions] About to start new page load');
        },
        onStart: {
          duration: 250,
          render: (function(_this) {
            return function($container) {
              $container.addClass('loading-out');
              dropshop.onPageFetch();
              _this.smoothState.restartCSSAnimations();
            };
          })(this)
        },
        onProgress: {
          duration: 0,
          render: function($container) {
            return console.log('[Dropshop][Transitions] Still loading....');
          }
        },
        onReady: {
          duration: 0,
          render: (function(_this) {
            return function($container, $newContent) {
              $container.removeClass('loading-out');
              $container.html($newContent);
            };
          })(this)
        },
        onAfter: function($container, $newContent) {
          console.log('[Dropshop][Transitions] finished');
          return dropshop.onPageLoad();
        }
      };
      this.init();
    }

    Transitions.prototype.init = function() {
      console.log('[Dropshop][Transitions] Initialised');
      return this.smoothState = this.$page.smoothState(this.options).data('smoothState');
    };

    return Transitions;

  })();

}).call(this);

//# sourceMappingURL=../sourcemaps/transitions.js.map
