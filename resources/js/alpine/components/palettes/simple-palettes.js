import Palettes from './palettes'
import DragDrop from '../drag-drop'

export default () => ({
  ...DragDrop,
  ...Palettes,
  dataKey: 'colors',
  palette: {
    backgroundColor: 'rgb(238,238,238)',
    primaryColor: 'rgb(69, 94, 171)',
    secondaryColor: 'rgb(231, 85, 86)'
  },
  get colors() {
    return [this.palette.backgroundColor, this.palette.primaryColor, this.palette.secondaryColor]
  },
  set colors(value) {
    this.palette.backgroundColor = value[0]
    this.palette.primaryColor = value[1]
    this.palette.secondaryColor = value[2]
  },
  getColorKeyByEditor() {
    switch (this.editor) {
      case 0:
        return 'backgroundColor'
      case 1:
        return 'primaryColor'
      case 2:
        return 'secondaryColor'
      default:
        return 'backgroundColor'
    }
  },
  getColorIndexFromKey(key) {
    switch (key) {
      case 'backgroundColor':
        return 0
      case 'primaryColor':
        return 1
      case 'secondaryColor':
        return 2
      default:
        return 0
    }
  },
  colorPickerStyle() {
    const marginLeft = this.editor * (100 / 3)
    return { marginLeft: `min(${marginLeft}%, calc(100% - 220px))` }
  }
})
