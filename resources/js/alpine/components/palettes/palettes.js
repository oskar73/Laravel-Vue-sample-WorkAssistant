import { createApp } from 'vue'
import axios from 'axios'
import { Sketch } from '@lk77/vue3-color'
import Pagination from '../pagination'
import { ImageSelector } from '../image-selector'
import ColorThief from 'colorthief'

const Palettes = {
  ...Pagination,
  ...ImageSelector,
  openModal: false,
  openOrderModal: false,
  loading: true,
  allPalettes: [],
  filteredPalettes: [],
  palettes: [],
  categories: [],
  activeCategory: null,
  selectedCategory: null,
  createBy: 'image', // image, hex, randomize,
  imageSrc: null,
  processing: false,
  imageColors: [],
  mode: 'light',
  editor: 0, // background, primary, secondary,
  file: null,
  openColorPicker: false,
  name: '',
  category: '',
  description: '',
  isDarkMode: false,
  submitting: false,
  deleting: false,
  isEditPalette: false,
  status: true,
  lockedColors: [],
  editId: null,
  type: 'all',
  async init() {
    this.getPalettes()
    this.$watch('createBy', (value) => {
      if (value === 'hex') {
        this.renderColorSketch()
      }
    })
    this.$watch('pagination', () => {
      this.updatePagination()
    })
  },
  onSelectType(type) {
    this.type = type
  },
  openSketch(editor) {
    this.editor = editor
    this.openColorPicker = true
  },
  getPalettes() {
    const self = this
    axios.get(window.getUrl).then((res) => {
      if (res.data.status) {
        self.allPalettes = res.data.data.palettes
        self.categories = res.data.data.categories
        if (self.categories.length > 0) {
          self.category = self.categories[0].id
        }
        self.loading = false
        self.filterPalettes()
      }
    })
  },
  handleCreate() {
    this.openModal = true
    this.isEditPalette = false
    this.dataKey = 'colors'
    this.name = ''
    this.imageSrc = null
    this.category = this.categories[0].id
    this.palette = Object.assign({}, this.palette)
    this.renderDarkModeSwitch()
  },
  openSortModal() {
    this.dataKey = 'allPalettes'
    this.openOrderModal = true
  },
  closeSortModal() {
    this.openOrderModal = false
  },
  renderDarkModeSwitch() {
    const self = this
    this.$nextTick(() => {
      window
        .$('#color-mode')
        .bootstrapToggle()
        .on('change', function (event) {
          self.isDarkMode = event.target.checked
        })
    })
  },
  renderColorSketch() {
    const self = this
    const activeColor = this.palette[this.getColorKeyByEditor()]
    const colorPicker = createApp(Sketch, {
      modelValue: activeColor,
      'onUpdate:modelValue': (value) => {
        self.palette[self.getColorKeyByEditor()] = value.hex
      }
    })
    colorPicker.mount('#color-picker')
  },
  async handleUploadImage(e) {
    const input = e.target
    if (!input.files?.length) {
      return false
    }
    this.file = input.files[0]
    const reader = new FileReader()
    const self = this
    reader.onloadend = async function () {
      self.imageSrc = reader.result
      self.renderImage()
      await self.getColorPaletteFromImage(self.imageSrc)
      self.imageColorRandomize()
    }
    reader.readAsDataURL(this.file)
  },
  async handleImagePick(image) {
    let imageUrl = image.url
    if (imageUrl.startsWith('http')) {
      imageUrl += '?1'
    }
    this.file = imageUrl
    this.imageSrc = imageUrl
    this.renderImage()
    await this.getColorPaletteFromImage(this.imageSrc)
    this.imageColorRandomize()
  },
  imageColorRandomize() {
    let index = 0
    for (const key in this.palette) {
      this.palette[key] = this.imageColors[index]
      index++
    }
  },
  async getColorPaletteFromImage(src) {
    this.processing = true
    try {
      const colorThief = new ColorThief()
      const img = new Image()
      img.crossOrigin = 'anonymous'
      img.onload = () => {
        const result = colorThief.getPalette(img, 8)
        this.imageColors = result.map(rgb => {
          function componentToHex(c) {
            const hex = c.toString(16)
            return hex.length === 1 ? '0' + hex : hex
          }

          return '#' + componentToHex(rgb[0]) + componentToHex(rgb[1]) + componentToHex(rgb[2])
        })
        this.processing = false
      }
      img.src = src
    } catch (error) {
      this.processing = false
      console.error('getColorPaletteFromImage Error: ', error)
    }
  },
  renderImage() {
    this.$nextTick(() => {
      try {
        this.loading = true
        const img = new Image()
        img.crossOrigin = 'Anonymous'
        const canvas = this.$refs.canvasRef
        const context = canvas.getContext('2d')

        this.canvas = canvas
        this.context = context
        const self = this
        img.onload = function () {
          canvas.width = this.width
          canvas.height = this.height

          const oldWidth = canvas.width
          context.drawImage(this, 0, 0)
          canvas.width = 370
          canvas.height = (canvas.height * canvas.width) / oldWidth

          context.drawImage(img, 0, 0, img.width, img.height, 0, 0, canvas.width, canvas.height)
          self.loading = false
        }
        img.onerror = function (error) {
          console.error('Palette Image rendering Error: ', error)
          console.error('Palette Image Url', this.imageSrc)
        }
        img.src = this.imageSrc
      } catch (error) {
        console.error("Build Palette Modal: couldn't render image", error)
      }
    })
  },
  handleColorItemClick(color) {
    this.palette[this.getColorKeyByEditor()] = color
  },
  isActiveColor(color) {
    return this.palette[this.getColorKeyByEditor()] === color
  },
  mouseDownHandler(e) {
    this.mouseDown = true
    this.performAction(e)
  },
  pickCanvasColor(e) {
    if (!this.mouseDown) return false
    this.performAction(e)
  },
  performAction(e) {
    const x = e.pageX - e.target.getBoundingClientRect().left
    const y = e.pageY - e.target.getBoundingClientRect().top
    const imageData = this.context.getImageData(x, y, 1, 1).data
    const r = imageData[0]
    const g = imageData[1]
    const b = imageData[2]
    const color = '#' + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1)
    this.handleColorItemClick(color)
  },
  handleSubmit() {
    if (!this.name) {
      window.itoastr('error', 'Palette name is required!')
      return
    }

    const formData = new FormData()
    formData.append('name', this.name)
    formData.append('category_id', this.category)
    formData.append('description', this.description)
    formData.append('data', JSON.stringify(this.palette))
    formData.append('mode', this.isDarkMode ? 'dark' : 'light')
    formData.append('status', this.status === true ? 1 : 0)
    if (this.createBy === 'image' && this.imageSrc) {
      formData.append('image', this.imageSrc)
    }
    const self = this
    self.submitting = true

    function storeNewPalette() {
      axios
        .post(window.storeUrl, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then((res) => {
          if (res.data.status) {
            window.itoastr('success', 'A new palette is added')
            const responseData = res.data.data.palette
            self.allPalettes.unshift({ ...responseData, data: responseData.data ?? responseData.colors })
            self.filterPalettes()
            self.openModal = false
            self.name = ''
          }
        })
        .finally(() => {
          self.submitting = false
        })
    }

    if (this.isEditPalette) {
      window.askToast.question(
        'Confirm',
        'Do you want to override this palette?',
        () => {
          if (!window.updateUrl) {
            throw 'updateUrl is undefined'
          }
          formData.append('id', self.editId)
          formData.append('_method', 'PUT')
          axios
            .post(window.updateUrl, formData, {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            })
            .then((res) => {
              if (res.data.status) {
                self.isEditPalette = false
                const index = self.allPalettes.findIndex((item) => item.id === this.editId)
                self.allPalettes[index] = res.data.data.palette
                self.editId = null
                self.filterPalettes()
                self.openModal = false
              }
            })
            .finally(() => {
              self.submitting = false
            })
        },
        () => {
          storeNewPalette()
        }
      )
    } else {
      storeNewPalette()
    }
  },
  updateOrder() {
    if (!window.sortUrl) {
      throw 'sortUrl is undefined'
    }
    const self = this
    const sorts = this.allPalettes.map((item) => item.id)
    axios.post(window.sortUrl, { sorts }).then((res) => {
      if (res.data.status) {
        window.itoastr('success', 'Sort is updated!')
        self.filterPalettes()
      }
    })
  },
  updatePagination() {
    this.pagination.total = Math.ceil(this.filteredPalettes.length / this.pagination.perPage)
    this.pagination.firstItem = (this.pagination.current - 1) * this.pagination.perPage
    this.pagination.lastItem = Math.min(this.pagination.current * this.pagination.perPage, this.filteredPalettes.length)
    this.palettes = this.filteredPalettes.slice(this.pagination.firstItem, this.pagination.lastItem)
  },
  filterPalettes() {
    this.filteredPalettes = this.allPalettes.map((item) => ({
      ...item,
      status: item.status === 1
    }))
    this.updatePagination()
  },
  handleRemove(id) {
    const self = this
    window.askToast.question('Confirm', 'Do you want to delete this palette?', () => {
      self.deleting = true
      axios.post(window.removeUrl, { id, _method: 'DELETE' }).then((res) => {
        if (res.data.status) {
          window.itoastr('success', 'One palette is removed!')
          const index = self.allPalettes.findIndex((item) => item.id === id)
          self.allPalettes.splice(index, 1)
          self.filterPalettes()
          self.deleting = false
        }
      })
    })
  },
  getRandomColor() {
    const letters = '0123456789ABCDEF'
    let color = '#'
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)]
    }
    return color
  },
  randomizePalette() {
    for (const key in this.palette) {
      if (!this.lockedColors.includes(this.getColorIndexFromKey(key))) {
        this.palette[key] = this.getRandomColor()
      }
    }
  },
  async handleEdit(palette) {
    this.dataKey = 'colors'
    this.openModal = true
    this.isEditPalette = true
    this.editId = palette.id
    this.palette = { ...palette.data }
    this.imageSrc = palette.image
    this.name = palette.name
    this.category = palette.category_id
    this.isDarkMode = palette.mode === 'dark'
    this.status = !!palette.status
    this.renderDarkModeSwitch()
    if (this.imageSrc) {
      await this.getColorPaletteFromImage(this.imageSrc)
      this.renderImage()
    } else {
      this.imageColors = []
    }
  },
  toggleLock(colorIndex) {
    const index = this.lockedColors.indexOf(colorIndex)
    if (index > -1) {
      this.lockedColors.splice(index, 1)
    } else {
      this.lockedColors.push(colorIndex)
    }
  }
}
export default Palettes
