import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import BasicInformation from './BasicInformation.vue'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'

const types = [
  { value: 1, text: 'From Template' },
  { value: 2, text: 'Downloadable' }
]

export const Default = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <BasicInformation
      />
    </v-container>
  `
})

export const Loading = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <BasicInformation
        is-loading
      />
    </v-container>
  `
})

export const Populated = () => ({
  components: { BasicInformation },
  data () {
    return {
      types: types,
      templates: FIXTURE_DOCUMENT_TEMPLATES,
      document: FIXTURE_DOCUMENTS[0]
    }
  },
  methods: {
    onInput (payload) {
      action('onInput')(payload)
    },
    onCancel (payload) {
      action('onCancel')(payload)
    },
    onPreview () {
      action('onPreview')()
    }
  },
  template: `
    <v-container>
      <BasicInformation
        :value="document"
        :types="types"
        :templates="templates"
        @value="onInput"
        @preview="onPreview"
        @input="onInput"
        @cancel="onCancel"
      />
    </v-container>
  `
})

// Components
storiesOf('FoodFleet|components/docs/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('Populated', Populated)
