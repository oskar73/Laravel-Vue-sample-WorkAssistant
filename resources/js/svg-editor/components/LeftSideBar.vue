<script>
import ColorPicker from '@/svg-editor/components/elements/color-picker.vue'
import GradientPicker from 'color-gradient-picker-vue3'
import axios from 'axios'
import { NS } from '@/svg-editor/editor/namespaces'
import eventBus from '@/public/eventBus'
import appMixin from '../mixins/app-mixin'
import editorMixin from '../mixins/editor-mixin'
import ImageSelectModal from '../../section-builder/components/modals/ImageSelectModal.vue'
import MaskSelectModal from '../../section-builder/components/modals/MaskSelectModal.vue'
import AdminImageSelectModal from './modals/AdminImageSelectModal.vue'

export default {
  name: 'LeftSideBar',
  components: { AdminImageSelectModal, ColorPicker, GradientPicker, ImageSelectModal, MaskSelectModal },
  mixins: [appMixin, editorMixin],
  data() {
    return {
      backgroundSelected: false,
      loading_div: false,
      activeTab: null,
      tabs: {
        bgSolid: 'bgSolid',
        bgGradient: 'bgGradient',
        fillSolid: 'fillSolid',
        fillGradient: 'fillGradient',
        strokeSolid: 'strokeSolid',
        strokeGradient: 'strokeGradient'
      },
      tools: {
        activeTool: null,
        types: {
          select: 'select',
          text: 'text',
          rect: 'rect',
          ellipse: 'ellipse',
          line: 'line',
          eyedrop: 'eyedrop'
        }
      },

      fill_color_show: false,
      fill_gradient_show: false,
      fill_gradient_category: 1,
      fill_gradient_selected_cat_name: '',
      fill_gradient_preset_cats: [],
      fill_gradient_preset_items: [],

      stroke_color_show: false,
      stroke_solid_preset_cats: [],
      stroke_solid_preset_items: [],
      stroke_solid_category: 1,
      stroke_solid_selected_cat_name: 1,
      stroke_selected_palette_item_key: 0,
      stroke_selected_preset_item_key: 0,
      stroke_gradient_show: false,
      stroke_gradient_category: 1,
      stroke_gradient_selected_cat_name: '',
      stroke_gradient_preset_cats: [],
      stroke_gradient_preset_items: [],

      solid_preset_cats: [],
      solid_preset_items: [],
      solid_category: 1,
      solid_selected_cat_name: 1,
      selected_palette_item_key: 0,
      selected_preset_item_key: 0,
      gradient_category: 1,
      gradient_selected_cat_name: '',
      gradient_preset_cats: [],
      gradient_preset_items: [],

      background_gradient: {
        type: 'linear',
        degree: 0,
        points: []
      },
      fill_gradient: {
        type: 'linear',
        degree: 0,
        points: []
      },
      stroke_gradient: {
        type: 'linear',
        degree: 0,
        points: []
      },
      username: '',
      userGradient: [],
      userSolid: [],
      palettes: {},
      selectedPalette: null,

      isOpenImageSelector: false,
      openAdminImageSelector: false,
      isOpenMaskSelector: false,
      openAdminImageSelectorOption: null,
      confirmAction: null
    }
  },
  computed: {
    activeFaviconGroup() {
      return false
    }
  },
  mounted() {
    this.getPresetGradientCats()

    eventBus.$on('background.selected', () => {
      this.backgroundSelected = true
    })

    eventBus.$on('background.cleared', () => {
      this.backgroundSelected = false
    })

    eventBus.$on('tools.set', (data) => {
      // Set active tool
      this.tools.activeTool = data.tool

      // Get sidebar with tools
      const sideBar = this.$refs['left-sidebar']

      if (sideBar) {
        // Get tools
        const tools = sideBar.children

        // Set class for active tool
        Array.from(tools).forEach((item) => {
          const toolName = item.getAttribute('data-tool-type')

          if (toolName === data.tool) {
            item.classList.add('active-tool')
          } else {
            item.classList.remove('active-tool')
          }
        })
      }
    })
  },
  methods: {
    getPresetGradientCats() {
      return axios
        .get(this.route('graphics.getPresetCategory'))
        .then((response) => {
          if (response.data.status === 1) {
            this.gradient_preset_cats = response.data.gradient
            this.fill_gradient_preset_cats = response.data.gradient
            this.stroke_gradient_preset_cats = response.data.gradient
            this.solid_preset_cats = response.data.solid
            this.stroke_solid_preset_cats = response.data.solid

            this.username = response.data.username
            this.userGradient = response.data.userGradient
            this.userSolid = response.data.userSolid
            this.palettes = response.data.palettes
          }
        })
        .catch((e) => {
          console.log(e)
        })
    },
    toolLine() {
      this.setTool(this.tools.types.line)
    },
    toolIcons() {
      this.icons.states.isVisible = !this.icons.states.isVisible
    },
    toolRectangle() {
      this.setTool(this.tools.types.rect)
    },
    toolEllipse() {
      this.setTool(this.tools.types.ellipse)
    },
    toolText() {
      this.setTool(this.tools.types.text)
    },
    setTool(tool) {
      this.canvas.setMode(tool)
      eventBus.$emit('tools.set', {
        tool,
        eyedrop: this.eyedrop
      })
    },
    onBackgroundEyeDrop(isEyeDrop) {
      this.eyedrop = isEyeDrop ? 1 : 0
      if (isEyeDrop) {
        this.toolEyeDrop()
      } else {
        this.toolSelect()
      }
    },
    toolSelect() {
      this.setTool(this.tools.types.select)
    },
    toolEyeDrop() {
      this.setTool(this.tools.types.eyedrop)
    },
    onFillEyeDrop(isEyeDrop) {
      this.eyedrop = isEyeDrop ? 2 : 0
      if (isEyeDrop) {
        this.toolEyeDrop()
      } else {
        this.toolSelect()
      }
    },
    onStrokeEyeDrop(isEyeDrop) {
      this.eyedrop = isEyeDrop ? 3 : 0
      if (isEyeDrop) {
        this.toolEyeDrop()
      } else {
        this.toolSelect()
      }
    },
    handleChangeBackgroundColorSolid(color) {
      // this.attributes.background = color
      // this.background_type = 'solid'
      // this.canvas.setBackground(this.prepareColor(this.attributes.background))
    },
    handleChangeBackgroundColorGradient(attrs, name, preventUndo) {
      this.background_type = 'gradient'
      this.canvas.setBackgroundPaint(this.makeGradientPaint(attrs), preventUndo)
    },
    makeTransParent() {
      this.attributes.color.fill = '#00000000'
      this.onChangeFillColor()
    },
    onChangeBackground(color) {
      this.attributes.color.fill = color
      this.onChangeFillColor()
    },
    onChangeBackgroundGradient(key) {
      this.selected_preset_item_key = key
      const selected_item = this.gradient_preset_items[key]
      this.background_gradient = JSON.parse(selected_item.data)
      this.background_type = 'gradient'
      this.canvas.setBackgroundPaint(this.makeGradientPaint(this.background_gradient), false)
    },
    strokeFillPaletteColor(stroke_solid_item_color) {
      this.attributes.stroke.color = '#' + stroke_solid_item_color
      this.onChangeStrokeColor()
    },
    strokeGetPaletteUserItems(stroke_category_id, stroke_name, loading) {
      this.stroke_solid_category = 0
      this.stroke_solid_selected_cat_name = stroke_name
      this.stroke_solid_preset_items = []
      this.loading_div = loading
      this.stroke_solid_preset_items = this.userSolid
    },
    strokeGetPaletteItems(stroke_category_id, stroke_name, loading) {
      this.stroke_solid_category = 0
      this.stroke_solid_selected_cat_name = stroke_name
      this.stroke_solid_preset_items = []
      this.loading_div = loading

      return axios
        .get(this.this.route('graphics.getPresetItem', stroke_category_id))
        .then((response) => {
          if (response.data.status === 1) {
            this.loading_div = 0
            this.stroke_solid_preset_items = response.data.data
          }
        })
        .catch((e) => {
          console.log(e)
        })
    },
    strokeSwipeGradientPresetItem(stroke_key) {
      this.stroke_selected_preset_item_key = stroke_key
      const stroke_selected_item = this.stroke_gradient_preset_items[stroke_key]
      this.stroke_fill_gradient = JSON.parse(stroke_selected_item.data)
      this.onChangeStrokeGradientColor(this.stroke_fill_gradient, 'change', true)
      this.updatePanel()
    },
    fillGetPresetUserItems() {
      this.fill_gradient_category = 0
      this.fill_gradient_selected_cat_name = this.username
      this.fill_gradient_preset_items = this.userGradient
    },
    fillGetPresetItems(fill_category_id, fill_name, loading) {
      this.fill_gradient_category = 0
      this.fill_gradient_selected_cat_name = fill_name
      this.fill_gradient_preset_items = []
      this.loading_div = loading
      return axios
        .get(this.route('graphics.getPresetItem', fill_category_id))
        .then((response) => {
          if (response.data.status === 1) {
            this.loading_div = 0
            this.fill_gradient_preset_items = response.data.data
          }
        })
        .catch((e) => {
          console.log(e)
        })
    },
    strokeGetPresetUserItems() {
      this.stroke_gradient_category = 0
      this.stroke_gradient_selected_cat_name = this.username
      this.stroke_gradient_preset_items = this.userGradient
    },
    strokeGetPresetItems(stroke_category_id, stroke_name, loading) {
      this.stroke_gradient_category = 0
      this.stroke_gradient_selected_cat_name = stroke_name
      this.stroke_gradient_preset_items = []
      this.loading_div = loading
      return axios
        .get(this.route('graphics.getPresetItem', stroke_category_id))
        .then((response) => {
          if (response.data.status === 1) {
            this.loading_div = 0
            this.stroke_gradient_preset_items = response.data.data
          }
        })
        .catch((e) => {
          console.log(e)
        })
    },
    fillPaletteColor(solid_item_color) {
      this.attributes.color.fill = '#' + solid_item_color
      this.onChangeFillColor()
    },
    getPaletteUserItems() {
      this.solid_category = 0
      this.solid_selected_cat_name = this.username
      this.solid_preset_items = this.userSolid
    },
    getPaletteItems(category_id, name, loading) {
      this.solid_category = 0
      this.solid_selected_cat_name = name
      this.solid_preset_items = []
      this.loading_div = loading
      return axios
        .get(this.route('graphics.getPresetItem', category_id))
        .then((response) => {
          if (response.data.status === 1) {
            this.loading_div = 0
            this.solid_preset_items = response.data.data
          }
        })
        .catch((e) => {
          console.log(e)
        })
    },
    swipeGradientPresetItem(key) {
      this.selected_preset_item_key = key
      const selected_item = this.fill_gradient_preset_items[key]
      const fill_gradient = JSON.parse(selected_item.data)
      this.onChangeFillGradientColor(fill_gradient, 'change', true)
      this.updatePanel()
    },
    getPresetUserItems() {
      this.gradient_category = 0
      this.gradient_selected_cat_name = this.username
      this.gradient_preset_items = this.userGradient
    },
    getPresetItems(category_id, name, loading) {
      this.gradient_category = 0
      this.gradient_selected_cat_name = name
      this.gradient_preset_items = []
      this.loading_div = loading
      return axios
        .get(this.route('graphics.getPresetItem', category_id))
        .then((response) => {
          if (response.data.status === 1) {
            this.loading_div = 0
            this.gradient_preset_items = response.data.data
          }
        })
        .catch((e) => {
          console.log(e)
        })
    },
    makeGradientPaint(attrs) {
      attrs.points.sort((a, b) => a.left - b.left)
      const paint = {
        type: attrs.type + 'Gradient',
        linearGradient: null,
        radialGradient: null
      }

      const anglePI = (attrs.degree + 90) * (Math.PI / 180)
      const angle = {
        x1: Math.round(50 + Math.cos(anglePI) * 50) + '%',
        y1: Math.round(50 + Math.sin(anglePI) * 50) + '%',
        x2: Math.round(50 + Math.cos(anglePI + Math.PI) * 50) + '%',
        y2: Math.round(50 + Math.sin(anglePI + Math.PI) * 50) + '%'
      }

      let mainGradient

      if (attrs.type === 'linear') {
        paint.linearGradient = document.createElementNS(NS.SVG, 'linearGradient')
        paint.linearGradient.setAttribute('x1', angle.x1)
        paint.linearGradient.setAttribute('y1', angle.y1)
        paint.linearGradient.setAttribute('x2', angle.x2)
        paint.linearGradient.setAttribute('y2', angle.y2)

        mainGradient = paint.linearGradient
      } else {
        paint.radialGradient = document.createElementNS(NS.SVG, 'radialGradient')
        paint.radialGradient.setAttribute('cx', 0.5)
        paint.radialGradient.setAttribute('cy', 0.5)
        paint.radialGradient.setAttribute('r', 1)

        mainGradient = paint.radialGradient
      }

      for (let i = 0; i < attrs.points.length; i++) {
        const point = attrs.points[i]
        const stopEle = document.createElementNS(NS.SVG, 'stop')
        stopEle.setAttribute('offset', attrs.points[i].left + '%')
        stopEle.setAttribute('stop-color', '#' + this.getHexNumber(point.red) + this.getHexNumber(point.green) + this.getHexNumber(point.blue))
        stopEle.setAttribute('stop-opacity', point.alpha)

        mainGradient.appendChild(stopEle)
      }
      return paint
    },
    onChangeFillGradientColor(attrs, name, preventUndo) {
      const paint = this.makeGradientPaint(attrs)
      this.canvas.setFillPaint(paint, preventUndo)
    },
    onChangeStrokeGradientColor(attrs, name, preventUndo) {
      const paint = this.makeGradientPaint(attrs)
      this.canvas.setStrokePaint(paint, preventUndo)
    },
    openImageSelector() {
      this.confirmAction = null
      this.isOpenImageSelector = true
    },
    openMaskSelector() {
      this.isOpenMaskSelector = true
    },
    openIconImageSelector(option) {
      this.openAdminImageSelector = true
      this.openAdminImageSelectorOption = option
    },
    onChangeFillColor(val) {
      const color = this.prepareColor(val ?? this.attributes.color.fill)
      this.canvas.setColor('fill', color)
    },
    onChangeStrokeColor(val) {
      const color = this.prepareColor(val ?? this.attributes.stroke.color)
      this.canvas.setColor('stroke', color)
    },
    selectTab(tab) {
      if (this.activeTab) {
        if (this.activeTab === tab) {
          this.activeTab = null
        } else {
          this.activeTab = tab
        }
      } else {
        this.activeTab = tab
      }
    },
    loadNewImage(data) {
      window.svgEditor.canvas.insertNewImage(data.url)
      this.isOpenImageSelector = false
      this.openAdminImageSelector = false
    },
    addMask(mask) {
      if (mask) {
        fetch(
          this.route('media-content', {
            id: mask.id,
            type: 'image/svg+xml'
          })
        )
          .then((res) => res.text())
          .then(async (text) => {
            if (text) {
              this.canvas.addMaskToCanvas(text)
            } else {
              this.canvas.removeMask()
            }
          })
      } else {
        this.canvas.removeMask()
      }
      this.isOpenMaskSelector = false
    },
    handleColorPickerImageSelect(cb) {
      this.confirmAction = cb
      this.isOpenImageSelector = true
    }
  }
}
</script>

