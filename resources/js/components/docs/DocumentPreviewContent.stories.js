import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import DocumentPreviewContent from './DocumentPreviewContent.vue'

const doc = {
  title: 'mock title',
  expiration_at: '2019-09-16 06:26:02',
  description: 'mock description',
  owner: {
    name: 'mock owner name'
  },
  attachment: 'https://downloadable.net/mock.zip'
}

export const Default = () => ({
  components: { DocumentPreviewContent },
  data () {
    return {
      doc: doc
    }
  },
  methods: {
    onClose () {
      action('onClose')()
    }
  },
  template: `
    <v-container>
      <document-preview-content
        :doc="doc"
        @close="onClose"
      />
    </v-container>
  `
})

// Components
storiesOf('FoodFleet|doc/DocumentPreviewContent', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
