<template>
  <div>
    <b-spinner
      v-if="!loaded"
      style="width: 3rem; height: 3rem;"
      variant="dark"
      label="Loading..." type="grow"
      class="text-center"
    />
    <div v-if="loaded">
      <b-card>
        <b-row class="counts">
          <!-- <b-card></b-card> -->

          <b-col xl="4">
            <b-card>
              <b-media class="counts-icon-details">
                <template
                  #aside
                  class=""
                >
                  <div
                    class="icon-cont"
                    style="background-color: #D3FFE7;"
                  >
                    <b-img :src="require('@/assets/images/icons/codicon_project.png')" />
                  </div>

                </template>
                <div class="count-title">
                  Projects
                </div>
                <h2 class="mt-0">
                  {{ totalProjects  }}
                </h2>
                <small>
                  Total Number of Projects
                </small>
              </b-media>
            </b-card>
          </b-col>
          <b-col xl="4">
            <b-card>
              <b-media class="counts-icon-details">
                <template #aside>
                  <div
                    class="icon-cont"
                    style="background-color: #CAF1FF;"
                  >
                    <b-img :src="require('@/assets/images/icons/wallet-money.svg')" />
                  </div>

                </template>
                <div class="count-title">
                  Total Clients
                </div>
                <h2 class="mt-0">
                  {{ totalClients }}
                </h2>
                <small>
                  Total Number of Clients
                </small>
              </b-media>
            </b-card>
          </b-col>
          <!-- <b-col
          xl="3"
        >
          <b-card>
            <b-media>
              <template #aside>
                <b-img
                  :src="require('@/assets/images/icons/dashboard-locations.svg')"
                />
              </template>
              <h2 class="mt-0">
                {{ totalLocations }}
              </h2>
              <small>
                Total Locations Covered
              </small>
            </b-media>
          </b-card>
        </b-col> -->
          <b-col xl="4">
            <b-card>
              <b-media class="counts-icon-details">
                <template #aside>
                  <div
                    class="icon-cont"
                    style="background-color: #FFA3CF;"
                  >
                    <b-img :src="require('@/assets/images/icons/dashboard-sales.png')" />
                  </div>

                </template>
                <div class="count-title">
                  Total Sales
                </div>
                <h2 class="mt-0">
                  {{ totalCapacity }}
                </h2>
                <small>
                  Total Sales
                </small>
              </b-media>
            </b-card>
          </b-col>
        </b-row>
      </b-card>
      <b-row
        class="mt-1"
      >
        <b-col
          xl="8"
          style="height: 100%"
        >
          <b-card style="height: 100%">

            <b-card-header class="mt-n1">
              <div> <h4
                      class="card-tile"
                      style="color: #000000; margin-top: 0.5em"
                    >
                      Overview
                    </h4>
                <div>Earnings</div>
              </div>

              <b-dropdown
                right
                :text="currentLine"
                size="sm"
                class="budget-dropdown"
                variant="outline-secondary"
              >
                <b-dropdown-item
                  @click="changeLine('Weekly')"
                >
                  Weekly
                </b-dropdown-item>
                <b-dropdown-item
                  @click="changeLine('Monthly')"
                >
                  Monthly
                </b-dropdown-item>
                <b-dropdown-item
                  @click="changeLine('Yearly')"
                >
                  Yearly
                </b-dropdown-item>
              </b-dropdown>
            </b-card-header>
            <b-row>
              <b-col lg="12">
                <b-spinner
                  v-if="loadingLine"
                  style="width: 3rem; height: 3rem;"
                  variant="primary"
                  label="Loading..." type="grow"
                  class="mb-2 ml-5"
                />
                <VueApexCharts
                  width="100%"
                  type="bar"
                  :options="barChartOptions"
                  :series="barSeries"
                />
              <!-- <VueApexCharts width="380" type="donut" :options="chartOptions" :series="series"/> -->
              </b-col>
            </b-row>

          </b-card>

        </b-col>
        <b-col
          xl="4"
        >
          <!-- <canvas id="planet-chart"></canvas> -->
          <b-card style="height: 95%">
            <!-- <b-card-header class="mt-n1"> -->
            <div
              class="mb-20"
              style="margin-bottom: 100px"
            >
              <h4
                style="color: #000000; margin-top: 0.5em"
                class="card-tile"
              >
                Customers
              </h4>
              <div>Customers that buy products</div></div>

            <!-- </b-card-header> -->

            <VueApexCharts
              width="100%"
              type="donut"
              :options="chartOptions"
              :series="series"
            /></b-card>

        </b-col>
      </b-row>

      </b-card>
      </b-col>
      </b-row>
      <b-card>
        <b-row>
          <b-col xl="12">
            <div class="d-flex w-100 justify-content-between">
              <div
                style="margin-bottom: 40px;"
                class="best_selling"
              >
                <div class="card-tile">
                  Best Selling Products
                </div>
                <div class="best_selling_search">
                  <div>
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
                    </b-input-group></div>
                  <div>
                    <b-dropdown
                      right
                      :text="bestSellingCurrentLine"

                      class="budget-dropdown"
                      variant="outline-secondary"
                    >
                      <b-dropdown-item
                        @click="changeLine('Last 30 days')"
                      >
                        Last 30 days
                      </b-dropdown-item>
                      <b-dropdown-item
                        @click="changeLine('Last 15 days')"
                      >
                        Last 15 days
                      </b-dropdown-item>
                      <b-dropdown-item
                        @click="changeLine('Last 5 days')"
                      >
                        Last 5 days
                      </b-dropdown-item>
                    </b-dropdown>
                  </div>
                </div>
              </div>

              <!-- <small>
                <b-link class="text-secondary" style="text-decoration: underline" to="/clients">
                  View all clients
                </b-link>
              </small> -->
              <div>
                <div />
              </div>
            </div>

            <b-card
              v-if="clientsTable"
              no-body
              class="card-company-table"
            >

              <b-table
                :items="clientsTable"
                responsive
                :fields="clientsField"
                class="mb-0"
                striped
                hover
              >
                <template #cell(clientName)="data">
                  <div class="text-nowrap">
                    <div class="table-name-col">
                      <div  style="width: 100px">
                        <img
                          class="package_image"
                          style="max-width:200px;"
                          alt="Vue logo"
                          :src="`./assets/img/${data.item.image}`"
                        ></div>
                      <div>
                        <div class="product-name">
                          {{ data.item.clientName }}
                        </div>
                        <div>{{ data.item.description }}</div>
                      </div>
                    </div>
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
          <!-- <b-col xl="2">
            <b-card class="dashboard-img-1" style="margin-top: 31px">
              <b-img center :src="require('@/assets/images/logo/dashboard-logo.svg')" />
              <b-img class="mt-3" center :src="require('@/assets/images/logo/dashboard-tag-line.svg')" />
            </b-card>
          </b-col> -->
        </b-row>
      </b-card>
    </div>
  </div>
