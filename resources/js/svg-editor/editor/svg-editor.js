import './touch.js'
import { isIE, isMac } from './browser.js'
import * as Utils from './utilities.js'

import SvgCanvas from './svgcanvas.js'
import Layer from './layer.js'
import { getConfig } from './settings'

import jQueryPluginJSHotkeys from './js-hotkeys/jquery.hotkeys.min.js'
import jQueryPluginSVG from './jQuery.attr.js'
import eventBus from '@/public/eventBus' // Needed for SVG attribute setting and array form with `attr`

const editor = (window.svgEditor = {})

const $ = [jQueryPluginJSHotkeys, jQueryPluginSVG].reduce((jq, func) => func(jq), jQuery)

editor.tool_scale = 1
/**
 * @type {Integer}
 */
editor.exportWindowCt = 0
/**
 * @type {boolean}
 */
editor.langChanged = false
/**
 * @type {boolean}
 */
editor.showSaveWarning = false
/**
 * Will be set to a boolean by `ext-storage.js`
 * @type {"ignore"|"waiting"|"closed"}
 */
editor.storagePromptState = 'ignore'

const callbacks = []
const defaultPrefs = {
  lang: '',
  iconsize: '',
  bkgd_color: '#FFFFFF',
  bkgd_url: '',
  img_save: 'embed',
  save_notice_done: false,
  export_notice_done: false
}
const defaultConfig = {
  canvasName: 'default',
  canvas_expansion: 3,
  initFill: {
    color: '#7a7a7a',
    opacity: 1
  },
  initStroke: {
    width: 3,
    color: '#303030',
    opacity: 1
  },
  text: {
    fill: '#282828',
    stroke_width: 0,
    stroke_color: '#000000',
    font_size: 38,
    font_family: 'Big Shoulders Text Regular'
  },
  initOpacity: 1,
  initTool: 'select',
  no_save_warning: false,
  langPath: 'locale/', // Default will be changed if this is a non-modular load
  canvgPath: 'canvg/', // Default will be changed if this is a non-modular load
  jspdfPath: 'jspdf/', // Default will be changed if this is a non-modular load
  jGraduatePath: 'jgraduate/images/',
  extIconsPath: 'extensions/',
  gridSnapping: false,
  gridColor: '#000',
  imgPath: getConfig().path.images,
  baseUnit: 'px',
  snappingStep: 10,
  showRulers: false,
  preventAllURLConfig: false,
  preventURLContentLoading: false,
  lockExtensions: false, // Disallowed in URL setting
  noDefaultExtensions: false, // noDefaultExtensions can only be meaningfully used in `svgedit-config-iife.js` or in the URL
  showGrid: false, // Set by ext-grid.js
  noStorageOnLoad: false, // Some interaction with ext-storage.js; prevent even the loading of previously saved local storage
  forceStorage: false, // Some interaction with ext-storage.js; strongly discouraged from modification as it bypasses user privacy by preventing them from choosing whether to keep local storage or not
  emptyStorageOnDecline: false, // Used by ext-storage.js; empty any prior storage if the user declines to store
  avoidClientSide: false, // Deprecated in favor of `avoidClientSideDownload`
  avoidClientSideDownload: false,
  avoidClientSideOpen: false
}

let svgCanvas
let isReady = false
const curPrefs = {}
// Note: The difference between Prefs and Config is that Prefs
//   can be changed in the UI and are stored in the browser,
//   while config cannot
let curConfig = {
  extensions: [],
  stylesheets: [],
  allowedOrigins: []
}

async function loadSvgString(str, { noAlert } = {}) {
  const success = svgCanvas.setSvgString(str) !== false
  if (success) {
    return
  }
  throw new Error('Error loading SVG')
}

