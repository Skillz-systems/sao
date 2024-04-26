<template>
    <div class="app">
        <button @click="updateLabels()">Update Labels</button>

        <apexcharts type="donut" width="280" :options="chartOptions" :series="series" />
    </div>
</template>
  
<script>
import VueApexCharts from "vue-apexcharts"

export default {
    name: "Chart",
    components: {
        apexcharts: VueApexCharts
    },
    data: function () {
        return {
            series: [44, 55, 41, 17, 15],
            chartOptions: {
                labels: ["a", "b", "c", "d", "e"]
            }
        }
    },
    methods: {
        updateLabels: function () {
            this.chartOptions = {
                labels: ["q1", "w2", "e3", "r4", "t5"]
            }
        }
    }
}
</script>
  