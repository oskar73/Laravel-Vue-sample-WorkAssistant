const DragDrop = {
  dataKey: 'a',
  a: [1, 2, 3, 4, 5],
  dragging: null,
  dropping: null,
  timer: null,
  handleDrop() {
    if (this.dragging !== null && this.dropping !== null) {
      const data = this[this.dataKey]
      if (this.dragging < this.dropping) {
        this[this.dataKey] = [...data.slice(0, this.dragging), ...data.slice(this.dragging + 1, this.dropping + 1), data[this.dragging], ...data.slice(this.dropping + 1)]
      } else {
        this[this.dataKey] = [...data.slice(0, this.dropping), data[this.dragging], ...data.slice(this.dropping, this.dragging), ...data.slice(this.dragging + 1)]
      }
    }
    this.dropping = null
  },
  handleDragOver(e) {
    e.dataTransfer.dropEffect = 'move'
  },
  handleDragStart(index) {
    this.dragging = index
  },
  handleDragEnd() {
    this.dragging = null
  },
  handleDragEnter(index) {
    if (this.dragging !== index) {
      this.dropping = index
    }
  },
  handleDragLeave(index) {
    if (this.dropping === index) {
      this.dropping = null
    }
  }
}

export default DragDrop