editor.setConfig = function (opts, cfgCfg) {
  cfgCfg = cfgCfg || {}

  function extendOrAdd(cfgObj, key, val) {
    if (cfgObj[key] && typeof cfgObj[key] === 'object') {
      $.extend(true, cfgObj[key], val)
    } else {
      cfgObj[key] = val
    }
  }

  Object.entries(opts).forEach(function ([key, val]) {
    // Only allow prefs defined in defaultPrefs or...
    if ({}.hasOwnProperty.call(defaultPrefs, key)) {
      if (cfgCfg.overwrite === false && (curConfig.preventAllURLConfig || {}.hasOwnProperty.call(curPrefs, key))) {
        return
      }
      if (cfgCfg.allowInitialUserOverride === true) {
        defaultPrefs[key] = val
      } else {
        editor.pref(key, val)
      }
    } else if ({}.hasOwnProperty.call(defaultConfig, key)) {
      if (cfgCfg.overwrite === false && (curConfig.preventAllURLConfig || {}.hasOwnProperty.call(curConfig, key))) {
        return
      }
      // Potentially overwriting of previously set config
      if ({}.hasOwnProperty.call(curConfig, key)) {
        if (cfgCfg.overwrite === false) {
          return
        }
        extendOrAdd(curConfig, key, val)
      } else if (cfgCfg.allowInitialUserOverride === true) {
        extendOrAdd(defaultConfig, key, val)
      } else if (defaultConfig[key] && typeof defaultConfig[key] === 'object') {
        curConfig[key] = Array.isArray(defaultConfig[key]) ? [] : {}
        $.extend(true, curConfig[key], val) // Merge properties recursively, e.g., on initFill, initStroke objects
      } else {
        curConfig[key] = val
      }
    }
  })

  editor.curConfig = curConfig // Update exported value
}

/**
 * Auto-run after a Promise microtask.
 * @function module:SVGEditor.init
 * @returns {void}
 */
