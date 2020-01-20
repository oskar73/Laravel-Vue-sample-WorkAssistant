<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 text-center">
        <div ref="svg" style="width: 500px; height: 500px">
          <svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient :id="'LinearGradient' + lid" :x1="angle.x1" :y1="angle.y1" :x2="angle.x2" :y2="angle.y2">
                <stop
                  v-for="(point, key1) in attrs.points"
                  :key="key1"
                  :offset="getPercent(point.left)"
                  :stop-color="getColor(point.red, point.green, point.blue)"
                  :stop-opacity="point.alpha"
                />
              </linearGradient>
              <radialGradient :id="'RadialGradient' + rid" ref="radial" cx="0.5" cy="0.5" r="1">
                <stop
                  v-for="(point, key2) in attrs.points"
                  :key="key2"
                  :offset="getPercent(point.left)"
                  :stop-color="getColor(point.red, point.green, point.blue)"
                  :stop-opacity="point.alpha"
                />
              </radialGradient>
            </defs>

            <rect v-if="attrs.type === 'linear'" x="0" y="0" width="100%" height="100%" :fill="'url(#LinearGradient' + lid + ')'" />
            <rect v-if="attrs.type === 'radial'" x="0" y="0" width="100%" height="100%" :fill="'url(#RadialGradient' + rid + ')'" />
          </svg>
        </div>
      </div>
      <div class="col-md-6">
        <color-picker
          :gradient="gradient"
          :is-gradient="true"
          :on-start-change="(color) => onChange(color, 'start')"
          :on-change="(color) => onChange(color, 'change')"
          :on-end-change="(color) => onChange(color, 'end')"
        />
        <div v-if="attrs.points">
          <table class="table table-centered table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Left</th>
                <th>Hex</th>
                <th>R</th>
                <th>G</th>
                <th>B</th>
                <th>Alpha</th>
              </tr>
            </thead>
            <tr v-for="(attr, i) in attrs.points" :key="i">
              <td>{{ i + 1 }}</td>
              <td><input v-model="attr.left" type="number" min="0" max="100" /></td>
              <td><input type="text" :value="getHexRow(attr)" @change="setHexRow(attr)" /></td>
              <td><input type="number" :value="attr.red" min="0" max="255" @change="setRGBRow(attr, 'red')" /></td>
              <td><input type="number" :value="attr.green" min="0" max="255" @change="setRGBRow(attr, 'green')" /></td>
              <td><input type="number" :value="attr.blue" min="0" max="255" @change="setRGBRow(attr, 'blue')" /></td>
              <td><input type="text" :value="getAlphaRow(attr.alpha)" @change="setAlphaRow(i)" /></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <input v-model="name" type="text" class="form-control m-input--square" placeholder="Name" />
          <div class="form-control-feedback error-name"></div>
        </div>
      </div>
      <div class="col-md-6">
        <a href="#" class="ml-auto btn m-btn--square btn-outline-primary mb-2" @click.prevent="gotoBack">Back</a>
        <a href="#" class="ml-auto btn m-btn--square btn-outline-success mb-2" :disabled="formBusy" @click.prevent="submit">Submit</a>
      </div>
    </div>
  </div>
</template>

