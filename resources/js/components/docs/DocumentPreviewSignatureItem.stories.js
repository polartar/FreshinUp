import { storiesOf } from '@storybook/vue'
import DocumentPreviewSignatureItem from './DocumentPreviewSignatureItem.vue'

export const Default = () => ({
  components: { DocumentPreviewSignatureItem },
  data () {
    return {
      item: 'mock signature field'
    }
  },
  template: `
    <v-container>
      <document-preview-signature-item
        :item="item"
      />
    </v-container>
  `
})

// Components
storiesOf('FoodFleet|doc/DocumentPreviewSignatureItem', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