editor.init = function (config = {}) {
  const modularVersion = !('svgEditor' in window) || !window.svgEditor || window.svgEditor.modules !== false

  const Actions = (function () {
    const modKey = isMac() ? 'meta+' : 'ctrl+'
    const toolButtons = [
      {
        key: ['up', true],
        fn() {
          moveSelected(0, -1)
        }
      },
      {
        key: ['down', true],
        fn() {
          moveSelected(0, 1)
        }
      },
      {
        key: ['left', true],
        fn() {
          moveSelected(-1, 0)
        }
      },
      {
        key: ['right', true],
        fn() {
          moveSelected(1, 0)
        }
      },
      {
        key: ['del/backspace', true],
        fn() {
          deleteSelected()
        }
      },

      // Standard shortcuts
      {
        key: modKey + 'a',
        fn() {
          svgCanvas.selectAllInCurrentLayer()
        }
      },
      {
        key: modKey + 'z',
        fn() {
          clickUndo()
        }
      },
      {
        key: modKey + 'shift+z',
        fn() {
          clickRedo()
        }
      },
      {
        key: modKey + 'y',
        fn() {
          clickRedo()
        }
      },
      {
        key: modKey + 'x',
        fn() {
          cutSelected()
        }
      },
      {
        key: modKey + 'c',
        fn() {
          copySelected()
        }
      },
      {
        key: modKey + 'v',
        fn() {
          pasteInCenter()
        }
      }
    ]

    return {
      setAll() {
        $.each(toolButtons, function (i, opts) {
          // Bind function to shortcut key
          if (opts.key) {
            // Set shortcut based on options
            let keyval
            let pd = false

            if (Array.isArray(opts.key)) {
              keyval = opts.key[0]
              if (opts.key.length > 1) {
                pd = opts.key[1]
              }
              // if (opts.key.length > 2) { disInInp = opts.key[2]; }
            } else {
              keyval = opts.key
            }
            keyval = String(keyval)

            const { fn } = opts
            $.each(keyval.split('/'), function (j, key) {
              $(document).bind('keydown', key, function (e) {
                fn()
                if (pd) {
                  e.preventDefault()
                }
                // Prevent default on ALL keys?
                return false
              })
            })
          }
          return true
        })
      }
    }
  })()

  /**
   * Sets up current config based on defaults.
   * @returns {void}
   */
  function setupCurConfig() {
    curConfig = $.extend(true, config, defaultConfig, curConfig) // Now safe to merge with priority for curConfig in the event any are already set

    // ...and remove any dupes
    ;['extensions', 'stylesheets', 'allowedOrigins'].forEach(function (cfg) {
      curConfig[cfg] = $.grep(curConfig[cfg], function (n, i) {
        // Supposedly faster than filter per http://amandeep1986.blogspot.hk/2015/02/jquery-grep-vs-js-filter.html
        return i === curConfig[cfg].indexOf(n)
      })
    })
    // Export updated config
    editor.curConfig = curConfig
  }

  ;(() => {
    setupCurConfig()
  })()

  // Create canvas instance
  editor.canvas = svgCanvas = new SvgCanvas(document.getElementById('svgcanvas'), curConfig)

  const { undoMgr } = svgCanvas
  const workarea = $('#workarea')

  let curScrollPos

  /**
   *
   * @returns {void}
   */
  const setSelectMode = function () {
    svgCanvas.setMode('select')
  }

  let selectedElement = null
  let multiselected = false

  /**
   * @type {module:svgcanvas.EventHandler}
   * @param {external:Window} wind
   * @param {module:svgcanvas.SvgCanvas#event:saved} svg The SVG source
   * @listens module:svgcanvas.SvgCanvas#event:saved
   * @returns {void}
   */
  const saveHandler = function (wind, svg) {
    //
  }

  /**
   * @function module:SVGEditor.updateCanvas
   * @param {boolean} center
   * @param {module:math.XYObject} newCtr
   * @returns {void}
   */
  const updateCanvas = (editor.updateCanvas = function (center, newCtr) {
    const zoom = svgCanvas.getZoom()
    const wArea = workarea
    const cnvs = $('#svgcanvas')

    let w = workarea.width()
    let h = workarea.height()
    const wOrig = w
    const hOrig = h
    const oldCtr = {
      x: wArea[0].scrollLeft + wOrig / 2,
      y: wArea[0].scrollTop + hOrig / 2
    }
    const multi = curConfig.canvas_expansion
    w = Math.max(wOrig, svgCanvas.contentW * zoom * multi)
    h = Math.max(hOrig, svgCanvas.contentH * zoom * multi)

    if (w === wOrig && h === hOrig) {
      workarea.css('overflow', 'hidden')
    } else {
      workarea.css('overflow', 'scroll')
    }

    const oldCanY = cnvs.height() / 2
    const oldCanX = cnvs.width() / 2
    cnvs.width(w).height(h)
    const newCanY = h / 2
    const newCanX = w / 2
    const offset = svgCanvas.updateCanvas(w, h)

    const ratio = newCanX / oldCanX

    const scrollX = w / 2 - wOrig / 2 // eslint-disable-line no-shadow
    const scrollY = h / 2 - hOrig / 2 // eslint-disable-line no-shadow

    if (!newCtr) {
      const oldDistX = oldCtr.x - oldCanX
      const newX = newCanX + oldDistX * ratio

      const oldDistY = oldCtr.y - oldCanY
      const newY = newCanY + oldDistY * ratio

      newCtr = {
        x: newX,
        y: newY
      }
    } else {
      newCtr.x += offset.x
      newCtr.y += offset.y
    }

    if (center) {
      // Go to top-left for larger documents
      if (svgCanvas.contentW > wArea.width()) {
        // Top-left
        workarea[0].scrollLeft = offset.x - 10
        workarea[0].scrollTop = offset.y - 10
      } else {
        // Center
        wArea[0].scrollLeft = scrollX
        wArea[0].scrollTop = scrollY
      }
    } else {
      wArea[0].scrollLeft = newCtr.x - wOrig / 2
      wArea[0].scrollTop = newCtr.y - hOrig / 2
    }
  })

  /**
   * Updates the toolbar (colors, opacity, etc) based on the selected element.
   * This function also updates the opacity and id elements that are in the
   * context panel.
   * @returns {void}
   */
  const updateToolbar = function () {
    if (!Utils.isNullish(selectedElement)) {
      switch (selectedElement.tagName) {
        case 'use':
        case 'image':
        case 'foreignObject':
          break
        case 'g':
        case 'a': {
          break
        }
        default: {
          //
        }
      }
    }
  }

  const selectedChanged = function (win, elems) {
    const mode = svgCanvas.getMode()
    if (mode === 'select') {
      setSelectMode()
    }
    const isNode = mode === 'pathedit'
    // if elems[1] is present, then we have more than one element
    selectedElement = elems.length === 1 || Utils.isNullish(elems[1]) ? elems[0] : null
    multiselected = elems.length >= 2 && !Utils.isNullish(elems[1])
    if (!Utils.isNullish(selectedElement)) {
      // unless we're already in always set the mode of the editor to select because
      // upon creation of a text element the editor is switched into
      // select mode and this event fires - we need our UI to be in sync

      if (!isNode) {
        updateToolbar()
      }
    } // if (!Utils.isNullish(elem))

    svgCanvas.runExtensions('selectedChanged', {
      elems,
      selectedElement,
      multiselected
    })
  }

  const elementTransition = function (win, elems) {
    const elem = elems[0]

    if (!elem) {
      return
    }

    multiselected = elems.length >= 2 && !Utils.isNullish(elems[1])

    svgCanvas.runExtensions('elementTransition', {
      elems
    })
  }

  function isLayer(elem) {
    return elem && elem.tagName === 'g' && Layer.CLASS_REGEX.test(elem.getAttribute('class'))
  }

  const elementChanged = function (win, elems) {
    const mode = svgCanvas.getMode()
    if (mode === 'select') {
      setSelectMode()
    }

    elems.forEach((elem) => {
      const isSvgElem = elem && elem.tagName === 'svg'
      if (isSvgElem || isLayer(elem)) {
        // if the element changed was the svg, then it could be a resolution change
        if (isSvgElem) {
          updateCanvas()
        }
        // Update selectedElement if element is no longer part of the image.
        // This occurs for the text elements in Firefox
      } else if (elem && selectedElement && Utils.isNullish(selectedElement.parentNode)) {
        // || elem && elem.tagName == "path" && !multiselected) { // This was added in r1430, but not sure why
        selectedElement = elem
      }
    })

    if (selectedElement && mode === 'select') {
    }

    svgCanvas.runExtensions('elementChanged', {
      elems
    })
  }

  /**
   * @returns {void}
   */
  const zoomDone = function () {
    updateCanvas(true)

    eventBus.$emit('zoom.updated')
  }

  const zoomChanged = (svgCanvas.zoomChanged = function (win, bbox, autoCenter) {
    const scrbar = 15
    // res = svgCanvas.getResolution(), // Currently unused
    const wArea = workarea
    // const canvasPos = $('#svgcanvas').position(); // Currently unused
    const zInfo = svgCanvas.setBBoxZoom(bbox, wArea.width() - scrbar, wArea.height() - scrbar)
    if (!zInfo) {
      return
    }
    const zoomlevel = zInfo.zoom
    const bb = zInfo.bbox

    if (zoomlevel < 0.001) {
      changeZoom({ value: 0.1 })
      return
    }

    $('#zoom').val((zoomlevel * 100).toFixed(1))

    if (autoCenter) {
      updateCanvas()
    } else {
      updateCanvas(false, {
        x: bb.x * zoomlevel + (bb.width * zoomlevel) / 2,
        y: bb.y * zoomlevel + (bb.height * zoomlevel) / 2
      })
    }

    if (svgCanvas.getMode() === 'zoom' && bb.width) {
      // Go to select if a zoom box was drawn
      setSelectMode()
    }

    zoomDone()
  })

  /**
   * @type {module:jQuerySpinButton.ValueCallback}
   */
  const changeZoom = (svgCanvas.changeZoom = function (ctl) {
    const zoomlevel = ctl.value / 100
    if (zoomlevel < 0.001) {
      ctl.value = 0.1
      return
    }
    const zoom = svgCanvas.getZoom()
    const wArea = workarea

    zoomChanged(
      window,
      {
        width: 0,
        height: 0,
        // center pt of scroll position
        x: (wArea[0].scrollLeft + wArea.width() / 2) / zoom,
        y: (wArea[0].scrollTop + wArea.height() / 2) / zoom,
        zoom: zoomlevel
      },
      true
    )
  })

  // bind the selected event to our function that handles updates to the UI
  svgCanvas.bind('selected', selectedChanged)
  svgCanvas.bind('transition', elementTransition)
  svgCanvas.bind('changed', elementChanged)
  svgCanvas.bind('saved', saveHandler)
  svgCanvas.bind('zoomed', zoomChanged)
  svgCanvas.bind('zoomDone', zoomDone)
  svgCanvas.bind('updateCanvas', function (win, { center, newCtr }) {
    updateCanvas(center, newCtr)
  })

  svgCanvas.textActions.setInputElem($('#text')[0])

  // Lose focus for select elements when changed (Allows keyboard shortcuts to work better)
  $('select').change(function () {
    $(this).blur()
  })
  ;(function () {
    const wArea = workarea[0]

    let lastX = null
    let lastY = null
    let panning = false
    const keypan = false

    $('#svgcanvas')
      .bind('mousemove mouseup', function (evt) {
        if (panning === false) {
          return true
        }

        wArea.scrollLeft -= evt.clientX - lastX
        wArea.scrollTop -= evt.clientY - lastY

        lastX = evt.clientX
        lastY = evt.clientY

        if (evt.type === 'mouseup') {
          panning = false
        }
        return false
      })
      .mousedown(function (evt) {
        if (evt.button === 1 || keypan === true) {
          panning = true
          lastX = evt.clientX
          lastY = evt.clientY
          return false
        }
        return true
      })
  })()

  /**
   *
   * @returns {void}
   */
  const cutSelected = function () {
    svgCanvas.cutSelectedElements()
  }

  /**
   *
   * @returns {void}
   */
  const copySelected = function () {
    svgCanvas.copySelectedElements()
  }

  /**
   *
   * @returns {void}
   */
  const pasteInCenter = function () {
    const zoom = svgCanvas.getZoom()
    const x = (workarea[0].scrollLeft + workarea.width() / 2) / zoom - svgCanvas.contentW
    const y = (workarea[0].scrollTop + workarea.height() / 2) / zoom - svgCanvas.contentH
    svgCanvas.pasteElements('point', x, y)
  }

  /**
   * @param {Float} dx
   * @param {Float} dy
   * @returns {void}
   */
  const moveSelected = function (dx, dy) {
    svgCanvas.moveSelectedElements(dx, dy)
  }

  const deleteSelected = function () {
    svgCanvas.deleteSelectedElements()
  }

  /**
   *
   * @returns {void}
   */
  const clickUndo = function () {
    if (undoMgr.getUndoStackSize() > 0) {
      undoMgr.undo()
    }
  }

  /**
   *
   * @returns {void}
   */
  const clickRedo = function () {
    if (undoMgr.getRedoStackSize() > 0) {
      undoMgr.redo()
    }
  }

  let resetScrollPos = $.noop

  const winWh = {
    width: $(window).width(),
    height: $(window).height()
  }

  // Fix for Issue 781: Drawing area jumps to top-left corner on window resize (IE9)
  if (isIE()) {
    resetScrollPos = function () {
      if (workarea[0].scrollLeft === 0 && workarea[0].scrollTop === 0) {
        workarea[0].scrollLeft = curScrollPos.left
        workarea[0].scrollTop = curScrollPos.top
      }
    }

    curScrollPos = {
      left: workarea[0].scrollLeft,
      top: workarea[0].scrollTop
    }

    $(window).resize(resetScrollPos)
    editor.ready(function () {
      // TODO: Find better way to detect when to do this to minimize
      // flickering effect
      return new Promise((resolve, reject) => {
        // eslint-disable-line promise/avoid-new
        setTimeout(function () {
          resetScrollPos()
          resolve()
        }, 500)
      })
    })

    workarea.scroll(function () {
      curScrollPos = {
        left: workarea[0].scrollLeft,
        top: workarea[0].scrollTop
      }
    })
  }

  $(window).resize(function (evt) {
    $.each(winWh, function (type, val) {
      const curval = $(window)[type]()
      workarea[0]['scroll' + (type === 'width' ? 'Left' : 'Top')] -= (curval - val) / 2
      winWh[type] = curval
    })
  })

  const centerCanvas = () => {
    // this centers the canvas vertically in the workarea (horizontal handled in CSS)
    workarea.css('line-height', workarea.height() + 'px')
  }

  $(window).bind('load resize', centerCanvas)

  // Prevent browser from erroneously repopulating fields
  $('input,select').attr('autocomplete', 'off')

  // Select given tool
  editor.ready(function () {
    //
  })

  Actions.setAll()
  updateCanvas(true)
}

editor.ready = function (cb) {
  // eslint-disable-line promise/prefer-await-to-callbacks
  return new Promise((resolve, reject) => {
    // eslint-disable-line promise/avoid-new
    if (isReady) {
      resolve(cb())
      return
    }
    callbacks.push([cb, resolve, reject])
  })
}

editor.runCallbacks = async function () {
  try {
    await Promise.all(
      callbacks.map(([cb]) => {
        return cb() // eslint-disable-line promise/prefer-await-to-callbacks
      })
    )
  } catch (err) {
    callbacks.forEach(([, , reject]) => {
      reject()
    })
    throw err
  }
  callbacks.forEach(([, resolve]) => {
    resolve()
  })
  isReady = true
}

editor.loadFromString = function (str, { noAlert } = {}) {
  return editor.ready(async function () {
    try {
      await loadSvgString(str, { noAlert })
    } catch (err) {
      if (noAlert) {
        throw err
      }
    }
  })
}

export default editor
