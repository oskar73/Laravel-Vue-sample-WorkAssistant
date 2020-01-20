// import 'bootstrap'
import Popper from 'popper.js'
import Vapor from 'laravel-vapor'
import jQuery from 'jquery'
import axios from 'axios'

window.Popper = Popper
window.$ = window.jQuery = jQuery
window.Vapor = Vapor
window.Vapor.withBaseAssetUrl(import.meta.env.VITE_VAPOR_ASSET_URL)

window.axios = axios
window.axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
}
