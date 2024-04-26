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
            <label style="padding: 10px 0">Show</label>
            <b-form-select
              v-model="perPage"
              :options="perPageOptions"
              class="per-page-selector d-inline-block ml-50 mr-50"
            />
            <label style="padding: 10px 0">entries</label>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="12">
        <b-card
          no-body
          class="card-company-table"
        >
          <b-table
            ref="inventory-table"
            :items="getAvailableStock"
            responsive
            :fields="availableStockFields"
            :per-page="perPage"
            :current-page="currentPage"
            :filter="filter"
            show-empty
            empty-text="Loading available stock..."
            class="mb-0"
          />
        </b-card>
        <div class="mx-2 mb-2 mt-1">
          <b-row>
            <b-col
              cols="12"
              sm="6"
              class="d-flex align-items-center justify-content-center justify-content-sm-start"
            >
              <span class="text-muted">There are {{ totalRows }} entries</span>
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
export default {
  data() {
    return {
      isLoading: false,
      tableLoading: false,
      perPage: 10,
      perPageOptions: [5, 10, 20, 100],
      filter: null,
      availableStockFields: [
        { key: 'referencenumber', label: 'Reference No' },
        { key: 'item.item', label: 'Category' },
        { key: 'name', label: 'Sub-Category Name' },
        { key: 'quantity', label: 'Quantity Available' },
      ],
      totalRows: 0,
      currentPage: 1,
    }
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Inventory / <span style="color: #C4C4C4">Available Stock</span>'
  },
  methods: {
    getAvailableStock(ctx, callback) {
      this.$store.dispatch('inventory/getAvailableStock', { page: ctx.currentPage, perPage: ctx.perPage }).then(res => {
        const { data, total } = res.data
        this.totalRows = total
        callback(data)
      }).catch(() => {
        callback([])
      })
      return null
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
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
[dir] .b-form-spinbutton.form-control:focus, [dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}

.bartum-button {
  color: white;
  background-color: #4D6D8A !important;
  border-color: #4D6D8A !important;
}
</style>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
