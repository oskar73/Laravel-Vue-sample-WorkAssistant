const AnchorFormExtension = require('./AnchorFormExtension')
const FormExtension = require('./FormExtension')
const ColorPickerExtension = require('./ColoerPickerExtension')
const AnchorPreviewExension = require('./AnchoPreviewExtionsion')
const KeyCommandsExtension = require('./KeyboardCommandsExtension')
const FontSizeFormExtension = require('./FontSizeFormExtension')
const PastHandleExension = require('./PastHandlerExension')
const PlaceHolderExtension = require('./PlaceholderExtension')
const ToolbarExtension = require('./ToolBarExtension')
const DefaultButtons = require('./DefaultButtons')
const ButtonExtension = require('./ButtonExtension')
const Events = require('./Events')
const Selection = require('./Selection')
const Extension = require('./Extension')
const Util = require('./Util')

;(function (root, factory) {
  'use strict'
  const isElectron = typeof module === 'object' && typeof process !== 'undefined' && process && process.versions && process.versions.electron
  if (!isElectron && typeof module === 'object') {
    module.exports = factory
  } else if (typeof define === 'function' && define.amd) {
    define(function () {
      return factory
    })
  } else {
    root.MediumEditor = factory
  }
})(
  this,
  (function () {
    'use strict'

    function MediumEditor(elements, options) {
      'use strict'
      return this.init(elements, options)
    }

    MediumEditor.extensions = {}

    // registering extensions
    ;(function () {
      // init Util
      Util.init(MediumEditor)
      // init Extension
      Extension.init(MediumEditor)
      // init Selection
      Selection.init(MediumEditor)
      // init Events
      Events.init(MediumEditor)
      // init ButtonExtension
      ButtonExtension.init(MediumEditor)
      // init DefaultButtons
      DefaultButtons.init(MediumEditor)

      // init FormExtension
      FormExtension.init(MediumEditor)
      // init AnchorFormExtension
      AnchorFormExtension.init(MediumEditor)
      // init ColorPickerExtension
      ColorPickerExtension.init(MediumEditor)
      // init AnchorPreviewExtension
      AnchorPreviewExension.init(MediumEditor)
      // init AutoLinkExtension

      // init FileDraggingExtension

      // init KeyCommandsExtension
      KeyCommandsExtension.init(MediumEditor)
      // init FontNameExtension

      // init FontSizeFormExtension
      FontSizeFormExtension.init(MediumEditor)
      // init PasteHandlerExtension
      PastHandleExension.init(MediumEditor)
      // init PlaceholderExtension
      PlaceHolderExtension.init(MediumEditor)
      // init ToolbarExtension
      ToolbarExtension.init(MediumEditor)
      // init ImageDraggingExtension
    })()
    ;(function () {
      'use strict'

      function handleDisabledEnterKeydown(event, element) {
        if (this.options.disableReturn || element.getAttribute('data-disable-return')) {
          event.preventDefault()
        } else if (this.options.disableDoubleReturn || element.getAttribute('data-disable-double-return')) {
          const node = MediumEditor.selection.getSelectionStart(this.options.ownerDocument)

          // if current text selection is empty OR previous sibling text is empty OR it is not a list
          if (
            (node && node.textContent.trim() === '' && node.nodeName.toLowerCase() !== 'li') ||
            (node.previousElementSibling && node.previousElementSibling.nodeName.toLowerCase() !== 'br' && node.previousElementSibling.textContent.trim() === '')
          ) {
            event.preventDefault()
          }
        }
      }

      function addToEditors(win) {
        if (!win._mediumEditors) {
          // To avoid breaking users who are assuming that the unique id on
          // medium-editor elements will start at 1, inserting a 'null' in the
          // array so the unique-id can always map to the index of the editor instance
          win._mediumEditors = [null]
        }

        // If this already has a unique id, re-use it
        if (!this.id) {
          this.id = win._mediumEditors.length
        }

        win._mediumEditors[this.id] = this
      }

      function removeFromEditors(win) {
        if (!win._mediumEditors || !win._mediumEditors[this.id]) {
          return
        }

        /* Setting the instance to null in the array instead of deleting it allows:
         * 1) Each instance to preserve its own unique-id, even after being destroyed
         *    and initialized again
         * 2) The unique-id to always correspond to an index in the array of medium-editor
         *    instances. Thus, we will be able to look at a contenteditable, and determine
         *    which instance it belongs to, by indexing into the global array.
         */
        win._mediumEditors[this.id] = null
      }

      function createElementsArray(selector, doc, filterEditorElements) {
        let elements = []

        if (!selector) {
          selector = []
        }
        // If string, use as query selector
        if (typeof selector === 'string') {
          selector = doc.querySelectorAll(selector)
        }
        // If element, put into array
        if (MediumEditor.util.isElement(selector)) {
          selector = [selector]
        }

        if (filterEditorElements) {
          // Remove elements that have already been initialized by the editor
          // selecotr might not be an array (ie NodeList) so use for loop
          for (let i = 0; i < selector.length; i++) {
            const el = selector[i]
            if (MediumEditor.util.isElement(el) && !el.getAttribute('data-medium-editor-element') && !el.getAttribute('medium-editor-textarea-id')) {
              elements.push(el)
            }
          }
        } else {
          // Convert NodeList (or other array like object) into an array
          elements = Array.prototype.slice.apply(selector)
        }

        return elements
      }

      function cleanupTextareaElement(element) {
        const textarea = element.parentNode.querySelector('textarea[medium-editor-textarea-id="' + element.getAttribute('medium-editor-textarea-id') + '"]')
        if (textarea) {
          // Un-hide the textarea
          textarea.classList.remove('medium-editor-hidden')
          textarea.removeAttribute('medium-editor-textarea-id')
        }
        if (element.parentNode) {
          element.parentNode.removeChild(element)
        }
      }

      function setExtensionDefaults(extension, defaults) {
        Object.keys(defaults).forEach(function (prop) {
          if (extension[prop] === undefined) {
            extension[prop] = defaults[prop]
          }
        })
        return extension
      }

      function initExtension(extension, name, instance) {
        const extensionDefaults = {
          window: instance.options.contentWindow,
          document: instance.options.ownerDocument,
          base: instance
        }

        // Add default options into the extension
        extension = setExtensionDefaults(extension, extensionDefaults)

        // Call init on the extension
        if (typeof extension.init === 'function') {
          extension.init()
        }

        // Set extension name (if not already set)
        if (!extension.name) {
          extension.name = name
        }

        return extension
      }

      function isToolbarEnabled() {
        // If any of the elements don't have the toolbar disabled
        // We need a toolbar
        if (
          this.elements.every(function (element) {
            return !!element.getAttribute('data-disable-toolbar')
          })
        ) {
          return false
        }

        return this.options.toolbar !== false
      }

      function isAnchorPreviewEnabled() {
        // If toolbar is disabled, don't add
        if (!isToolbarEnabled.call(this)) {
          return false
        }

        return this.options.anchorPreview !== false
      }

      function isPlaceholderEnabled() {
        return this.options.placeholder !== false
      }

      function isAutoLinkEnabled() {
        return this.options.autoLink !== false
      }

      function isKeyboardCommandsEnabled() {
        return this.options.keyboardCommands !== false
      }

      function initElement(element) {
        if (!element.getAttribute('data-medium-editor-element')) {
          if (!this.options.disableEditing && !element.getAttribute('data-disable-editing')) {
            element.setAttribute('contentEditable', true)
            element.setAttribute('spellcheck', this.options.spellcheck)
          }

          // Make sure we only attach to editableKeydownEnter once for disable-return options
          if (!this.instanceHandleEditableKeydownEnter) {
            if (element.getAttribute('data-disable-return') || element.getAttribute('data-disable-double-return')) {
              this.instanceHandleEditableKeydownEnter = handleDisabledEnterKeydown.bind(this)
              this.subscribe('editableKeydownEnter', this.instanceHandleEditableKeydownEnter)
            }
          }

          element.setAttribute('data-medium-editor-element', true)
        }

        return element
      }

      function initExtensions() {
        this.extensions = []

        // Passed in extensions
        Object.keys(this.options.extensions).forEach(function (name) {
          // Always save the toolbar extension for last
          if (name !== 'toolbar' && this.options.extensions[name]) {
            this.extensions.push(initExtension(this.options.extensions[name], name, this))
          }
        }, this)

        // Built-in extensions
        const builtIns = {
          paste: true,
          'anchor-preview': isAnchorPreviewEnabled.call(this),
          autoLink: isAutoLinkEnabled.call(this),
          keyboardCommands: isKeyboardCommandsEnabled.call(this),
          placeholder: isPlaceholderEnabled.call(this)
        }
        Object.keys(builtIns).forEach(function (name) {
          if (builtIns[name]) {
            this.addBuiltInExtension(name)
          }
        }, this)

        // Users can pass in a custom toolbar extension
        // so check for that first and if it's not present
        // just create the default toolbar
        let toolbarExtension = this.options.extensions.toolbar

        if (!toolbarExtension && isToolbarEnabled.call(this)) {
          // Backwards compatability
          const toolbarOptions = MediumEditor.util.extend({}, this.options.toolbar, {
            allowMultiParagraphSelection: this.options.allowMultiParagraphSelection // deprecated
          })
          toolbarExtension = new MediumEditor.extensions.toolbar(toolbarOptions)
        }

        // If the toolbar is not disabled, so we actually have an extension
        // initialize it and add it to the extensions array
        if (toolbarExtension) {
          this.extensions.push(initExtension(toolbarExtension, 'toolbar', this))
        }
      }

      // Execute actions
      function mergeOptions(defaults, options) {
        const deprecatedProperties = [['allowMultiParagraphSelection', 'toolbar.allowMultiParagraphSelection']]
        // warn about using deprecated properties
        if (options) {
          deprecatedProperties.forEach(function (pair) {
            if (options.hasOwnProperty(pair[0]) && options[pair[0]] !== undefined) {
              MediumEditor.util.deprecated(pair[0], pair[1], 'v6.0.0')
            }
          })
        }

        return MediumEditor.util.defaults({}, options, defaults)
      }

      // Execute actions
      function execActionInternal(action, opts) {
        /* jslint regexp: true */
        const appendAction = /^append-(.+)$/gi
        const justifyAction = /justify([A-Za-z]*)$/g /* Detecting if is justifyCenter|Right|Left */
        let match
        let cmdValueArgument
        /* jslint regexp: false */

        // Actions starting with 'append-' should attempt to format a block of text ('formatBlock') using a specific
        // type of block element (ie append-blockquote, append-h1, append-pre, etc.)
        match = appendAction.exec(action)

        if (match) {
          return MediumEditor.util.execFormatBlock(this.options.ownerDocument, match[1])
        }

        if (action === 'fontColor') {
          cmdValueArgument = opts.value
          this.options.ownerDocument.execCommand('foreColor', false, '#000000')
          const fontElements = document.getSelection().anchorNode.parentNode
          fontElements.removeAttribute('color')
          fontElements.style.color = `var(--bz-${cmdValueArgument.dashed()})`
          return true
        }

        if (action === 'fontSize') {
          cmdValueArgument = opts.value
          this.options.ownerDocument.execCommand('fontSize', false, '4')
          const fontElements = document.getSelection().anchorNode.parentNode
          fontElements.removeAttribute('size')
          fontElements.style.fontSize = cmdValueArgument + 'px'
          return true
        }

        if (action === 'fontName') {
          // TODO: Deprecate support for opts.name in 6.0.0
          if (opts.name) {
            MediumEditor.util.deprecated('.name option for fontName command', '.value', '6.0.0')
          }
          cmdValueArgument = opts.value || opts.name
          return this.options.ownerDocument.execCommand('fontName', false, cmdValueArgument)
        }

        if (action === 'createLink') {
          return this.createLink(opts)
        }

        if (action === 'image') {
          const src = this.options.contentWindow.getSelection().toString().trim()
          return this.options.ownerDocument.execCommand('insertImage', false, src)
        }

        if (action === 'html') {
          const html = this.options.contentWindow.getSelection().toString().trim()
          return MediumEditor.util.insertHTMLCommand(this.options.ownerDocument, html)
        }

        /* Issue: https://github.com/yabwe/medium-editor/issues/595
         * If the action is to justify the text */
        if (justifyAction.exec(action)) {
          const result = this.options.ownerDocument.execCommand(action, false, null)
          const parentNode = MediumEditor.selection.getSelectedParentElement(MediumEditor.selection.getSelectionRange(this.options.ownerDocument))
          if (parentNode) {
            cleanupJustifyDivFragments.call(this, MediumEditor.util.getTopBlockContainer(parentNode))
          }

          return result
        }
        cmdValueArgument = opts && opts.value
        return this.options.ownerDocument.execCommand(action, false, cmdValueArgument)
      }

      /* If we've just justified text within a container block
       * Chrome may have removed <br> elements and instead wrapped lines in <div> elements
       * with a text-align property.  If so, we want to fix this
       */
      function cleanupJustifyDivFragments(blockContainer) {
        if (!blockContainer) {
          return
        }

        let textAlign
        const childDivs = Array.prototype.slice.call(blockContainer.childNodes).filter(function (element) {
          const isDiv = element.nodeName.toLowerCase() === 'div'
          if (isDiv && !textAlign) {
            textAlign = element.style.textAlign
          }
          return isDiv
        })

        /* If we found child <div> elements with text-align style attributes
         * we should fix this by:
         *
         * 1) Unwrapping each <div> which has a text-align style
         * 2) Insert a <br> element after each set of 'unwrapped' div children
         * 3) Set the text-align style of the parent block element
         */
        if (childDivs.length) {
          // Since we're mucking with the HTML, preserve selection
          this.saveSelection()
          childDivs.forEach(function (div) {
            if (div.style.textAlign === textAlign) {
              const lastChild = div.lastChild
              if (lastChild) {
                // Instead of a div, extract the child elements and add a <br>
                MediumEditor.util.unwrap(div, this.options.ownerDocument)
                const br = this.options.ownerDocument.createElement('BR')
                lastChild.parentNode.insertBefore(br, lastChild.nextSibling)
              }
            }
          }, this)
          blockContainer.style.textAlign = textAlign
          // We're done, so restore selection
          this.restoreSelection()
        }
      }

      const initialContent = {}

      MediumEditor.prototype = {
        // NOT DOCUMENTED - exposed for backwards compatability
        init: function (elements, options) {
          this.options = mergeOptions.call(this, this.defaults, options)
          this.origElements = elements

          if (!this.options.elementsContainer) {
            this.options.elementsContainer = this.options.ownerDocument.body
          }

          return this.setup()
        },

        setup: function () {
          if (this.isActive) {
            return
          }

          addToEditors.call(this, this.options.contentWindow)
          this.events = new MediumEditor.Events(this)
          this.elements = []

          this.addElements(this.origElements)

          if (this.elements.length === 0) {
            return
          }

          this.isActive = true

          // Call initialization helpers
          initExtensions.call(this)
        },

        destroy: function () {
          if (!this.isActive) {
            return
          }

          this.isActive = false

          this.extensions.forEach(function (extension) {
            if (typeof extension.destroy === 'function') {
              extension.destroy()
            }
          }, this)

          this.events.destroy()

          this.elements.forEach(function (element) {
            // Reset elements content, fix for issue where after editor destroyed the red underlines on spelling errors are left
            if (this.options.spellcheck) {
              element.innerHTML = element.innerHTML
            }

            // cleanup extra added attributes
            element.removeAttribute('contentEditable')
            element.removeAttribute('spellcheck')
            element.removeAttribute('data-medium-editor-element')
            element.classList.remove('medium-editor-element')
            element.removeAttribute('role')
            element.removeAttribute('aria-multiline')
            element.removeAttribute('medium-editor-index')
            element.removeAttribute('data-medium-editor-editor-index')

            // Remove any elements created for textareas
            if (element.getAttribute('medium-editor-textarea-id')) {
              cleanupTextareaElement(element)
            }
          }, this)
          this.elements = []
          this.instanceHandleEditableKeydownEnter = null
          this.instanceHandleEditableInput = null

          removeFromEditors.call(this, this.options.contentWindow)
        },

        on: function (target, event, listener, useCapture) {
          this.events.attachDOMEvent(target, event, listener, useCapture)

          return this
        },

        off: function (target, event, listener, useCapture) {
          this.events.detachDOMEvent(target, event, listener, useCapture)

          return this
        },

        subscribe: function (event, listener) {
          this.events.attachCustomEvent(event, listener)

          return this
        },

        unsubscribe: function (event, listener) {
          this.events.detachCustomEvent(event, listener)

          return this
        },

        trigger: function (name, data, editable) {
          this.events.triggerCustomEvent(name, data, editable)

          return this
        },

        delay: function (fn) {
          const self = this
          return setTimeout(function () {
            if (self.isActive) {
              fn()
            }
          }, this.options.delay)
        },

        serialize: function () {
          let i
          let elementid
          const content = {}
          const len = this.elements.length

          for (i = 0; i < len; i += 1) {
            elementid = this.elements[i].id !== '' ? this.elements[i].id : 'element-' + i
            content[elementid] = {
              value: this.elements[i].innerHTML.trim()
            }
          }
          return content
        },

        getExtensionByName: function (name) {
          let extension
          if (this.extensions && this.extensions.length) {
            this.extensions.some(function (ext) {
              if (ext.name === name) {
                extension = ext
                return true
              }
              return false
            })
          }
          return extension
        },

        /**
         * NOT DOCUMENTED - exposed as a helper for other extensions to use
         */
        addBuiltInExtension: function (name, opts) {
          let extension = this.getExtensionByName(name)
          let merged

          if (extension) {
            return extension
          }

          switch (name) {
            case 'anchor':
              merged = MediumEditor.util.extend({}, this.options.anchor, opts)
              extension = new MediumEditor.extensions.anchor(merged)
              break
            case 'anchor-preview':
              extension = new MediumEditor.extensions.anchorPreview(this.options.anchorPreview)
              break
            case 'autoLink':
              extension = new MediumEditor.extensions.autoLink()
              break
            case 'fileDragging':
              extension = new MediumEditor.extensions.fileDragging(opts)
              break
            case 'fontname':
              extension = new MediumEditor.extensions.fontName(this.options.fontName)
              break
            case 'fontsize':
              extension = new MediumEditor.extensions.fontSize(opts)
              break
            case 'colorPicker':
              extension = new MediumEditor.extensions.colorPicker(opts)
              break
            case 'keyboardCommands':
              extension = new MediumEditor.extensions.keyboardCommands(this.options.keyboardCommands)
              break
            case 'paste':
              extension = new MediumEditor.extensions.paste(this.options.paste)
              break
            case 'placeholder':
              extension = new MediumEditor.extensions.placeholder(this.options.placeholder)
              break
            default:
              // All of the built-in buttons for MediumEditor are extensions
              // so check to see if the extension we're creating is a built-in button
              if (MediumEditor.extensions.button.isBuiltInButton(name)) {
                if (opts) {
                  merged = MediumEditor.util.defaults({}, opts, MediumEditor.extensions.button.prototype.defaults[name])
                  extension = new MediumEditor.extensions.button(merged)
                } else {
                  extension = new MediumEditor.extensions.button(name)
                }
              }
          }

          if (extension) {
            this.extensions.push(initExtension(extension, name, this))
          }

          return extension
        },

        stopSelectionUpdates: function () {
          this.preventSelectionUpdates = true
        },

        startSelectionUpdates: function () {
          this.preventSelectionUpdates = false
        },

        checkSelection: function () {
          const toolbar = this.getExtensionByName('toolbar')
          if (toolbar) {
            toolbar.checkState()
          }
          return this
        },

        // Wrapper around document.queryCommandState for checking whether an action has already
        // been applied to the current selection
        queryCommandState: function (action) {
          const fullAction = /^full-(.+)$/gi
          let match
          let queryState = null

          // Actions starting with 'full-' need to be modified since this is a medium-editor concept
          match = fullAction.exec(action)
          if (match) {
            action = match[1]
          }

          try {
            queryState = this.options.ownerDocument.queryCommandState(action)
          } catch (exc) {
            queryState = null
          }

          return queryState
        },

        execAction: function (action, opts) {
          /* jslint regexp: true */
          const fullAction = /^full-(.+)$/gi
          let match
          let result
          /* jslint regexp: false */

          // Actions starting with 'full-' should be applied to to the entire contents of the editable element
          // (ie full-bold, full-append-pre, etc.)
          match = fullAction.exec(action)

          const selection = this.exportSelection()
          // if (match || selection.start === selection.end) {

          if (match) {
            // Store the current selection to be restored after applying the action
            this.saveSelection()
            // Select all of the contents before calling the action
            this.selectAllContents()
            result = execActionInternal.call(this, (match && match[1]) || action, opts)
            // Restore the previous selection
            this.restoreSelection()
          } else {
            if (selection && selection.start < selection.end) {
              result = execActionInternal.call(this, action, opts)
            }
          }

          // do some DOM clean-up for known browser issues after the action
          if (action === 'insertunorderedlist' || action === 'insertorderedlist') {
            MediumEditor.util.cleanListDOM(this.options.ownerDocument, this.getSelectedParentElement())
          }

          this.checkSelection()

          // update content whenever the actions are executed.
          const currentEditor = MediumEditor.selection.getSelectionElement(this.options.contentWindow)
          const customEvent = {}
          this.events.triggerCustomEvent('editableInput', customEvent, currentEditor)

          return result
        },

        getSelectedParentElement: function (range) {
          if (range === undefined) {
            range = this.options.contentWindow.getSelection().getRangeAt(0)
          }
          return MediumEditor.selection.getSelectedParentElement(range)
        },

        selectAllContents: function () {
          let currNode = MediumEditor.selection.getSelectionElement(this.options.contentWindow)

          if (currNode) {
            // Move to the lowest descendant node that still selects all of the contents
            while (currNode.children.length === 1) {
              currNode = currNode.children[0]
            }

            this.selectElement(currNode)
          }
        },

        selectElement: function (element) {
          MediumEditor.selection.selectNode(element, this.options.ownerDocument)

          const selElement = MediumEditor.selection.getSelectionElement(this.options.contentWindow)
          if (selElement) {
            this.events.focusElement(selElement)
          }
        },

        getFocusedElement: function () {
          let focused
          this.elements.some(function (element) {
            // Find the element that has focus
            if (!focused && element.getAttribute('data-medium-focused')) {
              focused = element
            }

            // bail if we found the element that had focus
            return !!focused
          }, this)

          return focused
        },

        // Export the state of the selection in respect to one of this
        // instance of MediumEditor's elements
        exportSelection: function () {
          const selectionElement = MediumEditor.selection.getSelectionElement(this.options.contentWindow)
          const editableElementIndex = this.elements.indexOf(selectionElement)
          let selectionState = null

          if (editableElementIndex >= 0) {
            selectionState = MediumEditor.selection.exportSelection(selectionElement, this.options.ownerDocument)
          }

          if (selectionState !== null && editableElementIndex !== 0) {
            selectionState.editableElementIndex = editableElementIndex
          }

          return selectionState
        },

        saveSelection: function () {
          this.selectionState = this.exportSelection()
        },

        // Restore a selection based on a selectionState returned by a call
        // to MediumEditor.exportSelection
        importSelection: function (selectionState, favorLaterSelectionAnchor) {
          if (!selectionState) {
            return
          }

          const editableElement = this.elements[selectionState.editableElementIndex || 0]
          MediumEditor.selection.importSelection(selectionState, editableElement, this.options.ownerDocument, favorLaterSelectionAnchor)
        },

        restoreSelection: function () {
          this.importSelection(this.selectionState)
        },

        createLink: function (opts) {
          const currentEditor = MediumEditor.selection.getSelectionElement(this.options.contentWindow)
          let customEvent = {}
          let targetUrl

          // Make sure the selection is within an element this editor is tracking
          if (this.elements.indexOf(currentEditor) === -1) {
            return
          }

          try {
            this.events.disableCustomEvent('editableInput')
            // TODO: Deprecate support for opts.url in 6.0.0
            if (opts.url) {
              MediumEditor.util.deprecated('.url option for createLink', '.value', '6.0.0')
            }
            targetUrl = opts.url || opts.value
            if (targetUrl && targetUrl.trim().length > 0) {
              const currentSelection = this.options.contentWindow.getSelection()
              if (currentSelection) {
                const currRange = currentSelection.getRangeAt(0)
                let commonAncestorContainer = currRange.commonAncestorContainer
                let exportedSelection
                let startContainerParentElement
                let endContainerParentElement
                let textNodes

                // If the selection is contained within a single text node
                // and the selection starts at the beginning of the text node,
                // MSIE still says the startContainer is the parent of the text node.
                // If the selection is contained within a single text node, we
                // want to just use the default browser 'createLink', so we need
                // to account for this case and adjust the commonAncestorContainer accordingly
                if (
                  currRange.endContainer.nodeType === 3 &&
                  currRange.startContainer.nodeType !== 3 &&
                  currRange.startOffset === 0 &&
                  currRange.startContainer.firstChild === currRange.endContainer
                ) {
                  commonAncestorContainer = currRange.endContainer
                }

                startContainerParentElement = MediumEditor.util.getClosestBlockContainer(currRange.startContainer)
                endContainerParentElement = MediumEditor.util.getClosestBlockContainer(currRange.endContainer)

                // If the selection is not contained within a single text node
                // but the selection is contained within the same block element
                // we want to make sure we create a single link, and not multiple links
                // which can happen with the built in browser functionality
                if (commonAncestorContainer.nodeType !== 3 && commonAncestorContainer.textContent.length !== 0 && startContainerParentElement === endContainerParentElement) {
                  const parentElement = startContainerParentElement || currentEditor
                  let fragment = this.options.ownerDocument.createDocumentFragment()

                  // since we are going to create a link from an extracted text,
                  // be sure that if we are updating a link, we won't let an empty link behind (see #754)
                  // (Workaroung for Chrome)
                  this.execAction('unlink')

                  exportedSelection = this.exportSelection()
                  fragment.appendChild(parentElement.cloneNode(true))

                  if (currentEditor === parentElement) {
                    // We have to avoid the editor itself being wiped out when it's the only block element,
                    // as our reference inside this.elements gets detached from the page when insertHTML runs.
                    // If we just use [parentElement, 0] and [parentElement, parentElement.childNodes.length]
                    // as the range boundaries, this happens whenever parentElement === currentEditor.
                    // The tradeoff to this workaround is that a orphaned tag can sometimes be left behind at
                    // the end of the editor's content.
                    // In Gecko:
                    // as an empty <strong></strong> if parentElement.lastChild is a <strong> tag.
                    // In WebKit:
                    // an invented <br /> tag at the end in the same situation
                    MediumEditor.selection.select(
                      this.options.ownerDocument,
                      parentElement.firstChild,
                      0,
                      parentElement.lastChild,
                      parentElement.lastChild.nodeType === 3 ? parentElement.lastChild.nodeValue.length : parentElement.lastChild.childNodes.length
                    )
                  } else {
                    MediumEditor.selection.select(this.options.ownerDocument, parentElement, 0, parentElement, parentElement.childNodes.length)
                  }

                  const modifiedExportedSelection = this.exportSelection()

                  textNodes = MediumEditor.util.findOrCreateMatchingTextNodes(this.options.ownerDocument, fragment, {
                    start: exportedSelection.start - modifiedExportedSelection.start,
                    end: exportedSelection.end - modifiedExportedSelection.start,
                    editableElementIndex: exportedSelection.editableElementIndex
                  })
                  // If textNodes are not present, when changing link on images
                  // ex: <a><img src="http://image.test.com"></a>, change fragment to currRange.startContainer
                  // and set textNodes array to [imageElement, imageElement]
                  if (textNodes.length === 0) {
                    fragment = this.options.ownerDocument.createDocumentFragment()
                    fragment.appendChild(commonAncestorContainer.cloneNode(true))
                    textNodes = [fragment.firstChild.firstChild, fragment.firstChild.lastChild]
                  }

                  // Creates the link in the document fragment
                  MediumEditor.util.createLink(this.options.ownerDocument, textNodes, targetUrl.trim())

                  // Chrome trims the leading whitespaces when inserting HTML, which messes up restoring the selection.
                  const leadingWhitespacesCount = (fragment.firstChild.innerHTML.match(/^\s+/) || [''])[0].length

                  // Now move the created link back into the original document in a way to preserve undo/redo history
                  MediumEditor.util.insertHTMLCommand(this.options.ownerDocument, fragment.firstChild.innerHTML.replace(/^\s+/, ''))
                  exportedSelection.start -= leadingWhitespacesCount
                  exportedSelection.end -= leadingWhitespacesCount

                  this.importSelection(exportedSelection)
                } else {
                  this.options.ownerDocument.execCommand('createLink', false, targetUrl)
                }

                if (this.options.targetBlank || opts.target === '_blank') {
                  MediumEditor.util.setTargetBlank(MediumEditor.selection.getSelectionStart(this.options.ownerDocument), targetUrl)
                } else {
                  MediumEditor.util.removeTargetBlank(MediumEditor.selection.getSelectionStart(this.options.ownerDocument), targetUrl)
                }

                if (opts.buttonClass) {
                  MediumEditor.util.addClassToAnchors(MediumEditor.selection.getSelectionStart(this.options.ownerDocument), opts.buttonClass)
                }
              }
            }
            // Fire input event for backwards compatibility if anyone was listening directly to the DOM input event
            if (this.options.targetBlank || opts.target === '_blank' || opts.buttonClass) {
              customEvent = this.options.ownerDocument.createEvent('HTMLEvents')
              customEvent.initEvent('input', true, true, this.options.contentWindow)
              for (let i = 0, len = this.elements.length; i < len; i += 1) {
                this.elements[i].dispatchEvent(customEvent)
              }
            }
          } finally {
            this.events.enableCustomEvent('editableInput')
          }
          // Fire our custom editableInput event
          this.events.triggerCustomEvent('editableInput', customEvent, currentEditor)
        },

        cleanPaste: function (text) {
          this.getExtensionByName('paste').cleanPaste(text)
        },

        pasteHTML: function (html, options) {
          this.getExtensionByName('paste').pasteHTML(html, options)
        },

        setContent: function (html, index) {
          index = index || 0

          if (this.elements[index]) {
            const target = this.elements[index]
            target.innerHTML = html
            this.checkContentChanged(target)
          }
        },

        getContent: function (index) {
          index = index || 0

          if (this.elements[index]) {
            return this.elements[index].innerHTML.trim()
          }
          return null
        },

        checkContentChanged: function (editable) {
          editable = editable || MediumEditor.selection.getSelectionElement(this.options.contentWindow)
          this.events.updateInput(editable, { target: editable, currentTarget: editable })
        },

        resetContent: function (element) {
          // For all elements that exist in the this.elements array, we can assume:
          // - Its initial content has been set in the initialContent object
          // - It has a medium-editor-index attribute which is the key value in the initialContent object

          if (element) {
            const index = this.elements.indexOf(element)
            if (index !== -1) {
              this.setContent(initialContent[element.getAttribute('medium-editor-index')], index)
            }
            return
          }

          this.elements.forEach(function (el, idx) {
            this.setContent(initialContent[el.getAttribute('medium-editor-index')], idx)
          }, this)
        },

        addElements: function (selector) {
          // Convert elements into an array
          const elements = createElementsArray(selector, this.options.ownerDocument, true)

          // Do we have elements to add now?
          if (elements.length === 0) {
            return false
          }

          elements.forEach(function (element) {
            // Initialize all new elements (we check that in those functions don't worry)
            element = initElement.call(this, element, this.id)

            // Add new elements to our internal elements array
            this.elements.push(element)

            // Trigger event so extensions can know when an element has been added
            this.trigger('addElement', { target: element, currentTarget: element }, element)
          }, this)
        },

        removeElements: function (selector) {
          // Convert elements into an array
          const elements = createElementsArray(selector, this.options.ownerDocument)
          const toRemove = elements.map(function (el) {
            // For textareas, make sure we're looking at the editor div and not the textarea itself
            if (el.getAttribute('medium-editor-textarea-id') && el.parentNode) {
              return el.parentNode.querySelector('div[medium-editor-textarea-id="' + el.getAttribute('medium-editor-textarea-id') + '"]')
            } else {
              return el
            }
          })

          this.elements = this.elements.filter(function (element) {
            // If this is an element we want to remove
            if (toRemove.indexOf(element) !== -1) {
              this.events.cleanupElement(element)
              if (element.getAttribute('medium-editor-textarea-id')) {
                cleanupTextareaElement(element)
              }
              // Trigger event so extensions can clean-up elements that are being removed
              this.trigger('removeElement', { target: element, currentTarget: element }, element)
              return false
            }
            return true
          }, this)
        }
      }

      MediumEditor.getEditorFromElement = function (element) {
        const index = element.getAttribute('data-medium-editor-editor-index')
        const win = element && element.ownerDocument && (element.ownerDocument.defaultView || element.ownerDocument.parentWindow)
        if (win && win._mediumEditors && win._mediumEditors[index]) {
          return win._mediumEditors[index]
        }
        return null
      }
    })()
    ;(function () {
      // summary: The default options hash used by the Editor

      MediumEditor.prototype.defaults = {
        activeButtonClass: 'medium-editor-button-active',
        buttonLabels: false,
        delay: 0,
        disableReturn: false,
        disableDoubleReturn: false,
        disableExtraSpaces: false,
        disableEditing: false,
        autoLink: false,
        elementsContainer: false,
        contentWindow: window,
        ownerDocument: document,
        targetBlank: false,
        extensions: {},
        spellcheck: true
      }
    })()

    MediumEditor.parseVersionString = function (release) {
      const split = release.split('-')
      const version = split[0].split('.')
      const preRelease = split.length > 1 ? split[1] : ''
      return {
        major: parseInt(version[0], 10),
        minor: parseInt(version[1], 10),
        revision: parseInt(version[2], 10),
        preRelease: preRelease,
        toString: function () {
          return [version[0], version[1], version[2]].join('.') + (preRelease ? '-' + preRelease : '')
        }
      }
    }

    MediumEditor.version = MediumEditor.parseVersionString.call(
      this,
      {
        // grunt-bump looks for this:
        version: '5.23.3'
      }.version
    )

    return MediumEditor
  })()
)
