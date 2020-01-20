export class App {
  constructor() {
    this.config = this.getConfig()
  }

  getConfig() {
    return {
      fill: {
        color: 'FFFFFF',
        opacity: 0
      },
      stroke: {
        color: '#434343',
        width: 1,
        opacity: 1
      },
      canvas: {
        show_outside: false
      },
      path: {
        images: window.Vapor.asset('assets/img/editor/images/')
      },
      text: {
        default: 'Text'
      },
      blur: 0,
      opacity: 0,
      font: 'Robotto',
      unit: 'px',
      snap: {
        grid: {
          step: 5,
          is_enable: false
        },
        object: {
          step: 2,
          is_enable: true
        }
      }
    }
  }
}

export const getConfig = () => new App().config