<script>
import ColorPicker from 'color-gradient-picker-vue3'
export default {
  components: {
    ColorPicker
  },
  props: {
    category_id: Number,
    attrs_prop: '',
    name_prop: String,
    palette_id: Number
  },
  data() {
    return {
      lid: 0,
      rid: 0,
      formBusy: false,
      name: '',
      gradient: this.initGradient(),
      attrs: '',
      angle: {
        x1: 0,
        x2: 0,
        y1: 0,
        y2: 0
      }
    }
  },
  mounted() {
    this.lid = this.uuidv4()
    this.rid = this.uuidv4()
    this.name = this.name_prop
    if (this.attrs_prop) {
      this.attrs = this.attrs_prop
    }
    this.onChange(this.gradient)
  },
  methods: {
    initGradient() {
      if (this.attrs_prop) return this.attrs_prop
      return {
        type: 'linear',
        degree: 0,
        points: [
          {
            left: 0,
            red: 0,
            green: 0,
            blue: 0,
            alpha: 1
          },
          {
            left: 100,
            red: 255,
            green: 0,
            blue: 0,
            alpha: 1
          }
        ]
      }
    },
    onChange(attrs) {
      const anglePI = (attrs.degree + 90) * (Math.PI / 180)
      const percent = '%'
      this.attrs = attrs
      this.attrs.points.sort((a, b) => a.left - b.left)
      this.angle = {
        x1: Math.round(50 + Math.cos(anglePI) * 50) + percent,
        y1: Math.round(50 + Math.sin(anglePI) * 50) + percent,
        x2: Math.round(50 + Math.cos(anglePI + Math.PI) * 50) + percent,
        y2: Math.round(50 + Math.sin(anglePI + Math.PI) * 50) + percent
      }
    },
    getRotate(val) {
      const rotate = 'rotate('
      const last = ')'
      return rotate + val + last
    },
    getPercent(val) {
      const percent = '%'
      return val + percent
    },
    getHexNumber(val) {
      if (val) {
        const hex = val.toString(16)
        return hex.length == 1 ? '0' + hex : hex
      } else {
        return '00'
      }
    },
    getColor(red, green, blue) {
      const prefix = '#'
      return prefix + this.getHexNumber(red) + this.getHexNumber(green) + this.getHexNumber(blue)
    },
    removePointFun(index) {
      this.$delete(this.attrs.points, index)
    },
    getHexRow(attr) {
      return this.getHexNumber(attr.red) + this.getHexNumber(attr.green) + this.getHexNumber(attr.blue)
    },
    getAlphaRow(alpha) {
      return alpha * 100
    },
    setAlphaRow(index) {
      const value = parseInt(event.target.value)
      if (value >= 0 && value <= 100) {
        this.attrs.points[index].alpha = value / 100
      } else {
        event.target.value = this.attrs.points[index].alpha * 100
      }
    },
    setRGBRow(attr, field) {
      const value = parseInt(event.target.value)
      if (value >= 0 && value <= 255) {
        attr[field] = value
      } else {
        event.target.value = attr[field]
      }
    },
    setHexRow(attr) {
      const value = '#' + event.target.value
      const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(value)
      if (result) {
        attr.red = parseInt(result[1], 16)
        attr.green = parseInt(result[2], 16)
        attr.blue = parseInt(result[3], 16)
      } else {
        event.target.value = this.getHexRow(attr)
      }
    },
    gotoBack() {
      window.location.href = '/admin/logotypes/color/item/' + this.category_id
    },
    submit() {
      let url
      const cat_id = this.category_id
      const palette_id = this.palette_id
      const svg = this.$refs.svg.innerHTML

      if (this.palette_id === 0) {
        url = `/admin/logotypes/color/item/create/${cat_id}`
      } else {
        url = `/admin/logotypes/color/item/edit/${palette_id}`
      }
      axios
        .post(url, {
          name: this.name,
          svg: svg,
          attrs: JSON.stringify(this.attrs)
        })
        .then(function (response) {
          if (response.data.status === 1) {
            itoastr('success', 'success!')
            redirectAfterDelay('/admin/logotypes/color/item/' + cat_id)
          } else {
            dispErrors(response.data.data)
          }
        })
        .catch(function (error) {
          itoastr('error', error)
        })
    },
    setFormBusy(value = true) {
      this.formBusy = value
    },
    uuidv4() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const r = (Math.random() * 16) | 0
        const v = c == 'x' ? r : (r & 0x3) | 0x8
        return v.toString(16)
      })
    }
  }
}
</script>
<style lang="scss">
@import "~color-gradient-picker-vue3/dist/style.css";";
.gradient-degree-value p {
  margin-bottom: 0;
}
.table th,
.table td {
  vertical-align: middle !important;
  text-align: center;
}
.table td {
  padding: 5px;
  input {
    width: 100%;
    border: 0;
    outline: 0;
    text-align: center;
    &:focus {
      border: 1px solid #4d8ac9;
    }
  }
}
</style>
