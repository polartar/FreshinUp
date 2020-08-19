<template>
  <div>
    <v-layout
      row
      wrap
      pt-3
      pl-1
    >
      <a
        class="white--text font-weight-bold"
        @click.prevent="backPage"
      >
        Back to financial report
      </a>
    </v-layout>
    <v-layout
      row
      wrap
      pl-1
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
    </v-layout>
    <v-divider />

    <br>
    <v-card
      class="pa-4"
    >
      <v-layout
        v-if="!isLoading"
        row
      >
        <v-flex
          sm4
        >
          <h3>{{ transaction.event.name }} - {{ transaction.event.location.name }}</h3>
        </v-flex>
        <v-flex
          sm4
          class="text-sm-center"
        >
          {{ formatDate(transaction.square_created_at, 'MMM DD, YYYY - hh:mma') }}
        </v-flex>
        <v-flex
          sm4
          class="text-xs-right"
        >
          Transaction ID: {{ transaction.square_id }}
        </v-flex>
      </v-layout>
    </v-card>

    <v-layout
      row
      px-1
      py-4
    >
      <v-flex>
        <item-list
          v-if="!isLoading"
          :items="transaction.items"
        />
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import ItemList from '~/components/financials/ItemList.vue'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

const include = [
  'items.category',
  'store',
  'event.location'
]

export default {
  layout: 'admin',
  components: {
    ItemList
  },
  mixins: [FormatDate],
  data () {
    return {
      pageTitle: 'Transaction Details'
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('transactions', { 'transaction': 'item' })
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    backPage () {
      this.$router.go(-1)
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    const uuid = to.params.id
    Promise.all([
      vm.$store.dispatch('transactions/getItem', { params: { id: uuid, include: include } })
    ])
      .then(() => {
        if (next) next()
      })
      .catch(error => console.error(error))
      .then(() => vm.$store.dispatch('page/setLoading', false))
  }
}
</script>

<style scoped>
  /deep/ .right-dialog  {
    bottom: 0;
    right: 0;
    position: absolute;
    margin-right: 0px;
    margin-bottom: 0px;
  }
</style>
