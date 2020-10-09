import { storiesOf } from '@storybook/vue'
import DocumentPreviewSignature from './DocumentPreviewSignature.vue'

// Components
storiesOf('FoodFleet|doc/DocumentPreviewSignature', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { DocumentPreviewSignature },
    data () {
      return {
        signeeName: 'mock owner name'
      }
    },
    template: `
      <v-container>
        <DocumentPreviewSignature
          :signeeName="signeeName"
        />
      </v-container>
    `
  }))
