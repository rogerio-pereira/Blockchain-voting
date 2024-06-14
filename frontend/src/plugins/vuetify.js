import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

export default createVuetify({
  theme: {
    defaultTheme: 'dark',
    themes: {
        dark: {
            colors: {
                primary: '#90A4AE',     //blue-grey-lighten-2
                secondary: '#546E7A',   //blue-grey-darken-1
                success: '#4DB6AC', //teal-lighten-2
                error: '#F48FB1', //pink-lighten-3 
                info: '#4DD0E1', //cyan-lighten-2
                warning: '#FFCC80', //orange-lighten-3
            },
        }
    }
  },
  components,
  directives,
})