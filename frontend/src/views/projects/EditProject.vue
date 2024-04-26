<template>
  <div>
    <b-spinner
      v-if="!loaded"
      style="width: 3rem; height: 3rem;"
      variant="primary"
      label="Loading..." type="grow"
      class="text-center"
    />
    <b-row v-if="loaded">
      <b-col lg="12">
        <b-card no-body>
          <validation-observer ref="editProjectValidation">
            <b-form @submit.prevent>
              <b-card-header class="mt-n1">
                <h4 style="color: #4D6D8A; margin-top: 0.5em">
                  Edit Project
                </h4>
              </b-card-header>
              <div style="padding: 0 21px 20px 21px;">
                <h5 class="mb-1 bg-light p-1">
                  Project Details
                </h5>
                <b-row>
                  <b-col cols="12">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="project name"
                        rules="required"
                      >
                        <label
                          for="project-name"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Project Name
                        </label>
                        <b-form-input
                          id="project-name"
                          v-model="projectName"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="project type"
                        rules="required"
                      >
                        <label
                          for="project-type"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Project Type
                        </label>
                        <v-select
                          id="project-type"
                          v-model="projectType"
                          :reduce="(option) => option.value"
                          :options="projectTypes"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="installation type"
                        rules="required"
                      >
                        <label
                          for="installation-type"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Project Installation Type
                        </label>
                        <v-select
                          id="project-type"
                          v-model="installationType"
                          :reduce="(option) => option.value"
                          :options="installationTypes"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="status"
                        rules="required"
                      >
                        <label
                          for="status"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Project Status
                        </label>
                        <v-select
                          id="status"
                          v-model="status"
                          :reduce="(option) => option.value"
                          :options="statusOptions"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="description"
                        rules="required"
                      >
                        <label
                          for="description"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Project Description
                        </label>
                        <b-form-textarea
                          id="description"
                          v-model="description"
                          rows="3"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                </b-row>
                <!--client details-->
                <h5 class="mt-1 mb-1 bg-light p-1">
                  Client Details
                </h5>
                <b-row>
                  <b-col lg="6">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="client name"
                        rules="required"
                      >
                        <label
                          for="client-name"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Client Name
                        </label>
                        <v-select
                          id="client-name"
                          v-model="client"
                          :reduce="(option) => option.value"
                          :options="clients"
                          label="title"
                          @input="getClient"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="3">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="state"
                        rules="required"
                      >
                        <label
                          for="state"
                          class="mt-lg-0 mb-lg-1 font-weight-bolder"
                        >
                          State
                        </label>
                        <v-select
                          id="state"
                          v-model="state"
                          :reduce="(option) => option.value"
                          :options="states"
                          label="title"
                          @input="getLGAsByStateID()"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="3">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="lga"
                        rules="required"
                      >
                        <label
                          for="lga"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          LGA
                        </label>
                        <v-select
                          id="lga"
                          v-model="lga"
                          :reduce="(option) => option.value"
                          :options="lgas"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                </b-row>
                <!--product details-->
                <h5 class="mt-1 mb-1 bg-light p-1">
                  Product Details
                </h5>
                <b-row>
                  <b-col lg="6">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="lga"
                        rules="required"
                      >
                        <label
                          for="product"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Product
                        </label>
                        <v-select
                          id="product"
                          v-model="product"
                          :reduce="(option) => option.value"
                          :options="products"
                          label="title"
                          @input="getProduct"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="6">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="price"
                        rules="required"
                      >
                        <label
                          for="price"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Price
                        </label>
                        <b-input-group
                          id="price"
                          prepend="₦"
                          class="input-group-merge"
                        >
                          <b-form-input v-model="price" />
                        </b-input-group>
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="battery type"
                        rules="required"
                      >
                        <label
                          for="battery-type"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Battery Type
                        </label>
                        <v-select
                          id="battery-type"
                          v-model="batteryType"
                          :reduce="(option) => option.value"
                          :options="batteryTypes"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="inverter type"
                        rules="required"
                      >
                        <label
                          for="inverter-type"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Inverter Type
                        </label>
                        <v-select
                          id="inverter-type"
                          v-model="inverterType"
                          :reduce="(option) => option.value"
                          :options="inverterTypes"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <validation-provider
                        #default="{ errors }"
                        name="solar panel type"
                        rules="required"
                      >
                        <label
                          for="solar-panel-type"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          Solar Panel Type
                        </label>
                        <v-select
                          id="solar-panel-type"
                          v-model="solarPanelType"
                          :reduce="(option) => option.value"
                          :options="solarPanelTypes"
                          label="title"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <label
                        for="system-size"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Battery Quantity
                      </label>
                      <b-form-spinbutton
                        id="num-batteries"
                        v-model="batteryQuantity"
                        min="1"
                      />
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <label for="num-batteries" class="mb-lg-1 font-weight-bolder">
                        Inverter Quantity
                      </label>
                      <b-form-spinbutton
                        id="num-batteries"
                        v-model="inverterQuantity"
                        min="1"
                      />
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <b-form-group>
                      <label for="num-panels" class="mb-lg-1 font-weight-bolder">
                        Solar Panel Quantity
                      </label>
                      <b-form-spinbutton
                        id="num-panels"
                        v-model="solarPanelQuantity"
                        min="1"
                      />
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col cols="12">
                    <div class="d-flex float-right">
                      <b-button
                        style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
                        @click="$router.push('/projects')"
                      >
                        Discard
                      </b-button>
                      <b-button
                        v-if="!isLoading"
                        type="submit"
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                        @click="editProject"
                      >
                        Save Changes
                      </b-button>
                      <b-button
                        v-if="isLoading"
                        disabled
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                      >
                        Saving...
                      </b-button>
                    </div>
                  </b-col>
                </b-row>
              </div>
            </b-form>
          </validation-observer>
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
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Projects / <span style="color: #C4C4C4">Edit Project</span>'
  },
  beforeDestroy() {
    localStorage.removeItem('products')
    localStorage.removeItem('projectID')
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
      this.$store.dispatch('project/getProjectByID', { projectID }).then(res => {
        const { data } = res.data
        console.log(data, 'dsjskjks')
        this.projectName = data.projectname
        this.projectType = data.projecttype
        this.systemSize = data.solarsystemsize
        this.solarPanelQuantity = parseFloat(data.numberofpanels)
        this.batteryQuantity = parseFloat(data.numberofbatteries)
        this.description = data.description
        this.installationType = data.installationtype
        this.status = data.status
        this.client = data.client.id
        this.state = data.stateid
        this.getLGAsByStateID()
        this.lga = data.lgaid
        this.product = data.product.id
        this.price = parseFloat(data.price).toString()
        this.inverterQuantity = parseFloat(data.numberofinverters)
        this.solarPanelType = data.solarpaneltype.id
        this.inverterType = data.invertertype.id
        this.batteryType = data.batterytype.id
        this.loaded = true
      })
    },
    editProject() {
      this.isLoading = true
      const projectID = localStorage.getItem('projectID')
      this.$refs.editProjectValidation.validate().then(success => {
        if (success) {
          const form = {
            projectID,
            projectName: this.projectName,
            projectType: this.projectType,
            systemSize: this.systemSize,
            solarPanelQuantity: this.solarPanelQuantity,
            batteryQuantity: this.batteryQuantity,
            description: this.description,
            installationType: this.installationType,
            status: this.status,
            client: this.client,
            lga: this.lga,
            product: this.product,
            price: parseFloat(this.price.replace(/,/g, '')),
            inverterQuantity: this.inverterQuantity,
            solarPanelType: this.solarPanelType,
            inverterType: this.inverterType,
            batteryType: this.batteryType,
          }
          this.$store.dispatch('project/editProject', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.$router.push('/projects').then(() => {
              this.toast('Edit Project', 'CheckCircleIcon', message, 'success')
            })
          }).catch(err => {
            this.isLoading = false
            this.toast('Edit Project', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Edit Project Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
    getClients() {
      this.$store.dispatch('client/getClients').then(res => {
        const { data } = res.data
        data.forEach(client => {
          const clientobj = { value: client.id, title: client.clientname }
          this.clients.push(clientobj)
        })
      })
    },
    getClient() {
      const clientID = this.client
      this.$store.dispatch('client/getClientByID', { clientID }).then(res => {
        const { data } = res.data
        this.state = data.stateid
        const stateID = this.state
        this.lgas = [{ value: null, title: 'Please select an lga' }]
        this.$store.dispatch('client/getLGAsByStateID', { stateID }).then(innerRes => {
          const innerData = innerRes.data.data
          innerData.forEach(lga => {
            const lgaobj = { value: lga.lgaid, title: lga.lganame }
            this.lgas.push(lgaobj)
            this.lga = data.lgaid
          })
        })
      })
    },
    getProjectTypes() {
      this.$store.dispatch('project/getProjectTypes').then(res => {
        const { data } = res.data
        data.forEach(type => {
          const typeobj = { value: type, title: type }
          this.projectTypes.push(typeobj)
        })
      })
    },
    getStatusOptions() {
      this.$store.dispatch('project/getProjectStatus').then(res => {
        const { data } = res.data
        data.forEach(status => {
          const statusobj = { value: status, title: status }
          this.statusOptions.push(statusobj)
        })
      })
    },
    getProducts() {
      this.$store.dispatch('product/getProducts').then(res => {
        const { data } = res.data
        localStorage.setItem('products', JSON.stringify(data))
        data.forEach(product => {
          const productobj = { value: product.id, title: product.productname }
          this.products.push(productobj)
        })
      })
    },
    getStates() {
      this.$store.dispatch('client/getStates').then(res => {
        const { data } = res.data
        data.forEach(state => {
          const stateobj = { value: state.stateid, title: state.sname }
          this.states.push(stateobj)
        })
      })
    },
    getLGAsByStateID() {
      const stateID = this.state
      this.lgas = [{ value: null, text: 'Please select an lga' }]
      this.$store.dispatch('client/getLGAsByStateID', { stateID }).then(res => {
        const { data } = res.data
        data.forEach(lga => {
          const lgaobj = { value: lga.lgaid, title: lga.lganame }
          this.lgas.push(lgaobj)
        })
      })
    },
    getBatteriesAndPanels() {
      const products = JSON.parse(localStorage.getItem('products'))
      products.every(product => {
        if (product.id === this.product) {
          this.numBatteries = product.numberofbatteries
          this.numPanels = product.numberofpanels
          return false
        }
        return true
      })
    },
    getProduct() {
      const products = JSON.parse(localStorage.getItem('products'))
      products.every(product => {
        if (product.id === this.product) {
          this.price = parseFloat(product.price).toString()
          this.batteryType = product.batterytype.id
          this.batteryQuantity = parseFloat(product.numberofbatteries)
          this.inverterType = product.invertertype.id
          this.inverterQuantity = parseFloat(product.numberofinverters)
          this.solarPanelType = product.solarpaneltype.id
          this.solarPanelQuantity = parseFloat(product.numberofpanels)
          return false
        }
        return true
      })
    },
    getSubItems() {
      const itemIDs = [1, 2, 3]
      itemIDs.forEach(itemID => {
        this.$store.dispatch('inventory/getSubItemByID', { itemID }).then(res => {
          const { data } = res.data
          switch (itemID) {
            case 1: // battery
              data.forEach(subItem => {
                const batteryType = { value: subItem.id, title: subItem.name }
                this.batteryTypes.push(batteryType)
              })
              break
            case 2: // solar panel
              data.forEach(subItem => {
                const solarPanelType = { value: subItem.id, title: subItem.name }
                this.solarPanelTypes.push(solarPanelType)
              })
              break
            default: // inverter
              data.forEach(subItem => {
                const inverterType = { value: subItem.id, title: subItem.name }
                this.inverterTypes.push(inverterType)
              })
          }
        })
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
</style>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
