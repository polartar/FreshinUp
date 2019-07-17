/* eslint-disable import/no-extraneous-dependencies */
/**
 * @cite This configuration is heavily based on https://github.com/nidkil/vuetify-with-storybook/blob/master/config/storybook/config.js
 */
import { configureViewport, INITIAL_VIEWPORTS } from '@storybook/addon-viewport'
import { configure, addDecorator } from '@storybook/vue'
import { withNotes } from '@storybook/addon-notes'
import Vue from 'vue'
import Vuetify from 'vuetify'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VeeValidate from 'vee-validate'
// Load Styles
import '../vendor/freshinup/fresh-bus-forms/resources/assets/js/stylus/main.styl'

addDecorator(withNotes)
/**
 * Direct comments from (https://github.com/nidkil/vuetify-with-storybook/blob/master/config/storybook/config.js)
 * "Vuetify has a 12 point grid system. Built using flex-box, the grid is used to layout an application's content. It
 *    contains 5 types of media breakpoints that are used for targeting specific screen sizes and orientations. "
 */
const vuetifyViewports = {
  VuetifyLg: {
    name: 'Vuetify LG',
    styles: {
      width: '1904px'
    },
    type: 'desktop'
  },
  VuetifyXs: {
    name: 'Vuetify XS',
    styles: {
      width: '600px'
    },
    type: 'mobile'
  },
  VuetifySm: {
    name: 'Vuetify SM',
    styles: {
      width: '960px'
    },
    type: 'mobile'
  },
  VuetifyMd: {
    name: 'Vuetify MD',
    styles: {
      width: '1264px'
    },
    type: 'tablet'
  },
  VuetifyXl: {
    name: 'Vuetify XL',
    styles: {
      width: '4096px'
    },
    type: 'desktop'
  }
}

configureViewport({
  defaultViewport: 'VuetifyMd',
  viewports: {
    ...vuetifyViewports,
    ...INITIAL_VIEWPORTS
  }
})

Vue.use(Vuetify, {
  options: {
    customProperties: true
  },
  iconfont: 'fa',
  theme: {
    error: '#f42235',
    csmCorporate: '#b61524',
    tertiary: '#232c37',
    secondary: '#1a2029',
    primary: '#15b6a9',
    veryLightPink: '#e9e9e9',
    gray2: '#595959',
    orangeyYellow: '#f5a623',
    primaryHover: '#5ca8a0',
    info: '#2196f3',
    gray: '#4a4a4a',
    warning: '#f5ca23',
    success: '#33de95',
    secondaryHover: '#3d3f46'
  }
})

Vue.use(VueAxios, axios)
Vue.use(VeeValidate)

// Ensures every story is wrapped in a v-app tag. To enable the Storybook background addon to control the background
// color we need to add a hack to VApp, as it sets and controls the background color. We set the background-color on
// VApp to transparent.
addDecorator(() => ({
  template: `
    <v-app style="background-color: transparent;"  id="inspire">
        <v-content>
            <story/>
        </v-content>
    </v-app>
  `,
  style: '.theme--light.application { background-color: transparent; }'
}))

const req = require.context('../resources/js/components', true, /.stories.js$/)

function loadStories () {
  req.keys().forEach(filename => req(filename))
}

configure(loadStories, module)
