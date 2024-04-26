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
          <validation-observer ref="editClientValidation">
            <b-form @submit.prevent>
              <b-card-header class="mt-n1">
                <h4 style="color: #4D6D8A; margin-top: 0.5em">
                  Edit Connection
                </h4>
              </b-card-header>
              <b-row style="padding: 0 21px 20px 21px;">
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
                        Connection Name
                      </label>
                      <b-form-input
                        id="client-name"
                        v-model="clientName"
                        placeholder="E.g. John Doe"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col lg="6">
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="client type"
                      rules="required"
                    >
                      <label
                        for="client-type"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Connection Type
                      </label>
                      <b-form-select
                        id="client-type"
                        v-model="clientType"
                        :options="clientTypes"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col lg="6">
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="phone number"
                      rules="required"
                    >
                      <label
                        for="phone"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Phone No.
                      </label>
                      <b-form-input
                        id="phone"
                        v-model="phone"
                        placeholder="E.g. (+234)8245 789 7988"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col lg="6">
                  <b-form-group>
                    <label
                      for="email"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Email
                    </label>
                    <b-form-input
                      id="email"
                      v-model="email"
                      placeholder="E.g. johndoe@email.com"
                      disabled
                    />
                  </b-form-group>
                </b-col>
                <b-col lg="6">
                  <b-row>
                    <b-col lg="6">
                      <b-form-group>
                        <validation-provider
                          #default="{ errors }"
                          name="load"
                          rules="required"
                        >
                          <label
                            for="load"
                            class="mb-lg-1 font-weight-bolder"
                          >
                            Load
                          </label>
                          <b-form-input
                            id="load"
                            v-model="load"
                            placeholder="E.g. 100kWh"
                          />
                          <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                      </b-form-group>
                    </b-col>
                    <b-col
                      lg="6"
                      class="mt-lg-0 mt-1"
                    >
                      <b-form-group>
                        <label
                          for="house-size"
                          class="mb-lg-1 font-weight-bolder"
                        >
                          House Size
                        </label>
                        <b-form-select
                          id="house-size"
                          v-model="houseSize"
                          :options="houseSizes"
                        />
                      </b-form-group>
                    </b-col>
                  </b-row>
                </b-col>
                <b-col
                  lg="6"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="address"
                      rules="required"
                    >
                      <label
                        for="address"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Address
                      </label>
                      <b-form-input
                        id="address"
                        v-model="address"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col lg="6">
                  <b-form-group>
                    <label
                      for="state"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      State
                    </label>
                    <b-form-select
                      id="state"
                      v-model="state"
                      :options="states"
                      @change="getLGAsByStateID()"
                    />
                  </b-form-group>
                </b-col>
                <b-col lg="6">
                  <b-form-group>
                    <label
                      for="lga"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      LGA
                    </label>
                    <b-form-select
                      id="lga"
                      v-model="lga"
                      :options="lgas"
                    />
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <div class="d-flex float-right">
                    <b-button
                      style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
                      @click="$router.push('/projects/view-mesh-project')"
                    >
                      Cancel
                    </b-button>
                    <b-button
                      v-if="!isLoading"
                      type="submit"
                      style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                      @click="editClient"
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
// import { mapGetters } from 'vuex'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      loaded: false,
      isLoading: false,
      phone: null,
      email: null,
      load: null,
      clientName: null,
      clientType: null,
      houseSize: null,
      address: null,
      state: null,
      lga: null,
      clientTypes: [
        { value: null, text: 'Please select a client type' },
      ],
      houseSizes: [
        { value: null, text: 'Please select a house size' },
      ],
      states: [
        { value: null, text: 'Please select a state' },
      ],
      lgas: [
        { value: null, text: 'Please select an lga' },
      ],
      required,
    }
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Clients / <span style="color: #C4C4C4">Edit Client</span>'
  },
  beforeDestroy() {
    // localStorage.removeItem('projectID')
  },
  created() {
    this.getConnection()
    this.getClientTypes()
    this.getHouseSizes()
    this.getStates()
  },
  methods: {
    editClient() {
      this.isLoading = true
      const clientID = localStorage.getItem('clientID')
      this.$refs.editClientValidation.validate().then(success => {
        if (success) {
          const form = {
            clientID,
            phone: this.phone,
            clientName: this.clientName,
            clientType: this.clientType,
            load: this.load,
            houseSize: this.houseSize,
            address: this.address,
            lga: this.lga,
            state: this.state,
          }
          this.$store.dispatch('client/editClient', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.$router.push('/clients').then(() => {
              this.toast('Edit Client', 'CheckCircleIcon', message, 'success')
            })
          }).catch(err => {
            this.isLoading = false
            this.toast('Edit Client', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Edit Client Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
    getClientTypes() {
      this.$store.dispatch('client/getClientTypes').then(res => {
        const { data } = res.data
        data.forEach(type => {
          const typeobj = { value: type, text: type }
          this.clientTypes.push(typeobj)
        })
      })
    },
    getHouseSizes() {
      this.$store.dispatch('client/getHouseSizes').then(res => {
        const { data } = res.data
        data.forEach(size => {
          const sizeobj = { value: size, text: size }
          this.houseSizes.push(sizeobj)
        })
      })
    },
    getStates() {
      this.$store.dispatch('client/getStates').then(res => {
        const { data } = res.data
        data.forEach(state => {
          const stateobj = { value: state.stateid, text: state.sname }
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
          const lgaobj = { value: lga.lgaid, text: lga.lganame }
          this.lgas.push(lgaobj)
        })
      })
    },
    getConnection() {
      const clientID = localStorage.getItem('connectionID')
      this.$store.dispatch('client/getClientByID', { clientID }).then(res => {
        const { data } = res.data
        this.email = data.email
        this.phone = data.phone
        this.clientName = data.clientname
        this.clientType = data.clienttype
        this.load = data.load
        this.houseSize = data.housesize
        this.address = data.address
        this.lga = data.lgaid
        this.state = data.stateid
        this.getLGAsByStateID()
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
</style>
