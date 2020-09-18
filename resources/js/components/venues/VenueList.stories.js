import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import VenueList from './VenueList'
import { FIXTURE_VENUE_STATUSES, FIXTURE_VENUES } from '../../../../tests/Javascript/__data__/venues'

export const Empty = () => ({
  components: { VenueList },
  data () {
    return {
      items: []
    }
  },
  template: `
      <venue-list
        :items="items"
      />
    `
})

export const IsLoading = () => ({
  components: { VenueList },
  template: `
      <venue-list
        is-loading
      />
    `
})

export const Populated = () => ({
  components: { VenueList },
  data () {
    return {
      items: FIXTURE_VENUES,
      statuses: FIXTURE_VENUE_STATUSES
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
      <venue-list
        :items="items"
        :statuses="statuses"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/venues/VenueList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('IsLoading', IsLoading)
  .add('Populated', Populated)
