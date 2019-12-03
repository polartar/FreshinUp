import { storiesOf } from '@storybook/vue'
import MessageList from './MessageList'

const messages = [
  {
    uuid: 1,
    owner: { uuid: 1, name: 'William D', avatar: 'https://via.placeholder.com/100x100.png' },
    content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum leo, sed fringilla ipsum. Aenean vitae urna a nisl vulputate venenatis nec ac tortor. Nulla quis ante ac mi auctor placerat.',
    created_at: '2019-09-24T06:33:05.000000Z'
  },
  {
    uuid: 2,
    owner: { uuid: 2, name: 'John Smith' },
    content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum leo, sed fringilla ipsum. Aenean vitae urna a nisl vulputate venenatis nec ac tortor. Nulla quis ante ac mi auctor placerat.',
    created_at: '2019-09-24T06:33:05.000000Z'
  }
]

storiesOf('FoodFleet|event/MessageList', module)
  .add('default', () => {
    return {
      components: { MessageList },
      template: `
          <v-container>
            <message-list />
          </v-container>
      `
    }
  })
  .add('with messages', () => {
    return {
      components: { MessageList },
      data () {
        return {
          messages: messages
        }
      },
      template: `
          <v-container>
            <message-list
              :messages="messages"
            />
          </v-container>
      `
    }
  })
