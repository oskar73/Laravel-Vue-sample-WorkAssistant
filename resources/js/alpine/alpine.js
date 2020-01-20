import Alpine from 'alpinejs'
import axios from 'axios'

import SimplePalettes from './components/palettes/simple-palettes'
import AdvancedPalettes from './components/palettes/advanced-palettes'

window.axios = axios
window.Alpine = Alpine

Alpine.data('simplePalettesData', SimplePalettes)
Alpine.data('advancedPalettesData', AdvancedPalettes)

Alpine.start()
