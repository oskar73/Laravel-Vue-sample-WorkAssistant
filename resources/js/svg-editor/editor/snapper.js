import eventBus from '@/public/eventBus'
import _ from 'lodash'

export class Snapper {
  constructor(config) {
    this.svgContent = config.svgContent
    this.currentZoom = config.currentZoom
  }

  initSnapElements() {
    const lines = this.createLines()
    const lineX = lines.lineX
    const lineY = lines.lineY

    eventBus.$on('snap.lines.show', (data) => {
      const action = data.action

      // Hide lines
      if (action === 'hide') {
        lineX.style.display = 'none'
        lineY.style.display = 'none'

        return
      }

      // Show lines
      if (action === 'show') {
        lineX.style.display = 'block'
        lineY.style.display = 'block'

        return
      }

      const lineBorder = 1
      const bbox = this.getOffset(data.visible)
      const targetBbox = this.getOffset(data.target)
      const x = bbox.left - lineBorder
      const y = bbox.top - lineBorder
      const targetX = targetBbox.left - lineBorder
      const targetY = targetBbox.top - lineBorder
      const width = bbox.width
      const height = bbox.height
      const targetWidth = targetBbox.width
      const targetHeight = targetBbox.height

      const gluingSide = data.gluingSide

      if (gluingSide === 'left') {
        lineX.attributes.x1.value = x
        lineX.attributes.x2.value = x

        const y1 = _.min([targetY, y])
        const y2 = _.max([y + height, targetY + targetHeight])

        lineX.attributes.y1.value = y1
        lineX.attributes.y2.value = y2

        // Show line
        this.showLine(lineX)
      } else if (gluingSide === 'right') {
        lineX.attributes.x1.value = x + width
        lineX.attributes.x2.value = x + width

        const y1 = _.min([targetY, y])
        const y2 = _.max([y + height, targetY + targetHeight])

        lineX.attributes.y1.value = y1
        lineX.attributes.y2.value = y2

        this.showLine(lineX)
      } else if (gluingSide === 'top') {
        lineY.attributes.y1.value = y
        lineY.attributes.y2.value = y

        const x1 = _.min([targetX, x])
        const x2 = _.max([x + width, targetX + targetWidth])

        lineY.attributes.x1.value = x1
        lineY.attributes.x2.value = x2

        this.showLine(lineY)
      } else if (gluingSide === 'bot') {
        lineY.attributes.y1.value = y + height
        lineY.attributes.y2.value = y + height

        const x1 = _.min([targetX, x])
        const x2 = _.max([x + width, targetX + targetWidth])

        lineY.attributes.x1.value = x1
        lineY.attributes.x2.value = x2

        this.showLine(lineY)
      } else if (gluingSide === 'center_x') {
        lineY.attributes.y1.value = y + height / 2
        lineY.attributes.y2.value = y + height / 2

        const x1 = _.min([targetX, x])
        const x2 = _.max([x + width, targetX + targetWidth])

        lineY.attributes.x1.value = x1 - Math.round(lineBorder / 2)
        lineY.attributes.x2.value = x2 - Math.round(lineBorder / 2)

        this.showLine(lineY)
      } else if (gluingSide === 'center_y') {
        lineX.attributes.x1.value = x + width / 2
        lineX.attributes.x2.value = x + width / 2

        const y1 = _.min([targetY, y])
        const y2 = _.max([y + height, targetY + targetHeight])

        lineX.attributes.y1.value = y1 - Math.round(lineBorder / 2)
        lineX.attributes.y2.value = y2 - Math.round(lineBorder / 2)

        this.showLine(lineX)
      }
    })
  }

  getOffset(element) {
    const zoom = this.currentZoom
    const bound = element.getBoundingClientRect()
    const html = document.getElementById('canvasBackground').getBoundingClientRect()

    return {
      top: bound.top / zoom - html.top / zoom,
      left: bound.left / zoom - html.left / zoom,
      width: bound.width / zoom,
      height: bound.height / zoom
    }
  }

  showLine(line) {
    // Show line
    line.style.display = 'block'
  }

  createLines() {
    let lineX = document.getElementById('snap-line-x')
    let lineY = document.getElementById('snap-line-y')
    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g')

    // Add identification class for snap lines group
    group.classList.add('snap-lines')

    if (!lineX) {
      const line = document.createElementNS('http://www.w3.org/2000/svg', 'line')
      line.setAttribute('id', 'snap-line-x')
      line.setAttribute('x1', 0)
      line.setAttribute('y1', 0)
      line.setAttribute('x2', 0)
      line.setAttribute('y2', '100%')
      line.setAttribute('opacity', 0.7)
      line.setAttribute('stroke', '#3A58F9')
      line.setAttribute('stroke-width', 1)

      lineX = line
    }

    if (!lineY) {
      const line = document.createElementNS('http://www.w3.org/2000/svg', 'line')
      line.setAttribute('id', 'snap-line-y')
      line.setAttribute('x1', 0)
      line.setAttribute('y1', 0)
      line.setAttribute('x2', '100%')
      line.setAttribute('y2', 0)
      line.setAttribute('stroke', '#3A58F9')
      line.setAttribute('opacity', 0.7)
      line.setAttribute('stroke-width', 1)

      lineY = line
    }

    group.append(lineX)
    group.append(lineY)

    $(this.svgContent).append(group)

    lineX.style.display = 'none'
    lineY.style.display = 'none'

    return { lineX, lineY }
  }
}

export const initSnapper = (svgContent, currentZoom) =>
  new Snapper({
    svgContent,
    currentZoom
  }).initSnapElements()
