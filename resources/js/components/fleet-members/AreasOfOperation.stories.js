import AreasOfOperation from './AreasOfOperation'
import { storiesOf } from '@storybook/vue'
import { FIXTURE_STORE_AREAS } from '../../../../tests/Javascript/__data__/storeAreas'
import { action } from '@storybook/addon-actions'

export const Default = () => ({
  components: { AreasOfOperation },
  template: `
      <v-container>
        <AreasOfOperation />
      </v-container>
    `
})

export const IsLoading = () => ({
  components: { AreasOfOperation },
  template: `
      <v-container>
        <AreasOfOperation is-loading />
      </v-container>
    `
})

export const Populated = () => ({
  components: { AreasOfOperation },
  data () {
    return {
      areas: FIXTURE_STORE_AREAS
    }
  },
  methods: {
    onManage (act, item) {
      action('manage')(act, item)
    },
    onManageMultiple (act, items) {
      action('manage-multiple')(act, items)
    }
  },
  template: `
      <v-container>
        <AreasOfOperation
          :items="areas"
          @manage="onManage"
          @manage-multiple="onManageMultiple"
        />
      </v-container>
    `
})

storiesOf('FoodFleet|components/fleet-members/AreasOfOperation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('IsLoading', IsLoading)
  .add('Populated', Populated)