</template>

<script>
// import Chart from 'chart.js'
import VueApexCharts from 'vue-apexcharts'
import planetChartData from './chartData.js'
import Chart from './Chart'

export default {
  // name: 'PlanetChart',
  components: {
    VueApexCharts,
    Chart,
  },
  data() {
    return {
      currentLine: 'Monthly',
      bestSellingCurrentLine: 'Last 30 days',
      barChartOptions: {
        chart: {
          id: 'vuechart-example',
        },
        xaxis: {
          categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
        },
      },
      barSeries: [{
        name: 'series-1',
        data: [30, 40, 35, 50, 49, 60, 70, 91],
      }],
      series: [44, 55, 41, 17, 15],
      chartOptions: {
        labels: ['a', 'b', 'c', 'd', 'e'],
      },
      planetChartData,
      loaded: false,
      totalClients: 0,
      totalProjects: 0,
      totalLocations: 0,
      totalCapacity: 0,
      locationPie: {
        series: [],
        chartOptions: {
          chart: {
            toolbar: {
              show: false,
            },
          },
          labels: [],
          dataLabels: {
            enabled: false,
          },
          legend: { show: true },
          stroke: {
            width: 1,
          },
          colors: ['#4D6D8A', '#E06294', '#7F3F98', '#BD5B08', '#8AEBF1', '#FF0404', '#FFD102', '#000000', '#EEB7E9', '#0D6A70'],
        },
      },
      productsField: [
        { key: 'productname', label: 'Product Model' },
        { key: 'salesCount', label: 'Qty' },
        // { key: 'price', label: 'Price' },
        // { key: 'total', label: 'Total' },
      ],
      clientsField: [
        { key: 'clientName', label: 'Product Name' },
        { key: 'size', label: 'Location Size' },
        { key: 'sold', label: 'Sold' },
        { key: 'salesAmount', label: 'Total Sales Amount' },
        // { key: 'clienttype', label: 'Client Type' },
        // { key: 'Product Name', label: 'Name' },
        // { key: 'Location Size', label: 'Address' },
        // { key: 'Sold', label: 'Phone' },
        // { key: 'Total Sales Amount', label: 'Email' },
        // { key: 'clienttype',
      ],
      productsTable: [],
      clientsTable: [
        {
          clientName: 'kit-S',
          thClass: 'bg-white text-dark',
          size: 'Small',
          sold: '40',
          salesAmount: 40,
          description: ' when an unknown printer took a galley of type',
          image: 'panel-s.jpeg',
        },
        {
          clientName: 'kit-M',
          size: 'Medium',
          sold: '40',
          salesAmount: 40,
          description: 'also the leap into electronic typesetting, remaining essentially unchanged.',
          image: 'panel-m.jpeg',
        },
        {
          clientName: 'kit-B',
          size: 'Large',
          sold: '40',
          salesAmount: 40,
          description: ' when an unknown printer scrambled it to make a type sp',
          image: 'panel-b.jpeg',
        },
      ],
    }
  },
  computed: {
    // getImage() {
    //     let imgSrc = "";
    //     try {
    //         imgSrc = require(`../assets/weather-widget-icons/ww-icon-${this.weatherForecast.main.toLowerCase()}.svg`);
    //     } catch (error) {
    //         console.error(`Image '../assets/weather-widget-icons/ww-icon-${this.weatherForecast.main.toLowerCase()}.svg' not found!`);
    //     }
    //     return imgSrc;
    // }
    // dogImage() {
    //   // if (!this.selectedDog) {
    //   //   return
    //   // }

    //   // const fileName = this.selectedDog.toLowerCase()

    //   return require('../../assets/images/icons/solar-p.png') // the module request
    // },
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Overview'
    const ctx = document.getElementById('planet-chart')
    new Chart(ctx, this.planetChartData)
  },
  created() {
    this.getAdminDashboard()
  },
  methods: {
    getImgUrl(pet) {
      console.log(`./../assets/images/pages/${pet}`, 'ooooooooooooooooooooooooooooooooooooooo')
      // var images = require.context('../../assets/pages', false, /\.png$/)
      // return images('./' + pet + ".png")
      return `./../assets/images/pages/${pet}`
    },
    dogImage(file) {
      console.log(file, '-=-=')
      // if (!this.selectedDog) {
      //   return
      // }

      // const fileName = this.selectedDog.toLowerCase()

      return require('../../assets/images/icons/solar-p.png') // the module request
    },
    getAdminDashboard() {
      this.$store.dispatch('auth/getAdminDashboard').then(res => {
        const { data } = res.data
        console.log(data, 'dsjsdjs')

        this.totalClients = data.total_clients
        this.totalProjects = data.total_projects
        this.totalLocations = data.total_locations
        this.totalCapacity = data.total_installed_capacities
        this.productsTable = data.bestselling
        // this.clientsTable = data.topclient
        this.loaded = true
      })
    },
  },
}
</script>

<style>
.dashboard-img-1 {
  background-image: url("../../assets/images/pages/dashboard-img-1.svg");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

.counts {
  background: #ffffff
}

.b-table th {
  background-color: #ffffff !important;
}
.card-tile{
  font-weight: 600;
font-size: 22px;
line-height: 33px;
/* identical to box height */

letter-spacing: -0.01em;

color: #000000;
}

.package_image{
  width: 100%;
  height: 200%;
}

.best_selling {
  /* font-weight: 800; */
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 3rem;
}
.icon-cont{

  border-radius: 100px;
  height: 96px;
  width: 96px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.count-title{
  color: #ACACAC;
}
.table-name-col{
  display: flex;
  column-gap: 20px;
  align-items: center;
}
.product-name{
  /* font-family: 'Poppins'; */
font-style: normal;
font-weight: 600;
font-size: 18px;
line-height: 27px;
/* identical to box height */

letter-spacing: -0.01em;

color: #000000;
}
.best_selling_search{
  display: flex;
  column-gap: 20px;
  align-items: center;
}
.counts-icon-details{
  /* border: 2px solid red; */
  display: flex;
  align-items: center;

}
</style>
