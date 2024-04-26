<template>
  <div>
    <b-tabs content-class="mt-3">
      <b-tab
        title="General"
        active
      >
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
                    v-b-modal.add-product
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                  >
                    ADD GENERAL PRODUCT
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <div
                style="display: flex; flex-direction: column; justify-content: flex-end;align-items: flex-end; width: 100%; margin-bottom: 25px;"
              >
                <div><B>COLOR HINT:</B> </div>
                <div style="display: flex; align-items: center; column-gap: 10px;">
                  <!-- <div>Green</div> -->
                  <div style="background-color: green; border-radius: 0px; height: 15px; width: 15px;" /> -
                  Item Available
                </div>
                <div style="display: flex; align-items: center; column-gap: 10px;">
                  <!-- <div>Red</div> -->
                  <div style="background-color: red; border-radius: 0px; height: 15px; width: 15px;" /> - Item
                  UnAvailable
                </div>
              </div>
            </b-col>

          </b-row>
          <b-row>
            <b-col cols="12">
              <b-card
                v-if="products"
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
                  v-if="!tableLoading"
                  :items="products"
                  responsive
                  :fields="productsField"
                  :per-page="perPage"
                  :current-page="currentPage"
                  :filter="filter"
                  show-empty
                  empty-text="No products found"
                  class="mb-0"
                  @filtered="onFiltered"
                >
                  <template #cell(numberofinverters)="data">
                    {{ data.item.inverter_availability }} {{ data.item.numberofinverters }}
                    <div style="display: flex;">
                      <div
                        v-if="data.item.inverter_availability == 'green' && data.item.numberofinverters"
                        v-b-popover.hover.left="data.item.inverter_type"
                        style="background-color: green; display: flex; justify-content: center; color: #F9F9F9;width:20px"
                      >

                        {{ data.item.numberofinverters }}

                      </div>
                      <div
                        v-if="data.item.inverter_availability == 'red' && data.item.numberofinverters"
                        v-b-popover.hover.left="data.item.inverter_type"
                        style="background-color: red; display: flex; justify-content: center; color: #F9F9F9; height: 100%;width:20px"
                      >
                        {{ data.item.numberofinverters }}

                      </div>
                      <div><feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-popover.hover.left="data.item.inverter_type"
                        icon="InfoIcon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="viewProductDesc(data.item)"
                      /></div></div>

                  </template>
                  <template #cell(numberofbatteries)="data">
                    <div style="display: flex;">
                      <div
                        v-if="data.item.batter_availability == 'green' && data.item.numberofbatteries"
                        style="background-color: green; display: flex; justify-content: center; color: #F9F9F9; width:20px"
                      >
                        {{ data.item.numberofbatteries }}
                      </div>
                      <div
                        v-if="data.item.batter_availability == 'red' && data.item.numberofbatteries"
                        style="background-color: red; display: flex; justify-content: center; color: #F9F9F9; width:20px"
                      >
                        {{ data.item.numberofbatteries }}
                      </div>
                      <div><feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-popover.hover.left="data.item.batteries_type"
                        icon="InfoIcon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="viewProductDesc(data.item)"
                      /></div></div>

                  </template>
                  <template
                    #cell(numberofpanels)="data"
                    style="background-color: red;"
                  >
                    <div style="display: flex;">
                      <div
                        v-if="data.item.panel_availability == 'green' && data.item.numberofpanels"
                        style="background-color: green; display: flex; justify-content: center; color: #F9F9F9;width:20px"
                      >
                        {{ data.item.numberofpanels }}
                      </div>
                      <div
                        v-if="data.item.panel_availability == 'red' && data.item.numberofpanels"
                        style="background-color: red; display: flex; justify-content: center; color: #F9F9F9;width:20px"
                      >
                        {{ data.item.numberofpanels }}
                      </div>
                      <div><feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-popover.hover.left="data.item.panel_type"
                        icon="InfoIcon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="viewProductDesc(data.item)"
                      /></div></div>

                  </template>
                  <template #cell(price)="data">
                    &#8358;{{
                      parseInt(data.item.price)
                        .toFixed(2)
                        .replace(/\d(?=(\d{3})+\.)/g, "$&,")
                    }}
                  </template>
                  <template #cell(action)="data">
                    <div class="text-nowrap text-center">
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-popover.hover.left="data.item.description"
                        icon="InfoIcon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="viewProductDesc(data.item)"
                      />
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-tooltip.hover.top="'View'"
                        icon="EyeIcon"
                        size="16"
                        class="cursor-pointer"
                        @click="viewProductDesc(data.item)"
                      />
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-tooltip.hover.top="'Delete'"
                        icon="Trash2Icon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="deleteProduct(data.item)"
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
                    <span class="text-muted">There are {{ totalRows }} products</span>
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
        </Fragment>
      </b-tab>
      <!-- <b-tab title="Street Light">
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
                    v-b-modal.add-street-light-product
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                  >
                    ADD STREET LIGHT PRODUCT
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12">
              <b-card
                v-if="products"
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
                  v-if="!tableLoading"
                  :items="products"
                  responsive
                  :fields="productsField"
                  :per-page="perPage"
                  :current-page="currentPage"
                  :filter="filter"
                  show-empty
                  empty-text="No products found"
                  class="mb-0"
                  @filtered="onFiltered"
                >
                  <template #cell(price)="data">
                    &#8358;{{
                      parseInt(data.item.price)
                        .toFixed(2)
                        .replace(/\d(?=(\d{3})+\.)/g, "$&,")
                    }}
                  </template>
                  <template #cell(action)="data">
                    <div class="text-nowrap text-center">
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-popover.hover.left="data.item.description"
                        icon="InfoIcon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="viewProductDesc(data.item)"
                      />
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-tooltip.hover.top="'Edit'"
                        icon="EyeIcon"
                        size="16"
                        class="cursor-pointer"
                        @click="viewProductDesc(data.item)"
                      />
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-tooltip.hover.top="'Delete'"
                        icon="Trash2Icon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="deleteProduct(data.item)"
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
                    <span
                      class="text-muted"
                    >There are {{ totalRows }} products</span>
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
        </Fragment>
      </b-tab> -->
      <!-- <b-tab title="Gas">
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
                    v-b-modal.add-gas-product
                    class="mt-lg-0 mt-1 btn-block"
                    style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                  >
                    ADD GAS PRODUCT
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12">
              <b-card
                v-if="products"
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
                  v-if="!tableLoading"
                  :items="products"
                  responsive
                  :fields="productsField"
                  :per-page="perPage"
                  :current-page="currentPage"
                  :filter="filter"
                  show-empty
                  empty-text="No products found"
                  class="mb-0"
                  @filtered="onFiltered"
                >
                  <template #cell(price)="data">
                    &#8358;{{
                      parseInt(data.item.price)
                        .toFixed(2)
                        .replace(/\d(?=(\d{3})+\.)/g, "$&,")
                    }}
                  </template>
                  <template #cell(action)="data">
                    <div class="text-nowrap text-center">
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-popover.hover.left="data.item.description"
                        icon="InfoIcon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="viewProductDesc(data.item)"
                      />
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-tooltip.hover.top="'Description'"
                        icon="EyeIcon"
                        size="16"
                        class="cursor-pointer"
                        @click="viewProductDesc(data.item)"
                      />
                      <feather-icon
                        :id="`view-${data.item.id}`"
                        v-b-tooltip.hover.top="'Delete'"
                        icon="Trash2Icon"
                        size="16"
                        class="cursor-pointer mx-1"
                        @click="deleteProduct(data.item)"
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
                    <span
                      class="text-muted"
                    >There are {{ totalRows }} products</span>
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
        </Fragment>
      </b-tab> -->
    </b-tabs>

    <b-modal
      id="add-product"
      ref="add-product"
      hide-footer
      hide-header
      size="lg"
      centered
    >
      <validation-observer ref="addProductValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Add Product
          </h4>
          <b-row class="mt-1">

            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product name"
                  rules="required"
                >
                  <label
                    for="product"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product Name
                  </label>
                  <b-form-input
                    id="product"
                    v-model="productName"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col lg="12">
              <b-row>
                <b-col cols="4">
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="product type"
                      rules="required"
                    >
                      <label
                        for="product"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Type
                      </label>
                      <v-select
                        id="project-type"
                        v-model="ProductType"
                        :reduce="(option) => option.value"
                        :options="ProductTypes"
                        label="title"
                        @input="typeChanged"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

                <b-col
                  v-if="ProductType == 'meshgrid'"
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="inverter type"
                      rules="required"
                    >
                      <label
                        for="system-size"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Mesh Grid Type
                      </label>
                      <b-form-select
                        id="system-size"
                        v-model="inverterType"
                        :options="meshGridTypes"
                        @change.native="inverterTypeChanged"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>

                  </b-form-group>
                </b-col>
                <b-col
                  v-if="ProductType == 'default'"
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="inverter type"
                      rules="required"
                    >
                      <label
                        for="system-size"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Inverter Type
                      </label>
                      <b-form-select
                        id="system-size"
                        v-model="inverterType"
                        :options="inverterTypes"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>

                  </b-form-group>
                </b-col>
                <b-col
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <validation-provider
                      #default="{ errors }"
                      name="solar system type"
                      rules="required"
                    >
                      <label
                        for="system-size"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Solar System Type
                      </label>
                      <b-form-select
                        id="system-size"
                        v-model="solarType"
                        :options="systemSizes"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>

                  </b-form-group>
                </b-col>
                <b-col lg="4">
                  <b-form-group>
                    <label
                      for="num-panels"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Solar Panel Quantity
                    </label>
                    <vue-numeric-input
                      id="num-panels"
                      v-model="solarPanelQuantity"
                      :min="1"
                      :step="1"
                      width="100%"
                      class="number-input"
                      align="center"
                    />
                    <!-- <b-form-spinbutton
                  id="num-panels"
                  v-model="solarPanelQuantity"
                  min="1"
                /> -->
                  </b-form-group>
                </b-col>
                <!-- {{ hasBattery }} -->
                <b-col
                  v-if="this.hasBattery == false"
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <label
                      for="system-size"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Battery Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="batteryType"
                      :options="batteryTypes"
                    />
                  </b-form-group>
                </b-col>
                <b-col
                  v-if="this.hasBattery == false"
                  lg="4"
                >
                  <b-form-group>
                    <label
                      for="system-size"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Battery Quantity
                    </label>
                    <vue-numeric-input
                      id="num-batteries"
                      v-model="batteryQuantity"
                      :min="1"
                      :step="1"
                      width="100%"
                      class="number-input"
                      align="center"
                    />

                  </b-form-group>
                </b-col>
              </b-row>
            </b-col>

            <!-- <b-col lg="4">
                <b-form-group>
                  <label
                    for="num-batteries"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Inverter Quantity
                  </label>
                  <b-form-spinbutton
                    id="num-batteries"
                    v-model="inverterQuantity"
                    min="1"
                  />
                </b-form-group>
              </b-col> -->

            <!-- <b-col lg="4">
              <b-form-group>
                <label
                  for="num-panels"
                  class="mb-lg-1 font-weight-bolder"
                >
                  Inverter Quantity
                </label>
                <vue-numeric-input
                  id="num-inverters"
                  v-model="inverterQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />

              </b-form-group>
            </b-col> -->
            <!-- <b-col lg="4">
              <b-form-group>
                <label for="num-panels" class="mb-lg-1 font-weight-bolder">
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
            </b-col> -->

            <b-col
              lg="12"
              class="mt-lg-0 mt-1"
              style="
                  margin-left: 0rem; margin-right: 3rem;
                  "
            >
              <hr>
              <!-- <b-row> -->

              <b-form-group>
                <div
                  v-for="(accessory, index) in accessories"
                  :key="`phoneInput-${index}`"
                  class="input wrapper flex items-center"
                >
                  <div lg="4">
                    <validation-provider
                      #default="{ errors }"
                      name="Accessory Type"
                    >
                      <label
                        for="product"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Accessory Type
                      </label>

                      <b-form-select
                        id="system-size"
                        v-model="accessory.subitem_id"
                        :options="accessoryType"
                      />

                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </div>
                  <b-col lg="4">
                    <label
                      for="num-panels"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Accessory Quantity
                    </label>
                    <vue-numeric-input
                      id="num-inverters"
                      v-model="accessory.quantity"
                      :min="1"
                      :step="1"
                      width="100%"
                      class="number-input"
                      align="center"
                    />
                  </b-col>

                  <!--          Remove Svg Icon-->
                  <div
                    class="removeAccessory"
                    @click="removeField(accessory, accessories)"
                  >
                    Remove
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      width="24"
                      height="24"
                      class="ml-2 cursor-pointer"
                    >
                      <path
                        fill="none"
                        d="M0 0h24v24H0z"
                      />
                      <path
                        fill="#EC4899"
                        d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-9.414l2.828-2.829 1.415 1.415L13.414 12l2.829 2.828-1.415 1.415L12 13.414l-2.828 2.829-1.415-1.415L10.586 12 7.757 9.172l1.415-1.415L12 10.586z"
                      />
                    </svg>
                  </div>
                </div>

                <div
                  class="addnew"
                  @click="addField(accessory, accessories)"
                >
                  Add New Acccessory
                  <!--          Add Svg Icon-->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="ml-2 cursor-pointer"
                  >
                    <path
                      fill="none"
                      d="M0 0h24v24H0z"
                    />
                    <path
                      fill="green"
                      d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                    />
                  </svg>
                </div>
              </b-form-group>
              <!-- </b-col> -->
              <!-- </b-row> -->
              <hr>
            </b-col>

            <!-- /////// -->
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
                    Product Description / Details
                  </label>
                  <b-form-textarea
                    id="description"
                    v-model="description"
                    rows="4"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
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
              @click="addProduct"
            >
              Add Product
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
      id="add-street-light-product"
      ref="add-street-light-product"
      hide-footer
      hide-header
      size="lg"
      centered
    >
      <validation-observer ref="addProductValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Add Street Light Product
          </h4>
          <b-row class="mt-1">
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product name"
                  rules="required"
                >
                  <label
                    for="product"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product Name
                  </label>
                  <b-form-input
                    id="product"
                    v-model="productName"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <!-- <b-col lg="12">
              <b-row>
                <b-col lg="4" class="mt-lg-0 mt-1">
                  <b-form-group>
                    <label for="system-size" class="mb-lg-1 font-weight-bolder">
                      Solar System Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="solarType"
                      :options="systemSizes"
                    />
                  </b-form-group>
                </b-col>
                <b-col lg="4" class="mt-lg-0 mt-1">
                  <b-form-group>
                    <label for="system-size" class="mb-lg-1 font-weight-bolder">
                      Inverter Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="inverterType"
                      :options="inverterTypes"
                    />
                  </b-form-group>
                </b-col>
                <b-col lg="4" class="mt-lg-0 mt-1">
                  <b-form-group>
                    <label for="system-size" class="mb-lg-1 font-weight-bolder">
                      Battery Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="batteryType"
                      :options="batteryTypes"
                    />
                  </b-form-group>
                </b-col>
              </b-row>
            </b-col> -->

            <!-- <b-col lg="4">
              <b-form-group>
                <label for="system-size" class="mb-lg-1 font-weight-bolder">
                  Battery Quantity
                </label>
                <vue-numeric-input
                  id="num-batteries"
                  v-model="batteryQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />
              </b-form-group>
            </b-col> -->
            <!-- <b-col lg="4">
              <b-form-group>
                <label for="num-panels" class="mb-lg-1 font-weight-bolder">
                  Solar Panel Quantity
                </label>
                <vue-numeric-input
                  id="num-panels"
                  v-model="solarPanelQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />
              </b-form-group>
            </b-col> -->

            <!-- <b-col lg="4">
              <b-form-group>
                <label for="num-panels" class="mb-lg-1 font-weight-bolder">
                  Inverter Quantity
                </label>
                <vue-numeric-input
                  id="num-inverters"
                  v-model="inverterQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />
              </b-form-group>
            </b-col> -->

            <b-col>
              <b-form-group>
                <div
                  v-for="(accessory, index) in accessories"
                  :key="`phoneInput-${index}`"
                  class="input wrapper flex items-center"
                >
                  <div lg="4">
                    <validation-provider
                      #default="{ errors }"
                      name="product name"
                      rules="required"
                    >
                      <label
                        for="product"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Accessory Type
                      </label>
                      <b-form-select
                        id="system-size"
                        v-model="accessory.subitem_id"
                        :options="accessoryType"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </div>
                  <b-col lg="4">
                    <label
                      for="num-panels"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Accessory Quantity
                    </label>
                    <vue-numeric-input
                      id="num-inverters"
                      v-model="accessory.quantity"
                      :min="1"
                      :step="1"
                      width="100%"
                      class="number-input"
                      align="center"
                    />
                  </b-col>

                  <!--          Remove Svg Icon-->
                  <div
                    class="removeAccessory"
                    @click="removeField(accessory, accessories)"
                  >
                    Remove
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      width="24"
                      height="24"
                      class="ml-2 cursor-pointer"
                    >
                      <path
                        fill="none"
                        d="M0 0h24v24H0z"
                      />
                      <path
                        fill="#EC4899"
                        d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-9.414l2.828-2.829 1.415 1.415L13.414 12l2.829 2.828-1.415 1.415L12 13.414l-2.828 2.829-1.415-1.415L10.586 12 7.757 9.172l1.415-1.415L12 10.586z"
                      />
                    </svg>
                  </div>
                </div>

                <div
                  class="addnew"
                  @click="addField(accessory, accessories)"
                >
                  Add New Acccessory
                  <!--          Add Svg Icon-->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="ml-2 cursor-pointer"
                  >
                    <path
                      fill="none"
                      d="M0 0h24v24H0z"
                    />
                    <path
                      fill="green"
                      d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                    />
                  </svg>
                </div>
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
                    Product Description / Details
                  </label>
                  <b-form-textarea
                    id="description"
                    v-model="description"
                    rows="4"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
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
              @click="addStreetLightProduct"
            >
              Add Product
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
      id="add-gas-product"
      ref="add-gas-product"
      hide-footer
      hide-header
      size="lg"
      centered
    >
      <validation-observer ref="addProductValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Add GAS Product
          </h4>
          <b-row class="mt-1">
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product name"
                  rules="required"
                >
                  <label
                    for="product"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product Name
                  </label>
                  <b-form-input
                    id="product"
                    v-model="productName"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col lg="12">
              <b-row>
                <b-col
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <label
                      for="system-size"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Solar System Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="solarType"
                      :options="systemSizes"
                    />
                  </b-form-group>
                </b-col>
                <b-col
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <label
                      for="system-size"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Inverter Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="inverterType"
                      :options="inverterTypes"
                    />

                    <!-- v-model="inverterType"
                      :reduce="(option) => option.id"
                      :options="[ { title: 'One', id: 1 },
                                  { label: 'Two', id: 2 },]"

                      v-model="inverterType"
                      :reduce="(option) => option.id"
                      :options="inverterTypes.map(i => i.label)" -->
                  </b-form-group>
                </b-col>
                <b-col
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <b-form-group>
                    <label
                      for="system-size"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Battery Type
                    </label>
                    <b-form-select
                      id="system-size"
                      v-model="batteryType"
                      :options="batteryTypes"
                    />
                  </b-form-group>
                </b-col>
              </b-row>
            </b-col>

            <b-col lg="4">
              <b-form-group>
                <label
                  for="system-size"
                  class="mb-lg-1 font-weight-bolder"
                >
                  Battery Quantity
                </label>
                <vue-numeric-input
                  id="num-batteries"
                  v-model="batteryQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />
                <!-- <b-form-spinbutton
                  id="num-batteries"
                  v-model="batteryQuantity"
                  min="1"
                /> -->
              </b-form-group>
            </b-col>
            <!-- <b-col lg="4">
                <b-form-group>
                  <label
                    for="num-batteries"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Inverter Quantity
                  </label>
                  <b-form-spinbutton
                    id="num-batteries"
                    v-model="inverterQuantity"
                    min="1"
                  />
                </b-form-group>
              </b-col> -->
            <b-col lg="4">
              <b-form-group>
                <label
                  for="num-panels"
                  class="mb-lg-1 font-weight-bolder"
                >
                  Solar Panel Quantity
                </label>
                <vue-numeric-input
                  id="num-panels"
                  v-model="solarPanelQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />
                <!-- <b-form-spinbutton
                  id="num-panels"
                  v-model="solarPanelQuantity"
                  min="1"
                /> -->
              </b-form-group>
            </b-col>
            <b-col lg="4">
              <b-form-group>
                <label
                  for="num-panels"
                  class="mb-lg-1 font-weight-bolder"
                >
                  Inverter Quantity
                </label>
                <vue-numeric-input
                  id="num-inverters"
                  v-model="inverterQuantity"
                  :min="1"
                  :step="1"
                  width="100%"
                  class="number-input"
                  align="center"
                />
                <!-- <b-form-spinbutton
                  id="num-panels"
                  v-model="solarPanelQuantity"
                  min="1"
                /> -->
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group>
                <div
                  v-for="(accessory, index) in accessories"
                  :key="`phoneInput-${index}`"
                  class="input wrapper flex items-center"
                >
                  <div lg="4">
                    <validation-provider
                      #default="{ errors }"
                      name="product name"
                      rules="required"
                    >
                      <label
                        for="product"
                        class="mb-lg-1 font-weight-bolder"
                      >
                        Accessory Type
                      </label>
                      <b-form-select
                        id="system-size"
                        v-model="accessory.subitem_id"
                        :options="accessoryType"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </div>
                  <b-col lg="4">
                    <label
                      for="num-panels"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      Accessory Quantity
                    </label>
                    <vue-numeric-input
                      id="num-inverters"
                      v-model="accessory.quantity"
                      :min="1"
                      :step="1"
                      width="100%"
                      class="number-input"
                      align="center"
                    />
                  </b-col>

                  <!--          Remove Svg Icon-->
                  <div
                    class="removeAccessory"
                    @click="removeField(accessory, accessories)"
                  >
                    Remove
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      width="24"
                      height="24"
                      class="ml-2 cursor-pointer"
                    >
                      <path
                        fill="none"
                        d="M0 0h24v24H0z"
                      />
                      <path
                        fill="#EC4899"
                        d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-9.414l2.828-2.829 1.415 1.415L13.414 12l2.829 2.828-1.415 1.415L12 13.414l-2.828 2.829-1.415-1.415L10.586 12 7.757 9.172l1.415-1.415L12 10.586z"
                      />
                    </svg>
                  </div>
                </div>

                <div
                  class="addnew"
                  @click="addField(accessory, accessories)"
                >
                  Add New Acccessory
                  <!--          Add Svg Icon-->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="ml-2 cursor-pointer"
                  >
                    <path
                      fill="none"
                      d="M0 0h24v24H0z"
                    />
                    <path
                      fill="green"
                      d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"
                    />
                  </svg>
                </div>
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
                    Product Description / Details
                  </label>
                  <b-form-textarea
                    id="description"
                    v-model="description"
                    rows="4"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
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
              @click="addGasProduct"
            >
              Add Product
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
      id="view-product"
      ref="view-product"
      hide-footer
      hide-header
      size="lg"
      centered
    >
      <validation-observer ref="editProductValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Product
          </h4>
          <b-row class="mt-1">
            <b-col cols="12">
              <b-form-group>
                <label><b>Name</b> </label>
                <Fragment>
                  <div>{{ productNameView }}</div>
                </Fragment>
              </b-form-group>
            </b-col>

            <b-col cols="12">
              <b-form-group>
                <label><b>Description</b> </label>
                <div>{{ productDescriptionView }}</div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label><b>Product Type</b> </label>
                <div>{{ ViewProductType }}</div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label><b>Inverter Type</b> </label>
                <div>{{ productinverter_type }}</div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label><b>Number of Inverters</b> </label>
                <div>{{ productnumberofinverters }}</div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label><b>Pannel Type</b> </label>
                <div>{{ productpanel_type }}</div>
              </b-form-group>
            </b-col>

            <b-col cols="6">
              <b-form-group>
                <label><b>Number of Panels</b> </label>
                <div>{{ productnumberofpanels }}</div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label><b>Battery Type</b> </label>
                <div>{{ productbatteries_type }}</div>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label><b>Number of Batteries</b> </label>
                <div>{{ productnumberofbatteries }}</div>
              </b-form-group>
            </b-col>
          </b-row>
          <div class="d-flex float-right mt-1">
            <b-button
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px; margin-right: 1em"
              @click="hideModal"
            >
              Ok
            </b-button>
            <!-- <b-button
              v-if="!isLoading"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="editProduct"
            >
              Save Changes
            </b-button>
            <b-button
              v-if="isLoading"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Saving...
            </b-button> -->
          </div>
        </b-form>
      </validation-observer>
    </b-modal>
    <b-modal
      id="edit-product"
      ref="edit-product"
      hide-footer
      hide-header
      size="lg"
      centered
    >
      <validation-observer ref="editProductValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Edit Product
          </h4>
          <b-row class="mt-1">
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="product name"
                  rules="required"
                >
                  <label
                    for="product"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Product Name
                  </label>
                  <b-form-input
                    id="product"
                    v-model="productNameEdit"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <b-col lg="12">
              <b-row>
                <b-col lg="4">
                  <label
                    for="num-batteries"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Storage Capacity
                  </label>
                  <b-form-spinbutton
                    id="num-batteries"
                    v-model="numBatteriesEdit"
                    min="1"
                    max="100"
                  />
                </b-col>
                <b-col
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <label
                    for="num-panels"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    No. of Panels
                  </label>
                  <b-form-spinbutton
                    id="num-panels"
                    v-model="numPanelsEdit"
                    min="1"
                    max="100"
                  />
                </b-col>
                <b-col
                  lg="4"
                  class="mt-lg-0 mt-1"
                >
                  <label
                    for="num-panels"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    No. of Inverters
                  </label>
                  <b-form-spinbutton
                    id="num-inverter"
                    v-model="numInvertersEdit"
                    min="1"
                    max="100"
                  />
                </b-col>
              </b-row>
            </b-col>
            <!-- <b-col
              lg="6"
              class="mt-lg-0 mt-1"
            >
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
                    <b-form-input
                      v-model="priceEdit"
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col> -->
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
                    Product Description / Details
                  </label>
                  <b-form-textarea
                    id="description"
                    v-model="descriptionEdit"
                    rows="4"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
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
              @click="editProduct"
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
        </b-form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import VueNumericInput from 'vue-numeric-input'
