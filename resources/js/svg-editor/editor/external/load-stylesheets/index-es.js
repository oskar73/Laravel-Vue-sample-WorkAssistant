function _slicedToArray(arr, i) {
  return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest()
}

function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr
}

function _iterableToArrayLimit(arr, i) {
  if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === '[object Arguments]')) {
    return
  }

  const _arr = []
  let _n = true
  let _d = false
  let _e

  try {
    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value)

      if (i && _arr.length === i) break
    }
  } catch (err) {
    _d = true
    _e = err
  } finally {
    try {
      if (!_n && _i.return != null) _i.return()
    } finally {
      if (_d) throw _e
    }
  }

  return _arr
}

function _nonIterableRest() {
  throw new TypeError('Invalid attempt to destructure non-iterable instance')
}

function loadStylesheets(stylesheets) {
  const _ref = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {}
  const beforeDefault = _ref.before
  const afterDefault = _ref.after
  const faviconDefault = _ref.favicon
  const canvasDefault = _ref.canvas
  const _ref$image = _ref.image
  const imageDefault = _ref$image === void 0 ? true : _ref$image
  const acceptErrors = _ref.acceptErrors

  stylesheets = Array.isArray(stylesheets) ? stylesheets : [stylesheets]

  function setupLink(stylesheetURL) {
    let options = {}

    if (Array.isArray(stylesheetURL)) {
      const _stylesheetURL = stylesheetURL

      const _stylesheetURL2 = _slicedToArray(_stylesheetURL, 2)

      stylesheetURL = _stylesheetURL2[0]
      const _stylesheetURL2$ = _stylesheetURL2[1]
      options = _stylesheetURL2$ === void 0 ? {} : _stylesheetURL2$
    }

    const _options = options
    const _options$favicon = _options.favicon
    let favicon = _options$favicon === void 0 ? faviconDefault : _options$favicon
    const _options2 = options
    const _options2$before = _options2.before
    const before = _options2$before === void 0 ? beforeDefault : _options2$before
    const _options2$after = _options2.after
    const after = _options2$after === void 0 ? afterDefault : _options2$after
    const _options2$canvas = _options2.canvas
    const canvas = _options2$canvas === void 0 ? canvasDefault : _options2$canvas
    const _options2$image = _options2.image
    const image = _options2$image === void 0 ? imageDefault : _options2$image

    function addLink() {
      if (before) {
        before.before(link)
      } else if (after) {
        after.after(link)
      } else {
        // eslint-disable-next-line unicorn/prefer-node-append
        document.head.appendChild(link)
      }
    }

    var link = document.createElement('link') // eslint-disable-next-line promise/avoid-new

    return new Promise(function (resolve, reject) {
      let rej = reject

      if (acceptErrors) {
        rej =
          typeof acceptErrors === 'function'
            ? function (error) {
                acceptErrors({
                  error,
                  stylesheetURL,
                  options,
                  resolve,
                  reject
                })
              }
            : resolve
      }

      if (stylesheetURL.endsWith('.css')) {
        favicon = false
      } else if (stylesheetURL.endsWith('.ico')) {
        favicon = true
      }

      if (favicon) {
        link.rel = 'shortcut icon'
        link.type = 'image/x-icon'

        if (image === false) {
          link.href = stylesheetURL
          addLink()
          resolve(link)
          return
        }

        const cnv = document.createElement('canvas')
        cnv.width = 16
        cnv.height = 16
        const context = cnv.getContext('2d')
        const img = document.createElement('img')
        img.addEventListener('error', function (error) {
          reject(error)
        })
        img.addEventListener('load', function () {
          context.drawImage(img, 0, 0)
          link.href = canvas ? cnv.toDataURL('image/x-icon') : stylesheetURL
          addLink()
          resolve(link)
        })
        img.src = stylesheetURL
        return
      }

      link.rel = 'stylesheet'
      link.type = 'text/css'
      link.href = stylesheetURL
      addLink()
      link.addEventListener('error', function (error) {
        rej(error)
      })
      link.addEventListener('load', function () {
        resolve(link)
      })
    })
  }

  return Promise.all(
    stylesheets.map(function (stylesheetURL) {
      return setupLink(stylesheetURL)
    })
  )
}

export default loadStylesheets
