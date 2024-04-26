<template>
  <div>
    <b-row>
      <b-col lg="10">
        <b-row>
          <b-col cols="12">
            <b-card
              class="dashboard-img-2"
            >
              <b-media class="p-2 text-white">
                <h2 class="text-white">
                  Hi {{ firstname }}, Good morning
                </h2>
                <small>
                  This is your Bartum Energy service dashboard
                </small>
              </b-media>
            </b-card>
          </b-col>
        </b-row>
        <b-row>
          <b-col
            lg="4"
          >
            <b-card>
              <b-media class="pl-1">
                <template #aside>
                  <b-img
                    :src="require('@/assets/images/icons/dashboard-projects.svg')"
                  />
                </template>
                <h2 class="mt-0">
                  {{ numProjects }}
                </h2>
                <small>
                  All Projects
                </small>
              </b-media>
            </b-card>
          </b-col>
          <b-col
            lg="4"
          >
            <b-card>
              <b-media class="pl-1">
                <template #aside>
                  <b-img
                    :src="require('@/assets/images/icons/dashboard-load.svg')"
                  />
                </template>
                <h2 class="mt-0">
                  {{ load }}
                </h2>
                <small>
                  Current Load
                </small>
              </b-media>
            </b-card>
          </b-col>
          <b-col
            lg="4"
          >
            <b-card>
              <b-media class="pl-1">
                <template #aside>
                  <b-img
                    :src="require('@/assets/images/icons/dashboard-capacity.svg')"
                  />
                </template>
                <h2 class="mt-0">
                  {{ currentProduct }}
                </h2>
                <small>
                  Current Product
                </small>
              </b-media>
            </b-card>
          </b-col>
        </b-row>
      </b-col>
      <b-col lg="2">
        <b-card
          no-body
          style="background: linear-gradient(180.48deg, #62B6E1 0.41%, rgba(98, 182, 225, 0.35) 166.35%);"
        >
          <div style="margin-top: 1em; display: flex; flex-direction: column">
            <div class="text-center">
              <p
                class="text-white"
                style="font-size: 11px"
              >
                Tuesday 3 August <br>
                <b-img
                  :src="require('@/assets/images/icons/weather-icon.svg')"
                  alt="logo"
                />
                Abuja
              </p>
            </div>
            <div class="text-center">
              <b-img
                width="100"
                fluid
                :src="require('@/assets/images/icons/weather-image.svg')"
                alt="logo"
                class="mb-2"
              />
            </div>
            <div class="text-center">
              <h5 class="mb-1 text-white">
                Pretty Sunny
              </h5>
              <h1 class="display-2 text-white ml-n1">
                27<sup>&#176;</sup>
              </h1>
            </div>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col lg="10">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">
            Projects
          </h5>
          <small>
            <b-link
              class="text-secondary"
              style="text-decoration: underline"
              to="/projects"
            >
              View all projects
            </b-link>
          </small>
        </div>
        <b-card
          no-body
          class="card-company-table"
        >
          <b-table
            :items="clientsTable"
            responsive
            :fields="clientsField"
            class="mb-0"
          >
            <template #cell(name)="data">
              <div class="text-nowrap">
                {{ data.item.name }}
              </div>
              <!--              &#8358;{{ data.item.total.toLocaleString() }}-->
            </template>
            <template #cell(product)="data">
              <div class="text-nowrap">
                {{ data.item.productObject.productname }}
              </div>
            </template>
            <template #cell(address)="data">
              <div class="text-nowrap">
                {{ data.item.address }}
              </div>
            </template>
            <template #cell(phone)="data">
              <div class="text-nowrap">
                {{ data.item.phone }}
              </div>
            </template>
          </b-table>
        </b-card>
      </b-col>
      <b-col lg="2">
        <b-card
          class="dashboard-img-1"
        >
          <b-img
            center
            :src="require('@/assets/images/logo/dashboard-logo.svg')"
          />
          <b-img
            class="mt-3"
            center
            :src="require('@/assets/images/logo/dashboard-tag-line.svg')"
          />
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      isLoading: true,
      load: 0,
      numProjects: 0,
      currentProduct: 'None',
      clientsField: [
        { key: 'projectname', label: 'Project' },
        { key: 'product', label: 'Product' },
        { key: 'projecttype', label: 'System Type' },
        { key: 'installationtype', label: 'Installation Type' },
        { key: 'numberofpanels', label: 'Panels' },
        { key: 'numberofbatteries', label: 'Batteries' },
        { key: 'status', label: 'Status' },
      ],
      clientsTable: [
      ],
    }
  },
  computed: {
    ...mapGetters({
      firstname: 'auth/getFirstname',
    }),
  },
  created() {
    this.getClientDashboard()
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Overview'
  },
  methods: {
    getClientDashboard() {
      this.$store.dispatch('client/getClientDashboard').then(res => {
        const { data } = res.data
        this.load = data.load
        this.numProjects = data.projects_count
        if (data.current_product) {
          this.currentProduct = data.current_product.projectname
        }
        const { projects } = data
        this.clientsTable = projects
      })
    },
  },
}
</script>

<style>
/* .dashboard-img-2 {
  background-image: url("../../assets/images/pages/dashboard-img-2.svg");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
.dashboard-img-1 {
  background-image: url("../../assets/images/pages/dashboard-img-1.svg");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
} */
</style>
