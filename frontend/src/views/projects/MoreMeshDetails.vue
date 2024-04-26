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

    <b-col cols="12">
      <b-card>
        <b-row>
          <b-col cols="8">
            <div><b> Product Name:</b> {{ meshGrid[0].productname }} </div>
          </b-col>
          <b-col cols="4">
            <div><b>Product Price:</b> ₦{{ meshGrid[0].mesh_grid_price }} </div>
          </b-col>
          <b-col cols="4">
            <div><b>pod installation price:</b> ₦{{ meshGrid[0].pod_installation_price }} </div>
          </b-col>
          <b-col cols="8">
            <div> <b>maxmum connection:</b> {{ meshGrid[0].maxmum_connection }}  </div>
          </b-col>
          <!-- <b-col cols="4">
            <div> <b>Maximum Connection:</b> 10  </div>
          </b-col> -->
          <b-col cols="4">
            <div> <b>Created:</b>  {{  meshGrid[0].created_at }}  </div>
          </b-col>
          <!-- <b-col cols="12">
            <div style="display: flex; justify-content: center; align-items: center;">
              <b style="font-size: 18px;">Total Payment:</b> $10000
            </div>
          </b-col> -->
        </b-row>

        <div />

      </b-card></b-col>
    <b-row>
      <b-col lg="12">
        <b-card no-body>
          <b-card-header class="mt-n1">
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Connections
            </h4>

          </b-card-header>
          <b-col>
            <b-card
              no-body
              class="card-company-table"
            >
            <!-- {{ meshGrid[0] }} -->
              <b-table
                :items="meshGrid[0].connections"
                responsive
                :fields="connectionsField"
                class="mb-0"
              >
                <!-- type_of_payment -->
                <template #cell(serial)="data">
                  {{ data.item.serial + 1 }}
                </template>

                <template #cell(action)="data">
                  <div class="">
                    <feather-icon
                      :id="`view-${data.item.serial}`"
                      v-b-popover.hover.left="data.item.installation_name"
                      icon="InfoIcon"
                      size="16"
                      class="cursor-pointer mx-1"
                      @click="viewProductDesc(data.item)"
                    />
                    <feather-icon
                      :id="`view-${data.item.serial}`"
                      v-b-tooltip.hover.top="'Edit'"
                      right
                      icon="Edit3Icon"
                      size="16"
                      class="cursor-pointer"
                      style="color: #4D6D8A;"
                      @click="editConnection(data.item.serial)"
                    />
                    <feather-icon
                      :id="`view-${data.item.serial}`"
                      v-b-tooltip.hover.top="'Delete'"
                      icon="Trash2Icon"
                      size="16"
                      class="cursor-pointer mx-1"
                      @click="deleteConnection(data.item)"
                    />
                  </div>
                </template>
                <!--  -->
              </b-table>
            </b-card>
          </b-col>

        </b-card>
      </b-col>
    </b-row>
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
      meshGrid: [],
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
      lga: null,
      description: null,
      connectionsField: [
        { key: 'id', label: 'Connection id' },
        // { key: 'installation_name', label: 'installation name' },
        { key: 'client_id', label: 'client Id' },
        { key: 'amount', label: 'amount' },
        { key: 'actual_amount', label: 'actual_amount' },
        { key: 'connection_number', label: 'connection_number' },
        { key: 'discount', label: 'discount' },
        { key: 'meshgrid_id', label: 'meshgrid_id' },
        { key: 'order_description', label: 'order_description' },

      ],
      allConnections: [{
        serial: 1, installation_name: 'smigle', client_name: 'John COol', address: 'Lagod',
      },
      {
        serial: 2, installation_name: 'Negro', client_name: 'Lauren Cjharles', address: 'Abia',
      },
      {
        serial: 3, installation_name: 'Scotung', client_name: 'Vinicius Jr', address: 'Adeboka Single',
      }, {
        serial: 4, installation_name: 'kuinlo', client_name: 'Eleke Joyce', address: 'India',
      }],
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
    const { mesh } = this.$route.query
    this.mesh = JSON.parse(mesh)
    // this.amount = this.paymentData.amount;
    localStorage.setItem('meshmesh', mesh)
  },
  mounted() {
    // document.getElementById('custom-breadcrumb').innerHTML = 'Projects / <span style="color: #C4C4C4">Edit Project</span>'
    const { mesh } = this.$route.query
    console.log(mesh,'ddd', this.$route.query);
    this.meshGrid = [mesh]
    console.log(mesh, 'kkkkk')
  },
  beforeDestroy() {
    // localStorage.removeItem('products')
    // localStorage.removeItem('projectID')
  },
  created() {

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

[dir] .b-form-spinbutton.form-control:focus,
[dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}

[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control,
[dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
</style>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
