require('dotenv').config()
const mix = require('laravel-mix')
const path = require('path')

const ASSET_URL = process.env.ASSET_URL + '/'
mix.webpackConfig(webpack => {
  return {
    resolve: {
      fallback: {
        fs: false,
        tls: false,
        net: false,
        path: false,
        zlib: false,
        http: false,
        https: false,
        stream: false,
        crypto: false
      },
      alias: {
        '@': path.resolve(__dirname, 'resources/js')
      }
    },
    stats: {
      children: true
    },
    plugins: [
      new webpack.DefinePlugin({
        'process.env.ASSET_PATH': JSON.stringify(ASSET_URL)
      })
    ],
    output: {
      publicPath: ASSET_URL
    }
  }
})

// Disable generating License file
mix.options({
  terser: {
    extractComments: false
  }
})

mix.js('resources/js/section-builder/build/website.js', 'public/assets/resources/webpack/website.js').vue()
mix.sass('resources/sass/website.scss', 'public/assets/resources/webpack/website.css')
