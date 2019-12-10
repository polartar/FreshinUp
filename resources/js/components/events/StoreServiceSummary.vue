<template>
  <div>
    <v-card>
      <v-container
        font-weight-bold
        grey--text
        subheading
        py-3
      >
        Fleet Member Service Summary
      </v-container>
      <v-divider />
      <v-container
        pt-0
        font-weight-bold
        grey--text
      >
        <v-layout
          align-center
          my-2
        >
          <v-flex
            caption
            font-weight-bold
          >
            TOTAL SERVICES
          </v-flex>
          <v-flex
            text-xs-right
            subheading
            xs6
            text-truncate
          >
            {{ service.total_services }}
          </v-flex>
        </v-layout>
        <v-divider />
        <v-layout
          align-center
          my-2
        >
          <v-flex
            caption
            font-weight-bold
          >
            TOTAL COST
          </v-flex>
          <v-flex
            text-xs-right
            subheading
            xs6
            text-truncate
          >
            {{ service.total_cost }}
          </v-flex>
        </v-layout>
        <v-divider />
        <v-layout
          align-center
          primary--text
          font-weight-bold
          my-2
        >
          <v-flex
            caption
            font-weight-bold
          >
            COMMISSION RATE
          </v-flex>
          <v-btn
            class="transparent elevation-0 ma-0 pr-0 primary--text "
            @click="edit"
          >
            <v-card-text class="pa-0">
              {{ getCommissionValue() }}
            </v-card-text>
            <v-icon>edit</v-icon>
          </v-btn>
        </v-layout>
        <v-divider />

        <v-btn
          block
          class="grey ma-0 mt-4 white--text text-none"
          @click="viewContract"
        >
          View Contract
        </v-btn>
      </v-container>
    </v-card>

    <v-card
      v-show="showEdit"
      class="mx-3 ml-5 modal"
      :class="{'float': $vuetify.breakpoint.smAndUp}"
    >
      <v-form
        ref="form"
        v-model="isValid"
        lazy-validation
      >
        <v-container
          font-weight-bold
          grey--text
          subheading
          py-2
          mt-1
        >
          <v-layout>
            <v-layout
              mt-3
              xs6
              mr-2
              column
            >
              <v-flex
                caption
                font-weight-bold
                mb-2
              >
                OVERRIDE RATE
              </v-flex>
              <v-text-field
                v-model="inputValue.commission_rate"
                v-validate="'required'"
                type="number"
                :error-messages="errors.collect('rate')"
                data-vv-name="rate"
                caption
                solo
                single-line
              />
            </v-layout>

            <v-layout
              xs6
              d-flex
              mt-3
              column
            >
              <v-flex
                caption
                font-weight-bold
                mb-2
              >
                OVERRIDE TYPE
              </v-flex>
              <v-select
                v-model="inputValue.commission_type"
                :items="commissionTypes"
                item-value="id"
                item-text="label"
                solo
              />
            </v-layout>
          </v-layout>

          <v-layout>
            <v-flex>
              <v-btn
                class="ml-0 text-none"
                text-none
                @click="cancel"
              >
                Cancel
              </v-btn>
            </v-flex>
            <v-flex class="text-xs-right">
              <v-btn
                class="mr-0 right primary text-none"
                :disabled="!isValid"
                @click="whenValid(save)"
              >
                Save
              </v-btn>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
    </v-card>
  </div>
</template>

<script>
import Validate from 'fresh-bus/components/mixins/Validate'
export default {
  mixins: [Validate],
  props: {
    service: {
      type: Object,
      required: true
    }
  },
  data: function () {
    const commissionTypes = [ { id: 1, label: 'Percentage(%)', unit: '%' }, { id: 2, label: 'Flat ($)', unit: '$' } ]
    return {
      formValue: { ...this.service },
      inputValue: { ...this.service },
      commissionTypes: commissionTypes,
      showEdit: false
    }
  },
  watch: {
    service: {
      handler () {
        this.formValue = { ...this.service }
      },
      deep: true
    }
  },
  methods: {
    viewContract () {
      this.$emit('viewContract')
    },
    getCommissionValue () {
      const commissionRate = this.formValue.commission_rate
      const unit = this.commissionTypes[this.formValue.commission_type - 1].unit

      if (unit === '$') {
        return `${unit} ${commissionRate}`
      } else if (unit === '%') {
        return `${commissionRate} ${unit}`
      } else {
        return ''
      }
    },
    edit () {
      this.inputValue = { ...this.formValue }
      this.showEdit = true
    },
    save () {
      this.showEdit = false
      this.formValue = { ...this.inputValue }
      this.$emit('save', this.formValue)
    },
    cancel () {
      this.showEdit = false
      this.inputValue = { ...this.formValue }
    }
  }
}
</script>

<style lang="scss" scoped>
.modal {
   margin-top: -130px;
}
</style>
