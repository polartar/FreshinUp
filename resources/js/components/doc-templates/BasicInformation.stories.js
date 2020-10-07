import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import BasicInformation from './BasicInformation'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'
import { FIXTURE_DOCUMENT_TEMPLATE_STATUSES } from '../../../../tests/Javascript/__data__/documentTemplateStatuses'

export const Default = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <basic-information/>
    </v-container>
  `
})

export const Loading = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <basic-information is-loading/>
    </v-container>
  `
})

export const Populated = () => ({
  components: { BasicInformation },
  data () {
    return {
      template: FIXTURE_DOCUMENT_TEMPLATES[0],
      statuses: FIXTURE_DOCUMENT_TEMPLATE_STATUSES
    }
  },
  methods: {
    onSave (payload) {
      action('save')(payload)
    },
    onCancel (payload) {
      action('cancel')(payload)
    },
    onDelete (payload) {
      action('delete')(payload)
    }
  },
  template: `
    <v-container>
      <basic-information
        :value="template"
        :statuses="statuses"
        @input="onSave"
        @cancel="onCancel"
        @delete="onDelete"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/doc-templates/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('Populated', Populated)
