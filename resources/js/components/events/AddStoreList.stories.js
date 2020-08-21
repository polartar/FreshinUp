import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import AddStoreList from './AddStoreList.vue'

let stores = [
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

storiesOf('FoodFleet|components/event/AddStoreList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('member is empty', () => ({
    components: { AddStoreList },
    data () {
      return {
        stores: [],
        pagination: {
          page: 5,
          rowsPerPage: 10,
          totalItems: 5
        },
        sorting: {
          descending: false,
          sortBy: ''
        }
      }
    },
    template: `
      <add-store-list
        :stores="stores"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
      />
    `
  }))
  .add('member is set', () => ({
    components: { AddStoreList },
    data () {
      return {
        stores: stores,
        pagination: {
          page: 5,
          rowsPerPage: 10,
          totalItems: 5
        },
        sorting: {
          descending: false,
          sortBy: ''
        }
      }
    },
    methods: {
      assign (params) {
        params.assigned = true
        action('manage-assign')(params)
      },
      multipleAssign (params) {
        params.map(ele => {
          if (!ele.assigned) {
            ele.assigned = true
          }
        })
        action('manage-multiple-assign')(params)
      }
    },
    template: `
      <add-store-list
        :stores="stores"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @manage-assign="assign"
        @manage-multiple-assign="multipleAssign"
      />
    `
  }))
