import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { resolve } from 'path'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  server: {
    hmr: {
      host: 'localhost'
    }
  },
  define: {
    'process.env': process.env
  },
  css: {
    modules: true
  },
  plugins: [
    laravel({
      buildDirectory: '/assets/resources/vite',
      input: [
        'resources/sass/builder.scss',
        'resources/sass/front.scss',
        'resources/sass/back.scss',

        // Section Builder
        'resources/sass/builder.scss',
        'resources/js/section-builder/build/builder.js',
        'resources/sass/website.scss',
        'resources/js/section-builder/build/website.js',
        'resources/js/component/image-selector.js',

        // SVG Editor
        'resources/js/svg-editor/sass/app.scss',
        'resources/js/svg-editor/sass/video.scss',
        'resources/js/svg-editor/sass/_editor.scss',
        'resources/js/svg-editor/admin-vue.js',
        'resources/js/svg-editor/editor.js',

        // LiveChat
        'resources/js/chat.js',
        'resources/sass/app.scss',
        'resources/sass/chatbox.scss',
        'resources/sass/livechat.scss',
        'resources/sass/component/front/chat.scss',

        // Alpine.js
        'resources/js/alpine/alpine.js'
      ],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  resolve: {
    alias: {
      '@': resolve('./resources/js/'),
      $fonts: resolve('./public/assets/css/fonts/'),
      $image: resolve('./public/assets/img/')
    }
  },
  build: {
    outDir: 'public/assets/resources/vite'
  }
})
