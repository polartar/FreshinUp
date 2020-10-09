import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import DocumentPreview from './DocumentPreview.vue'

const doc = {
  title: 'mock title',
  expiration_at: '2019-09-16 06:26:02',
  description: 'mock description',
  owner: {
    name: 'mock owner name'
  },
  attachment: 'https://downloadable.net/mock.zip'
}

// Components
storiesOf('FoodFleet|doc/DocumentPreview', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { DocumentPreview },
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
        <DocumentPreview
          :doc="doc
          @close="onClose"
        />
      </v-container>
    `
  }))
