import { storiesOf } from '@storybook/vue'
import FMapMarker from './FMapMarker'

export const Default = () => ({
  components: { FMapMarker },
  template: `
    <f-map>
      <f-map-marker/>
    </f-map>
  `
})

export const Populated = () => ({
  components: { FMapMarker },
  data () {
    return {
      coordinates: [ -118.406829, 33.942912 ] // [longitude, latitude]
    }
  },
  template: `
    <f-map>
      <f-map-marker
        :coordinates="coordinates"
        color="green"
      />
    </f-map>
  `
})

storiesOf('FoodFleet|Introduction', module)
  .add('Default', Default)
  .add('Populated', Populated)
