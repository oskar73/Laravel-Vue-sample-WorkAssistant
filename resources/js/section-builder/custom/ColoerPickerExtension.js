module.exports = {
  init: function (MediumEditor) {
    'use strict'
    const colorPicker = MediumEditor.extensions.form.extend({
      name: 'colorPicker',
      action: 'colorPicker',
      divide: true,

      init: function () {
        MediumEditor.extensions.form.prototype.init.apply(this, arguments)
      },
      getButton: function () {
        if (!this.button) {
          this.button = this.createButton()
        }
        return this.button
      },
      createButton: function () {
        const button = this.document.createElement('button')
        button.classList.add('medium-editor-action')
        button.classList.add('medium-editor-color-picker')
        button.title = 'Text color'
        button.innerHTML =
          '<svg class="MuiSvgIcon-root jss79" focusable="false"  fill="#555555"  width="22" height="22"   viewBox="0 0 24 24" aria-hidden="true">' +
          '<path fill="#000000" d="M0 20h24v4H0z"></path>' +
          '<path d="M11 3L5.5 17h2.25l1.12-3h6.25l1.12 3h2.25L13 3h-2zm-1.38 9L12 5.67 14.38 12H9.62z"></path></svg>'
        button.addEventListener('click', this.handleClick.bind(this))
        return button
      },
      getForm: function () {
        if (!this.form) {
          this.form = this.createForm()
        }
        return this.form
      },
      updateForm: function () {
        const color = window.getComputedStyle(window.getSelection().anchorNode.parentElement, null).getPropertyValue('color')
        this.getButton().innerHTML = `<svg class="MuiSvgIcon-root jss79" focusable="false"  fill="#555555"  width="22" height="22"   viewBox="0 0 24 24" aria-hidden="true">' +
                    '<path fill="${color}" d="M0 20h24v4H0z"></path>' +
                    '<rect width="24" height="4" x="0" y="20" style="fill:rgba(0,0,0,0); stroke-width:1; stroke:rgba(0,0,0,0.15)" />
                    '<path d="M11 3L5.5 17h2.25l1.12-3h6.25l1.12 3h2.25L13 3h-2zm-1.38 9L12 5.67 14.38 12H9.62z"></path></svg>`

        if (this.colorItems) {
          this.colorItems.forEach((item) => {
            const ItemContent = item.getElementsByClassName('content')[0]
            const backgroundColor = window.getComputedStyle(ItemContent, null).getPropertyValue('background-color')
            if (ItemContent.classList.contains('active')) {
              ItemContent.classList.remove('active')
            }
            if (color === backgroundColor) {
              ItemContent.classList.add('active')
            }
          })
        }
      },
      createForm: function () {
        const doc = this.document
        const form = doc.createElement('div')
        // Font Size Form (div)
        form.className = 'medium-editor-toolbar-form medium-editor-toolbar-form-color-picker'
        form.id = 'medium-editor-toolbar-form-color-picker-' + this.getEditorId()

        const colorItems = []

        this.base.options.colors.forEach((color) => {
          const colorItemElement = doc.createElement('button')
          colorItemElement.className = 'color-item'

          const colorContentElement = doc.createElement('div')
          colorContentElement.className = 'content'
          colorContentElement.style.backgroundColor = `var(--bz-${color.dashed()})`
          colorItemElement.appendChild(colorContentElement)
          colorItemElement.addEventListener('click', this.handleClickColorItem.bind(this, color))
          colorItems.push(colorItemElement)
          form.appendChild(colorItemElement)
        })

        this.colorItems = colorItems

        const themeSelector = doc.createElement('div')
        themeSelector.className = 'theme-selector'
        themeSelector.innerHTML =
          '<svg class="MuiSvgIcon-root jss79" width="30" height="30" fill="#555555" focusable="false" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"></path></svg>'
        themeSelector.addEventListener('click', this.openTheme.bind(this))

        form.appendChild(themeSelector)

        return form
      },
      handleClickColorItem: function (color, e) {
        e.preventDefault()
        e.stopPropagation()
        this.activeColor = color
        this.execAction('fontColor', { value: color })
      },
      openTheme: function () {
        this.base.trigger('openTheme')
      },
      hideForm: function () {
        this.getForm().style.display = 'none'
      },
      showForm: function () {
        this.base.saveSelection()
        this.getForm().style.display = 'flex'
        this.setToolbarPosition()
      },
      handleClick: function (e) {
        e.preventDefault()
        e.stopPropagation()
        this.showForm()
      }
    })

    MediumEditor.extensions.colorPicker = colorPicker
  }
}
