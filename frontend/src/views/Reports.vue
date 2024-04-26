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
      <b-col lg="4">
        <b-card no-body>
          <b-card-header class="mt-n1">
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Total Installed Systems
            </h4>
          </b-card-header>
          <div style="display: flex; justify-content: center; align-items: center; padding: 20px 0 62px 0">
            <b-card-text>
              <h1 class="display-3">
                {{ installed }}
              </h1>
              <h4 class="text-center">
                Units
              </h4>
            </b-card-text>
          </div>
          <div>
            <p
              class="float-right"
              style="padding-right: 21px"
            >
              <feather-icon
                icon="TrendingUpIcon"
                size="20"
                class="text-success"
                style="margin-right: 0.5em; margin-top: -0.6em"
              />
              <span class="font-large-1 font-weight-bolder">3</span>
              <small>%</small>
            </p>
          </div>
        </b-card>
      </b-col>
      <b-col lg="8">
        <b-card no-body>
          <b-card-header class="mt-n1">
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Installation By Location
            </h4>
            <b-dropdown
              right
              :text="currentState"
              size="sm"
              class="budget-dropdown"
              variant="outline-secondary"
            >
              <vue-perfect-scrollbar
                :settings="perfectScrollbarSettings"
                class="scroll-area"
              >
                <b-dropdown-item
                  v-for="state in states"
                  :key="state.stateid"
                  @click="changeState(state)"
                >
                  {{ state.sname }}
                </b-dropdown-item>
              </vue-perfect-scrollbar>
            </b-dropdown>
          </b-card-header>
          <b-row>
            <b-col lg="6">
              <b-spinner
                v-if="loadingChart"
                style="width: 3rem; height: 3rem;"
                variant="primary"
                label="Loading..." type="grow"
                class="mb-2 ml-5"
              />
              <vue-apex-charts
                v-if="!loadingChart && !nodata"
                type="pie"
                height="300"
                class="mt-0 mb-2"
                :options="locationPie.chartOptions"
                :series="locationPie.series"
              />
              <b-card-text
                v-if="!loadingChart && nodata"
                class="ml-lg-5 mb-2 px-lg-0 px-2"
              >
                Sorry, there's currently no sales data available for {{ currentState }}
              </b-card-text>
            </b-col>
            <b-col
              lg="6"
              class="pl-lg-0 pl-3 pb-lg-0 pb-2"
            >
              <b-row>
                <b-col cols="6">
                  <!--                  <div class="series-info">-->
                  <!--                    <feather-icon-->
                  <!--                      icon="CircleIcon"-->
                  <!--                      size="16"-->
                  <!--                      class="mr-50"-->
                  <!--                      style="color: #4D6D8A; margin-top: -0.3em"-->
                  <!--                    />-->
                  <!--                    <span class="font-weight-bolder">Wuse 2</span>-->
                  <!--                  </div>-->
                </b-col>
              </b-row>
            </b-col>
          </b-row>
        </b-card>
      </b-col>
    </b-row>
    <b-row v-if="loaded">
      <b-col lg="9">
        <b-card no-body>
          <b-card-header class="mt-n1">
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Sales Overview
            </h4>
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
                variant="dark"
                label="Loading..." type="grow"
                class="mb-2 ml-5"
              />
              <vue-apex-charts
                v-if="!loadingLine"
                type="line"
                height="300"
                :options="salesChart.chartOptions"
                :series="salesChart.series"
              />
            </b-col>
          </b-row>
        </b-card>
      </b-col>
      <b-col lg="3">
        <b-card no-body>
          <b-card-header class="mt-n1">
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Top Selling Products
            </h4>
          </b-card-header>
          <b-card-body>
            <b-table
              stacked
              :items="products"
            />
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'

