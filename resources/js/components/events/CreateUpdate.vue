<template>
  <v-form
    v-if="!isLoading"
    ref="form"
    v-model="isValid"
  >
    <v-layout
      row
      align-center
      pt-3
    >
      <v-btn
        flat
        small
        @click="backToList"
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to Events list</span>
        </div>
      </v-btn>
    </v-layout>
    <v-layout
      row
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <status-select
          v-model="status_id"
          :options="statuses"
          single-line
          solo
          flat
          hide-details
        />
      </v-flex>
    </v-layout>
    <v-divider />
    <br>
    <v-layout
      row
      wrap
      pa-2
      justify-space-between
      class="event-new-wrap"
    >
      <v-flex
        md12
        sm12
      >
        <BasicInfoformation
          ref="basicInfo"
          :event="event"
          :errors="errors"
          @data-change="changeBasicInfo"
          @cancel="onCancel"
          @save="onSave"
          @delete="onDelete"
        />
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
import { omitBy, isNull } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import Validate from 'fresh-bus/components/mixins/Validate'
import StatusSelect from '~/components/events/StatusSelect'
import BasicInfoformation from '~/components/events/BasicInformation.vue'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    StatusSelect,
    BasicInfoformation
  },
  mixins: [Validate],
  data () {
    return {
      isNew: false
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapFields('events', [
      'status_id'
    ]),
    pageTitle () {
      return (this.isNew ? 'New Event' : 'Event Details')
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    changeBasicInfo (data) {
      this.event.atendees = data.atendees
      this.event.budget = data.budget
      this.event.commission_rate = data.commission_rate
      this.event.commission_type = data.commission_type
      this.event.type = data.type
      this.event.start_at = data.start_at
      this.event.end_at = data.end_at
      this.event.event_tags = data.event_tags
      this.event.host_uuid = data.host_uuid
      this.event.manager_uuid = data.manager_uuid
      this.event.name = data.name
    },
    async validator () {
      const valids = await Promise.all([
        this.$validator.validateAll(),
        this.$refs.basicInfo.$validator.validateAll()
      ])
      return valids.every(valid => valid)
    },
    onSave () {
      this.validator().then(async valid => {
        let data = omitBy(this.event, (value, key) => {
          const extra = ['created_at', 'updated_at']
          return extra.includes(key) || isNull(value)
        })
        if (valid) {
          if (this.isNew) {
            data.id = 'new'
            await this.$store.dispatch('events/createItem', { data })
            await this.$store.dispatch('generalMessage/setMessage', 'Saved')
            this.$router.push('/admin/events/')
          } else {
            await this.$store.dispatch('events/updateItem', { data, params: { id: data.uuid } })
            await this.$store.dispatch('generalMessage/setMessage', 'Modified')
          }
        }
      })
    },
    onCancel () {
      this.$router.push({ path: '/admin/events' })
    },
    async onDelete () {
      await this.$store.dispatch('events/deleteItem', { getItems: false, params: { id: this.event.uuid } })
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted')
      this.$router.push('/admin/events/')
    },
    backToList () {
      this.$router.push({ path: '/admin/events' })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id || 'new'
    let params = { id }
    if (id !== 'new') {
      params = {
        id,
        include: 'manager,host,event_tags'
      }
    }
    Promise.all([
      vm.$store.dispatch('events/getItem', { params }),
      vm.$store.dispatch('eventStatuses/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
<style scoped>
  .event-new-wrap{
    background-color: #fff;
  }
  .back-btn-inner{
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 13px;
  }
  .back-btn-inner span{
    margin-left: 10px;
    font-weight: bold;
    text-transform: initial;
  }
  .back-btn-inner .v-icon{
    font-size: 16px;
  }
</style>
