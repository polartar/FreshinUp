<template>
  <v-card>
    <v-container
      font-weight-bold
      grey--text
      subheading
      py-3
    >
      Fleet Member Summary
    </v-container>
    <v-divider />
    <v-container
      pt-0
      font-weight-bold
      grey--text
    >
      <v-layout
        align-center
        py-2
      >
        <v-flex
          caption
          font-weight-bold
        >
          OWNER
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ store.owner }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        align-center
        py-2
      >
        <v-flex
          caption
          font-weight-bold
        >
          LISENCE DUE
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ store.lisence_due }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        align-center
        py-2
      >
        <v-flex
          caption
          font-weight-bold
        >
          CONTACT PHONE
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ store.phone }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        align-center
        class="py-2"
      >
        <v-flex
          caption
          font-weight-bold
        >
          CONTACT EMAIL
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ store.email }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        v-if="store.tags"
        tags_title
        font-weight-bold
        grey--text
        mt-2
        pa-1
      >
        <v-flex>TAGS</v-flex>
      </v-layout>
      <v-layout
        v-if="store.tags"
        row
        wrap
      >
        <f-chip
          v-for="(tag, index) in store.tags"
          :key="index"
          :color="'primary'"
          class="my-1 ml-0 mr-2"
        >
          {{ tag.name }}
        </f-chip>
      </v-layout>

      <v-divider class="mt-3" />
      <v-btn
        block
        class="grey ma-0 mt-4 white--text text-none"
        @click="viewMemberProfile"
      >
        View Fleet Member Profile
      </v-btn>
      <v-btn
        block
        class="transparent elevation-0 ma-0 pa-0 caption  grey--text mt-3 button-remove text-none"
        @click="removeMemberProfile"
      >
        <v-icon class="pr-2 caption">
          far fa-trash-alt
        </v-icon>
        <span>Remove fleet member from this event</span>
      </v-btn>
    </v-container>
  </v-card>
</template>

<script>
import FChip from 'fresh-bus/components/ui/FChip.vue'

export default {
  components: { FChip },
  props: {
    store: {
      type: Object,
      required: true
    }
  },
  methods: {
    viewMemberProfile () {
      this.$emit('viewMemberProfile')
    },
    removeMemberProfile () {
      this.$emit('removeMemberProfile')
    }
  }
}
</script>
