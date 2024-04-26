<template>
  <div>
    <b-row class="mb-2">
      <b-col
        cols="12"
        md="6"
      >
        <b-row>
          <b-col lg="8">
            <b-input-group style="border-radius: 20px">
              <b-input-group-prepend
                is-text
                style="background-color: #F9F9F9"
              >
                <feather-icon icon="SearchIcon" />
              </b-input-group-prepend>
              <b-form-input
                v-model="filter"
                placeholder="Quick Search"
              />
            </b-input-group>
          </b-col>
          <b-col
            lg="4"
            class="d-flex mt-lg-0 mt-1 text-center"
          >
            <b-input-group class="input-group-merge">
              <b-input-group-prepend is-text>
                Location:
              </b-input-group-prepend>
              <b-form-select
                v-model="location"
                :options="locationOptions"
              />
            </b-input-group>
          </b-col>
        </b-row>
      </b-col>
      <b-col
        cols="12"
        md="6"
      >
        <b-row>
          <b-col lg="7" />
          <b-col lg="5">
            <b-button
              class="mt-lg-0 mt-1 btn-block"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
              @click="$router.push('/clients/add-client')"
            >
              ADD CLIENT
            </b-button>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="12">
        <b-card
          v-if="clients"
          no-body
          class="card-company-table"
        >
          <b-skeleton-table
            v-if="tableLoading"
            :rows="1"
            :columns="8"
            :table-props="{ bordered: false, striped: false }"
          />
          <b-table
            v-if="!tableLoading"
            :items="clients"
            responsive
            :fields="clientsField"
            :per-page="perPage"
            :current-page="currentPage"
            :filter="filter"
            show-empty
            empty-text="No clients found"
            class="mb-0"
            @filtered="onFiltered"
          >
            <template #cell(clientname)="data">
              <div class="text-nowrap">
                {{ data.item.clientname }}
              </div>
            </template>
            <template #cell(email)="data">
              <div class="text-nowrap">
                {{ data.item.email }}
              </div>
            </template>
            <template #cell(phone)="data">
              <div class="text-nowrap">
                {{ data.item.phone }}
              </div>
            </template>
            <template #cell(address)="data">
              <div class="text-nowrap">
                {{ data.item.address }}
              </div>
            </template>
            <template #cell(state)="data">
              <div class="text-nowrap">
                {{ data.item.state }}
              </div>
            </template>
            <template #cell(action)="data">
              <div class="text-nowrap text-center">
                <feather-icon
                  :id="`view-${data.item.id}`"
                  v-b-tooltip.hover.top="'Edit'"
                  icon="Edit3Icon"
                  size="16"
                  class="cursor-pointer"
                  @click="editClient(data.item.id)"
                />
              </div>
            </template>
          </b-table>
        </b-card>
        <div class="mx-2 mb-2 mt-1">
          <b-row>
            <b-col
              cols="12"
              sm="6"
              class="d-flex align-items-center justify-content-center justify-content-sm-start"
            >
              <span class="text-muted">There are {{ totalRows }} clients</span>
            </b-col>
            <b-col
              cols="12"
              sm="6"
              class="d-flex align-items-center justify-content-center justify-content-sm-end"
            >
              <b-pagination
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                class="mb-0 mt-1 mt-sm-0"
              />
            </b-col>
          </b-row>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// import vSelect from 'vue-select'

export default {
  components: {
    // vSelect,
  },
  data() {
    return {
      tableLoading: true,
      numBatteries: 1,
      numPanels: 1,
      perPage: 5,
      perPageOptions: [5, 10, 25, 100],
      clients: [],
      location: null,
      locationOptions: [
        { value: null, text: 'All' },
        { value: 1, text: 'Abuja' },
        { value: 2, text: 'Akwa-Ibom' },
        { value: 3, text: 'Adamawa' },
        { value: 4, text: 'Bayelsa' },
        { value: 5, text: 'Lagos' },
      ],
      filter: null,
      clientsField: [
        { key: 'clientname', label: 'Client Name' },
        { key: 'email', label: 'Email' },
        { key: 'phone', label: 'Phone' },
        { key: 'address', label: 'Address' },
        { key: 'clienttype', label: 'Client Type' },
        { key: 'state', label: 'Location' },
        { key: 'load', label: 'Load' },
        { key: 'action', label: 'Actions', thStyle: { width: '10%', textAlign: 'center' } },
      ],
      totalRows: 0,
      currentPage: 1,
    }
  },
  computed: {
  },
  created() {
    this.getClients()
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Clients'
  },
  methods: {
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    getClients() {
      this.$store.dispatch('client/getClients').then(res => {
        const { data } = res.data
        this.clients = data
        this.totalRows = this.clients.length
        this.tableLoading = false
      })
    },
    editClient(clientID) {
      this.$router.push('/clients/edit-client').then(() => {
        localStorage.setItem('clientID', clientID)
      })
    },
  },
}
</script>

<style lang="scss">
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
.per-page-selector {
  width: 70px;
}
.invoice-filter-select {
  min-width: 190px;
  ::v-deep .vs__selected-options {
    flex-wrap: nowrap;
  }
  ::v-deep .vs__selected {
    width: 100px;
  }
}
[dir] .custom-select:focus {
  border-color: #4D6D8A;
}
.page-item.active .page-link {
  background-color: #4D6D8A;
  border-color: #4D6D8A;
}
.page-item .page-link:hover {
  color: #4D6D8A;
}
</style>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
