import { mapGetters, mapState } from 'vuex'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
export default {
  data () {
    return {
      docsDeleteDialog: false,
      docsLastFilterParams: {
        sort: '-created_at'
      },
      docsDeleteTemp: []
    }
  },
  computed: {
    ...mapGetters('documentStatuses', { docsStatuses: 'items' }),
    ...mapState('documents', { docsSortables: 'sortables' }),
    ...mapGetters('documents', {
      docs: 'items',
      docsPagination: 'pagination',
      docsSorting: 'sorting',
      docsSortBy: 'sortBy'
    }),

    docsActiveStoreUuid () {
      if (this.$route && this.$route.params) return this.$route.params.id
      return null
    }
  },
  mixins: [deletables],
  methods: {
    docsNew () {
      this.$router.push({ path: '/admin/docs/new' })
    },
    docsView (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.uuid })
    },
    docsEdit (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.uuid })
    },
    docsDelete (docs) {
      if (!Array.isArray(docs)) {
        docs = [docs]
      }
      this.docsDeleteTemp = docs
      this.docsDeleteDialog = true
    },
    async docsOnSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.docsDeleteTemp.forEach(doc => {
        dispatcheables.push(
          this.$store.dispatch('documents/deleteItem', {
            getItems: false,
            params: { id: doc.uuid }
          })
        )
      })

      let chunks = this.chunk(
        dispatcheables,
        this.docsDeleteTempParrallelRequest
      )
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.docsDeleteTempStatus =
          doneCount + ' / ' + this.docsDeleteTemp.length + ' Done'
        this.docsDeleteTempProgress =
          (doneCount / this.docsDeleteTemp.length) * 100
        await this.sleep(this.docsDeletablesSleepTime)
      }

      this.docsFilter(this.docsLastFilterParams)
      await this.sleep(500)
      this.docsDeletablesProcessing = false
      this.docsDeleteDialog = false
    },
    docsOnCancelDelete () {
      this.docsDeleteDialog = false
      this.docsDeleteTemp = []
    },

    docsOnPaginate (value) {
      this.$store.dispatch('documents/setPagination', value)
      this.$store.dispatch('documents/getItems', {
        params: {
          'filter[assigned_uuid]': this.docsActiveStoreUuid
        }
      })
    },
    docsChangeStatus (status, doc) {
      this.$store
        .dispatch('documents/patchItem', {
          data: { status },
          params: { id: doc.uuid }
        })
        .then(() => {
          this.docsFilter(this.docsLastFilterParams)
        })
    },
    docsChangeStatusMultiple (status, docs) {
      docs.forEach(doc => {
        this.docsChangeStatus(status, doc)
      })
    },

    docsFilter (params) {
      this.$store.dispatch('documents/setSort', params.sort)
      this.$store.dispatch('documents/setFilters', params)
      this.$store.dispatch('documents/getItems', {
        params: {
          'filter[assigned_uuid]': this.docsActiveStoreUuid
        }
      })
    }
  }
}
