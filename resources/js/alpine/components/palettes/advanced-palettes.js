import Palettes from './palettes'
import DragDrop from '../drag-drop'

export default () => ({
  ...DragDrop,
  ...Palettes,
  dataKey: 'colors',
  palette: {
    buttonColor: '#bd3a27',
    boxColor: '#eef593',
    headingColor: '#fea0d2',
    socialIconColor: '#4452d2',
    secondaryColor: 'rgb(231, 85, 86)',
    backgroundColor: 'rgb(200, 200, 200)'
  },
  get colors() {
    return [this.palette.backgroundColor, this.palette.buttonColor, this.palette.socialIconColor, this.palette.headingColor, this.palette.boxColor, this.palette.secondaryColor]
  },
  set colors(value) {
    this.palette.backgroundColor = value[0]
    this.palette.buttonColor = value[1]
    this.palette.socialIconColor = value[2]
    this.palette.headingColor = value[3]
    this.palette.boxColor = value[4]
    this.palette.secondaryColor = value[5]
  },
  getColorKeyByEditor() {
    switch (this.editor) {
      case 0:
        return 'backgroundColor'
      case 1:
        return 'buttonColor'
      case 2:
        return 'socialIconColor'
      case 3:
        return 'headingColor'
      case 4:
        return 'boxColor'
      case 5:
        return 'secondaryColor'
      default:
        return 'backgroundColor'
    }
  },

  getColorIndexFromKey(key) {
    switch (key) {
      case 'backgroundColor':
        return 0
      case 'buttonColor':
        return 1
      case 'socialIconColor':
        return 2
      case 'headingColor':
        return 3
      case 'boxColor':
        return 4
      case 'secondaryColor':
        return 5
      default:
        return 0
    }
  },
  colorPickerStyle() {
    const marginLeft = this.editor * (100 / 6)
    return { marginLeft: `min(${marginLeft}%, calc(100% - 220px))` }
  }
})