<template>
  <div id="left-sidebar" ref="left-sidebar">
    <admin-image-select-modal :show="openAdminImageSelector" :option="openAdminImageSelectorOption" @close="openAdminImageSelector = false" @confirm="loadNewImage" />
    <image-select-modal :open="isOpenImageSelector" :confirm-action="confirmAction" @confirm="loadNewImage" @close="isOpenImageSelector = false" />
    <mask-select-modal :open="isOpenMaskSelector" :graphic-masks="graphicMasks" :add-mask="addMask" :graphic="graphic" @close="isOpenMaskSelector = false" />

    <div class="d-flex flex-wrap w-100">
      <span class="tool" :data-tool-type="tools.types.select" @click="toolSelect">
        <svg width="12" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M16.637 10.1287L1.33576 0.812602L0 0L0.314857 1.53873L3.97183 19.383L4.30304 21L5.20809 19.6232L9.34893 13.3228L16.4639 11.3778L18 10.9578L16.637 10.1287V10.1287ZM1.96547 2.80018L14.5693 10.4732L8.74375 12.0655L8.49568 12.1341L8.35393 12.3483L4.97501 17.4902L1.96547 2.80018Z"
            fill="#3A58F9"
          />
        </svg>
        Select
      </span>
      <span class="tool" :data-tool-type="tools.types.text" @click="toolText">
        <svg width="8" height="15" viewBox="0 0 11 15" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M10 0V1H6V15H5V1H1V0H10Z" fill="#3A58F9" />
          <rect x="10" width="1" height="3" fill="#3A58F9" />
          <rect width="1" height="3" fill="#3A58F9" />
        </svg>
        Text
      </span>
      <span class="tool" :data-tool-type="tools.types.rect" @click="toolRectangle">
        <svg width="12" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="0.5" y="0.5" width="20" height="20" stroke="#3A58F9" />
        </svg>
        Rectangle
      </span>
      <span class="tool" :data-tool-type="tools.types.ellipse" @click="toolEllipse">
        <svg width="12" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="10.5" cy="10.5" r="10" stroke="#3A58F9" />
        </svg>
        Ellipse
      </span>
      <span class="tool" :data-tool-type="tools.types.line" @click="toolLine">
        <svg width="12" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1 20.5L20.5 1" stroke="#3A58F9" />
        </svg>
        Line
      </span>
      <span class="tool" @click="openImageSelector">
        <i class="mdi mdi-image-outline tw-text-lg tw-mr-2"></i>
        My image
      </span>
      <span class="tool" @click="openMaskSelector">
        <i class="mdi mdi-select-compare tw-text-lg tw-mr-2"></i>
        Mask
      </span>
      <span class="tool" @click="openIconImageSelector('icon')">
        <i class="mdi mdi-shape-outline tw-text-lg tw-mr-2"></i>
        Icon
      </span>
      <span class="tool" @click="openIconImageSelector('image')">
        <i class="mdi mdi-image-outline tw-text-lg tw-mr-2"></i>
        Images
      </span>
    </div>
    <h5 class="text-gray text-center my-3">
      <strong class="logo-color-change">Logo Color Change</strong>
    </h5>

    <div class="tw-p-2">
      <!-- background color solid -->
      <template v-if="backgroundSelected">
        <div>
          <!-- This slot will handle the title/header of the accordion and is the part you click on -->
          <a class="btn btn-primary w-100 mb-2" @click="selectTab(tabs.bgSolid)">Background Color Solid</a>
          <!-- This slot will handle all the content that is passed to the accordion -->
          <div v-if="activeTab === tabs.bgSolid" class="item">
            <label class="label_txt">background</label>
            <span class="color-picker solid">
              <color-picker v-model="attributes.color.fill" :color="attributes.color.fill" @update:modelValue="onChangeBackground" @eyeDrop="onBackgroundEyeDrop"></color-picker>
              <p class="mb-0 toggle_header" @click.prevent="stroke_color_show = !stroke_color_show"><b>Preset Palette Colors ⇣⇣⇣</b></p>
              <div v-if="stroke_solid_category && stroke_color_show" class="preset_category_area mt-2">
                <ul class="gradient_preset_area">
                  <li v-if="userSolid.length" class="folder_item" @click.prevent="strokeGetPaletteUserItems()">
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" />
                    {{ username }} ({{ userSolid.length }})
                  </li>
                  <li
                    v-for="(stroke_solid_cat, stroke_p_key) in stroke_solid_preset_cats"
                    :key="stroke_p_key"
                    class="folder_item"
                    @click.prevent="strokeGetPaletteItems(stroke_solid_cat.id, stroke_solid_cat.name, 3)"
                  >
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" /> {{ stroke_solid_cat.name }} ({{ stroke_solid_cat.palettes_count }})
                  </li>
                </ul>
              </div>

              <div v-if="!stroke_solid_category && stroke_color_show" class="preset_item_area">
                <p class="mb-0">
                  <a href="#" class="mb-0" @click.prevent="stroke_solid_category = 1"><b>Palettes</b></a> &#8250; <span v-text="stroke_solid_selected_cat_name"></span>
                </p>
                <img v-if="loading_div === 3" :src="asset('assets/img/loading_div.gif')" alt="" style="width: 50px" />
                <ul class="gradient_preset_area">
                  <li
                    v-for="(stroke_solid_item, stroke_pa_key) in stroke_solid_preset_items"
                    :key="stroke_pa_key"
                    class="d-block"
                    :class="stroke_selected_palette_item_key === stroke_pa_key ? 'active' : ''"
                    :title="stroke_solid_item.name"
                  >
                    <div v-if="stroke_solid_item.data">
                      <span
                        v-for="(stroke_solid_item_color, stroke_ps_key) in JSON.parse(stroke_solid_item.data)"
                        :key="stroke_ps_key"
                        :style="{ backgroundColor: '#' + stroke_solid_item_color }"
                        class="solid_item_span"
                        @click.prevent="onChangeBackground('#' + stroke_solid_item_color)"
                      ></span>
                    </div>
                  </li>
                </ul>
              </div>
            </span>
          </div>
        </div>
        <!--  background color gradient -->
        <div>
          <a class="btn btn-primary w-100 mb-2" @click="selectTab(tabs.bgGradient)">Background Color Gradient</a>
          <div v-if="activeTab === tabs.bgGradient" class="mb-2">
            <span class="color-picker">
              <gradient-picker
                name="background-gradient-picker"
                :color="background_gradient"
                :is-gradient="true"
                :on-start-change="(color) => onChangeFillGradientColor(color, 'start', false)"
                :on-change="(color) => onChangeFillGradientColor(color, 'change', true)"
                :on-end-change="(color) => onChangeFillGradientColor(color, 'end', true)"
              ></gradient-picker>
              <p class="mb-0 toggle_header" @click.prevent="fill_gradient_show = !fill_gradient_show"><b>Preset Gradients ⇣⇣⇣</b></p>
              <div v-if="gradient_category && fill_gradient_show" class="preset_category_area">
                <ul class="gradient_preset_area">
                  <li v-if="userGradient.length" class="folder_item" @click.prevent="getPresetUserItems()">
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" />
                    {{ username }} ({{ userGradient.length }})
                  </li>
                  <li v-for="(preset_cat, p_key) in gradient_preset_cats" :key="p_key" class="folder_item" @click.prevent="getPresetItems(preset_cat.id, preset_cat.name, 2)">
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" /> {{ preset_cat.name }} ({{ preset_cat.palettes_count }})
                  </li>
                </ul>
              </div>
              <div v-if="!gradient_category && fill_gradient_show" class="preset_item_area">
                <p class="mb-0">
                  <a href="#" class="mb-0" @click.prevent="gradient_category = 1"><b>Presets</b></a> &#8250; <span v-text="gradient_selected_cat_name"></span>
                </p>
                <img v-if="loading_div === 2" :src="asset('assets/img/loading_div.gif')" alt="" style="width: 50px" />
                <ul class="gradient_preset_area">
                  <a
                    v-for="(preset_item, pi_key) in gradient_preset_items"
                    :key="pi_key"
                    href="#"
                    class="gradient_preset_item"
                    :class="selected_preset_item_key === pi_key ? 'active' : ''"
                    :title="preset_item.name"
                    @click.prevent="onChangeBackgroundGradient(pi_key)"
                    v-html="preset_item.preview"
                  >
                  </a>
                </ul>
              </div>
            </span>
          </div>
        </div>

        <div class="w-100">
          <div class="btn btn-primary w-100" @click="makeTransParent">Make Transparent</div>
        </div>
      </template>

      <template v-if="isSelected">
        <!--  fill color solid -->
        <div>
          <a class="btn btn-primary w-100 mb-2" @click="selectTab(tabs.fillSolid)">Fill Color Solid</a>
          <div v-if="activeTab === tabs.fillSolid" class="item">
            <span class="color-picker solid">
              <color-picker
                v-model="attributes.color.fill"
                :color="attributes.color.fill"
                @openSelectImageModal="handleColorPickerImageSelect"
                @update:modelValue="onChangeFillColor"
                @eyeDrop="onFillEyeDrop"
              ></color-picker>
              <p class="mb-0 toggle_header" @click.prevent="fill_color_show = !fill_color_show"><b>Preset Palette Colors ⇣⇣⇣</b></p>
              <div v-if="solid_category && fill_color_show" class="preset_category_area mt-2">
                <ul class="gradient_preset_area">
                  <template v-if="selectedPalette == null">
                    <li class="folder_item" @click="selectedPalette = 'admin'">Admin Palettes ({{ palettes['admin']?.length }})</li>
                    <li class="folder_item" @click="selectedPalette = 'mine'">My Palettes ({{ palettes['mine'].length }})</li>
                  </template>
                  <div v-if="selectedPalette" class="mt-2">
                    <p class="mb-0">
                      <a href="#" class="mb-0" @click.prevent="selectedPalette = null"><b>Palettes</b></a> &#8250;
                      <span v-text="(selectedPalette == 'admin' ? 'Admin' : 'My') + ' Palettes'"></span>
                    </p>
                    <div v-for="(palette, index) of palettes[selectedPalette] ?? []" :key="index">
                      <div>{{ palette.name }}</div>
                      <div class="color-group">
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.backgroundColor }"
                          @click.prevent="palette.data.backgroundColor && onChangeFillColor(palette.data.backgroundColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.buttonColor }"
                          @click.prevent="palette.data.buttonColor && onChangeFillColor(palette.data.buttonColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.socialIconColor }"
                          @click.prevent="palette.data.socialIconColor && onChangeFillColor(palette.data.socialIconColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.headingColor }"
                          @click.prevent="palette.data.headingColor && onChangeFillColor(palette.data.headingColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.boxColor }"
                          @click.prevent="palette.data.boxColor && onChangeFillColor(palette.data.boxColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.secondaryColor }"
                          @click.prevent="palette.data.secondaryColor && onChangeFillColor(palette.data.secondaryColor)"
                        />
                      </div>
                    </div>
                  </div>
                </ul>
              </div>
            </span>
            <!--                                <div class="w-100 my-2">-->
            <!--                                    <a class="btn btn-outline-primary w-100 mb-2" @click.prevent="createYourOwnPalette">Create your own Palette</a>-->
            <!--                                </div>-->
          </div>
        </div>
        <!--                    fill color gradient -->
        <div>
          <a class="btn btn-primary w-100 mb-2" @click="selectTab(tabs.fillGradient)">Fill Color Gradient</a>
          <div v-if="activeTab === tabs.fillGradient" class="mb-2">
            <span class="color-picker">
              <gradient-picker
                name="fill-gradient-picker"
                :gradient="fill_gradient"
                :is-gradient="true"
                :on-start-change="(color) => onChangeFillGradientColor(color, 'start', false)"
                :on-change="(color) => onChangeFillGradientColor(color, 'change', true)"
                :on-end-change="(color) => onChangeFillGradientColor(color, 'end', true)"
              ></gradient-picker>
              <p class="mb-0 toggle_header" @click.prevent="fill_gradient_show = !fill_gradient_show"><b>Preset Gradients ⇣⇣⇣</b></p>
              <div v-if="fill_gradient_category && fill_gradient_show" class="preset_category_area">
                <ul class="gradient_preset_area">
                  <li v-if="userGradient.length" class="folder_item" @click.prevent="fillGetPresetUserItems()">
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" />
                    {{ username }} ({{ userGradient.length }})
                  </li>
                  <li
                    v-for="(preset_cat, p_key) in fill_gradient_preset_cats"
                    :key="p_key"
                    class="folder_item"
                    @click.prevent="fillGetPresetItems(preset_cat.id, preset_cat.name, 2)"
                  >
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" /> {{ preset_cat.name }} ({{ preset_cat.palettes_count }})
                  </li>
                </ul>
              </div>
              <div v-if="!fill_gradient_category && fill_gradient_show" class="preset_item_area">
                <p class="mb-0">
                  <a href="#" class="mb-0" @click.prevent="fill_gradient_category = 1"><b>Presets</b></a> &#8250; <span v-text="fill_gradient_selected_cat_name"></span>
                </p>
                <img v-if="loading_div === 2" :src="asset('assets/img/loading_div.gif')" alt="" style="width: 50px" />
                <ul class="gradient_preset_area">
                  <a
                    v-for="(preset_item, pi_key) in fill_gradient_preset_items"
                    :key="pi_key"
                    href="#"
                    class="gradient_preset_item"
                    :class="selected_preset_item_key === pi_key ? 'active' : ''"
                    :title="preset_item.name"
                    @click.prevent="swipeGradientPresetItem(pi_key)"
                    v-html="preset_item.preview"
                  >
                  </a>
                </ul>
              </div>
            </span>
          </div>
        </div>
        <!--                    stroke color solid -->
        <div>
          <a class="btn btn-primary w-100 mb-2" @click="selectTab(tabs.strokeSolid)">Stroke Color Solid</a>
          <div v-if="activeTab === tabs.strokeSolid" class="item">
            <label class="label_txt">stroke</label>
            <span class="color-picker solid">
              <color-picker
                v-model="attributes.stroke.color"
                :color="attributes.stroke.color"
                @openSelectImageModal="handleColorPickerImageSelect"
                @update:modelValue="onChangeStrokeColor"
                @eyeDrop="onStrokeEyeDrop"
              ></color-picker>
              <p class="mb-0 toggle_header" @click.prevent="stroke_color_show = !stroke_color_show"><b>Preset Palette Colors ⇣⇣⇣</b></p>
              <div v-if="stroke_solid_category && stroke_color_show" class="preset_category_area mt-2">
                <ul class="gradient_preset_area">
                  <template v-if="selectedPalette == null">
                    <li class="folder_item" @click="selectedPalette = 'admin'">Admin Palettes ({{ palettes['admin']?.length }})</li>
                    <li class="folder_item" @click="selectedPalette = 'mine'">My Palettes ({{ palettes['mine'].length }})</li>
                  </template>
                  <div v-if="selectedPalette" class="mt-2">
                    <p class="mb-0">
                      <a href="#" class="mb-0" @click.prevent="selectedPalette = null"><b>Palettes</b></a> &#8250;
                      <span v-text="(selectedPalette == 'admin' ? 'Admin' : 'My') + ' Palettes'"></span>
                    </p>
                    <div v-for="(palette, index) of palettes[selectedPalette] ?? []" :key="index">
                      <div>{{ palette.name }}</div>
                      <div class="color-group">
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.backgroundColor }"
                          @click.prevent="palette.data.backgroundColor && onChangeStrokeColor(palette.data.backgroundColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.buttonColor }"
                          @click.prevent="palette.data.buttonColor && onChangeStrokeColor(palette.data.buttonColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.socialIconColor }"
                          @click.prevent="palette.data.socialIconColor && onChangeStrokeColor(palette.data.socialIconColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.headingColor }"
                          @click.prevent="palette.data.headingColor && onChangeStrokeColor(palette.data.headingColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.boxColor }"
                          @click.prevent="palette.data.boxColor && onChangeStrokeColor(palette.data.boxColor)"
                        />
                        <div
                          class="color-item"
                          :style="{ backgroundColor: palette.data.secondaryColor }"
                          @click.prevent="palette.data.secondaryColor && onChangeStrokeColor(palette.data.secondaryColor)"
                        />
                      </div>
                    </div>
                  </div>
                </ul>
              </div>
            </span>
          </div>
        </div>

        <div>
          <a class="btn btn-primary w-100 mb-2" @click="selectTab(tabs.strokeGradient)">Stroke Color Gradient</a>
          <div v-if="activeTab === tabs.strokeGradient" class="mb-2">
            <span class="color-picker">
              <gradient-picker
                name="stroke-gradient-picker"
                :gradient="stroke_gradient"
                :is-gradient="true"
                :on-start-change="(color) => onChangeStrokeGradientColor(color, 'start', false)"
                :on-change="(color) => onChangeStrokeGradientColor(color, 'change', true)"
                :on-end-change="(color) => onChangeStrokeGradientColor(color, 'end', true)"
              ></gradient-picker>
              <p class="mb-0 toggle_header" @click.prevent="stroke_gradient_show = !stroke_gradient_show"><b>Preset Gradients ⇣⇣⇣</b></p>
              <div v-if="stroke_gradient_category && stroke_gradient_show" class="preset_category_area">
                <ul class="gradient_preset_area">
                  <li v-if="userGradient.length" class="folder_item" @click.prevent="strokeGetPresetUserItems()">
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" />
                    {{ username }} ({{ userGradient.length }})
                  </li>
                  <li
                    v-for="(stroke_preset_cat, stroke_p_key) in stroke_gradient_preset_cats"
                    :key="stroke_p_key"
                    class="folder_item"
                    @click.prevent="strokeGetPresetItems(stroke_preset_cat.id, stroke_preset_cat.name, 4)"
                  >
                    <img :src="asset('assets/img/folder.png')" alt="" class="folder_img" /> {{ stroke_preset_cat.name }} ({{ stroke_preset_cat.palettes_count }})
                  </li>
                </ul>
              </div>
              <div v-if="!stroke_gradient_category && stroke_gradient_show" class="preset_item_area">
                <p class="mb-0">
                  <a href="#" class="mb-0" @click.prevent="stroke_gradient_category = 1"><b>Presets</b></a> &#8250; <span v-text="stroke_gradient_selected_cat_name"></span>
                </p>
                <img v-if="loading_div === 4" :src="asset('assets/img/loading_div.gif')" alt="" style="width: 50px" />
                <ul class="gradient_preset_area">
                  <a
                    v-for="(stroke_preset_item, stroke_pi_key) in stroke_gradient_preset_items"
                    :key="stroke_pi_key"
                    href="#"
                    class="gradient_preset_item"
                    :class="stroke_selected_preset_item_key === stroke_pi_key ? 'active' : ''"
                    :title="stroke_preset_item.name"
                    @click.prevent="strokeSwipeGradientPresetItem(stroke_pi_key)"
                    v-html="stroke_preset_item.preview"
                  >
                  </a>
                </ul>
              </div>
            </span>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<style scoped lang="scss">
$active: rgb(0, 118, 223);
.color-group {
  margin: 4px 0;
  width: 100%;
  height: 20px;
  border: solid 1px #8080803f;
  overflow: hidden;
  display: flex;

  .color-item {
    flex: 1;
    height: 100%;
    cursor: pointer;
    margin-right: 2px;

    &:hover {
      border: solid 2px rgb(123, 236, 214);
    }
  }

  .color-item:last-child {
    margin-right: 0;
  }
}
</style>
