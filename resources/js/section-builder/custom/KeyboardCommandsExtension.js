module.exports = {
  init: function (MediumEditor) {
    'use strict'

    const KeyboardCommands = MediumEditor.Extension.extend({
      name: 'keyboard-commands',

      commands: [
        {
          command: 'bold',
          key: 'B',
          meta: true,
          shift: false,
          alt: false
        },
        {
          command: 'italic',
          key: 'I',
          meta: true,
          shift: false,
          alt: false
        },
        {
          command: 'underline',
          key: 'U',
          meta: true,
          shift: false,
          alt: false
        }
      ],

      init: function () {
        MediumEditor.Extension.prototype.init.apply(this, arguments)

        this.subscribe('editableKeydown', this.handleKeydown.bind(this))
        this.keys = {}
        this.commands.forEach(function (command) {
          const keyCode = command.key.charCodeAt(0)
          if (!this.keys[keyCode]) {
            this.keys[keyCode] = []
          }
          this.keys[keyCode].push(command)
        }, this)
      },

      handleKeydown: function (event) {
        const keyCode = MediumEditor.util.getKeyCode(event)
        if (!this.keys[keyCode]) {
          return
        }

        const isMeta = MediumEditor.util.isMetaCtrlKey(event)
        const isShift = !!event.shiftKey
        const isAlt = !!event.altKey

        this.keys[keyCode].forEach(function (data) {
          if (data.meta === isMeta && data.shift === isShift && (data.alt === isAlt || undefined === data.alt)) {
            // TODO deprecated: remove check for undefined === data.alt when jumping to 6.0.0
            event.preventDefault()
            event.stopPropagation()

            // command can be a function to execute
            if (typeof data.command === 'function') {
              data.command.apply(this)
            }
            // command can be false so the shortcut is just disabled
            else if (data.command !== false) {
              this.execAction(data.command)
            }
          }
        }, this)
      }
    })

    MediumEditor.extensions.keyboardCommands = KeyboardCommands
  }
}
