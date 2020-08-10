import { INITIAL_VIEWPORTS } from '@storybook/addon-viewport'
import { addDecorator, addParameters, configure } from '@storybook/vue'
import storybookTheme from './theme'
import './installVuePlugins'
import '@mdi/font/css/materialdesignicons.css'

// Vuetify has a 12 point grid system. Built using flex-box, the grid is used to layout an application's content. It
// contains 5 types of media breakpoints that are used for targeting specific screen sizes and orientations.
const vuetifyViewports = {
  VuetifyLg: {
    name: 'Vuetify LG',
    styles: {
      height: '100%',
      width: '1903px'
    },
    type: 'desktop'
  },
  VuetifyXs: {
    name: 'Vuetify XS',
    styles: {
      height: '100%',
      width: '599px'
    },
    type: 'mobile'
  },
  VuetifySm: {
    name: 'Vuetify SM',
    styles: {
      height: '100%',
      width: '959px'
    },
    type: 'mobile'
  },
  VuetifyMd: {
    name: 'Vuetify MD',
    styles: {
      height: '100%',
      width: '1263px'
    },
    type: 'tablet'
  },
  VuetifyXl: {
    name: 'Vuetify XL',
    styles: {
      height: '100%',
      width: '4096px'
    },
    type: 'desktop'
  }
}

addParameters({
  viewport: {
    viewports: {
      ...INITIAL_VIEWPORTS,
      ...vuetifyViewports
    }
  },
  options: {
    hierarchyRootSeparator: /\|/,
    theme: storybookTheme,
    storySort: (a, b) => {
      if (a[1].id.indexOf('foodfleet') === 0) {
        return -1
      } else if (b[1].kind.indexOf('Foodfleet') === 0) {
        return 1
      }
      if (a[1].kind === b[1].kind) {
        return 0
      }
      if (b[1].kind.indexOf('/') !== -1 && a[1].kind.indexOf('/') === -1) {
        return 1
      }
      return a[1].id.localeCompare(b[1].id, undefined, { numeric: true })
    }
  }
})

// Ensures every story is wrapped in a v-app tag. To enable the Storybook background addon to control the background
// color we need to add a hack to VApp, as it sets and controls the background color. We set the background-color on
// VApp to transparent.
addDecorator(() => ({
  template: '<v-app style="background-color: transparent;"><story/></v-app>',
  style: '.theme--light.application { background-color: transparent; }'
}))

function loadStories () {
  require('../resources/js/components/_Intro.stories')
}

configure(loadStories, module)
