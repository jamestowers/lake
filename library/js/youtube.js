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
