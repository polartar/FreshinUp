<template>
  <v-layout
    row
    wrap
    pa-3
  >
    <v-flex
      xs12
      mb-3
    >
      <v-layout
        mb-2
        class="title font-weight-bold"
      >
        Submitted on
      </v-layout>
      <div v-if="ownerName">
        {{ createdAt }} by {{ ownerName }}
      </div>
    </v-flex>
    <v-flex
      xs12
    >
      <v-layout
        mb-2
        class="title font-weight-bold"
      >
        Assigned to
      </v-layout>
      <AssignedSearch
        :value="doc.assigned && doc.assigned.uuid"
        :type="doc.assigned_type"
        @assign-change="selectAssigned"
        @type-change="changeAssignedType"
      />
    </v-flex>
    <v-flex
      xs12
    >
      <v-layout
        mb-2
        class="title font-weight-bold"
      >
        Expiration Date
      </v-layout>
      <vue-ctk-date-time-picker
        v-model="doc.expiration_at"
        only-date
        format="YYYY-MM-DD"
        formatted="MM-DD-YYYY"
        input-size="lg"
        :color="$vuetify.theme.primary"
        :button-color="$vuetify.theme.primary"
      />
    </v-flex>
    <v-flex
      xs12
      mt-3
    >
      <v-layout
        row
        wrap
        justify-space-between
      >
        <v-flex
          xs5
        >
          <v-btn
            block
          >
            Preview
          </v-btn>
        </v-flex>
        <v-flex
          xs5
        >
          <v-btn
            block
            :disabled="!isvalid"
            class="primary"
            @click="onSaveClick"
          >
            Save Changes
          </v-btn>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
<script>
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import AssignedSearch from '~/components/docs/AssignedSearch.vue'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
import { pick } from 'lodash'

export default {
  components: {
    VueCtkDateTimePicker,
    AssignedSearch
  },
  mixins: [FormatDate],
  props: {
    isvalid: {
      type: Boolean,
      default: false
    },
    initdata: {
      type: Object,
      default: () => {
        return {
          owner: null,
          created_at: null,
          assigned: null,
          expiration_at: null,
          assigned_type: 1,
          event_store_uuid: null
        }
      }
    }
  },
  data () {
    return {
      doc: {
        ...pick(this.initdata, [
          'owner',
          'created_at',
          'assigned',
          'expiration_at',
          'assigned_type',
          'event_store_uuid'
        ]),
        assigned_uuid: null
      }
    }
  },
  computed: {
    ownerName () {
      return this.doc.owner ? this.doc.owner.name : null
    },
    createdAt () {
      return this.doc.created_at ? this.formatDate(this.doc.created_at, 'MMM DD, YYYY • HH:mm a') : null
    }
  },
  watch: {
    doc: {
      handler (val) {
        this.$emit('data-change', pick(val, [
          'assigned_type',
          'assigned_uuid',
          'expiration_at',
          'event_store_uuid'
        ]))
      },
      deep: true
    }
  },
  methods: {
    selectAssigned (assigned) {
      this.doc.assigned_uuid = assigned.uuid
      this.doc.event_store_uuid = assigned.event_store_uuid
    },
    changeAssignedType (value) {
      this.doc.assigned_type = value
    },
    onSaveClick () {
      this.$emit('data-save')
    }
  }
}
</script>
