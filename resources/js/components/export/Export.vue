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
import moment from 'moment'

export default {
  props: {
    arrayDatas: {
      type: Array,
      required: true
    },
    visibleParameters: {
      type: Array,
      required: true
    },
    parameters: {
      type: Array,
      required: true
    },
    dataType: {
      type: String,
      required: true
    },
    tireChartStatuses: {
      type: Array,
      default: () => []
    },
    wheels: {
      type: Array,
      default: () => []
    },
    branches: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
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
      return this.visibleParameters
    },
    headerArray () {
      return reduce(this.dataVisibilityComputed, (result, value, key) => {
        let headerObject = this.parameters.find(item => item.name === value)
        result.push(headerObject.label)
        return result
      }, [])
    },
    tireChartStatusesForComparison () {
      return reduce(this.tireChartStatuses, (result, value, key) => {
        result[value.uuid] = value.label
        return result
      }, [])
    },
    wheelsForComparison () {
      return reduce(this.wheels, (result, value, key) => {
        result[value.uuid] = value.label
        return result
      }, [])
    },
    branchesValues () {
      return reduce(this.branches, (result, value, key) => {
        result[value.id] = value.title
        return result
      }, [])
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    csvExport () {
      let arrData = this.arrayDatas
      let csvContent = 'data:text/csv;charset=utf-8,'
      let attributesArray = []
      if (this.dataType === 'vehicle') {
        attributesArray = this.vehicleAttributes(arrData)
      }
      if (this.dataType === 'appraisal') {
        attributesArray = this.appraisalAttributes(arrData)
      }
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
      let arrData = this.arrayDatas
      var doc = new Jspdf('landscape')
      let attributesArray = []
      if (this.dataType === 'vehicle') {
        attributesArray = this.vehicleAttributes(arrData)
      }
      if (this.dataType === 'appraisal') {
        attributesArray = this.appraisalAttributes(arrData)
      }
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
    formatCreatedAt (date) {
      return moment(date).format('MMM D, YYYY')
    },
    vehicleAttributes (vehicles) {
      return reduce(vehicles, (result, value, key) => {
        let elementArray = reduce(this.dataVisibilityComputed, (resultElement, element, key) => {
          let val = ''
          switch (element) {
            case 'truck_overview':
              val = value.make.label + ' ' + value.model.label + '/' +
                      value.stock_number + '/' + value.vin + '/' + value.mileage
              break
            case 'optional_equipment':
            case 'glider_types':
            case 'deposit_statuses':
              val = value[element].map(item => item.label).join(',')
              break
            case 'engine':
              val = value.engine.label + ' ' + value.engine_model.label
              break
            case 'tire_chart':
              val = value.tire_chart.map(item => this.tireChartStatusesForComparison[item.tire_chart_status_uuid] + ' ' + item.remaining_tread).join(',')
              break
            case 'wheels':
              val = value.wheels.map(item => item.label + ' ' + this.wheelsForComparison[item.wheel_uuid]).join(',')
              break
            case 'axles':
              val = value.axles.map(item => item.label + ' ' + item.value).join(',')
              break
            case 'prepared_by':
            case 'taken_in_by':
            case 'authorized_by':
            case 'customer':
              if (value.appraisal !== null && value.appraisal[element] !== null) {
                val = value.appraisal[element].name
              }
              break
            case 'appraisal_status':
            case 'sell_status':
              if (value.appraisal !== null && value.appraisal[element] !== null) {
                val = value.appraisal[element].label
              }
              break
            case 'owning_location':
            case 'current_location':
              if (value.appraisal !== null && value.appraisal[element + '_id'] !== null) {
                val = this.branchesValues[value.appraisal[element + '_id']]
              }
              break
            case 'matrix_value':
            case 'appraised_value':
            case 'minimum_sell':
            case 'asking_price':
            case 'serial_number':
              if (value.appraisal !== null && value.appraisal[element] !== null) {
                val = value.appraisal[element]
              }
              break
            case 'prepared_at':
            case 'scheduled_at':
            case 'acquired_at':
            case 'delivery_at':
              if (value.appraisal !== null && value.appraisal[element] !== null) {
                val = this.formatCreatedAt(value.appraisal[element])
              }
              break
            default:
              if (typeof value[element] === 'object') {
                val = value[element].label
              } else {
                val = value[element]
              }
          }
          resultElement.push(val)
          return resultElement
        }, [])
        result.push(elementArray)
        return result
      }, [])
    },
    appraisalAttributes (appraisals) {
      return reduce(appraisals, (result, value, key) => {
        let elementArray = reduce(this.dataVisibilityComputed, (resultElement, element, key) => {
          let val = ''
          switch (element) {
            case 'truck_overview':
              val = value.vehicle.make.label + ' ' + value.vehicle.model.label + '/' +
                      value.vehicle.stock_number + '/' + value.vehicle.vin + '/' + value.vehicle.mileage
              break
            case 'prepared_by':
            case 'taken_in_by':
            case 'authorized_by':
            case 'customer':
              if (value[element] !== null) {
                val = value[element].name
              }
              break
            case 'owning_location':
            case 'current_location':
              if (value[element] !== null) {
                val = value[element].company.name + ' ' + value[element].title
              }
              break
            case 'prepared_at':
            case 'scheduled_at':
            case 'acquired_at':
            case 'delivery_at':
              if (value.appraisal !== null && value !== null) {
                val = this.formatCreatedAt(value[element])
              }
              break
            default:
              if (typeof value[element] === 'object') {
                val = value[element].label
              } else {
                val = value[element]
              }
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
