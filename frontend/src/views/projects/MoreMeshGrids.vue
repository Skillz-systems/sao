<template>
  <div>
    <!-- <b-spinner
      v-if="!loaded"
      style="width: 3rem; height: 3rem;"
      variant="dark"
      label="Loading..." type="grow"
      class="text-center"
    />
    <b-row v-if="loaded">
      <b-col lg="12"> -->
 
    <div class="order-cards">
      <b-col
        v-for="(order, index) in orders.installations"
        :key="index"
        class="mt-1"
        lg="12"
      >
        <div
          v-for="(orderDetails, index) in order.orderdetails.inverter"
          :key="index"
          class="mt-1 order-card"
        >
          <p>
            <strong class="mr-1">Name:</strong>
            <span>{{ orderDetails.name }}</span>
          </p>
          <p>
            <strong class="mr-1">Price:</strong>
            <span>{{ orderDetails.price }}</span>
          </p>
          <p>
            <strong class="mr-1">Rating:</strong>
            <span>{{ orderDetails.rating }}</span>
          </p>
       
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            width="30"
            variant="primary"
            class="btn-icon"
            @click="createConnection()"
          >Create Connection</b-button>
          <b-button @click="moreMeshDetails(orderDetails)">
            View More Details
          </b-button>
        </div>
      </b-col>

    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import vSelect from 'vue-select'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  data() {
    return {
      loaded: false,
      isLoading: false,
      client: null,
      product: null,
      projectName: null,
      projectType: null,
      systemModel: null,
      systemSize: null,
      installationType: null,
      price: null,
      numBatteries: 1,
      numPanels: 1,
      status: null,
      location: null,
      state: null,
      orders: [],
      lga: null,
      description: null,
      clients: [
        { value: null, title: 'Please select a client' },
      ],
      products: [
        { value: null, title: 'Please select a product' },
      ],
      projectTypes: [
        { value: null, title: 'Please select a project type' },
      ],
      systemModels: [
        { value: null, text: 'Please select a solar system model' },
        { value: 1, text: 'BES-1' },
        { value: 2, text: 'BES-3' },
        { value: 3, text: 'BES-5' },
        { value: 4, text: 'BES-8' },
      ],
      systemSizes: [
        { value: null, text: 'Please select a solar system size' },
        { value: '1 - 2 kW', text: '1 - 2 kW' },
        { value: '3.0 kW', text: '3.0 kW' },
        { value: '4.0 kW', text: '4.0 kW' },
        { value: '5.0 kW', text: '5.0 kW' },
        { value: '5 - 10 kW', text: '5 - 10 kW' },
        { value: '10 + kW', text: '10 + kW' },
      ],
      installationTypes: [
        { value: null, title: 'Please select an installation type' },
        { value: 'Rooftop', title: 'Rooftop' },
        { value: 'Groundmount', title: 'Groundmount' },
      ],
      statusOptions: [
        { value: null, title: 'Please select a project status' },
      ],
      locations: [
        { value: null, title: 'Please select a location' },
      ],
      states: [
        { value: null, title: 'Please select a state' },
      ],
      lgas: [
        { value: null, title: 'Please select an lga' },
      ],
      batteryType: null,
      batteryQuantity: 1,
      batteryTypes: [
        { value: null, title: 'Please select a battery type' },
      ],
      inverterType: null,
      inverterQuantity: 1,
      inverterTypes: [
        { value: null, title: 'Please select an inverter type' },
      ],
      solarPanelType: null,
      solarPanelQuantity: 1,
      solarPanelTypes: [
        { value: null, title: 'Please select a solar panel type' },
      ],
      required,
    }
  },
  watch: {
    price(newValue) {
      const result = newValue.replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, ',')
      // eslint-disable-next-line no-return-assign
      this.$nextTick(() => this.price = result)
    },
  },

  created() {
    const { order } = this.$route.query
    this.mesh = JSON.parse(order)
    // this.amount = this.paymentData.amount;
    localStorage.setItem('orderorder', order)
  },
  mounted() {
    // document.getElementById('custom-breadcrumb').innerHTML = 'Projects / <span style="color: #C4C4C4">Edit Project</span>'
    const { order } = this.$route.query
    console.log(order, 'kkkkk')
    this.getProject()
  },
  beforeDestroy() {
    // localStorage.removeItem('products')
    // localStorage.removeItem('projectID')
  },
  created() {
    this.getProject()
    this.getClients()
    this.getProjectTypes()
    this.getStatusOptions()
    this.getProducts()
    this.getSubItems()
    this.getStates()
  },

  methods: {
    getProject() {
      const projectID = localStorage.getItem('projectID')
      this.$store
        .dispatch('project/getProjectByID', { projectID })
        .then(res => {
          const { data } = res.data

          console.log(data, 'djdsjsks')

          const allPayments = data.payments.map((value, index) => {
            const serial = index
            return { ...value, serial }
          })

          // const allConnections = data.payments.map((value, index) => {
          //   const serial = index
          //   return { ...value, serial }
          // })

          console.log(allPayments, 'allPayments')
          this.allPayments = allPayments

          const miscellanClone = []
          let serial = 0
          data.installations.map(order => order.orderdetails.accessories.map((accessory, index) => {
          // datum.map(accessory => {

            const fil = miscellanClone.filter(element => element.name == accessory.name)
            if (fil.length != 0) {
              miscellanClone.forEach(element => {
                if (element.name == accessory.name) {
                  return element.count += 1
                }
              })
            } else {
              const dataIn = {
                name: accessory.name, price: accessory.price, rating: accessory.rating, count: 1, total: '', serial: serial += 1,
              }
              miscellanClone.push(dataIn)
            }
          }))
          this.miscellanClone = miscellanClone

          console.log(miscellanClone, 'miscellanClone')

          const payments = []
          data.payments.map(payment => {
            payments.push(Number(payment.amount))
          })
          const initialValue = 0
          const sumWithInitial = payments.reduce(
            (previousValue, currentValue) => previousValue + currentValue,
            initialValue,
          )

          this.allAmountsPaidIN = sumWithInitial.toFixed(2)
          this.orders = data
          this.project = data
          this.loaded = true
        })
    },
  },

}
</script>

<style>
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
[dir] .custom-select:focus {
  border-color: #4D6D8A;
}
[dir] .b-form-spinbutton.form-control:focus, [dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}

.order-cards {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  flex-wrap: wrap;
  column-gap: 10px;
  /* border: 2px solid red; */
}

.order-card {
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
    rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
  padding: 1rem 1rem;
  width: 17%;
}
</style>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
