import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import BasicInfoForm from './BasicInfoForm.vue'
import { FIXTURE_DOCUMENT } from 'tests/__data__/documents'

const types = [
  { value: 1, text: 'From Template' },
  { value: 2, text: 'Downloadable' }
]

// Components
storiesOf('FoodFleet|doc/BasicInfoForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { BasicInfoForm },
    methods: {
      changeBasicInfo (params) {
        action('dataChange')(params)
      }
    },
    template: `
      <v-container>
        <BasicInfoForm
          @dataChange="changeBasicInfo"
        />
      </v-container>
    `
  }))
  .add('types is set', () => ({
    components: { BasicInfoForm },
    data () {
      return {
        types: types
      }
    },
    methods: {
      changeBasicInfo (params) {
        action('dataChange')(params)
      }
    },
    template: `
      <v-container>
        <BasicInfoForm
          :types="types"
          @dataChange="changeBasicInfo"
        />
      </v-container>
    `
  }))
  .add('initData is set', () => ({
    components: { BasicInfoForm },
    data () {
      return {
        types: types,
        doc: FIXTURE_DOCUMENT
      }
    },
    methods: {
      changeBasicInfo (params) {
        action('dataChange')(params)
      }
    },
    template: `
      <v-container>
        <BasicInfoForm
          :initData="doc"
          :types="types"
          @dataChange="changeBasicInfo"
        />
      </v-container>
    `
  }))
