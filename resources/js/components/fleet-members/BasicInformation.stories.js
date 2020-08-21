import { storiesOf } from '@storybook/vue'

import BasicInformation from './BasicInformation'

const junkMember = {
  uuid: 'abc123',
  name: 'Da Lobster',
  type_id: 2,
  tags: ['ASIAN', 'VEGAN'],
  pos_system: 'Square',
  business_name: '',
  size_of_truck_trailer: '',
  owner: 'Josh Smith @ Restaurant Inc',
  phone: '938 374822',
  state_of_incorporation: 'California',
  website: 'www.restaurantinc.com',
  twitter: 'www.twitter.com/restaurantinc',
  facebook: 'www.facebook.com/restaurantinc',
  instagram: 'www.instagram.com/restaurantinc',
  staff_notes: 'Only visible for Food Fleet Staff'
}

const junkLocations = ['Square']

const junkTypes = [
  {
    id: 2,
    name: 'Mobile'
  }
]

storiesOf('FoodFleet|fleet-member/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('empty', () => ({
    components: { BasicInformation },
    template: `
      <v-container>
        <basic-information />
      </v-container>
    `
  }))
  .add('with data', () => ({
    components: { BasicInformation },
    data () {
      return {
        member: junkMember,
        types: junkTypes,
        locations: junkLocations
      }
    },
    template: `
      <v-container>
        <basic-information :member="member" :locations="locations" :types="types"/>
      </v-container>
    `
  }))
