import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DuplicateDialog from './DuplicateDialog.vue'

export const Default = () => ({
  components: { DuplicateDialog },
  data () {
    return {
      duplicate: {
        basicInformation: true,
        venue: false,
        fleetMember: true,
        customer: true
      }
    }
  },
  template: `
    <v-container>
      <duplicate-dialog
        :duplicate="duplicate"
      />
    </v-container>
  `
})

export const WithData = () => ({
  components: { DuplicateDialog },
  data () {
    return {
      duplicate: {
        basicInformation: true,
        venue: true,
        fleetMember: false,
        customer: true
      }
    }
  },
  template: `
    <v-container>
      <duplicate-dialog
      :duplicate="duplicate"
      />
    </v-container>
  `
})

export const DuplicateDialogOn = () => ({
  components: { DuplicateDialog },
  data () {
    return {
      duplicate: {
        basicInformation: true,
        venue: false,
        fleetMember: true,
        customer: true
      }
    }
  },

  methods: {
    changeDuplicateDialogue (status) {
      action('manage-duplicate-dialog')(status)
    },
    onDuplicate () {
      action('Duplicate')(this.duplicate)
    }
  },
  template: `
    <duplicate-dialog
      :loading="loading"
      @Duplicate="onDuplicate"
      @manage-duplicate-dialog="changeDuplicateDialogue"
    />
  `
})

storiesOf('FoodFleet|components/event/DuplicateDialog', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { DuplicateDialog },
    data () {
      return {
        loading: true
      }
    },
    template: `
      <v-container>
        <duplicate-dialog
          :loading="loading"
        />
      </v-container>
    `
  }))
