import { cloneDeep } from 'lodash'
import eventBus from '@/public/eventBus'

let SAVE_BZ_TEMPLATE_HISTORY_SAVE_TIMER = null

export default {
  data() {
    return {
      historyProcessing: false,
      historyNextState: null,
      historyUndo: [],
      historyRedo: []
    }
  },
  computed: {
    canUndo() {
      return this.historyUndo.length > 0
    },
    canRedo() {
      return this.historyRedo.length > 0
    }
  },
  mounted() {
    this.historyInit()
  },
  methods: {
    historyInit() {
      this.historyProcessing = false
      this.historyUndo = []
      this.historyRedo = []
      this.historyNextState = this.historyNext()
      const events = [
        'section:update',
        'section:swap',
        'section:delete',
        'section:invisible',
        'section:update.settings',
        'text:update',
        'button:update',
        'button:added',
        'button:removed',
        'image:update'
      ]
      for (const event of events) {
        eventBus.$on(event, () => {
          this.historySaveAction(event)
        })
      }
    },
    historyNext() {
      return cloneDeep(this.template)
    },
    historySaveAction(action) {
      if (this.historyProcessing) return
      console.log(action)

      if (SAVE_BZ_TEMPLATE_HISTORY_SAVE_TIMER !== null) {
        window.clearTimeout(SAVE_BZ_TEMPLATE_HISTORY_SAVE_TIMER)
        SAVE_BZ_TEMPLATE_HISTORY_SAVE_TIMER = null
      }

      SAVE_BZ_TEMPLATE_HISTORY_SAVE_TIMER = window.setTimeout(() => {
        this.historyRedo = []
        const json = this.historyNextState
        this.historyUndo.push(json)
        this.historyNextState = this.historyNext()
        eventBus.$emit('history:append')
      }, 500)
    },
    loadHistory(history, event, callback) {
      this.$store.commit('updateTemplate', history)
      this.$nextTick(() => {
        eventBus.$emit(event)
        if (callback && typeof callback === 'function') callback()
        this.historyProcessing = false
      })
    },
    undo(callback) {
      this.historyProcessing = true
      const history = this.historyUndo.pop()
      if (history) {
        this.historyRedo.push(this.historyNext())
        this.historyNextState = history
        this.loadHistory(history, 'history:undo', callback)
      } else {
        this.historyProcessing = false
      }
    },
    redo(callback) {
      this.historyProcessing = true
      const history = this.historyRedo.pop()
      if (history) {
        // Every redo action is actually a new action to the undo history
        this.historyUndo.push(this.historyNext())
        this.historyNextState = history
        this.loadHistory(history, 'history:redo', callback)
      } else {
        this.historyProcessing = false
      }
    },
    clearHistory() {
      this.historyUndo = []
      this.historyRedo = []
      eventBus.$emit('history:clear')
    }
  }
}
