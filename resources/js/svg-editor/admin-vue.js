import { createApp } from 'vue'
import axios from 'axios'
import GradientPalette from './components/admin/gradient-palette.vue'

window.axios = axios

const app = createApp({})
app.component('GradientPalette', GradientPalette)
app.mount('#admin_vue')