export default {
  components: {
    VuePerfectScrollbar,
    VueApexCharts,
  },
  data() {
    return {
      loadingChart: false,
      loadingLine: false,
      loaded: false,
      nodata: false,
      perfectScrollbarSettings: {
        maxScrollbarLength: 60,
      },
      currentState: 'Federal Capital Territory',
      currentLine: 'Monthly',
      installed: 0,
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
      salesChart: {
        series: [
          {
            data: [],
          },
        ],
        chartOptions: {
          chart: {
            zoom: {
              enabled: false,
            },
            toolbar: {
              show: false,
            },
          },
          markers: {
            strokeWidth: 3,
            strokeOpacity: 1,
            strokeColors: ['#c4c4c4'],
            colors: ['#2C3E50'],
          },
          colors: ['#2C3E50'],
          dataLabels: {
            enabled: false,
          },
          stroke: {
            curve: 'straight',
          },
          grid: {
            xaxis: {
              lines: {
                show: true,
              },
            },
          },
          tooltip: {
            // custom(data) {
            //   return `${'<div class="px-1 py-50"><span>'}${
            //     data.series[data.seriesIndex][data.dataPointIndex]
            //   }%</span></div>`
            // },
          },
          xaxis: {
            categories: [],
          },
          yaxis: {
            // opposite: isRtl,
          },
        },
      },
      products: [
        {},
      ],
      states: [],
    }
  },
  created() {
    this.getReports()
    this.getStates()
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Reports'
  },
  methods: {
    getReports() {
      this.$store.dispatch('report/getReports').then(res => {
        const { data } = res.data
        this.installed = data.total_intalled_systems
        const pieChart = data.pie_chat_data
        const lineGraph = data.line_graph_data
        const topSelling = data.top_selling
        if (pieChart.length < 1) {
          this.nodata = true
          this.locationPie.chartOptions.legend.show = false
        } else {
          this.nodata = false
          this.locationPie.chartOptions.legend.show = true
          pieChart.forEach(pie => {
            this.locationPie.series.push(pie.Series)
            this.locationPie.chartOptions.labels.push(pie.Label)
          })
        }
        lineGraph.forEach(line => {
          this.salesChart.series[0].data.push(line.Series)
          this.salesChart.chartOptions.xaxis.categories.push(line.Label)
        })
        topSelling.forEach(product => {
          const productName = product.productname
          this.products[0] = { ...this.products[0], [productName]: `${product.salesCount} units` }
        })
        this.loaded = true
      })
    },
    getStates() {
      this.$store.dispatch('client/getStates').then(res => {
        const { data } = res.data
        this.states = data
      })
    },
    changeState(state) {
      this.loadingChart = true
      this.currentState = state.sname
      const stateID = state.stateid
      this.$store.dispatch('report/getSalesByStateID', { stateID }).then(res => {
        const { data } = res.data
        this.locationPie.series = []
        this.locationPie.chartOptions.labels = []
        if (data.length < 1) {
          this.nodata = true
          this.locationPie.chartOptions.legend.show = false
        } else {
          this.nodata = false
          this.locationPie.chartOptions.legend.show = true
          data.forEach(pie => {
            this.locationPie.series.push(pie.Series)
            this.locationPie.chartOptions.labels.push(pie.Label)
          })
        }
        this.loadingChart = false
      })
    },
    changeLine(newLine) {
      this.currentLine = newLine
      this.loadingLine = true
      switch (newLine) {
        case 'Weekly':
          this.$store.dispatch('report/getWeeklyLine').then(res => {
            const { data } = res.data
            this.salesChart.series[0].data = []
            this.salesChart.chartOptions.xaxis.categories = []
            data.forEach(line => {
              this.salesChart.series[0].data.push(line.Series)
              this.salesChart.chartOptions.xaxis.categories.push(line.Label)
            })
            this.loadingLine = false
          })
          break
        case 'Yearly':
          this.$store.dispatch('report/getYearlyLine').then(res => {
            const { data } = res.data
            this.salesChart.series[0].data = []
            this.salesChart.chartOptions.xaxis.categories = []
            data.forEach(line => {
              this.salesChart.series[0].data.push(line.Series)
              this.salesChart.chartOptions.xaxis.categories.push(line.Label)
            })
            this.loadingLine = false
          })
          break
        default:
          this.$store.dispatch('report/getMonthlyLine').then(res => {
            const { data } = res.data
            this.salesChart.series[0].data = []
            this.salesChart.chartOptions.xaxis.categories = []
            data.forEach(line => {
              this.salesChart.series[0].data.push(line.Series)
              this.salesChart.chartOptions.xaxis.categories.push(line.Label)
            })
            this.loadingLine = false
          })
      }
    },
  },
}
</script>

<style scoped>
.scroll-area {
  position: relative;
  margin: auto;
  width: 400px;
  height: 40vh;
  max-height: 40vh;
}
</style>
