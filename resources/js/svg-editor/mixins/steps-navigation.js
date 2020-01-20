import _ from 'lodash'

export default {
  data() {
    return {
      login: this.route('login'),
      register: this.route('register'),
      steps: {}
    }
  },

  computed: {
    currentStep: function () {
      return _.find(this.steps, ['name', this.route().current()])
    }
  },

  mounted() {
    this.steps = {
      index: {
        name: 'index',
        route: this.route('home'),
        priority: 1
      },
      choose: {
        name: 'user.graphics.live',
        route: this.route('user.graphics.live'),
        priority: 2
      },
      edit: {
        name: 'graphics.edit',
        route: null,
        priority: 3
      },
      userEdit: {
        name: 'user.graphics.edit',
        route: null,
        priority: 3
      },
      buy: {
        name: 'logo.buy',
        route: null,
        priority: 4
      }
    }
  },

  methods: {
    isActive(step) {
      if (this.currentStep) {
        return step.name === this.currentStep.name || 'user.' + step.name === this.currentStep.name
      }
      return false
    },

    isSuccess(step) {
      if (this.currentStep) {
        return this.currentStep !== step && this.currentStep.priority > step.priority
      }
      return false
    }
  }
}
