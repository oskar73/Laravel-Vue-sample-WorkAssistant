module.exports = {
  init: function (MediumEditor) {
    'use strict'

    const AnchorForm = MediumEditor.extensions.form.extend({
      /* Anchor Form Options */

      /* customClassOption: [string]  (previously options.anchorButton + options.anchorButtonClass)
       * Custom class name the user can optionally have added to their created links (ie 'button').
       * If passed as a non-empty string, a checkbox will be displayed allowing the user to choose
       * whether to have the class added to the created link or not.
       */
      customClassOption: null,

      /* customClassOptionText: [string]
       * text to be shown in the checkbox when the __customClassOption__ is being used.
       */
      customClassOptionText: 'Button',

      /* linkValidation: [boolean]  (previously options.checkLinkFormat)
       * enables/disables check for common URL protocols on anchor links.
       */
      linkValidation: false,

      /* placeholderText: [string]  (previously options.anchorInputPlaceholder)
       * text to be shown as placeholder of the anchor input.
       */
      placeholderText: 'Paste or type a link',

      /* targetCheckbox: [boolean]  (previously options.anchorTarget)
       * enables/disables displaying a "Open in new window" checkbox, which when checked
       * changes the `target` attribute of the created link.
       */
      targetCheckbox: false,

      /* targetCheckboxText: [string]  (previously options.anchorInputCheckboxLabel)
       * text to be shown in the checkbox enabled via the __targetCheckbox__ option.
       */
      targetCheckboxText: 'Open in new window',

      // Options for the Button base class
      name: 'anchor',
      action: 'createLink',
      aria: 'link',
      tagNames: ['a'],
      contentDefault:
        '<b><i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#555555"> <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"></path> </svg> </i></b>',
      contentFA: '<i class="fa fa-link"></i>',

      init: function () {
        MediumEditor.extensions.form.prototype.init.apply(this, arguments)

        this.subscribe('editableKeydown', this.handleKeydown.bind(this))
      },

      // Called when the button the toolbar is clicked
      // Overrides ButtonExtension.handleClick
      handleClick: function (event) {
        event.preventDefault()
        event.stopPropagation()

        const range = MediumEditor.selection.getSelectionRange(this.document)

        if (
          range.startContainer.nodeName.toLowerCase() === 'a' ||
          range.endContainer.nodeName.toLowerCase() === 'a' ||
          MediumEditor.util.getClosestTag(MediumEditor.selection.getSelectedParentElement(range), 'a')
        ) {
          return this.execAction('unlink')
        }

        if (!this.isDisplayed()) {
          this.showForm()
        }

        return false
      },

      // Called when user hits the defined shortcut (CTRL / COMMAND + K)
      handleKeydown: function (event) {
        if (MediumEditor.util.isKey(event, MediumEditor.util.keyCode.K) && MediumEditor.util.isMetaCtrlKey(event) && !event.shiftKey) {
          this.handleClick(event)
        }
      },

      // Called by medium-editor to append form to the toolbar
      getForm: function () {
        if (!this.form) {
          this.form = this.createForm()
        }
        return this.form
      },

      getTemplate: function () {
        const template = ['<input type="text" class="medium-editor-toolbar-input" placeholder="', this.placeholderText, '">']

        template.push(
          '<a href="#" class="medium-editor-toolbar-save">',
          this.getEditorOption('buttonLabels') === 'fontawesome' ? '<i class="fa fa-check"></i>' : this.formSaveLabel,
          '</a>'
        )

        template.push(
          '<a href="#" class="medium-editor-toolbar-close">',
          this.getEditorOption('buttonLabels') === 'fontawesome' ? '<i class="fa fa-times"></i>' : this.formCloseLabel,
          '</a>'
        )

        // both of these options are slightly moot with the ability to
        // override the various form buildup/serialize functions.

        if (this.targetCheckbox) {
          // fixme: ideally, this targetCheckboxText would be a formLabel too,
          // figure out how to deprecate? also consider `fa-` icon default implcations.
          template.push(
            '<div class="medium-editor-toolbar-form-row">',
            '<input type="checkbox" class="medium-editor-toolbar-anchor-target" id="medium-editor-toolbar-anchor-target-field-' + this.getEditorId() + '">',
            '<label for="medium-editor-toolbar-anchor-target-field-' + this.getEditorId() + '">',
            this.targetCheckboxText,
            '</label>',
            '</div>'
          )
        }

        if (this.customClassOption) {
          // fixme: expose this `Button` text as a formLabel property, too
          // and provide similar access to a `fa-` icon default.
          template.push(
            '<div class="medium-editor-toolbar-form-row">',
            '<input type="checkbox" class="medium-editor-toolbar-anchor-button">',
            '<label>',
            this.customClassOptionText,
            '</label>',
            '</div>'
          )
        }

        return template.join('')
      },

      // Used by medium-editor when the default toolbar is to be displayed
      isDisplayed: function () {
        return MediumEditor.extensions.form.prototype.isDisplayed.apply(this)
      },

      hideForm: function () {
        MediumEditor.extensions.form.prototype.hideForm.apply(this)
        this.getInput().value = ''
      },

      // Show attach link form
      showForm: function () {
        // trigger custom event that registered by subscribe
        // "openAttachLinkForm" event was subscribed on the mounted function of components/sections/Editor
        this.base.trigger('openAttachLinkForm')

        // Toolbar has been registered by extension with key "toolbar";
        const toolBar = this.base.extensions.find((extension) => extension.toolbar)

        // Hide tool bar when opening Attach link form that is customized components/modals/AttachLinkModal
        toolBar.hideToolbar()
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

      getFormOpts: function () {
        // no notion of private functions? wanted `_getFormOpts`
        const targetCheckbox = this.getAnchorTargetCheckbox()
        const buttonCheckbox = this.getAnchorButtonCheckbox()
        const opts = {
          value: this.getInput().value.trim()
        }

        if (this.linkValidation) {
          opts.value = this.checkLinkFormat(opts.value)
        }

        opts.target = '_self'
        if (targetCheckbox && targetCheckbox.checked) {
          opts.target = '_blank'
        }

        if (buttonCheckbox && buttonCheckbox.checked) {
          opts.buttonClass = this.customClassOption
        }

        return opts
      },

      doFormSave: function () {
        const opts = this.getFormOpts()
        this.completeFormSave(opts)
      },

      completeFormSave: function (opts) {
        this.base.restoreSelection()
        this.execAction(this.action, opts)
        this.base.checkSelection()
      },

      ensureEncodedUri: function (str) {
        return str === decodeURI(str) ? encodeURI(str) : str
      },

      ensureEncodedUriComponent: function (str) {
        return str === decodeURIComponent(str) ? encodeURIComponent(str) : str
      },

      ensureEncodedParam: function (param) {
        const split = param.split('=')
        const key = split[0]
        const val = split[1]

        return key + (val === undefined ? '' : '=' + this.ensureEncodedUriComponent(val))
      },

      ensureEncodedQuery: function (queryString) {
        return queryString.split('&').map(this.ensureEncodedParam.bind(this)).join('&')
      },

      checkLinkFormat: function (value) {
        // Matches any alphabetical characters followed by ://
        // Matches protocol relative "//"
        // Matches common external protocols "mailto:" "tel:" "maps:"
        // Matches relative hash link, begins with "#"
        const urlSchemeRegex = /^([a-z]+:)?\/\/|^(mailto|tel|maps):|^\#/i
        const hasScheme = urlSchemeRegex.test(value)
        let scheme = ''
        // telRegex is a regex for checking if the string is a telephone number
        const telRegex = /^\+?\s?\(?(?:\d\s?\-?\)?){3,20}$/
        const urlParts = value.match(/^(.*?)(?:\?(.*?))?(?:#(.*))?$/)
        const path = urlParts[1]
        const query = urlParts[2]
        const fragment = urlParts[3]

        if (telRegex.test(value)) {
          return 'tel:' + value
        }

        if (!hasScheme) {
          const host = path.split('/')[0]
          // if the host part of the path looks like a hostname
          if (host.match(/.+(\.|:).+/) || host === 'localhost') {
            scheme = 'http://'
          }
        }

        return (
          scheme +
          // Ensure path is encoded
          this.ensureEncodedUri(path) +
          // Ensure query is encoded
          (query === undefined ? '' : '?' + this.ensureEncodedQuery(query)) +
          // Include fragment unencoded as encodeUriComponent is too
          // heavy handed for the many characters allowed in a fragment
          (fragment === undefined ? '' : '#' + fragment)
        )
      },

      doFormCancel: function () {
        this.base.restoreSelection()
        this.base.checkSelection()
      },

      // form creation and event handling
      attachFormEvents: function (form) {
        const close = form.querySelector('.medium-editor-toolbar-close')
        const save = form.querySelector('.medium-editor-toolbar-save')
        const input = form.querySelector('.medium-editor-toolbar-input')

        // Handle clicks on the form itself
        this.on(form, 'click', this.handleFormClick.bind(this))

        // Handle typing in the textbox
        this.on(input, 'keyup', this.handleTextboxKeyup.bind(this))

        // Handle close button clicks
        this.on(close, 'click', this.handleCloseClick.bind(this))

        // Handle save button clicks (capture)
        this.on(save, 'click', this.handleSaveClick.bind(this), true)
      },

      createForm: function () {
        const doc = this.document
        const form = doc.createElement('div')

        // Anchor Form (div)
        form.className = 'medium-editor-toolbar-form'
        form.id = 'medium-editor-toolbar-form-anchor-' + this.getEditorId()
        form.innerHTML = this.getTemplate()
        this.attachFormEvents(form)

        return form
      },

      getInput: function () {
        return this.getForm().querySelector('input.medium-editor-toolbar-input')
      },

      getAnchorTargetCheckbox: function () {
        return this.getForm().querySelector('.medium-editor-toolbar-anchor-target')
      },

      getAnchorButtonCheckbox: function () {
        return this.getForm().querySelector('.medium-editor-toolbar-anchor-button')
      },

      handleTextboxKeyup: function (event) {
        // For ENTER -> create the anchor
        if (event.keyCode === MediumEditor.util.keyCode.ENTER) {
          event.preventDefault()
          this.doFormSave()
          return
        }

        // For ESCAPE -> close the form
        if (event.keyCode === MediumEditor.util.keyCode.ESCAPE) {
          event.preventDefault()
          this.doFormCancel()
        }
      },

      handleFormClick: function (event) {
        // make sure not to hide form when clicking inside the form
        event.stopPropagation()
      },

      handleSaveClick: function (event) {
        // Clicking Save -> create the anchor
        event.preventDefault()
        this.doFormSave()
      },

      handleCloseClick: function (event) {
        // Click Close -> close the form
        event.preventDefault()
        this.doFormCancel()
      }
    })

    MediumEditor.extensions.anchor = AnchorForm
  }
}
