import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import CompanyOverview from './CompanyOverview.vue'

import { FIXTURE_USER_TYPES } from '../../../../tests/Javascript/__data__/userTypes'
import { FIXTURE_COMPANY } from '../../../../tests/Javascript/__data__/companies'

const methods = {
  onViewDetails (payload) {
    action('viewDetails')(payload)
  }
}

export const Empty = () => ({
  components: { CompanyOverview },
  template: `
      <v-container>
        <company-overview/>
      </v-container>
    `
})

export const Loading = () => ({
  components: { CompanyOverview },
  template: `
      <v-container>
        <company-overview
          is-loading
        />
      </v-container>
    `
})

export const Populated = () => ({
  components: { CompanyOverview },
  data () {
    return {
      company: { ...FIXTURE_COMPANY['data'] },
      companyTypes: FIXTURE_USER_TYPES
    }
  },
  methods,
  template: `
      <v-container>
        <company-overview
          :value="company"
          :types="companyTypes"
          @manage-view="onManageView"/>
      </v-container>
    `
})

storiesOf('FoodFleet|components/companies/CompanyOverview', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
