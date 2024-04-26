<template>
  <div>
    <b-tabs
      content-class="mt-3"
      @changed="setTabValue(e)"
    >
      <b-tab title="Inverters">
        <Fragment>
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
                      v-model="filterInverter"
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(1)"
                  >
                    ADD STOCK
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>

          <b-tabs content-class="mt-3">
            <b-tab :title="totalInverters || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-skeleton-table
                      v-if="tableLoading"
                      :rows="1"
                      :columns="5"
                      :table-props="{ bordered: false, striped: false }"
                    />

                    <b-table
                      id="my-table"
                      ref="inventory-table-inverter"
                      :items="allTotalInverters"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="inverterCurrentPage"
                      :filter="filterInverter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                      @filtered="onFiltered"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 1, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} products (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="inverterCurrentPage"
                          :total-rows="totalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                          aria-controls="my-table"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="availableInverters || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      :items="allAvailableInverters"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="inverterCurrentPage"
                      :filter="filterInverter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 1, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} products (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="inverterCurrentPage"
                          :total-rows="totalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldInverters">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      :items="allSoldInverters"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="inverterCurrentPage"
                      :filter="filterInverter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 1, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldInverterRows }} products
                          (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="inverterCurrentPage"
                          :total-rows="totalSoldInverterRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </Fragment>
      </b-tab>
      <b-tab title="Solar Panels">
        <Fragment>
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(2)"
                  >
                    ADD STOCK
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-tabs content-class="mt-3">
            <b-tab :title="totalSolarPanels || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      id="my-table-solar"
                      ref="inventory-table-solar"
                      :items="allTotalSolars"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="solarCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 2, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} products (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="solarCurrentPage"
                          :total-rows="solarTotalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                          aria-controls="my-table-solar"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="availableSolarPanels || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-solar"
                      :items="getSolarPanelInventory"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="solarCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 2, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} products (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="solarCurrentPage"
                          :total-rows="solarTotalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldSolarPanels">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-solar"
                      :items="getSolarPanelInventorySold"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="solarCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 2, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldSolarRows }} products (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="solarCurrentPage"
                          :total-rows="totalSoldSolarRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </Fragment>
      </b-tab>
      <b-tab title="Batteries">
        <Fragment>
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(3)"
                  >
                    ADD STOCK
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-tabs content-class="mt-3">
            <b-tab :title="totalBatterries || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-battery"
                      :items="getBatteryInventoryTotal"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="batteryCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 3, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="batteryCurrentPage"
                          :total-rows="batteryTotalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>

            <b-tab :title="availableBatteries || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-battery"
                      :items="getBatteryInventory"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="batteryCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 3, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="batteryCurrentPage"
                          :total-rows="batteryTotalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldBatteries">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-battery"
                      :items="getBatteryInventorySold"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="batteryCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 3, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldBatteryRows }} product
                          (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="batteryCurrentPage"
                          :total-rows="totalSoldBatteryRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </Fragment>
      </b-tab>

      <b-tab title="Mesh Grid">
        <Fragment>
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(4)"
                  >
                    ADD STOCK
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-tabs content-class="mt-3">
            <b-tab :title="totalStreetLight || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-light"
                      :items="getStreetLightInventoryTotal"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="streetLightCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 4, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="streetLightCurrentPage"
                          :total-rows="streetLightTotalRows"
                          :per-page="perPage"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="availableStreetLight || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-light"
                      :items="getStreetLightInventory"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="streetLightCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 4, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="streetLightCurrentPage"
                          :total-rows="streetLightTotalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldStreetLight">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-light"
                      :items="getStreetLightInventorySold"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="streetLightCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 4, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldStreetLightRows }} product
                          (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="streetLightCurrentPage"
                          :total-rows="totalSoldStreetLightRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </Fragment>
      </b-tab>

      <!-- ////Gas stocks -->
      <!-- <b-tab title="Gas IPP">
        <Fragment>
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(5)"
                  >
                    ADD STOCK
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-tabs content-class="mt-3">
            <b-tab :title="totalGas || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-gas"
                      :items="getGasInventoryTotal"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="gasCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 5, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="gasCurrentPage"
                          :total-rows="GasTotalRow"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="availableGas || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-gas"
                      :items="getGasInventory"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="gasCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 5, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="gasCurrentPage"
                          :total-rows="GasTotalRow"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldGas || 'Sold'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-gas"
                      :items="getGasInventorySold"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="gasCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 5, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldGasRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="gasCurrentPage"
                          :total-rows="totalSoldGasRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </Fragment>
      </b-tab> -->

      <!-- // Accessories Stocks -->
      <b-tab title="Accessories">
        <Fragment>
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(5)"
                  >
                    ADD STOCK ACCESSORIES
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>

          <b-tabs content-class="mt-3">
            <b-tab :title="totalAccessories || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-accessories"
                      :items="getAccessoriesInventoryTotal"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="accessoryCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 6, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="accessoryCurrentPage"
                          :total-rows="AccessoriesTotalRow"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>

            <b-tab :title="availableAccessories || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-accessories"
                      :items="getAccessoriesInventory"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="accessoryCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 6, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="accessoryCurrentPage"
                          :total-rows="AccessoriesTotalRow"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldAccessories">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-accessories"
                      :items="getAccessoriesInventorySold"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="accessoryCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 6, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldAccessoryRows }} product
                          (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="accessoryCurrentPage"
                          :total-rows="totalSoldAccessoryRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>

          </b-tabs>
        </Fragment>
      </b-tab>

      <!-- <<<PODS>>> -->
        <b-tab title="Pod">
        <Fragment>
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
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col lg="7" />
                <b-col lg="5">
                  <b-button
                    v-b-modal.add-stock
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                    @click="setCategory(6)"
                  >
                    ADD POD LAST
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-tabs content-class="mt-3">
            <b-tab :title="totalPods || 'All'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-light"
                      :items="getPodsInventoryTotal"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="streetLightCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 4, 'all')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="streetLightCurrentPage"
                          :total-rows="streetLightTotalRows"
                          :per-page="perPage"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="availablePOD || 'Available'">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-light"
                      :items="getPODInventory"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="streetLightCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 4, 'available')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalRows }} product (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="streetLightCurrentPage"
                          :total-rows="streetLightTotalRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>
            <b-tab :title="soldPods || SOLD">
              <b-row>
                <b-col cols="12">
                  <b-card
                    no-body
                    class="card-company-table"
                  >
                    <b-table
                      ref="inventory-table-light"
                      :items="getPodsSold"
                      responsive
                      :fields="inventoryFields"
                      :per-page="perPage"
                      :current-page="streetLightCurrentPage"
                      :filter="filter"
                      show-empty
                      empty-text="Loading inventory details..."
                      class="mb-0"
                    >
                      <template #cell(created_at)="data">
                        {{ new Date(data.item.created_at).toUTCString() }}
                      </template>
                      <template #cell(itemid)="data">
                        <feather-icon
                          v-b-tooltip.hover.top="'Edit'"
                          right
                          icon="Edit3Icon"
                          size="16"
                          class="cursor-pointer"
                          style="color: #4D6D8A;"
                          @click="edit(data.item, 4, 'sold')"
                        />
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
                        <span
                          class="text-muted"
                        >There are {{ totalSoldStreetLightRows }} product
                          (s)</span>
                      </b-col>
                      <b-col
                        cols="12"
                        sm="6"
                        class="d-flex align-items-center justify-content-center justify-content-sm-end"
                      >
                        <b-pagination
                          v-model="streetLightCurrentPage"
                          :total-rows="totalSoldStreetLightRows"
                          :per-page="perPage"
                          class="mb-0 mt-1 mt-sm-0"
                        />
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
              </b-row>
            </b-tab>

          </b-tabs>
        </Fragment>
      </b-tab>
    </b-tabs>

    <b-modal
      id="add-stock"
      ref="add-stock"
      hide-footer
      hide-header
      size="md"
      centered
    >
      <validation-observer ref="addStockValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Add Stock
          </h4>
          <b-row class="mt-1">
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-name"
                  rules="required"
                >
                  <label
                    for="product-name"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-name
                  </label>
                  <b-form-input
                    id="product-name"
                    v-model="productName"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="sub-category"
                  rules="required"
                >
                  <label
                    for="sub-category"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Sub-Category
                  </label>
                  <v-select
                    id="solar-panel-type"
                    v-model="subItem"
                    :reduce="(option) => option.value"
                    :options="subItems"
                    label="title"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-capacity"
                  rules="required"
                >
                  <label
                    for="product-capacity"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-capacity
                  </label>
                  <b-form-input
                    id="product-capacity"
                    v-model="productCapacity"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-rating"
                  rules="required"
                >
                  <label
                    for="product-rating"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-rating
                  </label>
                  <b-form-input
                    id="product-rating"
                    v-model="productRating"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-price"
                  rules="required"
                >
                  <label
                    for="product-price"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-price
                  </label>
                  <b-form-input
                    id="product-price"
                    v-model="productPrice"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <vue-number-input
                v-model="value"
                :min="1"
                :max="10"
                inline
                controls
              />
              <!-- <vue-number-input v-model="value" :min="1" :max="10" inline controls></vue-number-input> -->

              <b-form-group>
                <label
                  for="quantity"
                  class="mb-lg-1 font-weight-bolder"
                >
                  Quantity
                </label>
                <vue-numeric-input
                  id="quantity"
                  v-model="quantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />

              </b-form-group>
            </b-col>
          </b-row>
          <div class="d-flex float-right mt-1">
            <b-button
              style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
              @click="hideModal"
            >
              Cancel
            </b-button>
            <b-button
              v-if="!isLoading"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="addStock"
            >
              Add Stock
            </b-button>
            <b-button
              v-if="isLoading"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Adding...
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-modal>

    <b-modal
      id="edit-stock"
      ref="edit-stock"
      hide-footer
      hide-header
      size="md"
      centered
    >
      <validation-observer ref="editStockValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Edit Stock
          </h4>
          <b-row class="mt-1">
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-name"
                  rules="required"
                >
                  <label
                    for="product-name"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-name
                  </label>
                  <b-form-input
                    id="product-name"
                    v-model="editProductName"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="sub-category"
                  rules="required"
                >
                  <label
                    for="sub-category"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Sub-Category
                  </label>
                  <v-select
                    id="solar-panel-type"
                    v-model="editsubItem"
                    :reduce="(option) => option.value"
                    :options="subItems"
                    label="title"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-capacity"
                  rules=""
                >
                  <label
                    for="product-capacity"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-capacity
                  </label>
                  <b-form-input
                    id="product-capacity"
                    v-model="editProductCapacity"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-rating"
                  rules=""
                >
                  <label
                    for="product-rating"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-rating
                  </label>
                  <b-form-input
                    id="product-rating"
                    v-model="editProductRating"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-price"
                  rules=""
                >
                  <label
                    for="product-price"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product-price
                  </label>
                  <b-form-input
                    id="product-price"
                    v-model="editProductPrice"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col cols="6">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product-rating"
                  rules=""
                >
                  <label
                    for="product-rating"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Stock Id
                  </label>
                  <b-form-input
                    id="product-rating"
                    v-model="editStockid"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <!-- <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="stock-id"
                  rules="required"
                >
                  <label for="stock-id" class="mb-lg-1 font-weight-bolder">
                    Stock Id
                  </label>
                  <b-form-input id="stock-id" v-model="editStockid" />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col> -->
          </b-row>
          <div class="d-flex float-right mt-1">
            <b-button
              style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
              @click="hideEditModal"
            >
              Cancel
            </b-button>
            <b-button
              v-if="!isLoading"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="editStock"
            >
              Update Stock
            </b-button>
            <b-button
              v-if="isLoading"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Adding...
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import { required } from '@validations'
import vSelect from 'vue-select'
import VueNumericInput from 'vue-numeric-input'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
    VueNumericInput,
  },
  data() {
    return {
      categoryType: null,
      isLoading: false,
      tableLoading: false,
      perPage: 20,
      perPageOptions: [20],
      filter: null,
      filterInverter: null,
      item: null,
      filterText: '',
      productName: null,
      productRating: null,
      productPrice: null,
      productCapacity: null,
      productSerialNumber: null,
      totalInverters: null,
      inverterTableData: [],
      totalBatterries: null,
      totalGas: null,
      totalAccessories: null,
      totalSolarPanels: null,
      totalStreetLight: null,
      soldAccessories: null,
      soldInverters: null,
      soldBatteries: null,
      soldSolarPanels: null,
      soldStreetLight: null,
      soldGas: null,
      availableAccessories: null,
      availableInverters: null,
      availableBatteries: null,
      availableSolarPanels: null,
      availableStreetLight: null,
      totalPods: null,
      availablePOD: null,
      availableGas: null,
      soldPods: null,
      // itemID: this.item,
      // subItemID: this.subItem,
      editsubItem: null,
      editQuantity: null,
      editProductName: null,
      editProductRating: null,
      editProductPrice: null,
      editProductCapacity: null,
      editProductSerialNumber: null,
      editStockid: null,
      editStatus: null, // sold, available, or all
      editSelectedStockID: null,
      editSelectedStockStatus: null,
      allTotalInverters: [],
      allAvailableInverters: [],
      allSoldInverters: [],

      allTotalSolars: [],
      allSolars2: [],
      allBatteries: [],
      allStreetLights: [],
      allGases: [],
      allAccesseries: [],
      items: [{ value: null, title: 'Please select a category' }],
      subItem: null,
      subItems: [
        { value: null, title: 'Please select a sub-category' },
        { key: 'item.item', value: 'Category' },
      ],
      refNo: null,
      quantity: 1,
      inventoryFields: [
        { key: 'stockid', label: 'Device No' },
        { key: 'item.item', label: 'Category' },
        { key: 'subitem.name', label: 'Sub-Category' },
        { key: 'created_at', label: 'Date Entered' },
        { key: 'capacity', label: 'capacity' },
        { key: 'price', label: 'price' },
        { key: 'name', label: 'name' },
        // { key: "transactiontype", label: "Status" },
        { key: 'itemid', label: 'Edit' },
      ],
      totalSoldInverterRows: 0,
      totalSoldSolarRows: 0,
      totalSoldBatteryRows: 0,
      totalSoldStreetLightRows: 0,
      totalSoldGasRows: 0,
      totalSoldAccessoryRows: 0,
      totalRows: 0,
      solarTotalRows: 0,
      batteryTotalRows: 0,
      streetLightTotalRows: 0,
      GasTotalRow: 0,
      AccessoriesTotalRow: 0,
      currentPage: 1,
      inverterCurrentPage: 1,
      solarCurrentPage: 1,
      batteryCurrentPage: 1,
      streetLightCurrentPage: 1,
      gasCurrentPage: 1,
      accessoryCurrentPage: 1,
      required,
    }
  },
  computed: {},
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Inventory / <span style="color: #C4C4C4">Overview</span>'
  },
  created() {
    this.getItems()
    this.getInverterInventoryTotal(null, null, 1)
    this.getInverterInventory(null, null, 1)
    this.getInverterInventorySold(null, null, 1)
    this.getSolarPanelInventoryTotal(null, null, 1)
  },

  methods: {
    editStock() {
      this.isLoading = true
      this.$refs.editStockValidation.validate().then(success => {
        if (success) {
          const form = {
            itemID: this.item,
            subItemID: this.editsubItem,
            quantity: this.editQuantity,
            productName: this.editProductName,
            rating: this.editProductRating,
            price: this.editProductPrice,
            capacity: this.editProductCapacity,
            productSerialNumber: this.editProductSerialNumber,
            stockid: this.editStockid,
            status: this.editStatus, // sold, available, or all
          }
          this.$store
            .dispatch('inventory/editStock', {
              id: this.editSelectedStockID,
              form,
            })
            .then(res => {
              this.isLoading = false
              const projectID = localStorage.getItem('projectID')
              this.loaded = true
              window.location.reload()
              this.hideEditModal()
              this.toast('Edit Product', 'CheckCircleIcon', message, 'success')
            })
            .catch(err => {
              this.isLoading = false
              this.toast(
                'Edit Product',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Edit Product Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
        }
      })
    },
    hideEditModal() {
      this.$refs['edit-stock'].hide()
    },
    edit(stock, category, status) {
      this.setCategory(category)
      this.editSelectedStockID = stock.id
      this.editSelectedStockStatus = status
      this.editQuantity = stock.quantity
      this.editProductName = stock.name
      this.editProductRating = stock.rating
      this.editProductPrice = stock.price
      this.editProductCapacity = stock.capacity
      this.editStockid = stock.stockid
      this.editsubItem = stock.subitemid
      this.editStatus = stock.status

      this.$refs['edit-stock'].show()
    },

    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    hideModal() {
      this.$refs['add-stock'].hide()
    },
    setCategory(category) {
      this.categoryType = category
      this.getSubItemsByItemID(category)
      this.itemid = category
      this.item = category
    },

    getInverterInventoryData(ctx, callback) {
      if (this.filterText !== null) {
        return callback(
          this.inverterTableData.filter(item => this.filterText
            .toLowerCase()
            .split(' ')
            .every(v => item.title.toLowerCase().includes(v))),
        )
      }
      return callback(this.inverterTableData)
    },
    getInverterInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 1,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, totalpages, page, perpage,
          } = res.data
          const Inverter = data
          this.itemid = 1
          this.totalRows = total
          this.inverterTableData = Inverter

          this.totalInverters = `All (${total})`
          this.allTotalInverters = Inverter
        })
        .catch(err => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getInverterInventorySold(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 1,
          status: 'sold',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, totalpages, page, perpage,
          } = res.data
          const Inverter = data
          this.itemid = 1
          // this.totalRows = Inverter.length
          this.soldInverters = total
          this.inverterTableData = Inverter
          this.totalSoldInverterRows = total
          this.soldInverters = `Sold (${total})`
          this.allSoldInverters = Inverter
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getInverterInventory(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 1,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, totalpages, page, perpage,
          } = res.data
          const Inverter = data
          this.itemid = 1

          this.availableInverters = `Available (${total})`
          this.inverterTableData = Inverter
          this.allAvailableInverters = Inverter
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getBatteryInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 3,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const Batteries = data
          this.itemid = 3
          // this.totalRows = Batteries.length
          this.batteryTotalRows = total
          this.totalBatterries = `All (${total})`
          this.allBatteries = Batteries
          callback(Batteries)
        })
        .catch(err => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getBatteryInventorySold(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 3,
          status: 'sold',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          const Batteries = data
          this.itemid = 3

          this.soldBatteries = `Sold (${total})`
          this.totalSoldBatteryRows = total

          callback(Batteries)
        })
        .catch(err => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getBatteryInventory(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 3,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          const Batteries = data
          this.itemid = 3

          this.availableBatteries = `Available (${total})`

          callback(Batteries)
        })
        .catch(err => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getSolarPanelInventorySold(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 2,
          status: 'sold',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          // const Solar = data.filter(product => product.item.item === 'Solar Panel')
          const Solar = data
          this.itemid = 2
          // this.totalRows = Solar.length

          this.soldSolarPanels = `Sold (${total})`
          this.totalSoldSolarRows = total
          // this.totalSolarPanels = `Solar Panel (${perpage * totalpages})`
          callback(Solar)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    async getSolarPanelInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true

      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 2,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          this.allTotalSolars = data
          const Solar = data.sort((a, b) => b.created_at - a.created_at)

          this.itemid = 2

          this.totalSolarPanels = `All (${total})`
          this.solarTotalRows = total
          // this.allSolars = Solar;
          this.allSolars2 = Solar
        })
        .catch(err => {
          this.tableLoading = false
          callback([])
        })

      return null
    },

    getSolarPanelInventory(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 2,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          // const Solar = data.filter(product => product.item.item === 'Solar Panel')
          const Solar = data
          this.itemid = 2
          this.availableSolarPanels = `Available (${total})`
          // this.totalRows = Solar.length
          // this.totalSolarPanels = `Solar Panel (${perpage * totalpages})`
          callback(Solar)
        })
        .catch(err => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getGasInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || 1,
          id: 5,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          const Gas = data

          this.itemid = 1
          // this.totalRows = total
          this.totalGas = `All(${total})`
          this.GasTotalRow = ptotal
          this.allGases = Gas
          callback(Gas)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getGasInventorySold(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 5,
          status: 'sold',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const Gas = data

          this.itemid = 1
          // this.totalRows = total
          this.soldGas = `Sold (${total})`
          this.totalSoldGasRows = total

          // this.soldGas = 'kk'
          callback(Gas)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getGasInventory(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 5,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const Gas = data

          this.itemid = 1
          this.availableGas = `Available (${total})`
          // this.totalRows = total
          // this.totalGas = `Gas IPP (${perpage * totalpages})`
          callback(Gas)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getStreetLightInventorySold(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', { page: 1, id: 4, status: 'sold' })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const StreetLight = data

          this.itemid = 1
          // this.totalRows = total
          this.soldStreetLight = `Sold (${total})`
          this.totalSoldStreetLightRows = total
          callback(StreetLight)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getStreetLightInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 4,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          const StreetLight = data

          this.itemid = 1
          // this.totalRows = total
          this.totalStreetLight = `All (${total})`
          this.streetLightTotalRows = total
          this.allStreetLights = StreetLight

          callback(StreetLight)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getStreetLightInventory(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 4,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const StreetLight = data

          this.availableStreetLight = `Available (${total})`

          this.itemid = 1
          // this.totalRows = total
          // this.totalStreetLight = `Inverterts (${perpage * totalpages})`
          callback(StreetLight)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getAccessoriesInventorySold(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 5,
          status: 'sold',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const Accessories = data

          this.itemid = 1
          // this.totalRows = total

          this.soldAccessories = `Sold (${total})`
          this.totalSoldAccessoryRows = total

          callback(Accessories)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getAccessoriesInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 5,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          const Accessories = data

          this.itemid = 1
          // this.totalRows = total
          this.totalAccessories = `All (${total})`
          callback(Accessories)
          this.AccessoriesTotalRow = total
          this.allAccessories = Accessories
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getAccessoriesInventory(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 5,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const Accessories = data
          this.availableAccessories = `Available (${total})`

          this.itemid = 1
          // this.totalRows = total
          // this.totalAccessories = `Accessories (${perpage * totalpages})`
          callback(Accessories)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },

    getPodsInventoryTotal(ctx, callback, pageNumber) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: pageNumber || ctx.currentPage,
          id: 6,
          status: 'all',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data

          const StreetLight = data

          this.itemid = 1
          // this.totalRows = total
          this.totalPods = `All (${total})`
          this.streetLightTotalRows = total
          this.allStreetLights = StreetLight

          callback(StreetLight)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getPODInventory(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', {
          page: ctx.currentPage,
          id: 6,
          status: 'available',
        })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const StreetLight = data

          this.availablePOD = `Available (${total})`

          this.itemid = 1
          // this.totalRows = total
          // this.totalStreetLight = `Inverterts (${perpage * totalpages})`
          callback(StreetLight)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getPodsSold(ctx, callback) {
      this.tableLoading = true
      this.$store
        .dispatch('inventory/getInventory', { page: 1, id: 6, status: 'sold' })
        .then(res => {
          this.tableLoading = false
          const {
            data, total, perpage, totalpages,
          } = res.data
          const StreetLight = data

          this.itemid = 1
          // this.totalRows = total
          this.soldPods = `Sold (${total})`
          this.totalSoldStreetLightRows = total
          callback(StreetLight)
        })
        .catch(() => {
          this.tableLoading = false
          callback([])
        })
      return null
    },
    getItems() {
      this.$store.dispatch('inventory/getItems').then(res => {
        const { data } = res.data

        data.forEach(item => {
          const itemObj = { value: item.id, title: item.item }
          this.items.push(itemObj)
        })
      })
    },
    addStock() {
      this.isLoading = true
      this.$refs.addStockValidation.validate().then(success => {
        if (success) {
          const form = {
            itemID: this.item,
            subItemID: this.subItem,
            quantity: this.quantity,
            productName: this.productName,
            rating: this.productRating,
            price: this.productPrice,
            capacity: this.productCapacity,
            productSerialNumber: this.productSerialNumber,
            status: 1,
          }
          this.$store
            .dispatch('inventory/addStock', { form })
            .then(res => {
              this.isLoading = false
              const { message } = res.data
              window.location.reload()
              this.$refs['inventory-table-inverter'].reinitializeSession()
              this.$refs['inventory-table-light'].refresh()
              this.$refs['inventory-table-gas'].refresh()
              this.$refs['inventory-table-battery'].refresh()
              this.$refs['inventory-table-solar'].refresh()
              this.$refs['inventory-table-accessories'].refresh()
              this.hideModal()
              // inventory-table-accessories
              this.toast('Add Stock', 'CheckCircleIcon', message, 'success')
            })
            .catch(err => {
              this.isLoading = false
              this.toast(
                'Add Stock',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Add Stock Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
        }
      })
    },
    getSubItemsByItemID(item) {
      const itemID = item
      this.subItems = [{ value: null, title: 'Please select a sub-category' }]
      this.$store
        .dispatch('inventory/getSubItemByID', { itemID })
        .then(res => {
          const { data } = res.data
          data.forEach(subItem => {
            const subitemobj = { value: subItem.id, title: subItem.name }
            this.subItems.push(subitemobj)
          })
        })
    },
    filterFunction(item, filter) {
      if (item.quantity === filter) {
        return true
      }
      return false
    },
  },
}
</script>

<style lang="scss">
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control,
[dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
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

[dir] .b-form-spinbutton.form-control:focus,
[dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}

.bartum-button {
  color: white;
  background-color: #4D6D8A !important;
  border-color: #4D6D8A !important;
}

.number-input {
  height: 38px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
