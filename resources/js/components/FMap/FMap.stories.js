import FMap from './FMap'
import { storiesOf } from '@storybook/vue'
import { select } from '@storybook/addon-knobs'
import FMapMarker from '~/components/FMapMarker'

const MAP_STYLES = [
  'mapbox://styles/mapbox/streets-v11',
  'mapbox://styles/mapbox/outdoors-v11',
  'mapbox://styles/mapbox/light-v10',
  'mapbox://styles/mapbox/dark-v10',
  'mapbox://styles/mapbox/satellite-v9',
  'mapbox://styles/mapbox/satellite-streets-v11'
]

export const Basic = () => ({
  components: {
    FMap
  },
  data () {
    return {
      // Most likely taken from env variables ie process.env.MAPBOX_ACCESS_TOKEN
      accessToken: process.env.MAPBOX_ACCESS_TOKEN
    }
  },
  template: `
    <v-container>
      <f-map
        :access-token="accessToken"
      />
    </v-container>
  `
})

export const OtherStyles = () => ({
  components: {
    FMap,
    FMapMarker
  },
  data () {
    return {
      accessToken: process.env.MAPBOX_ACCESS_TOKEN,
      mStyle: select('MapBox Style', MAP_STYLES, MAP_STYLES[0])
    }
  },
  template: `
    <v-container>
      <f-map
        :access-token="accessToken"
        :mStyle="mStyle"
      />
    </v-container>
  `
})

export const WithMarker = () => ({
  components: {
    FMap,
    FMapMarker
  },
  data () {
    return {
      accessToken: process.env.MAPBOX_ACCESS_TOKEN,
      center: [ -118.406829, 33.942912 ] // [longitude, latitude]
    }
  },
  template: `
    <v-container>
      <f-map
        :access-token="accessToken"
        :center="center"
      >
        <f-map-marker
          :coordinates="center"
          color="primary"
        />
      </f-map>
    </v-container>
  `
})

// TODO: add other styles

storiesOf('FoodFleet|components/FMap', module)
  .add('Basic', Basic)
  .add('OtherStyles', OtherStyles)
  .add('WithMarker', WithMarker)
