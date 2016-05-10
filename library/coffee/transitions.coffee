class @Transitions
  
  constructor: ->
    console.log '[Dropshop][Transitions] Initialising...'
    @$page = $('#wrapper')
    @options = 
      debug: true
      prefetch: true
      cacheLength: 2
      forms: 'form'
      loadingClass: 'loading'
      blacklist: '.no-ajax'
      onBefore: ($currentTarget, $container) ->
        console.log '[Dropshop][Transitions] About to start new page load'
      onStart:
        duration: 250
        render: ($container) =>
          $container.addClass 'loading-out'
          dropshop.onPageFetch()
          @smoothState.restartCSSAnimations()
          return
      onProgress:
        duration: 0,
        render: ($container)-> 
          console.log '[Dropshop][Transitions] Still loading....'
      onReady:
        duration: 0
        render: ($container, $newContent) =>
          $container.removeClass 'loading-out'
          $container.html $newContent
          return
      onAfter: ($container, $newContent)->
        console.log '[Dropshop][Transitions] finished'
        dropshop.onPageLoad()

    @init()

  init: ->
    console.log '[Dropshop][Transitions] Initialised'
    @smoothState = @$page.smoothState(@options).data('smoothState')