import { storiesOf } from '@storybook/vue'
import DocumentPreviewSignature from './DocumentPreviewSignature.vue'

export const Default = () => ({
  components: { DocumentPreviewSignature },
  data () {
    return {
      signeeName: 'mock owner name'
    }
  },
  template: `
    <v-container>
      <document-preview-signature
        :signeeName="signeeName"
      />
    </v-container>
  `
})

// Components
storiesOf('FoodFleet|doc/DocumentPreviewSignature', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
