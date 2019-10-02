<template>
  <v-card class="">
    <v-card-title class="justify-space-between px-4">
      <span class="black--text font-weight-bold title text-uppercase">Export to PDF/CSV</span>
      <v-btn
        icon
        large
        class="mr-0 mt-2 small-btn"
        @click="close"
      >
        <v-icon class="grey--text">
          far fa-times-circle
        </v-icon>
      </v-btn>
    </v-card-title>
    <hr>
    <div class="pa-4">
      <v-layout
        row
        mb-2
      >
        <v-flex
          sm12
          :class="{'pr-2': $vuetify.breakpoint.smAndUp}"
        >
          <v-layout
            row
            justify-space-between
            my-2
          >
            Select the file format
          </v-layout>
          <v-select
            v-model="selected_type"
            :items="selectable_types"
            solo
            hide-details
          />
        </v-flex>
      </v-layout>
      <v-layout
        row
        mt-4
        mb-2
      >
        <v-flex>
          <v-btn
            class="ml-0"
            @click="close"
          >
            Cancel
          </v-btn>
        </v-flex>
        <v-flex class="texs-xs-right">
          <v-btn
            class="mr-0 right primary"
            @click="exportFile"
          >
            Export
          </v-btn>
        </v-flex>
      </v-layout>
    </div>
  </v-card>
</template>

<script>
import reduce from 'lodash/reduce'
import Jspdf from 'jspdf'
import 'jspdf-autotable'
import FormatMoney from 'fresh-bus/components/mixins/FormatMoney'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export default {
  mixins: [FormatMoney, FormatDate],
  props: {
    transactions: {
      type: Array,
      required: true
    },
    dataVisibility: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      parameters: [
        { name: 'event_location', label: 'Event / Location' },
        { name: 'square_created_at', label: 'Creation Date' },
        { name: 'square_updated_at', label: 'Update Date' },
        { name: 'total_money', label: 'Total' },
        { name: 'total_tax_money', label: 'Tax Total' },
        { name: 'total_discount_money', label: 'Total Discount' },
        { name: 'total_service_charge_money', label: 'Total Service Charge' },
        { name: 'items', label: 'Items' },
        { name: 'event_tags', label: 'Event Tags' },
        { name: 'square_id', label: 'Square ID' },
        { name: 'store', label: 'Fleet member' },
        { name: 'store_square_id', label: 'Fleet Member Square ID' },
        { name: 'host', label: 'Customer Company' },
        { name: 'supplier', label: 'Supplier' },
        { name: 'customer', label: 'Customer name' },
        { name: 'customer_square_id', label: 'Customer Square ID' },
        { name: 'customer_reference_id', label: 'Customer Reference ID' }
      ],
      selected_type: 'csv',
      selectable_types: [
        { value: 'csv', text: 'Export to csv' },
        { value: 'pdf', text: 'Export to pdf' }
      ]
    }
  },
  computed: {
    downloadAttribute () {
      if (this.selected_type === 'csv') {
        return 'export.csv'
      }
      return 'export.pdf'
    },
    dataVisibilityComputed () {
      return this.dataVisibility
    },
    headerArray () {
      return reduce(this.dataVisibilityComputed, (result, value, key) => {
        let headerObject = this.parameters.find(item => item.name === value)
        result.push(headerObject.label)
        return result
      }, [])
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    csvExport () {
      let csvContent = 'data:text/csv;charset=utf-8,'
      let attributesArray = this.attributes(this.transactions)
      csvContent += [
        this.headerArray.join(';'),
        ...attributesArray.map(item => item.join(';').replace(/#/g, ''))
      ].join('\n').replace(/(^\[)|(\]$)/gm, '')

      let data = encodeURI(csvContent)
      let link = document.createElement('a')
      link.setAttribute('href', data)
      link.setAttribute('download', this.downloadAttribute)
      link.click()
    },
    pdfExport () {
      var doc = new Jspdf('landscape')
      let attributesArray = this.attributes(this.transactions)
      doc.autoTable({
        head: [this.headerArray],
        body: attributesArray,
        startY: 10,
        theme: 'grid'
      })
      doc.save(this.downloadAttribute)
    },
    exportFile () {
      if (this.selected_type === 'csv') {
        this.csvExport()
      } else {
        this.pdfExport()
      }
    },
    formatTransactionDate (date) {
      return this.formatDate(date, 'MMM DD, YYYY - hh:mma')
    },
    formatItems (array) {
      return reduce(array, (result, value, key) => {
        if (result !== '') {
          result += ', '
        }
        result += value.quantity + ' ' + value.name
        return result
      }, '')
    },
    formatEventTags (array) {
      return reduce(array, (result, value, key) => {
        if (result !== '') {
          result += ', '
        }
        result += value.name
        return result
      }, '')
    },
    attributes (transactions) {
      return reduce(transactions, (result, value, key) => {
        let elementArray = reduce(this.dataVisibilityComputed, (resultElement, element, key) => {
          let val = ''
          switch (element) {
            case 'event_location':
              val = value.event.name + ' / ' + ((value.event.location) ? value.event.location.name : '')
              break
            case 'square_created_at':
            case 'square_updated_at':
              val = this.formatTransactionDate(value[element])
              break
            case 'total_money':
            case 'total_tax_money':
            case 'total_discount_money':
            case 'total_service_charge_money':
              val = this.formatMoney(value[element], { format: '$0,0.00', precision: 4 })
              break
            case 'items':
              val = this.formatItems(value[element])
              break
            case 'event_tags':
              val = this.formatEventTags(value.event.event_tags)
              break
            case 'customer':
            case 'store':
              val = value[element] ? value[element].name : ''
              break
            case 'host':
              val = value.event.host ? value.event.host.name : ''
              break
            case 'supplier':
              val = value.store.supplier.name
              break
            case 'store_square_id':
              val = value.store.square_id
              break
            case 'customer_square_id':
              val = value.customer ? value.customer.square_id : ''
              break
            case 'customer_reference_id':
              val = value.customer ? value.customer.reference_id : ''
              break
            default:
              val = value[element]
          }
          resultElement.push(val)
          return resultElement
        }, [])
        result.push(elementArray)
        return result
      }, [])
    }
  }
}
</script>
<style lang="styl" scoped>
  .v-card {
    border-radius: 6px;
  }
</style>
