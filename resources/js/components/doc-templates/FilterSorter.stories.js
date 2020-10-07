import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'

// Components
import FilterSorter from './FilterSorter.vue'
import { FIXTURE_DOCUMENT_TEMPLATE_STATUSES } from '../../../../tests/Javascript/__data__/documentTemplateStatuses'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'

export const Default = () => ({
  components: { FilterSorter },
  template: `
    <v-container>
      <filter-sorter
      />
    </v-container>
  `
})

export const Expanded = () => ({
  components: { FilterSorter },
  template: `
    <v-container>
      <filter-sorter
        expanded
      />
    </v-container>
  `
})

const mock = new MockAdapter(axios)
const users = FIXTURE_USERS
mock.onGet('users?filter[status]=1').reply(200, {
  data: users
})

export const Populated = () => ({
  components: { FilterSorter },
  data () {
    return {
      statuses: FIXTURE_DOCUMENT_TEMPLATE_STATUSES,
      filters: {
        status_id: [2],
        title: 'Some title',
        updated_by_uuid: users[0],
        updated_at: '2020-10-06'
      }
    }
  },
  methods: {
    filterItems (params) {
      action('runFilter')(params)
    }
  },
  template: `
    <v-container>
      <filter-sorter
        :value="filters"
        :statuses="statuses"
        @runFilter="filterItems"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/doc-templates/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Expanded', Expanded)
  .add('Populated', Populated)
