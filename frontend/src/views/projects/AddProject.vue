<template>
  <div>
    <b-row>
      <b-col lg="12">
        <b-card no-body>
          <validation-observer ref="addProjectValidation">
            <b-form @submit.prevent>
              <b-card-header class="mt-n1">
                <h4 style="color: #4D6D8A; margin-top: 0.5em">
                  Add Project
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
                  <b-col lg="3">
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
                          Project Selection Mode
                        </label>
                        <v-select
                          id="project-type"
                          v-model="selectionMode"
                          :reduce="(option) => option.value"
                          :options="selectionModes"
                          label="title"
                          @input="onLanguageChange"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col lg="3">
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
                  <b-col lg="3">
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
                  <b-col lg="3">
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

                  <b-col
                    v-if="showSingleSelectionCont == true && selectionMode != 'meshgrid'"
                    lg="3"
                  >
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
                          Category
                        </label>
                        <v-select
                          id="project-type"
                          v-model="category"
                          :reduce="(option) => option.value"
                          :options="categories"
                          label="title"
                          @input="onCategoryChange"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>

                  <b-col
                    v-if="showSingleSelectionCont == true && selectionMode != 'meshgrid'"
                    lg="3"
                  >
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
                          Sub Category
                        </label>
                        <v-select
                          id="project-type"
                          v-model="subCategory"
                          :reduce="(option) => option.value"
                          :options="subCategories"
                          label="title"
                          @input="onsubCategoryChange"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col
                    v-if="showSingleSelectionCont == true && selectionMode != 'meshgrid'"
                    lg="3"
                  >
                    <b-form-group>
                      <label
                        for="num-panels"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Discount
                      </label>
                      <vue-numeric-input
                        id="discount"
                        v-model="productDiscount"
                        :min="0"
                        :step="1"
                        width="100%"
                        class="number-input"
                        align="center"
                      />
                    </b-form-group>
                  </b-col>
                  <b-col
                    v-if="showSingleSelectionCont == true && selectionMode != 'meshgrid'"
                    lg="3"
                  >
                    <b-form-group>
                      <div>
                        <label
                          for="installation-type"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          &nbsp;
                        </label>
                      </div>

                      <b-button
                        v-if="!isLoading"
                        type="submit"
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                        @click=""
                      >
                        Add Item
                      </b-button>
                    </b-form-group>
                  </b-col>

                  <b-col
                    v-if="showProductSalesCont === true"
                    lg="6"
                  >
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
                  <b-col
                    v-if="showProductSalesCont === true"
                    lg="6"
                  >
                    <b-form-group>
                      <label
                        for="num-panels"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Discount
                      </label>
                      <vue-numeric-input
                        id="discount"
                        v-model="productDiscount"
                        :min="0"
                        :step="1"
                        width="100%"
                        class="number-input"
                        align="center"
                      />
                    </b-form-group>
                  </b-col>
                  <b-col
                    cols="12"
                    style="color: red"
                  >
                    {{ misceMessage }}
                  </b-col>

                  <b-col
                    v-if="selectionMode != 'meshgrid'"
                    cols="4"
                  >

                    <validation-provider
                      #default="{ errors }"
                      name="Miscellaneous"
                      rules=""
                    >
                      <label
                        for="product"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Miscellaneous <span
                          v-if="allMiscellaneous.length == 0"
                          style="color: red;"
                          cols="4"
                        >
                          ( You can Add Miscellaneous from the Account Page )
                        </span>
                      </label>

                      <b-form-select
                        id="system-size"
                        v-model="miscellaneous"
                        :options="allMiscellaneous"
                        @change="miscellaneousSelectChange"
                      />

                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-col>

                  <b-col
                    v-if="selectionMode != 'meshgrid'"
                    cols="4"
                  >
                    <b-form-group>
                      <label
                        for="num-panels"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Amount
                      </label>

                      <b-form-input v-model="miscellanousAmount" />
                    </b-form-group>
                  </b-col>
                  <b-col
                    v-if="selectionMode != 'meshgrid'"
                    cols="4"
                  >
                    <b-form-group>
                      <div>
                        <label
                          for="product"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          &nbsp;
                        </label>
                      </div>

                      <b-button
                        type="submit"
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                        @click="addMiscellaneous"
                      >
                        ADD Miscellaneous
                      </b-button></b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <!-- <b-table
                      :items="createdMiscellaneous"
                      responsive
                      :fields="miscellaneousField"
                      class="mb-0"
                    >
                      <template #cell(amount)="data">
                        <del style="text-decoration-style: double;">N</del>
                        {{
                          data.item.amount
                            .toString()
                            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                      <template #cell(miscellaneous_id)="data">
                        {{
                          data.item.mainmiscallaneous.title
                            .toString()
                            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                    </b-table> -->
                    <div
                      v-if="createdMiscellaneous.length != 0"
                      style="border: #ccc solid 1px; padding: 1rem"
                    >
                      <h6>Miscellaneous Lists:</h6>
                      <div v-for="(mif, index) of createdMiscellaneous">
                        <div
                          class="flex"
                          style="padding: 1rem"
                        >
                          <div>{{ mif.title }}</div>
                          <div>{{ mif.amount }}</div>
                          <div>
                            <feather-icon
                              v-b-tooltip.hover.top="'Delete'"
                              icon="Trash2Icon"
                              size="16"
                              class="cursor-pointer mx-1"
                              @click="deleteMiscellaneous(index)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
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

                <div v-if="selectionMode != 'meshgrid'">
                  <h5 class="mt-1 mb-1 bg-light p-1">
                    Client/ Installation Details
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
                    <b-col lg="12">
                      <b-form-group
                        v-slot="{ ariaDescribedby }"
                        label="Multiple Locations?"
                      >
                        <b-form-radio
                          v-model="selected"
                          :aria-describedby="ariaDescribedby"
                          name="some-radios"
                          value="true"
                        >
                          YES
                        </b-form-radio>
                        <b-form-radio
                          v-model="selected"
                          :aria-describedby="ariaDescribedby"
                          name="some-radios"
                          value="false"
                        >
                          NO
                        </b-form-radio>
                      </b-form-group>
                    </b-col>

                    <div v-if="showAddAnotherLocationBtn === true">
                      <b-button
                        style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
                        @click="showAddressField"
                      >
                        Add Another Location
                      </b-button>
                    </div>
                    <b-col
                      v-if="showLocationInputFields === true"
                      lg="12"
                    >
                      <b-form-group>
                        <label
                          for="state"
                          class="mt-lg-0 mt-1 mb-lg-1 font-weight-bolder"
                        >
                          Address
                        </label>
                        <vue-google-autocomplete
                          id="map"
                          ref="address"
                          classname="form-control"
                          placeholder="Please type your current address"
                          country="ng"
                          @placechanged="getAddressData"
                        />
                      </b-form-group>
                    </b-col>
                    <b-col
                      v-if="showLocationInputFields === true"
                      lg="12"
                    >
                      <b-form-group>
                        <label
                          for="state"
                          class="mt-lg-0 mt-1 mb-lg-1 font-weight-bolder"
                        >
                          Full Address with Landmark
                        </label>
                        <b-form-input
                          id="project-name"
                          v-model="fullAddress"
                        />
                      </b-form-group>
                    </b-col>
                    <b-col
                      v-if="showLocationInputFields === true"
                      lg="3"
                    >
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
                    <b-col
                      v-if="showLocationInputFields === true"
                      lg="3"
                    >
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
                  <!-- </b-row> -->
                  </b-row>
                  <b-col lg="12">
                    <div v-if="selected === 'true'">
                      <b-button
                        v-if="!isAddingLocation"
                        type="submit"
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                        @click="addLocation"
                      >
                        ADD LOCATION
                      </b-button>
                      <b-button
                        v-if="isAddingLocation"
                        disabled
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                      >
                        Adding...
                      </b-button>
                      <b style="color: red; margin-left: 40px">{{ message }}</b>
                    </div>
                  </b-col>
                  <b-col>
                    <div
                      v-for="location in installationLocationsObject"
                      :key="location.id"
                    >
                      <b>Location </b> =>
                      {{ location.installationAddress.route }},
                      {{ location.installationAddress.locality }} =>
                      {{ location.installationAddress.country }}
                      <b
                        style="color: red; margin-left: 20px; cursor: pointer"
                        @click="removeLocation(location.id)"
                      >Remove
                      </b>
                    </div>
                  </b-col>
                </div>
                <b-row>
                  <b-col cols="12">
                    <div
                      class="d-flex float-lg-right justify-content-center mt-1"
                    >
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
                        @click="addProject"
                      >
                        Add Project
                      </b-button>
                      <b-button
                        v-if="isLoading"
                        disabled
                        style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                      >
                        Adding...
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
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import VueNumericInput from 'vue-numeric-input'
import axios from 'axios'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
    VueGoogleAutocomplete,
    VueNumericInput,
  },

  data() {
    return {
      misceMessage: null,
      showAddAnotherLocationBtn: false,
      showLocationInputFields: true,
      id: 0,
      installationLocationsObject: new Map(),
      installationAddress: '',
      installationLocations: [],
      message: '',
      selected: 'false',
      locationCountAboveOne: false,
      isAddingLocation: false,
      isLoading: false,
      client: null,
      product: null,
      projectName: null,
      fullAddress: null,
      projectType: null,
      systemModel: null,
      systemSize: 0,
      installationType: null,
      selectionMode: null,
      category: null,
      subCategory: null,
      price: null,
      numBatteries: 1,
      numPanels: 1,
      status: null,
      location: null,
      state: null,
      lga: null,
      productDiscount: null,
      description: null,
      showSingleSelectionCont: false,
      showProductSalesCont: false,
      accessoryType: [],
      accessory: null,
      categories: [],
      subCategories: [],
      allMiscellaneous: [],
      createdMiscellaneous: [],
      miscellaneous: null,
      miscellanousAmount: null,
      clients: [{ value: null, title: 'select a client' }],
      products: [{ value: null, title: 'select a product', id: null }],
      projectTypes: [{ value: null, title: 'select a project type' }],
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
      selectionModes: [
        { value: null, title: 'select product mode' },
        { value: 'product', title: 'Product Sales' },
        { value: 'single', title: 'Single Stock Sales' },
        { value: 'meshgrid', title: 'Mesh Grid' },
      ],
      installationTypes: [
        { value: null, title: 'select an installation type' },
        { value: 'Rooftop', title: 'Rooftop' },
        { value: 'Groundmount', title: 'Groundmount' },
      ],
      statusOptions: [{ value: null, title: 'select a project status' }],
      locations: [{ value: null, text: 'Please select a location' }],
      states: [{ value: null, title: 'Please select a state' }],
      address: [{ value: null, text: 'Please select an address' }],
      lgas: [{ value: null, title: 'Please select an lga' }],
      batteryType: null,
      batteryQuantity: 1,
      batteryTypes: [{ value: null, title: 'Please select a battery type' }],
      inverterType: null,
      inverterQuantity: 1,
      inverterTypes: [{ value: null, title: 'Please select an inverter type' }],
      solarPanelType: null,
      solarPanelQuantity: 1,
      solarPanelTypes: [
        { value: null, title: 'Please select a solar panel type' },
      ],
      miscellaneousField: [
        { key: 'miscellaneous_id', label: 'Name' },
        { key: 'amount', label: 'amount' },
        { key: 'title', label: 'title' },
      ],

      required,
    }
  },

  watch: {
    price(newValue) {
      const result = newValue
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, ',')
      // eslint-disable-next-line no-return-assign
      this.$nextTick(() => (this.price = result))
    },
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Projects / <span style="color: #C4C4C4">Add Project</span>'
    this.$refs.address.focus()
    this.getSubItemsMis(6)
    this.getMiscellaneous()
  },
  beforeDestroy() {
    localStorage.removeItem('products')
  },
  created() {
    this.getClients()
    this.getProjectTypes()
    this.getStatusOptions()
    this.getProducts()
    this.getSubItems()
    this.getStates()
    this.getItems()
    this.getSubItemsMis(6)
    this.getMiscellaneous()
  },
  methods: {
    deleteMiscellaneous(index) {
      this.createdMiscellaneous.splice(index, 1)
    },
    addMiscellaneous() {
      console.log(this.allMiscellaneous, 'djdsjsjks')

      this.misceMessage = ''
      if (!this.miscellaneous || !this.miscellanousAmount) {
        this.misceMessage = 'Select Options'
        return
      }
      const miscellan = this.allMiscellaneous.filter(
        e => e.value == this.miscellaneous,
      )
      this.createdMiscellaneous.push({
        miscellaneous_id: this.miscellaneous,
        amount: this.miscellanousAmount,
        title: miscellan[0].text,
      })
    },
    miscellaneousSelectChange(e, ki) {
      console.log(e, ki, 'sjsdjkdjs')
    },
    getMiscellaneous() {
      const token = localStorage.getItem('token')
      axios.defaults.headers.Authorization = `Bearer ${token}`

      const formData = new FormData()
      formData.append('title', this.miscellaneous)

      axios
        .get(`${process.env.VUE_APP_BASE_URL}/mis`)
        .then(response => {
          const { data } = response.data
          const output = []
          data.map(subItem => {
            const allMiscellaneous = { value: subItem.id, text: subItem.title }
            output.push(allMiscellaneous)
          })
          this.allMiscellaneous = output
        })
        .catch(err => {
          alert(err.response.data?.message)
          this.message = err.response.data?.message
          console.log(err)
        })
    },
    removeLocation(id) {
      this.installationLocationsObject = this.installationLocationsObject.filter(
        location => location.id !== id,
      )
    },
    getSubItemsMis(item) {
      const itemID = item
      this.$store
        .dispatch('inventory/getSubItemByID', { itemID })
        .then(res => {
          console.log(res, 'jsjksk')
          const { data } = res.data

          if (item === 2) {
            data.forEach(subItem => {
              const systemSize = { value: subItem.id, text: subItem.name }
              this.systemSizes.push(systemSize)
            })
          } else if (item === 1) {
            data.forEach(subItem => {
              const inverterType = { value: subItem.id, text: subItem.name }
              this.inverterTypes.push(inverterType)
            })
          } else if (item === 6) {
            data.forEach(subItem => {
              const accessoryType = { value: subItem.id, text: subItem.name }
              this.accessoryType.push(accessoryType)
            })
          } else {
            data.forEach(subItem => {
              const batteryType = { value: subItem.id, text: subItem.name }
              this.batteryTypes.push(batteryType)
            })
          }
        })
    },
    getItems() {
      this.$store.dispatch('inventory/getItems').then(res => {
        this.loaded = true
        const { data } = res.data
        console.log(data, 'sksjkskkjsd')
        // this.categories = data
        data.forEach(item => {
          const itemObj = { value: item.id, title: item.item }
          this.categories.push(itemObj)
        })
      })
    },
    getSubItemsSelected(item) {
      console.log(item, 'dkkklslklks')
      this.subLoaded = false
      this.currentItem = item
      const itemID = item
      this.$store
        .dispatch('inventory/getSubItemByID', { itemID })
        .then(res => {
          this.subLoaded = true
          const { data } = res.data

          console.log(data, 'resonsellle')
          // this.subItems = data;
          this.subCategories = []
          data.forEach(item => {
            const itemObj = { value: item.id, title: item.name }
            this.subCategories.push(itemObj)
          })
          this.totalRows = data.length
        })
    },
    onLanguageChange(selection) {
      console.log(selection, 'newssang', this.products)
      if (selection == 'product') {
        this.showProductSalesCont = true
        this.showSingleSelectionCont = false
        this.products = this.products.filter(product => product.product_type == 'default')
      } else if (selection == 'single') {
        this.showSingleSelectionCont = true
        this.showProductSalesCont = false
      } else if (selection == 'meshgrid') {
        this.showSingleSelectionCont = false
        this.showProductSalesCont = true
        this.products = this.products.filter(product => product.product_type == 'meshgrid')
      } else {
        this.showSingleSelectionCont = false
        this.showProductSalesCont = false
      }
    },
    onCategoryChange(item) {
      console.log(item, 'sskjdsjsjdkjskkjsj')
      this.getSubItemsSelected(item)
    },
    onsubCategoryChange(item) {
      console.log(item, 'itemmmmm')
    },

    /**
     * When the location found
     * @param {Object} addressData Data of the found location
     * @param {Object} placeResultData PlaceResult object
     * @param {String} id Input container ID
     */
    getAddressData(addressData, placeResultData, id) {
      console.log('changed')
      const addressLat = {
        lat: addressData.latitude,
        long: addressData.longitude,
        country: addressData.country,
        route: addressData.route,
        locality: addressData.locality,
      }
      this.installationAddress = addressLat
      console.log(addressData, placeResultData, id)
    },

    addLocation() {
      this.isAddingLocation = true
      this.message = ''

      if (
        this.state === ''
        || this.lga === ''
        || this.installationAddress === ''
      ) {
        this.message = 'fill all address fields'
      } else {
        const currentLocation = {
          id: this.id + 1,
          log: this.installationAddress.long,
          lat: this.installationAddress.lat,
          installationAddress: this.installationAddress,
          states_id: this.state,
          lgas_id: this.lga,
          address: this.fullAddress,
        }
        this.id += 1
        console.log(currentLocation, 'currentLocation')
        this.installationLocations = this.installationLocations.concat(
          currentLocation,
        )
        this.installationLocationsObject = this.installationLocations
        this.installationAddress = ''
        this.state = ''
        this.lga = ''
      }

      this.isAddingLocation = false
      this.showLocationInputFields = false
      this.selected = 'false'
      this.showAddAnotherLocationBtn = true
    },

    showAddressField() {
      this.showLocationInputFields = true
      this.showAddAnotherLocationBtn = false
      this.selected = 'true'
      this.fullAddress = ''
      this.isAddingLocation = false
    },

    addProject() {
      this.isLoading = true
      let clientAddress

      console.log(this.installationLocationsObject, 'dsjjs')
      if (Object.keys(this.installationLocationsObject).length !== 0) {
        console.log('yes')
        clientAddress = this.installationLocationsObject
      } else {
        console.log('no')
        clientAddress = [
          {
            log: this.installationAddress.long,
            lat: this.installationAddress.lat,
            states_id: this.state,
            lgas_id: this.lga,
            address: this.fullAddress,
          },
        ]
      }

      this.$refs.addProjectValidation.validate().then(success => {
        if (success) {
          const form = {
            address: JSON.stringify(clientAddress),
            projectName: this.projectName,
            projectType: this.projectType,
            systemSize: this.systemSize,
            solarPanelQuantity: this.solarPanelQuantity,
            batteryQuantity: this.batteryQuantity,
            description: this.description,
            installationType: this.installationType,
            project_selection_mode: this.selectionMode,
            status: this.status,
            client: this.client,
            lga: this.lga,
            product: this.product,
            price: parseFloat(this?.price?.replace(/,/g, '')),
            inverterQuantity: this.inverterQuantity,
            solarPanelType: this.solarPanelType,
            inverterType: this.inverterType,
            batteryType: this.batteryType,
            discount: this.productDiscount,
            miscellaneous: JSON.stringify(this.createdMiscellaneous),
          }
          this.$store
            .dispatch('project/addProject', { form })
            .then(res => {
              this.isLoading = false
              const { message } = res.data
              this.$router.push('/projects').then(() => {
                this.toast(
                  'Add Project',
                  'CheckCircleIcon',
                  message,
                  'success',
                )
              })
            })
            .catch(err => {
              console.log(err)
              this.isLoading = false
              this.toast(
                'Add Project',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Add Project Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
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
        this.$store
          .dispatch('client/getLGAsByStateID', { stateID })
          .then(innerRes => {
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
        console.log(data, 'dsjsjjskjs')
        localStorage.setItem('products', JSON.stringify(data))
        data.forEach(product => {
          const productobj = {
            value: product.id,
            title: product.productname,
            id: product.id,
            product_type: product.product_type,
          }
          this.products.push(productobj)
        })
        console.log(this.products, 'sdjksdjks')
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
      this.lgas = [{ value: null, title: 'Please select an lga' }]
      this.$store
        .dispatch('client/getLGAsByStateID', { stateID })
        .then(res => {
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
      console.log(products, 'sdjksjsk')
      products.every(product => {
        console.log(product, products, 'dsksdskj')
        if (product.id === this.product) {
          this.price = parseFloat(product.price).toString()
          this.batteryType = product.batteries_type
          this.batteryQuantity = parseFloat(product.numberofbatteries)
          this.inverterType = product.inverter_type
          this.inverterQuantity = parseFloat(product.numberofinverters)
          this.solarPanelType = product.panel_type
          this.solarPanelQuantity = parseFloat(product.numberofpanels)
          return false
        }
        return true
      })
    },
    getSubItems() {
      const itemIDs = [1, 2, 3]
      itemIDs.forEach(itemID => {
        this.$store
          .dispatch('inventory/getSubItemByID', { itemID })
          .then(res => {
            const { data } = res.data
            switch (itemID) {
              case 1: // battery
                data.forEach(subItem => {
                  const batteryType = {
                    value: subItem.id,
                    title: subItem.name,
                  }
                  this.batteryTypes.push(batteryType)
                })
                break
              case 2: // solar panel
                data.forEach(subItem => {
                  const solarPanelType = {
                    value: subItem.id,
                    title: subItem.name,
                  }
                  this.solarPanelTypes.push(solarPanelType)
                })
                break
              default:
                // inverter
                data.forEach(subItem => {
                  const inverterType = {
                    value: subItem.id,
                    title: subItem.name,
                  }
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
[dir] .b-form-spinbutton.form-control:focus,
[dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control,
[dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
.number-input {
  height: 38px;
}
</style>

<style lang="scss">
.flex {
  display: flex;
  column-gap: 1rem;
}
@import "@core/scss/vue/libs/vue-select.scss";
</style>
