class @Dropshop

  constructor: ->
    console.log '[Dropshop] Initialising...'
    @$doc = $(document)
    @youTube = new YouTubePlayer()
    @$body = $('body')
    @windowHeightMargin = 0
    @init()

  init: ->
    FastClick.attach(document.body);
    @setWidths()
    @onPageLoad()

    @$doc.on 'click', 'a#menu-toggle', ->
      dropshop.$body.toggleClass 'slide-from-right'
      false

    @$doc.on 'click', '#nav-header a', (e) ->
      e.preventDefault()
      href = $(e.target).attr 'href'
      if href.length
        pathname = href.pathname
        console.log pathname
        target = $('[data-slug="' + pathname + '"]')
        $('html, body').animate { scrollTop: target.offset().top }, 1000

    @$doc.on 'click', '.share-fb', ->
      url = this.href
      url = $(this).data 'share-url' unless url
      url = window.location.href unless url
      FB.ui {
        method: 'share'
        href: url
      }, (response) ->
        console.log (response)
      false

    @$doc.on 'click', '.share-email', ->
      url = this.href
      url = window.location.href unless url
      emailBody = 'Read this: '+ url
      window.location = 'mailto:?subject=' + document.title + '&body=' +   emailBody;

    @$doc.on 'click', '.share-twitter', ->
      url = this.href
      url = window.location.href unless url
      popUp = window.open('http://twitter.com/intent/tweet?text=Read this: ' + url, 'popupwindow', 'scrollbars=yes,width=600,height=400,top=150,left=150')
      popUp.focus()
      false

    @$doc.on 'click', '.video-controls a.play-pause', ->
      el = $(this)
      dropshop.youTube.playPause(el)
      false

    @$doc.on 'click', '.video-controls a.mute', ->
      el = $(this)
      dropshop.youTube.toggleVolume(el)
      false

    @$doc.on animEndEventName, '#logo-svg path#R', ->
      $(this).parent().attr('class', 'animated')

  onFullLoad: ->
    console.log '[Dropshop] Page fully loaded'
    window.fullyLoaded = true
    @onScroll()
    @$curtain = $('#curtain')

  onPageFetch: ->
    console.log '[Dropshop] fetching new page'
    @showLoadingAnimation()

  onPageLoad: ->
    console.log '[Dropshop] onPageLoad'
    @setEventListeners()
    @$body.removeClass 'slide-from-right'
    $('.hero-container').css
      'height' : @sizes.windowHeight + 'px'
    #$('nav#nav-header a.clicked').removeClass 'clicked'
    @$allMods = $("[data-animate]")
    @$embeds = $(".embeds")
    @videos = $('video')
    @onScroll() if window.fullyLoaded # Trgger scroll to show visible embedsand videos
    @hideLoadingAnimation()
    @preloadBgImage()
    if @isMobile
      $('nav#nav-header:not(#top-banner nav#nav-header)').prependTo('body')
    setTimeout(=>
      $.each @$embeds, (i, el)=>
        el = $(el)
        el.renderEmbeds() if el.attr('data-status') is 'pending'
    , 3000)
    

  setWidths:->
    console.log '[Dropshop] setting page dims'
    @sizes = 
      windowWidth: $(window).width()
      windowHeight: $(window).height()
      headerHeight: $('header.header').outerHeight() || 0
    @isMobile = @sizes.windowWidth < 768
    #@setWrapperHeight()

  showLoadingAnimation: ->
    

  hideLoadingAnimation: ->
    

  setWrapperHeight: ->
    # Amend #wrapper height in stylesheet so no need to do it on each page load
    css = 'div#wrapper { min-height : '+ @sizes.windowHeight + 'px }'
    $sheet = document.getElementById('styles')
    unless $sheet
      head = document.head or document.getElementsByTagName('head')[0]
      $sheet = document.createElement('style')
      $sheet.appendChild(document.createTextNode("")) # WebKit hack :(
      $sheet.type = 'text/css'
      $sheet.setAttribute("id", "styles")
      head.appendChild $sheet
    $sheet = document.styleSheets[(document.styleSheets.length - 1)]
    @addCSSRule($sheet, '#wrapper', 'min-height :' + @sizes.windowHeight + 'px !important' )

  addCSSRule: (sheet, selector, rules, index) ->
    myrules = if sheet.cssRules then sheet.cssRules else sheet.rules
    if myrules
      sheet.crossdelete = if sheet.deleteRule then sheet.deleteRule else sheet.removeRule
      i = 0
      while i < myrules.length
        if myrules[i].selectorText.toLowerCase().indexOf(selector) != -1
          sheet.crossdelete i
          i--
        i++ 

      if 'insertRule' of sheet
        sheet.insertRule selector + '{' + rules + '}', index
      else if 'addRule' of sheet
        sheet.addRule selector, rules, index
      return

  preloadBgImage: ->
    img = $('.hero-container img')
    return unless img.length
    img.each (i, el)->
      console.log '[App] loading background image'
      $(el).parent().addClass 'loaded'

  renderEmbeds: ->
    $.each @$embeds, (i, el)=>
      el = $(el)
      postId = el.data 'post-id'
      $.when @fetchEmbeds(postId).then (embedCodes)->
        el.html('')
        $.each embedCodes, (i, code)->
          el.append code

  fetchEmbeds: (postId)->
    $.ajax
      url: ajax.ajax_url
      dataType: 'json'
      type: 'POST'
      data: 
        'action': 'get_post_embeds',
        'post_id':   postId
      success: (response)->
        if response.embeds
          return response.embeds
        else
          console.error 'There was a problem fetching the embed codes'




  ######################
  ## ON SCROLL
  ######################
  onScroll: ->
    window.ticking = false
    #unless dropshop.isMobile
    if window.latestKnownScrollY > 44
      dropshop.$body.addClass 'scrolled'
    else
      dropshop.$body.removeClass 'scrolled'

    dropshop.$embeds.each (i, el) ->
      el = $(el)
      if el.attr('data-status') is 'pending' and el.visible(true)
        el.renderEmbeds()

    unless dropshop.isMobile
      if(dropshop.youTube.ready)
        $youTubePlayer = $(dropshop.youTube.player.c)
        if $youTubePlayer.visible(true)
          dropshop.youTube.player.playVideo()
        else
          if dropshop.youTube.player.isMuted() and !dropshop.youTube.player.getPlayerState() == YT.PlayerState.PLAYING 
            dropshop.youTube.player.pauseVideo()
            
      dropshop.videos.each (i, el) ->
        el = $(el)
        if el.visible(true)
          el.get(0).play() 
        else
          el.get(0).pause() 
    # Animate elements on scroll when visible
    dropshop.$allMods.each (i, el) ->
      el = $(el)
      el.addClass 'animate' if el.visible(true)
    return




  ######################
  ## EVENT LISTENERS
  ######################
  setEventListeners: ->
    # Use this for setting new event listeners that need to be set after ajax page loads
    console.log '[Dropshop] setting event listeners'
    
    
    # @$doc.on 'click', '.expand-text', ->
    #   $(this).parent().toggleClass 'expanded'
    #   false


