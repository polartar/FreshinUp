import { storiesOf } from '@storybook/vue'

import AddStore from './AddStore'

const stores = [
  {
    uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
    name: 'Burger Babes',
    type: {
      id: 1,
      name: 'Mobil'
    },
    location: {
      id: 1,
      uuid: 'c4fd0928-b7eb-43ee-871e-e63bfbd0ae7a',
      name: 'Port Gerald'
    },
    store_tags: [
      {
        uuid: '1',
        name: 'minus'
      },
      {
        uuid: '2',
        name: 'hic'
      }
    ],
    assigned: false
  },
  {
    uuid: 'c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5',
    name: 'Holy Canoli',
    type: {
      id: 1,
      name: 'Mobil'
    },
    location: {
      id: 2,
      uuid: '00aabd70-aa77-42de-87fd-96449bbd5439',
      name: 'Carminestad'
    },
    store_tags: [
      {
        uuid: '1',
        name: 'minus'
      },
      {
        uuid: '2',
        name: 'sit'
      },
      {
        uuid: '3',
        name: 'hicsdfsdf'
      }
    ],
    assigned: true
  },
  {
    uuid: '790aba97-1eb6-4630-82d9-7bd561256c67',
    name: 'Da Lobsta',
    type: {
      id: 1,
      name: 'Mobil'
    },
    location: {
      id: 2,
      uuid: 'dfb4bd02-c298-4dd4-ab49-2c3f156f2894',
      name: 'New Charlenebury'
    },
    store_tags: [
      {
        uuid: '1',
        name: 'minus'
      },
      {
        uuid: '2',
        name: 'hic'
      },
      {
        uuid: '3',
        name: 'acsfdd'
      },
      {
        uuid: '4',
        name: 'fsdf'
      }
    ],
    assigned: false
  }
]

storiesOf('FoodFleet|event/AddStore', module)
  .add('empty', () => ({
    components: { AddStore },
    template: `
      <v-container>
        <add-store />
      </v-container>
    `
  }))
  .add('with data', () => ({
    components: { AddStore },
    data () {
      return {
        stores: stores
      }
    },
    template: `
      <v-container>
        <add-store :members="stores"/>
      </v-container>
    `
  }))
