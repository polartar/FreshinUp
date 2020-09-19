import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import DocumentList from '../venues/DocumentList'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/document'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'

export const Default = () => ({
  components: { DocumentList },
  data () {
    return {
      items: []
    }
  },
  template: `
      <document-list
        :items="items"
      />
    `
})

export const Populated = () => ({
  components: { DocumentList },
  data () {
    return {
      items: FIXTURE_DOCUMENTS,
      statuses: FIXTURE_DOCUMENT_STATUSES
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    }
  },
  template: `
      <document-list
        :items="items"
        :statuses="statuses"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/venues/DocumentList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