import Popover from 'vue-js-popover'

export default {
  components: {
    vSelect,
    ValidationProvider,
    ValidationObserver,
    VueNumericInput,
    // Popover,
    // Popper,
    Popover,
  },
  data() {
    return {
      hasBattery: false,
      ProductType: 'default',
      ViewProductType: null,
      isLoading: false,
      tableLoading: true,
      productID: null,
      productName: null,
      productNameEdit: null,
      numBatteries: 1,
      numBatteriesEdit: 1,
      numPanels: 1,
      numPanelsEdit: 1,
      numInvertersEdit: 1,
      description: null,
      descriptionEdit: null,
      price: null,
      priceEdit: null,
      solarPanelQuantity: 1,
      inverterQuantity: 1,
      batteryQuantity: 1,
      perPage: 10,
      perPageOptions: [5, 10, 25, 100],
      filter: null,
      products: [],
      solarType: null,
      inverterType: null,
      batteryType: 0,
      productNameView: null,
      productDescriptionView: null,
      productnumberofpanels: null,
      productnumberofinverters: null,
      productnumberofbatteries: null,
      productbatteries_type: null,
      productinverter_type: null,
      productpanel_type: null,
      accessories: [{ subitem_id: null, quantity: null }],
      productDiscount: null,
      ProductTypes: [
        // { value: null, title: 'Please select a product type' },
        { value: 'default', title: 'Default' },
        { value: 'meshgrid', title: 'Mesh' },
      ],
      systemSizes: [
        // { value: null, text: 'Please select a solar system size' },
        // { value: '1 - 2 kW', text: '1 - 2 kW' },
        // { value: '3.0 kW', text: '3.0 kW' },
        // { value: '4.0 kW', text: '4.0 kW' },
        // { value: '5.0 kW', text: '5.0 kW' },
        // { value: '5 - 10 kW', text: '5 - 10 kW' },
        // { value: '10 + kW', text: '10 + kW' },
      ],
      inverterTypes: [
        // { value: null, text: 'Please select a solar system size' },
      ],
      meshGridTypes: [
        // { value: null, text: 'Please select a solar system size' },
      ],
      accessoryType: [],
      batteryTypes: [
        // { value: null, text: 'Please select a solar system size' },
        // { value: '1 - 2 kW', text: '1 - 2 kW' },
        // { value: '3.0 kW', text: '3.0 kW' },
        // { value: '4.0 kW', text: '4.0 kW' },
        // { value: '5.0 kW', text: '5.0 kW' },
        // { value: '5 - 10 kW', text: '5 - 10 kW' },
        // { value: '10 + kW', text: '10 + kW' },
      ],
      productsField: [
        { key: 'productname', label: 'Product' },
        { key: 'product_type', label: 'Product Type' },
        { key: 'numberofinverters', label: 'No. of Inverters' },
        { key: 'numberofpanels', label: 'No. of Panels' },
        { key: 'numberofbatteries', label: 'No. of Batteries' },
        {
          key: 'action',
          label: 'Actions',
          thStyle: { width: '10%', textAlign: 'center' },
        },
      ],
      totalRows: 0,
      currentPage: 1,
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
    priceEdit(newValue) {
      const result = newValue
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, ',')
      // eslint-disable-next-line no-return-assign
      this.$nextTick(() => (this.priceEdit = result))
    },
  },
  mounted() {
    // document.getElementById("custom-breadcrumb").innerHTML = "Products";
    this.getSubItems(1)
    this.getSubItems(2)
    this.getSubItems(3)
    this.getSubItems(4)
    this.getSubItems(5)
    this.getSubItems(6)
  },
  created() {
    this.getProducts(1)
    this.getProducts(2)
    this.getProducts(3)
  },
  methods: {
    typeChanged(event) {
      // update hasBattery
      console.log(event, 'jdjdjdjd')
    },
    inverterTypeChanged(event) {
      console.log(event.target.value, 'inverter event', event.target, event.value)
      console.log(this.inverterTypes, 'dsskksks')

      const inverter = this.meshGridTypes.filter(inverterType => inverterType.value == event.target.value)

      if (inverter[0]?.hasb3 == 'yes') {
        if (this.ProductType == 'meshgrid') {
          this.hasBattery = true
        }
      } else {
        this.hasBattery = false
      }
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    addField(value, fieldType) {
      console.log(value, fieldType, 'adfiled>>>>')
      fieldType.push({ value: '' })
    },
    removeField(index, fieldType) {
      fieldType.splice(index, 1)
    },
    getSubItems(item) {
      const itemID = item
      this.$store
        .dispatch('inventory/getSubItemByID', { itemID })
        .then(res => {
          const { data } = res.data
          console.log(res, 'ALLLL')
          if (item === 2) {
            data.forEach(subItem => {
              const systemSize = { value: subItem.id, text: subItem.name, hasb3: subItem.hasb3 }
              this.systemSizes.push(systemSize)
            })
          } else if (item === 1) {
            data.forEach(subItem => {
              const inverterType = { value: subItem.id, text: subItem.name, hasb3: subItem.hasb3 }
              this.inverterTypes.push(inverterType)
            })
          } else if (item === 4) {
            data.forEach(subItem => {
              const meshGridType = { value: subItem.id, text: subItem.name, hasb3: subItem.hasb3 }
              this.meshGridTypes.push(meshGridType)
            })
          } else if (item === 5) {
            data.forEach(subItem => {
              const accessoryType = { value: subItem.id, text: subItem.name, hasb3: subItem.hasb3 }
              this.accessoryType.push(accessoryType)
            })
          } else if (item === 3) {
            console.log(res, 'fifth')
            data.forEach(subItem => {
              const batteryType = { value: subItem.id, text: subItem.name, hasb3: subItem.hasb3 }
              this.batteryTypes.push(batteryType)
            })
          } else {
            console.log(res, 'ELSEEE')
            // data.forEach(subItem => {
            //   const batteryType = { value: subItem.id, text: subItem.name, hasb3: subItem.hasb3 }
            //   this.batteryTypes.push(batteryType)
            // })
          }
        })
    },
    hideModal() {
      this.$refs['add-product'].hide()
      this.$refs['add-gas-product'].hide()
      this.$refs['add-street-light-product'].hide()
      this.$refs['view-product'].hide()
    },
    hideEditModal() {
      this.$refs['edit-product'].hide()
    },
    deleteProduct(product) {
      this.$swal({
        title: `Delete ${product.productname}?`,
        text: 'Please note, deleting a product is irreversible!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if (result.value) {
          const form = {
            productID: product.id,
          }
          this.$store
            .dispatch('product/deleteProduct', { form })
            .then(res => {
              this.$store.dispatch('product/getProducts').then(res2 => {
                this.isLoading = false
                const { message } = res.data
                const { data } = res2.data
                this.products = data
                this.$swal({
                  title: `Deleted ${product.productname}`,
                  text: message,
                  icon: 'success',
                })
              })
            })
            .catch(err => {
              this.isLoading = false
              this.$swal({
                title: `Delete ${product.productname}?`,
                text: err.response.data.message,
                icon: 'danger',
              })
            })
        }
      })
    },
    edit(product) {
      this.productID = product.id
      this.productNameEdit = product.productname
      this.numBatteriesEdit = parseInt(product.numberofbatteries, 10)
      this.numPanelsEdit = parseInt(product.numberofpanels, 10)
      this.numInvertersEdit = parseInt(product.numInvertersEdit, 10)
      this.descriptionEdit = product.description
      this.priceEdit = parseFloat(product.price).toString()
      this.$refs['edit-product'].show()
    },
    viewProductDesc(product) {
      console.log(product, 'fjjdkjd')
      this.ViewProductType = product.product_type
      this.productDescriptionView = product.description
      this.productNameView = product.productname
      this.productnumberofpanels = product.numberofpanels
      this.productnumberofinverters = product.numberofinverters
      this.productnumberofbatteries = product.numberofbatteries
      this.productbatteries_type = product.batteries_type
      this.productinverter_type = product.inverter_type
      this.productpanel_type = product.panel_type

      this.$refs['view-product'].show()
    },
    editProduct() {
      this.isLoading = true
      this.$refs.editProductValidation.validate().then(success => {
        if (success) {
          const form = {
            productID: this.productID,
            productName: this.productNameEdit,
            numBatteries: this.numBatteriesEdit,
            numPanels: this.numPanelsEdit,
            numInverters: this.numInvertersEdit,
            description: this.descriptionEdit,
            // price: parseFloat(this.priceEdit.replace(/,/g, '')),
          }
          this.$store
            .dispatch('product/editProduct', { form })
            .then(res => {
              this.$store.dispatch('product/getProducts').then(res2 => {
                this.isLoading = false
                const { message } = res.data
                const { data } = res2.data
                this.products = data
                this.hideEditModal()
                this.toast(
                  'Edit Product',
                  'CheckCircleIcon',
                  message,
                  'success',
                )
              })
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
    addProduct() {
      this.isLoading = true
      this.$refs.addProductValidation.validate().then(success => {
        if (success) {
          const form = {
            productName: this.productName,
            type: this.ProductType,
            numBatteries: this.numBatteries,
            numPanels: this.numPanels,
            description: this.description,
            solarPanelQuantity: this.solarPanelQuantity,
            inverterQuantity: this.inverterQuantity,
            batteryQuantity: this.batteryQuantity,
            solarpaneltypeid: this.solarType,
            invertertypeid: this.inverterType,
            batterytypeid: this.batteryType,
            accessories: this.accessories[0].subitem_id !== null ? JSON.stringify(this.accessories) : '',
            productDiscount: this.productDiscount,
            numberofpanels: this.numberofpanels,
            // price: parseFloat(this.price.replace(/,/g, '')),
          }

          this.$store
            .dispatch('product/addProduct', { form })
            .then(res => {
              this.$store.dispatch('product/getProducts').then(res2 => {
                this.isLoading = false
                const { message } = res.data
                const { data } = res2.data
                this.products = data
                this.hideModal()
                this.toast(
                  'Add Product',
                  'CheckCircleIcon',
                  message,
                  'success',
                )
              })
            })
            .catch(err => {
              this.isLoading = false
              this.toast(
                'Add Product',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Add Product Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
        }
      })
    },
    addGasProduct() {
      this.isLoading = true
      this.$refs.addProductValidation.validate().then(success => {
        if (success) {
          const form = {
            productName: this.productName,
            numBatteries: this.numBatteries,
            type: this.ProductType,
            numPanels: this.numPanels,
            description: this.description,
            solarPanelQuantity: this.solarPanelQuantity,
            inverterQuantity: this.inverterQuantity,
            batteryQuantity: this.batteryQuantity,
            solarpaneltypeid: this.solarType,
            invertertypeid: this.inverterType,
            batterytypeid: this.batteryType,
            accessories: JSON.stringify(this.accessories),
            productDiscount: this.productDiscount,
            // price: parseFloat(this.price.replace(/,/g, '')),
          }

          this.$store
            .dispatch('product/addProduct', { form })
            .then(res => {
              this.$store.dispatch('product/getProducts').then(res2 => {
                this.isLoading = false
                const { message } = res.data
                const { data } = res2.data
                this.products = data
                this.hideModal()
                this.toast(
                  'Add Product',
                  'CheckCircleIcon',
                  message,
                  'success',
                )
              })
            })
            .catch(err => {
              this.isLoading = false
              this.toast(
                'Add Product',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Add Product Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
        }
      })
    },
    addStreetLightProduct() {
      this.isLoading = true
      this.$refs.addProductValidation.validate().then(success => {
        if (success) {
          const form = {
            productName: this.productName,
            numBatteries: this.numBatteries,
            type: this.ProductType,
            numPanels: this.numPanels,
            description: this.description,
            solarPanelQuantity: this.solarPanelQuantity,
            inverterQuantity: this.inverterQuantity,
            batteryQuantity: this.batteryQuantity,
            solarpaneltypeid: this.solarType,
            invertertypeid: this.inverterType,
            batterytypeid: this.batteryType,
            accessories: JSON.stringify(this.accessories),
            productDiscount: this.productDiscount,
            // price: parseFloat(this.price.replace(/,/g, '')),
          }

          this.$store
            .dispatch('product/addProduct', { form })
            .then(res => {
              this.$store.dispatch('product/getProducts').then(res2 => {
                this.isLoading = false
                const { message } = res.data
                const { data } = res2.data
                this.products = data
                this.hideModal()
                this.toast(
                  'Add Product',
                  'CheckCircleIcon',
                  message,
                  'success',
                )
              })
            })
            .catch(err => {
              this.isLoading = false
              this.toast(
                'Add Product',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Add Product Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
        }
      })
    },
    getProducts(id) {
      this.$store.dispatch('product/getProducts', { id }).then(res => {
        const { data } = res.data
        this.products = data
        this.totalRows = this.products.length
        this.tableLoading = false
      })
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

.flex {
  display: flex;
  padding: 0;
  align-items: center;
  row-gap: 1rem;
  column-gap: 1rem;
}

.addnew {
  background-color: #4D6D8A;
  width: max-content;
  padding: 0.5rem 1rem;
  margin-top: 2rem;
  color: #ffffff;
  cursor: pointer;
}

.removeAccessory {
  background-color: red;
  width: max-content;
  padding: 0.5rem 1rem;
  margin-top: 2rem;
  color: #ffffff;
  cursor: pointer;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
