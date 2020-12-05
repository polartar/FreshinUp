import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import DocumentPreview from './DocumentPreview'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'
import { FIXTURE_DOCUMENT_TEMPLATES, FIXTURE_DOCUMENT_TEMPLATES_VARIABLES } from '../../../../tests/Javascript/__data__/documentTemplates'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'

const document = FIXTURE_DOCUMENTS[0]

export const Default = () => ({
  components: { DocumentPreview },
  template: `
    <v-container>
      <document-preview
      />
    </v-container>
  `
})

export const Populated = () => ({
  components: { DocumentPreview },
  data () {
    return {
      document: {
        ...document,
        event_store_uuid: FIXTURE_EVENTS[0].uuid,
        template_uuid: FIXTURE_DOCUMENT_TEMPLATES[0].uuid,
        signed_at: null
      },
      templates: FIXTURE_DOCUMENT_TEMPLATES,
      events: FIXTURE_EVENTS,
      variables: FIXTURE_DOCUMENT_TEMPLATES_VARIABLES,
      previewDialog: true
    }
  },
  methods: {
    onClose () {
      action('onClose')()
    },
    acceptContract () {
      action('acceptContract')()
    }
  },
  template: `
    <v-container>
      <document-preview
        :value="document"
        :templates="templates"
        :events="events"
        :variables="variables"
        :preview-dialog="previewDialog"
        @accept-contract="acceptContract"
        @close="onClose"
      />
    </v-container>
  `
})

export const Signed = () => ({
  components: { DocumentPreview },
  data () {
    return {
      document: { ...document, event_store_uuid: FIXTURE_EVENTS[0].uuid, signed_at: '2020-11-26T12:00:00' },
      templates: FIXTURE_DOCUMENT_TEMPLATES,
      events: FIXTURE_EVENTS,
      variables: FIXTURE_DOCUMENT_TEMPLATES_VARIABLES
    }
  },
  methods: {
    onClose () {
      action('onClose')()
    }
  },
  template: `
    <v-container>
      <document-preview
        :value="document"
        :templates="templates"
        :events="events"
        :variables="variables"
        @close="onClose"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/docs/DocumentPreview', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
  .add('Signed', Signed)
