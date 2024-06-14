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
                primary: '#455A64',     //blue-grey-darken-2
                secondary: '#90A4AE',   //blue-grey-lighten-2
            },
        }
    }
  },
  components,
  directives,
})