import { storiesOf } from '@storybook/vue'
import DocumentPreviewSignatureItem from './DocumentPreviewSignatureItem.vue'

// Components
storiesOf('FoodFleet|doc/DocumentPreviewSignatureItem', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { DocumentPreviewSignatureItem },
    data () {
      return {
        item: 'mock signature field'
      }
    },
    template: `
      <v-container>
        <DocumentPreviewSignatureItem
          :item="item"
        />
      </v-container>
    `
  }))
