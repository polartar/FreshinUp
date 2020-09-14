import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import StoreAreaList from './StoreAreaList'
import { FIXTURE_STORE_AREAS } from '../../../../tests/Javascript/__data__/storeAreas'

export const Empty = () => ({
  components: { StoreAreaList },
  data () {
    return {
      items: []
    }
  },
  template: `
      <store-area-list
        :items="items"
      />
    `
})

export const Loading = () => ({
  components: { StoreAreaList },
  template: `
      <store-area-list
        is-loading
      />
    `
})

export const Populated = () => ({
  components: { StoreAreaList },
  data () {
    return {
      items: FIXTURE_STORE_AREAS
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    }
  },
  template: `
      <store-area-list
        :items="items"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/fleet-members/StoreAreaList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
