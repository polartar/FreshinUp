import { storiesOf } from '@storybook/vue'

// Components
import FilterLabel from './FilterLabel.vue'
import { MAIN } from '../../../.storybook/categories'

export const Default = () => ({
  components: { FilterLabel },
  template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-label>
          label
        </filter-label>
      </v-container>
    `
})

export const Black = () => ({
  components: { FilterLabel },
  template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-label
          color="black"
        >
          black label
        </filter-label>
      </v-container>
    `
})

storiesOf(`${MAIN}|components/FilterLabel`, module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Black', Black)
