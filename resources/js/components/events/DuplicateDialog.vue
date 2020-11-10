<template>
  <v-card>
    <v-progress-linear
      v-if="duplicating"
      indeterminate
    />
    <v-card-title>
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3>Duplicate Event</h3>
        </v-flex>
        <v-btn
          small
          round
          color="grey"
          class="white--text"
          @click="changeDuplicateDialogue(false)"
        >
          <v-flex>
            <v-icon
              small
              class="white--text"
            >
              fa fa-times
            </v-icon>
          </v-flex>
          <v-flex>
            Close
          </v-flex>
        </v-btn>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="grey--text">
      <small class="font-weight-bold">SELECT</small>
      <p>Choose what will be carried over to the duplicate event</p>
      <v-checkbox
        v-model="duplicate.basicInformation"
        class="mt-0 mb-0 p-0"
        label="Basic Information"
      />
      <v-checkbox
        v-model="duplicate.venue"
        class="mt-0 mb-0 p-0"
        label="Venue/lovation (coming soon)"
      />
      <v-checkbox
        v-model="duplicate.fleetMember"
        class="mt-0 mb-0 p-0"
        label="Fleet Member (coming soon)"
      />
      <v-checkbox
        v-model="duplicate.customer"
        class="mt-0 mb-0 p-0"
        label="Customer (coming soon)"
      />
    </v-card-text>
    <v-divider />
    <v-card-actions>
      <v-layout
        row
        justify-end
      >
        <v-btn
          @click="changeDuplicateDialogue(false)"
        >
          Cancel
        </v-btn>
        <v-btn
          color="primary"
          @click="onDuplicate"
        >
          Duplicate
        </v-btn>
      </v-layout>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  layout: 'admin',
  props: {
    duplicateDialog: { type: Boolean, default: false },
    duplicating: { type: Boolean, default: false }
  },
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
    changeDuplicateDialogue (value) {
      this.$emit('manage-duplicate-dialog', value)
    },
    onDuplicate () {
      this.$emit('manage-duplicate', this.duplicate)
    }
  }
}
</script>
