module.exports = {
  init: function (MediumEditor) {
    'use strict'

    const FontSizeForm = MediumEditor.extensions.button.extend({
      name: 'fontsize',
      action: 'fontSize',
      aria: 'increase/decrease font size',
      contentDefault: '&#xB1;',
      contentFA: '<i class="fa fa-text-height"></i>',
      fontSize: '14px',
      increaseButton: null,
      decreaseButton: null,
      fontSizeBox: null,

      init: function () {
        MediumEditor.extensions.button.prototype.init.apply(this, arguments)
      },

      getButton: function () {
        const that = this

        const fontIncreaseBtn = window.document.createElement('button')
        fontIncreaseBtn.classList.add('medium-editor-action')
        fontIncreaseBtn.innerHTML = '<b><i><svg width="24" height="24" viewBox="0 0 24 24" fill="#555555"> <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path></svg></i></b>'
        fontIncreaseBtn.addEventListener('click', function () {
          const fontSize = getFontSize()
          const newFontSize = fontSize + 1
          that.fontSizeBox.innerHTML = newFontSize + 'px'
          that.execAction('fontSize', { value: newFontSize })
        })
        this.increaseButton = fontIncreaseBtn

        const fontDecreaseBtn = window.document.createElement('button')
        fontDecreaseBtn.classList.add('medium-editor-action')
        fontDecreaseBtn.innerHTML = '<b><i><svg width="24" height="24" viewBox="0 0 24 24" fill="#555555"><path d="M19 13H5v-2h14v2z"></path></svg></i></b>'
        fontDecreaseBtn.addEventListener('click', function () {
          const fontSize = getFontSize()
          const newFontSize = fontSize - 1
          that.fontSizeBox.innerHTML = newFontSize + 'px'
          that.execAction('fontSize', { value: newFontSize })
        })
        this.decreaseButton = fontDecreaseBtn

        const fontSizeBox = window.document.createElement('div')
        fontSizeBox.classList.add('medium-editor-font-size')
        fontSizeBox.innerHTML = this.fontSize
        this.fontSizeBox = fontSizeBox

        function getFontSize() {
          const fontSize = window.getComputedStyle(window.getSelection().anchorNode.parentElement, null).getPropertyValue('font-size')
          return parseInt(fontSize.replace('px', ''))
        }

        return [fontDecreaseBtn, fontSizeBox, fontIncreaseBtn]
      },
      updateFontSize: () => {
        this.fontSize = window.getComputedStyle(window.getSelection().anchorNode.parentElement, null).getPropertyValue('font-size')
        if (this.fontSizeBox) {
          this.fontSizeBox.innerHTML = this.fontSize
        }
      },
      // Called when the button the toolbar is clicked
      // Overrides ButtonExtension.handleClick
      handleClick: function (event) {
        event.preventDefault()
        event.stopPropagation()
        if (!this.isDisplayed()) {
          // Get fontsize of current selection (convert to string since IE returns this as number)
          const fontSize = window.getComputedStyle(window.getSelection().anchorNode.parentElement, null).getPropertyValue('font-size')
          this.fontSize = fontSize
          this.fontSizeBox.innerHTML = fontSize
        }
        return false
      },

      // Called by medium-editor to append form to the toolbar
      getForm: function () {
        if (!this.form) {
          this.form = this.createForm()
        }
        return this.form
      },

      // Used by medium-editor when the default toolbar is to be displayed
      isDisplayed: function () {
        return this.getForm().style.display === 'flex'
      },

      hideForm: function () {
        this.getForm().style.display = 'none'
        this.getInput().value = ''
      },

      showForm: function (fontSize) {
        const input = this.getInput()

        this.base.saveSelection()
        this.hideToolbarDefaultActions()
        this.getForm().style.display = 'flex'
        this.setToolbarPosition()

        input.value = fontSize || ''
        input.focus()
      },

      // Called by core when tearing down medium-editor (destroy)
      destroy: function () {
        if (!this.form) {
          return false
        }

        if (this.form.parentNode) {
          this.form.parentNode.removeChild(this.form)
        }

        delete this.form
      },

      // core methods

      doFormSave: function () {
        this.base.restoreSelection()
        this.base.checkSelection()
      },

      doFormCancel: function () {
        this.base.restoreSelection()
        this.clearFontSize()
        this.base.checkSelection()
      },

      // form creation and event handling
      createForm: function () {
        const doc = this.document
        const form = doc.createElement('div')
        const input = doc.createElement('input')
        const close = doc.createElement('a')
        const save = doc.createElement('a')

        // Font Size Form (div)
        form.className = 'medium-editor-toolbar-form'
        form.id = 'medium-editor-toolbar-form-fontsize-' + this.getEditorId()

        // Handle clicks on the form itself
        this.on(form, 'click', this.handleFormClick.bind(this))

        // Add font size slider
        input.setAttribute('type', 'range')
        input.setAttribute('min', '1')
        input.setAttribute('max', '7')
        input.className = 'medium-editor-toolbar-input'
        form.appendChild(input)

        // Handle typing in the textbox
        this.on(input, 'change', this.handleSliderChange.bind(this))

        // Add save buton
        save.setAttribute('href', '#')
        save.className = 'medium-editor-toobar-save'
        save.innerHTML = this.getEditorOption('buttonLabels') === 'fontawesome' ? '<i class="fa fa-check"></i>' : '&#10003;'
        form.appendChild(save)

        // Handle save button clicks (capture)
        this.on(save, 'click', this.handleSaveClick.bind(this), true)

        // Add close button
        close.setAttribute('href', '#')
        close.className = 'medium-editor-toobar-close'
        close.innerHTML = this.getEditorOption('buttonLabels') === 'fontawesome' ? '<i class="fa fa-times"></i>' : '&times;'
        form.appendChild(close)

        // Handle close button clicks
        this.on(close, 'click', this.handleCloseClick.bind(this))

        return form
      },

      getInput: function () {
        return this.getForm().querySelector('input.medium-editor-toolbar-input')
      },

      clearFontSize: function () {
        MediumEditor.selection.getSelectedElements(this.document).forEach(function (el) {
          if (el.nodeName.toLowerCase() === 'font' && el.hasAttribute('size')) {
            el.removeAttribute('size')
          }
        })
      },

      handleSliderChange: function () {
        const size = this.getInput().value
        if (size === '4') {
          this.clearFontSize()
        } else {
          this.execAction('fontSize', { value: size })
        }
      },

      handleFormClick: function (event) {
        // make sure not to hide form when clicking inside the form
        event.stopPropagation()
      },

      handleSaveClick: function (event) {
        // Clicking Save -> create the font size
        event.preventDefault()
        this.doFormSave()
      },

      handleCloseClick: function (event) {
        // Click Close -> close the form
        event.preventDefault()
        this.doFormCancel()
      }
    })

    MediumEditor.extensions.fontSize = FontSizeForm
  }
}
