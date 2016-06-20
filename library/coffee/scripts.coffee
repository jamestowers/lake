# Fix IE consol.log bug
if !window.console
  window.console = {}
if !window.console.log
  window.console.log = ->

window.latestKnownScrollY = 0

# transition end event name
transEndEventNames =
  'WebkitTransition': 'webkitTransitionEnd'
  'MozTransition': 'transitionend'
  'OTransition': 'oTransitionEnd'
  'msTransition': 'MSTransitionEnd'
  'transition': 'transitionend'
window.transEndEventName = transEndEventNames[Modernizr.prefixed('transition')]

animEndEventNames =
  'WebkitAnimation' : 'webkitAnimationEnd',
  'OAnimation' : 'oAnimationEnd',
  'msAnimation' : 'MSAnimationEnd',
  'animation' : 'animationend'
window.animEndEventName = animEndEventNames[Modernizr.prefixed('animation')]

$.fn.visible = (partial) ->
  $t = $(this)
  $w = $(window)
  viewTop = latestKnownScrollY
  viewBottom = viewTop + $w.height()
  _top = $t.offset().top
  _bottom = _top + $t.height()
  compareTop = (if partial is true then _bottom else _top)
  compareBottom = (if partial is true then _top else _bottom)
  (compareBottom <= viewBottom) and (compareTop >= viewTop)

$.fn.renderEmbeds = ->
  $el = $(this)
  $el.attr('data-status', 'loading')
  postId = $el.data 'post-id'
  console.log '[Dropshop] Loading embeds on page id: ' + postId
  $.when dropshop.fetchEmbeds(postId).then (embedCodes)->
    $el.html('')
    $.each embedCodes, (i, code)->
      $el.append code
      $el.attr('data-status', 'loaded')

window.fullyLoaded = false
window.dropshop = new Dropshop()



##################################
## requestAnimationFrame Polyfill
##################################

lastTime = 0
vendors = [
  "ms"
  "moz"
  "webkit"
  "o"
]
x = 0

while x < vendors.length and not window.requestAnimationFrame
  window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"]
  window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"] or window[vendors[x] + "CancelRequestAnimationFrame"]
  ++x
unless window.requestAnimationFrame
  window.requestAnimationFrame = (callback, element) ->
    currTime = new Date().getTime()
    timeToCall = Math.max(0, 16 - (currTime - lastTime))
    id = window.setTimeout(->
      callback currTime + timeToCall
      return
    , timeToCall)
    lastTime = currTime + timeToCall
    id
unless window.cancelAnimationFrame
  window.cancelAnimationFrame = (id) ->
    clearTimeout id
    return
################################## 
## end requestAnimationFrame Polyfill
##################################




repeatOften = ->
  window.globalID = requestAnimationFrame(repeatOften)
  return

# ON SCROLL EVENTS

# Call requestTick on window.scroll - see bottom
requestTick = ->
  requestAnimationFrame dropshop.onScroll  unless window.ticking
  window.ticking = true
  return





$(window).scroll ->
  window.latestKnownScrollY = window.scrollY
  requestTick()

$(window).resize ->
  dropshop.setWidths()

$(window).load ->
  dropshop.onFullLoad()










#! A fix for the iOS orientationchange zoom bug.
# Script by @scottjehl, rebound by @wilto.
# MIT License.
#
((w) ->
  
  # This fix addresses an iOS bug, so return early if the UA claims it's something else.
  restoreZoom = ->
    meta.setAttribute "content", enabledZoom
    enabled = true
    return
  disableZoom = ->
    meta.setAttribute "content", disabledZoom
    enabled = false
    return
  checkTilt = (e) ->
    aig = e.accelerationIncludingGravity
    x = Math.abs(aig.x)
    y = Math.abs(aig.y)
    z = Math.abs(aig.z)
    
    # If portrait orientation and in one of the danger zones
    if not w.orientation and (x > 7 or ((z > 6 and y < 8 or z < 8 and y > 6) and x > 5))
      disableZoom()  if enabled
    else restoreZoom()  unless enabled
    return
  return  unless /iPhone|iPad|iPod/.test(navigator.platform) and navigator.userAgent.indexOf("AppleWebKit") > -1
  doc = w.document
  return  unless doc.querySelector
  meta = doc.querySelector("meta[name=viewport]")
  initialContent = meta and meta.getAttribute("content")
  disabledZoom = initialContent + ",maximum-scale=1"
  enabledZoom = initialContent + ",maximum-scale=10"
  enabled = true
  x = undefined
  y = undefined
  z = undefined
  aig = undefined
  return  unless meta
  w.addEventListener "orientationchange", restoreZoom, false
  w.addEventListener "devicemotion", checkTilt, false
  return
) this