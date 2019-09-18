<template>
  <div>
    <v-flex
      d-flex
      align-center
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex text-xs-right>
        <v-btn
          slot="activator"
          color="primary"
          dark
          @click="docNew"
        >
          Add New Content
        </v-btn>
      </v-flex>
    </v-flex>
    <v-divider />

    <br>
    <doc-filter
      v-if="!isLoading"
      :statuses="statuses"
      :info-list="infoList"
      @runFilter="filterDocs"
    />
    <doctable-list
      v-if="!isLoading"
      :docs="docs"
      :statuses="statuses"
      @change-status="changeStatus"
      @manage-view="docView"
      @manage-edit="docEdit"
      @manage-delete="deleteDoc"
      @manage-multiple-delete="deleteDoc"
    />
    <v-dialog
      v-model="deleteDialog"
      max-width="500"
    >
      <simple-confirm
        :class="{ 'deleting': deletablesProcessing }"
        :title="deleteDialogTitle"
        ok-label="Yes"
        cancel-label="No"
        @ok="onSubmitDelete"
        @cancel="onCancelDelete"
      >
        <div class="py-5 px-2">
          <template>
            <p class="subheading">
              <span v-if="deletables.length < 2">Document</span>
              <span v-else> Documents</span>
              : {{ deleteTemp | formatDeleteTitles }}
            </p>
          </template>
        </div>
      </simple-confirm>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex'
import docFilter from '~/components/docs/FilterSorter.vue'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import DoctableList from '~/components/docs/DoctableList.vue'
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import get from 'lodash/get'

export default {
  layout: 'admin',
  components: {
    docFilter,
    DoctableList,
    simpleConfirm
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.title).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Document List',
      deleteDialog: false,
      docs: [
        { id: 1, status: 1, type: 1, title: 'James Lee ID', owner: '3 Brothers Kitchen', created_at: '2019-09-16 06:26:03', expiration_date: '2019-09-16 06:26:03' },
        { id: 2, status: 2, type: 2, title: 'James Lee ID', owner: '3 Brothers Kitchen', created_at: '2019-09-16 06:26:03', expiration_date: '2019-09-16 06:26:03' }
      ],
      infoList: [],
      statuses: [
        { value: 1, text: 'Pending' },
        { value: 2, text: 'Approved' },
        { value: 3, text: 'Rejected' },
        { value: 4, text: 'Expiring' },
        { value: 5, text: 'Expired' }
      ],
      lastFilterParams: {},
      deleteTemp: []
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.users.pending.items', true)
    },
    ...mapGetters('page', ['isLoading']),
    ...mapState('users', ['sortables']),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this document?' : 'Are you sure you want to delete the following documents?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    docNew () {
      this.$router.push({ path: '/admin/docs/new' })
    },
    docView (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.id })
    },
    docEdit (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.id })
    },
    deleteDoc (docs) {
      if (!Array.isArray(docs)) {
        docs = [docs]
      }
      this.deleteTemp = docs
      this.deleteDialog = true
    },
    onSubmitDelete () {
      // to do...
      this.deleteDialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },
    changeStatus (status, doc) {
      // to do...
    },
    filterDocs (params) {
      // to do...
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('page/setLoading', false)
  }
}
</script>
