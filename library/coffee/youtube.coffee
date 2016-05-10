class @YouTubePlayer

  constructor: ->
    console.log '[Dropshop][YouTube] Initialising...'
    @player = null
    @ready = false
    @done = false
    @init()

  init: ->
    window.onYouTubeIframeAPIReady = ->
      dropshop.youTube.scanForYouTubePlayers()

    tag = document.createElement('script')
    tag.src = 'https://www.youtube.com/iframe_api'
    firstScriptTag = document.getElementsByTagName('script')[0]
    firstScriptTag.parentNode.insertBefore tag, firstScriptTag

  scanForYouTubePlayers: ->
    console.log '[Dropshop][YouTube] Scanning for YouTube players'
    $player = $('#youtube-player')
    return unless $player.length
    youtubeId = $player.data('video-id')
    @player = new (YT.Player)('youtube-player',
      height: '562'
      width: '1000'
      videoId: youtubeId
      events:
        'onReady': @onPlayerReady
        'onStateChange': @onPlayerStateChange)

  onPlayerReady: (event) ->
    console.log '[Dropshop][YouTube] Player ready'
    dropshop.youTube.ready = true
    event.target.mute()
    return

  onPlayerStateChange: (event) ->
    # if event.data == YT.PlayerState.PLAYING and !@done
      # setTimeout @stopVideo, 6000
      # @done = true
    return

  playVideo: ->
    @player.playVideo()

  pauseVideo: ->
    @player.pauseVideo()

  stopVideo: ->
    @player.stopVideo()

  playPause: (btn)->
    if @player.getPlayerState() == YT.PlayerState.PLAYING 
      @pauseVideo() 
      btn.removeClass('icon-pause').addClass 'icon-play'
    else 
      @playVideo()
      btn.removeClass('icon-play').addClass 'icon-pause'

  toggleVolume: (btn)->
    if @player.isMuted()
      @player.unMute() 
      btn.removeClass('icon-volume').addClass 'icon-mute'
    else 
      @player.mute()
      btn.removeClass('icon-mute').addClass 'icon-volume'

