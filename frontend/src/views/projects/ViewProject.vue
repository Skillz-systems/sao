<template>
  <div>
    <b-spinner
      v-if="!loaded"
      style="width: 3rem; height: 3rem;"
      variant="primary"
      label="Loading..." type="grow"
      class="text-center"
    />
    <div v-if="loaded">
      <b-row>
        <b-col cols="12">
          <b-card>
            <b-media>
              <template #aside>
                <b-avatar
                  :text="avatarText(project.client.clientname)"
                  variant="light-dark"
                  size="130px"
                  class="mr-1"
                />
              </template>
              <b-button
                v-b-modal.send-message
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                class="d-flex float-lg-right"
              >
                <feather-icon
                  icon="MessageSquareIcon"
                  size="17"
                  class="mr-lg-1"
                  style="margin-right: 0.17em"
                />
                SEND MESSAGE
              </b-button>
              <div class="total-cost">
                <span>
                  <h5>Project Discounted Total Cost: &nbsp;</h5>
                </span>
                <span class="text-muted">
                  <h3>
                    <del style="text-decoration-style: double;">N</del>
                    {{
                      parseFloat(orders.price).toFixed(2)

                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </h3>
                </span>
              </div>
              <div class="total-cost">
                <span>
                  <h5>Project Actual Total Cost: &nbsp;</h5>
                </span>
                <span class="text-muted">
                  <h3>
                    <del style="text-decoration-style: double;">N</del>
                    {{
                      parseFloat(orders.actual_amount).toFixed(2)
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </h3>
                </span>
              </div>
              <div class="total-cost">
                <span>
                  <h5>Discount &nbsp;</h5>
                </span>
                <span class="text-muted">
                  <h3>{{ orders.discount_value }}%</h3>
                </span>
              </div>

              <h3 class="mt-1">
                {{ project.client.clientname }}
              </h3>
              <p>
                <b-img
                  :src="
                    require('@/assets/images/icons/view-project-project-icon.svg')
                  "
                  style="margin-right: 0.5em;"
                />
                <span class="text-muted">{{ project.projectname }}</span>
              </p>
              <p class="mt-0">
                <b-img
                  :src="
                    require('@/assets/images/icons/view-project-grid-icon.svg')
                  "
                  style="margin-right: 0.5em;"
                />
                <span class="text-muted">{{ project.projecttype }}</span>
              </p>

              <p class="mt-0">
                <b-img
                  :src="
                    require('@/assets/images/icons/view-project-location-icon.svg')
                  "
                  style="margin-right: 0.5em;"
                />
                <span class="text-muted">{{ project.state }}</span>
              </p>
            </b-media>
          </b-card>
        </b-col>
      </b-row>

      <b-row
        v-if="orders.installations.length > 0"
        b-row
        class="mt-1"
      >
        <b-col lg="6">
          <b-card no-body>
            <b-card-header class="mt-n1">
              <h4 style="color: #4D6D8A; margin-top: 0.5em">
                Client Info
              </h4>
              <feather-icon
                v-b-tooltip.hover.top="'Edit'"
                right
                icon="Edit3Icon"
                size="16"
                class="cursor-pointer"
                style="color: #4D6D8A;"
                @click="editClient(project.clientid)"
              />
            </b-card-header>
            <div style="padding: 0 21px 10px 21px;">
              <p>
                <strong class="mr-1">Client Name:</strong>
                <span>{{ orders.client.clientname }}</span>
              </p>
              <p>
                <strong class="mr-1">Project Name:</strong>
                <span>{{ orders.projectname }}</span>
              </p>
              <p>
                <strong class="mr-1">Location:</strong>
                <span>{{ orders.state }}</span>
              </p>
              <p>
                <strong class="mr-1">House Size:</strong>
                <span>{{ orders.client.housesize }}</span>
              </p>
              <p>
                <strong class="mr-1">Address:</strong>
                <span>{{ orders.client.address }}</span>
              </p>
              <p>
                <strong class="mr-1">Phone:</strong>
                <span>{{ orders.client.phone }}</span>
              </p>
              <p>
                <strong class="mr-1">Email:</strong>
                <span>{{ orders.client.email }}</span>
              </p>
              <p>
                <strong class="mr-1">Client Type:</strong>
                <span>{{ orders.client.clienttype }}</span>
              </p>
              <p>
                <strong class="mr-1">State/City:</strong>
                <span>{{ orders.client.state }}</span>
              </p>
            </div>
          </b-card>
        </b-col>
        <b-col lg="6">
          <b-card no-body>
            <b-card-header class="mt-n1">
              <h4 style="color: #4D6D8A; margin-top: 0.5em">
                Product Info
              </h4>
              <feather-icon
                v-b-tooltip.hover.top="'Edit'"
                right
                icon="Edit3Icon"
                size="16"
                class="cursor-pointer"
                style="color: #4D6D8A;"
                @click="edit(project)"
              />
            </b-card-header>
            <div style="padding: 0 21px 10px 21px;">
              <p>
                <strong class="mr-1">Product Name:</strong>
                <span>{{ orders.product.productname }}</span>
              </p>
              <p>
                <strong class="mr-1">Project Type:</strong>
                <span>{{ orders.projecttype }}</span>
              </p>
              <p>
                <strong class="mr-1">System Size:</strong>
                <span>{{ orders.solarsystemsize }}</span>
              </p>
              <p>
                <strong class="mr-1">Installation Type:</strong>
                <span>{{ orders.installationtype }}</span>
              </p>
              <p>
                <strong class="mr-1">Number of Panels:</strong>
                <span>{{ orders.numberofpanels }}</span>
              </p>
              <p>
                <strong class="mr-1">Number of Batteries:</strong>
                <span>{{ orders.numberofbatteries }}</span>
              </p>
              <p>
                <strong class="mr-1">Status:</strong>
                <span>{{ orders.status }}</span>
              </p>
              <p>
                <strong class="mr-1">Description:</strong>
                <span>{{ orders.description }}</span>
              </p>
            </div>
          </b-card>
        </b-col>
        <b-col lg="4">
          <!-- <info-card
            :front-type="'graph'"
            :front-title="front.title"
            :front-data="front.graphData"
            :back-title="back.title"
            :back-data="back.message"
          /> -->
          <b-card no-body>
            <b-card-header class="mt-n1">
              <h4 style="color: #4D6D8A; margin-top: 0.5em">
                Payment Info
              </h4>
            </b-card-header>
            <div style="padding: 0 21px 10px 21px;">
              <div style="display: flex;">
                <strong class="mr-1">Payment Amount:</strong>
                <span class="text-muted">
                  <h3>
                    <del style="text-decoration-style: double;">N</del>
                    {{
                      orders.price
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </h3>
                </span>
              </div>
              <p>
                <strong class="mr-1">Total Amount Paid:</strong>
                <span> <del style="text-decoration-style: double;">N</del> {{ allAmountsPaidIN.toString()
                  .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                }} </span>
              </p>
              <p>
                <strong class="mr-1">Mode of Payment:</strong>
                <span>{{ orders.mode_of_payment || "null" }}</span>
              </p>
              <p>
                <strong class="mr-1">Payment Deadline:</strong>
                <span>{{ orders.payment_duration || "nill" }} </span>
              </p>
              <!-- <p>
                <strong class="mr-1">Type of Payment:</strong>

                <span v-if="orders.type_of_payment == 1">Card</span>
                <span v-if="orders.type_of_payment == 2">Transfer</span>
              </p> -->

              <hr>
              <!-- <div style="display: flex;">
                <strong class="mr-1">Paid:</strong>
                <span class="text-muted">
                  nill
                </span>
              </div> -->

              <div style="display: flex;">
                <!-- <strong class="mr-1">To Pay:</strong>
                <span class="text-muted">
                  <h3>
                    <del style="text-decoration-style: double;">N</del>
                    {{
                      orders.price
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </h3>
                </span> -->

                <div>
                  <div v-if="allAmountsPaidIN < Number(orders.price)">

                    <b style="color: red">To Balance:</b> <del style="text-decoration-style: double;">N</del>
                    {{ (parseFloat((Number(orders.price)) - Number(allAmountsPaidIN))).toFixed(2).toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </div>
                  <div v-if="allAmountsPaidIN == Number(orders.price)">

                    <b style="color: green">Fully Paid:</b> <del style="text-decoration-style: double;">N</del> {{ allAmountsPaidIN.toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </div>

                  <div v-if="allAmountsPaidIN > Number(orders.price)">
                    <b style="color: red">Over Paid:</b> <del style="text-decoration-style: double;">N</del> {{ (Number(allAmountsPaidIN) - parseFloat(orders.price)).toFixed(2).toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </div>
                </div>
              </div>
            </div>
          </b-card>
        </b-col>
        <!-- ////// -->
        <!-- <b-card> -->

        <!-- <b-col lg="6">
            <b-card no-body> -->

        <b-col lg="8">
          <b-card no-body>
            <b-card-header class="mt-n1">
              <h4 style="color: #4D6D8A; margin-top: 0.5em">
                Payment History
              </h4>

            </b-card-header>
            <b-col>
              <b-card
                no-body
                class="card-company-table"
              >
                <b-table
                  :items="allPayments"
                  responsive
                  :fields="productsField"
                  class="mb-0"
                >
                  <!-- type_of_payment -->
                  <template #cell(type_of_payment)="data">
                    <div v-if="data.item.type_of_payment == 1">
                      Card
                    </div>
                    <div v-if="data.item.type_of_payment == 2">
                      Transfer
                    </div>
                  </template>
                  <template #cell(serial)="data">
                    {{ data.item.serial + 1 }}
                  </template>

                  <template #cell(amount)="data">
                    <del style="text-decoration-style: double;">N</del> {{ data.item.amount.toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </template>

                  <template #cell(actual_amount)="data">
                    <del style="text-decoration-style: double;">N</del> {{ data.item.actual_amount.toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </template>
                  <template #cell(created_at)="data">
                    {{ $moment(String(data.item.created_at)).format('MM/DD/YYYY hh:mm')

                    }}
                  </template>
                  <!--  -->
                </b-table>
              </b-card>
            </b-col>

          </b-card>
        </b-col>

        <!-- </b-card> -->
        <b-col
          v-for="(order, index) in orders.installations"
          :key="index"
          class="mt-1"
          lg="12"
        >
          <b-card no-body>
            <b-card-header class="mt-n1">
              <h4 style="color: #4D6D8A; margin-top: 0.5em">
                Installation {{ index + 1 }}
              </h4>

              <b-dropdown
                variant="link"
                no-caret
                class="chart-dropdown"
                toggle-class="p-0"
                dropleft
              >
                <template #button-content>
                  <feather-icon
                    icon="MoreVerticalIcon"
                    size="18"
                    class="text-body cursor-pointer"
                  />
                </template>
                <b-dropdown-item @click="advancedEdit(order.id)">
                  <feather-icon
                    icon="EyeIcon"
                    size="15"
                    class="mr-1"
                  />
                  Advanced Edit
                </b-dropdown-item>
                <b-dropdown-item @click="basicEdit(order)">
                  <feather-icon
                    icon="EditIcon"
                    size="15"
                    class="mr-1"
                  />
                  Edit Serial Numbers
                </b-dropdown-item>
                <b-dropdown-item @click="cordinatesEdit(order.address_id)">
                  <feather-icon
                    icon="EditIcon"
                    size="15"
                    class="mr-1"
                  />
                  Edit Coordinates
                </b-dropdown-item>
                <!-- <b-dropdown-item @click="deleteProject(project)">
                  <feather-icon icon="TrashIcon" size="15" class="mr-1" />
                  Delete
                </b-dropdown-item> -->
              </b-dropdown>
            </b-card-header>

            <div style="padding: 0 21px 10px 21px;">
              <div style="display: flex;">
                <strong class="mr-1">Product Price:</strong>
                <h3>
                  <del style="text-decoration-style: double;">N</del>
                  {{
                    (order.actual_amount / (index + 1))
                      .toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                  }}
                </h3>
              </div>

              <p>
                <strong class="mr-1">Discounted Product Price:</strong>
                <span>
                  {{
                    (order.amount / (index + 1))
                      .toString()
                      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                  }}</span>
              </p>

              <p>
                <strong class="mr-1">Installation Number:</strong>
                <span>{{ order.order_number }}</span>
              </p>
              <p>
                <strong class="mr-1">Latitude:</strong>
                <span>{{ order.orderaddress.log }}</span>
              </p>
              <p>
                <strong class="mr-1">Longitude:</strong>
                <span>{{ order.orderaddress.lat }}</span>
              </p>

              <p>
                <strong class="mr-1">Address Description:</strong>
                <span>{{ order.orderaddress.address_description }}</span>
              </p>
              <p>
                <strong class="mr-1">State:</strong>
                <span>{{ order.orderaddress.state.sname }}</span>
              </p>
              <p>
                <strong class="mr-1">LGA:</strong>
                <span>{{ order.orderaddress.lga.lganame }}</span>
              </p>
              <!-- <p><strong class="mr-1">kkkkk:</strong> <span>{{ order.orderdetails.updated_at }}</span></p> -->
              <hr>

              <div style="margin-bottom: 1rem; margin-top:'2rem">
                <b>
                  <h3>PRODUCT PACKAGES</h3>
                </b>
              </div>

              <div
                class="package-categories"
                style="display: flex; justify-content: space-between"
              >
                <div>
                  <div class="package-image-cont">
                    <div>
                      <h3 class="package-text">
                        <b>Accessories</b>
                      </h3>
                    </div>

                  </div>

                  <div class="order-cards">
                    <b-table
                      :items="miscellanClone"
                      responsive
                      :fields="packageField"
                      class="mb-0"
                    >
                      <template #cell(price)="data">
                        <del style="text-decoration-style: double;">N</del> {{ data.item.price.toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                      <template #cell(total)="data">
                        <del style="text-decoration-style: double;">N</del> {{ (data.item.count * data.item.price).toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}
                      </template>
                    </b-table>
                  </div>
                </div>
                <div>
                  <div class="package-image-cont">
                    <div>
                      <h3 class="package-text">
                        <b>Miscellaneous</b>
                      </h3>
                    </div>

                  </div>
                  <div class="order-cards">

                    <b-table
                      :items="orders.miscallaneous"
                      responsive
                      :fields="miscellaneousField"
                      class="mb-0"
                    >

                      <template #cell(amount)="data">
                        <del style="text-decoration-style: double;">N</del> {{ data.item.amount.toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                      <template #cell(miscellaneous_id)="data">
                        {{ data.item.mainmiscallaneous.title.toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                    </b-table>
                  </div>
                </div>
                <!-- <div>
                  <div class="package-image-cont">
                    <div>
                      <h3 class="package-text">
                        <b>Street Light</b>
                      </h3>
                    </div>

                  </div>
                  <div class="order-cards">
                    <b-table
                      :items="order.orderdetails.street_light"
                      responsive
                      :fields="packageField"
                      class="mb-0"
                    >
                      <template #cell(price)="data">
                        <del style="text-decoration-style: double;">N</del> {{ data.item.price.toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                    </b-table>
                  </div>
                </div>
                <div>
                  <div class="package-image-cont">
                    <div>
                      <h3 class="package-text">
                        <b>Gas</b>
                      </h3>
                    </div>

                  </div>
                  <div class="order-cards">
                    <b-table
                      :items="order.orderdetails.gas"
                      responsive
                      :fields="packageField"
                      class="mb-0"
                    >
                      <template #cell(price)="data">
                        <del style="text-decoration-style: double;">N</del> {{ data.item.price.toString()
                          .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                      </template>
                    </b-table>
                  </div>
                </div> -->
              </div>

              <div class="package-categories">
                <div class="package-image-cont">
                  <div>
                    <h3 class="package-text">
                      <b>Inverters</b>
                    </h3>
                  </div>
                  <div class="package-image">
                    <!-- <img
                      class="package_image"
                      style="max-width:200px;"
                      alt="Vue logo"
                      src="../../assets/solar.jpeg"
                    > -->
                  </div>
                </div>
                <div class="order-cards">
                  <div
                    v-for="(orderDetails, index) in order.orderdetails.inverter"
                    :key="index"
                    class="mt-1 order-card"
                  >
                    <p>
                      <strong class="mr-1">Name:</strong>
                      <span>{{ orderDetails.name }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Price:</strong>
                      <span>{{ orderDetails.price }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Rating:</strong>
                      <span>{{ orderDetails.rating }}</span>
                    </p>
                    <!-- <p>
                      <strong class="mr-1">Quantity:</strong>
                      <span>{{ orderDetails.quantity }}</span>
                    </p> -->
                  </div>
                </div>
              </div>
              <!-- ////// -->
              <div class="package-categories">
                <div class="package-image-cont">
                  <div>
                    <h3 class="package-text">
                      <b>Batteries</b>
                    </h3>
                  </div>
                  <div class="package-image">
                    <!-- <img
                      style="max-width:200px;"
                      alt="Vue logo"
                      class="package_image"
                      src="../../assets/battery.jpeg"
                    > -->
                  </div>
                </div>
                <div class="order-cards">
                  <!-- <div class="package-image">
                  <img
                    style="max-width:200px;"
                    alt="Vue logo"

                  />
                </div> -->

                  <div
                    v-for="(orderDetails, index) in order.orderdetails
                      .batteries"
                    :key="index"
                    class="mt-1 order-card"
                  >
                    <p>
                      <strong class="mr-1">Name:</strong>
                      <span>{{ orderDetails.name }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Price:</strong>
                      <span>{{ orderDetails.price }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Rating:</strong>
                      <span>{{ orderDetails.rating }}</span>
                    </p>
                  </div>
                </div>
              </div>
              <!-- </div> -->
              <!-- //gofof -->
              <div class="package-categories">
                <div class="package-image-cont">
                  <div>
                    <h3 class="package-text">
                      <b>Solar Panel</b>
                    </h3>
                  </div>
                  <div class="package-image">
                    <!-- <img
                      class="package_image"
                      style="max-width:200px;"
                      alt="Vue logo"
                      src="../../assets/spanel.webp"
                    > -->
                  </div>
                </div>
                <div class="order-cards">
                  <!-- <div class="package-image">
                  <img
                    style="max-width:200px;"
                    alt="Vue logo"

                  />
                </div> -->

                  <div
                    v-for="(orderDetails, index) in order.orderdetails.pannel"
                    :key="index"
                    class="mt-1 order-card"
                  >
                    <p>
                      <strong class="mr-1">Name:</strong>
                      <span>{{ orderDetails.name }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Price:</strong>
                      <span>{{ orderDetails.price }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Rating:</strong>
                      <span>{{ orderDetails.rating }}</span>
                    </p>
                    <!-- <p>
                      <strong class="mr-1">Quantity:</strong>
                      <span>{{ orderDetails.quantity }}</span>
                    </p> -->
                  </div>
                </div>

              </div>
              <div class="package-categories">
                <div class="package-image-cont">
                  <div>
                    <h3 class="package-text">
                      <b>Gas</b>
                    </h3>
                  </div>
                  <div class="package-image">
                    <!-- <img
                      class="package_image"
                      style="max-width:200px;"
                      alt="Vue logo"
                      src="../../assets/solar.jpeg"
                    > -->
                  </div>
                </div>
                <div class="order-cards">
                  <div
                    v-for="(orderDetails, index) in order.orderdetails.gas"
                    :key="index"
                    class="mt-1 order-card"
                  >
                    <p>
                      <strong class="mr-1">Name:</strong>
                      <span>{{ orderDetails.name }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Price:</strong>
                      <span>{{ orderDetails.price }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Rating:</strong>
                      <span>{{ orderDetails.rating }}</span>
                    </p>
                    <!-- <p>
                      <strong class="mr-1">Quantity:</strong>
                      <span>{{ orderDetails.quantity }}</span>
                    </p> -->
                  </div>
                </div>
              </div>
              <div class="package-categories">
                <div class="package-image-cont">
                  <div>
                    <h3 class="package-text">
                      <b>Street Light</b>
                    </h3>
                  </div>
                  <div class="package-image">
                    <!-- <img
                      class="package_image"
                      style="max-width:200px;"
                      alt="Vue logo"
                      src="../../assets/solar.jpeg"
                    > -->
                  </div>
                </div>
                <div class="order-cards">
                  <div
                    v-for="(orderDetails, index) in order.orderdetails.street_light"
                    :key="index"
                    class="mt-1 order-card"
                  >
                    <p>
                      <strong class="mr-1">Name:</strong>
                      <span>{{ orderDetails.name }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Price:</strong>
                      <span>{{ orderDetails.price }}</span>
                    </p>
                    <p>
                      <strong class="mr-1">Rating:</strong>
                      <span>{{ orderDetails.rating }}</span>
                    </p>
                    <!-- <p>
                      <strong class="mr-1">Quantity:</strong>
                      <span>{{ orderDetails.quantity }}</span>
                    </p> -->
                  </div>
                </div>
              </div>
            </div>
          </b-card>
        </b-col>
      </b-row>

      <b-modal
        id="send-message"
        ref="send-message"
        hide-footer
        hide-header
        centered
      >
        <validation-observer ref="sendMessageValidation">
          <b-form @submit.prevent>
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Leave Message
            </h4>
            <b-row class="mt-1">
              <b-col
                cols="12"
                class="mb-1"
              >
                <validation-provider
                  #default="{ errors }"
                  name="subject"
                  rules="required"
                >
                  <b-form-input
                    id="product"
                    v-model="subject"
                    placeholder="Enter subject"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-col>
              <b-col cols="12">
                <validation-provider
                  #default="{ errors }"
                  name="message"
                  rules="required"
                >
                  <b-form-textarea
                    id="textarea-default"
                    v-model="message"
                    placeholder="Write message"
                    rows="5"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
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
                @click="sendMessage"
              >
                Send
              </b-button>
              <b-button
                v-if="isLoading"
                disabled
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              >
                Sending...
              </b-button>
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
              <b-col lg="6">
                <b-row>
                  <b-col lg="6">
                    <label
                      for="num-batteries"
                      class="mb-lg-1 font-weight-bolder"
                    >
                      No. of Batteries
                    </label>
                    <b-form-spinbutton
                      id="num-batteries"
                      v-model="numBatteriesEdit"
                      min="1"
                      max="100"
                    />
                  </b-col>
                  <b-col
                    lg="6"
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
                </b-row>
              </b-col>
              <b-col
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
                      <b-form-input v-model="priceEdit" />
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
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

      <b-modal
        id="edit-coordinate"
        ref="edit-coordinate"
        hide-footer
        hide-header
        size="lg"
        centered
      >
        <validation-observer ref="editProductValidation">
          <b-form @submit.prevent>
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Edit-coordinate
            </h4>

            <b-col lg="12">
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
                  style="position: relative; z-index: 1000000"
                  classname="form-control"
                  placeholder="Please type your current address"
                  country="ng"
                  @placechanged="getAddressData"
                />
              </b-form-group>
            </b-col>

            <div class="d-flex float-right mt-1">
              <b-button
                style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
                @click="hideSerialNumberModal"
              >
                Cancel
              </b-button>
              <b-button
                v-if="!isLoading"
                type="submit"
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                @click="editSerialNumbers"
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

      <b-modal
        id="edit-product"
        ref="edit-serial-number"
        hide-footer
        hide-header
        size="lg"
        centered
      >
        <validation-observer ref="editProductValidation">
          <b-form @submit.prevent>
            <h4 style="color: #4D6D8A; margin-top: 0.5em">
              Edit Serial Numbers
            </h4>
            <div class="editserial-category">
              <div>
                <h3>Accessories</h3>
              </div>
              <b-row
                v-if="installationGadgets !== undefined"
                class=""
              >
                <b-col
                  v-for="(accessories,
                          index) in installationGadgets.accessories"
                  :key="index"
                  class="margin-bottom"
                  lg="6"
                >

                  <div><b>Product Name: </b> {{ accessories.name }}</div>
                  <div><b>Product Rating:</b> {{ accessories.rating }}</div>
                  <b-form-input
                    id="product"
                    v-model="accessories.serial"
                    placeholder="Enter Serial Number"
                  />
                </b-col>
              </b-row>
            </div>
            <hr>
            </hr>
            <div class="editserial-category">
              <div>
                <h3>Batteries</h3>
              </div>
              <b-row
                v-if="installationGadgets !== undefined"
                class=""
              >
                <b-col
                  v-for="(batteries, index) in installationGadgets.batteries"
                  :key="index"
                  class="margin-bottom"
                  lg="6"
                >
                  <div><b>Product Name: </b> {{ batteries.name }}</div>
                  <div><b>Product Rating:</b> {{ batteries.rating }}</div>
                  <b-form-input
                    id="product"
                    v-model="batteries.serial"
                    placeholder="Enter Serial Number"
                  />
                </b-col>
              </b-row>
            </div>
            <hr>
            </hr>
            <div class="editserial-category">
              <div>
                <h3>Inverters</h3>
              </div>
              <b-row
                v-if="installationGadgets !== undefined"
                class=""
              >
                <b-col
                  v-for="(inverter, index) in installationGadgets.inverter"
                  :key="index"
                  class="margin-bottom"
                  lg="6"
                >
                  <div><b>Product Name: </b> {{ inverter.name }}</div>
                  <div><b>Product Rating:</b> {{ inverter.rating }}</div>
                  <b-form-input
                    id="product"
                    v-model="inverter.serial"
                    placeholder="Enter Serial Number"
                  />
                </b-col>
              </b-row>
            </div>
            <hr>
            </hr>
            <div class="editserial-category">
              <div>
                <h3>Panels</h3>
              </div>
              <b-row
                v-if="installationGadgets !== undefined"
                class=""
              >
                <b-col
                  v-for="(pannel, index) in installationGadgets.pannel"
                  :key="index"
                  class="margin-bottom"
                  lg="6"
                >
                  <div><b>Product Name: </b> {{ pannel.name }}</div>
                  <div><b>Product Rating:</b> {{ pannel.rating }}</div>
                  <b-form-input
                    id="product"
                    v-model="pannel.serial"
                    placeholder="Enter Serial Number"
                  />
                </b-col>
              </b-row>
            </div>
            <hr>
            </hr>
            <div
              v-if="installationGadgets.street_light !== undefined"
              class="editserial-category"
            >
              <div>
                <h3>Street Light</h3>
              </div>
              <b-row class="">
                <b-col
                  v-for="(street_light,
                          index) in installationGadgets.street_light"
                  :key="index"
                  class=""
                  lg="6"
                >

                  <div><b>Product Name: </b> {{ street_light.name }}</div>
                  <div><b>Product Rating:</b> {{ street_light.rating }}</div>
                  <b-form-input
                    id="product"
                    v-model="street_light.serial"
                    placeholder="Enter Serial Number"
                  />
                </b-col>
              </b-row>
            </div>
            <hr>
            </hr>
            <div class="editserial-category">
              <div>
                <h3>Gas</h3>
              </div>
              <b-row
                v-if="installationGadgets !== undefined"
                class=""
              >
                <b-col
                  v-for="(gas, index) in installationGadgets.gas"
                  :key="index"
                  class="margin-bottom"
                  lg="6"
                >

                  <div><b>Product Name: </b> {{ gas.name }}</div>
                  <div><b>Product Rating:</b> {{ gas.rating }}</div>
                  <b-form-input
                    id="product"
                    v-model="gas.serial"
                    placeholder="Enter Serial Number"
                  />
                </b-col>
              </b-row>
            </div>

            <div class="d-flex float-right mt-1">
              <b-button
                style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
                @click="hideSerialNumberModal"
              >
                Cancel
              </b-button>
              <b-button
                v-if="!isLoading"
                type="submit"
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                @click="editSerialNumbers"
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
  </div>
</template>

<script>
import Vue from 'vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import { avatarText } from '@core/utils/filter'
// import VueNumerals from 'vue-numerals';
import axios from 'axios'
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import moment from 'vue-moment'
// import moment from 'moment-timezone'
import InfoCard from 'vue-info-card'

Vue.use(moment)

export default {
  components: {
    // vSelect,
    // VueNumerals,
    ValidationProvider,
    ValidationObserver,
    VueGoogleAutocomplete,
    moment,
    InfoCard,

  },
  data() {
    return {
      miscellanClone: [],
      allAmountsPaidIN: null,
      allAmountsPaid: null,
      productsField: [
        { key: 'serial', label: 'S/N' },
        { key: 'actual_amount', label: 'Amount Paid' },
        { key: 'amount', label: 'Amount Received' },
        { key: 'type_of_payment', label: 'Type of Payment' },
        { key: 'created_at', label: 'Date Paid' },

      ],
      packageField: [
        { key: 'serial', label: 'S/N' },
        { key: 'name', label: 'Name' },
        { key: 'price', label: 'Price per Qty' },
        { key: 'count', label: 'Quantity' },
        { key: 'total', label: 'Total' },

      ],
      miscellaneousField: [
        { key: 'miscellaneous_id', label: 'Name' },
        { key: 'amount', label: 'amount' },

      ],

      avatarText,
      isLoading: false,
      loaded: false,
      project: null,
      installationGadgets: null,
      subject: null,
      message: null,
      productID: null,
      productNameEdit: null,
      numBatteriesEdit: 1,
      numPanelsEdit: 1,
      descriptionEdit: null,
      priceEdit: null,
      required,
      installationAddress: '',
      orders: [],
      allPayments: [],
      installationGadgets: [
        { accessories: [{ name: '0', rating: '0' }] },
        { batteries: [{ name: '0', rating: '0' }] },
        { inverter: [{ name: '0', rating: '0' }] },
        { street_light: [{ name: '0', rating: '0' }] },
        { gas: [{ name: '0', rating: '0' }] },
        { pannel: [{ name: '0', rating: '0' }] },
      ],
      front: {
        title: 'Daily Conversion Value',
        graphData: [3, 2, 5, 9, 5, 10, 3, 5, 0, 0, 1, 8, 2, 9, 0],
      },
      back: {
        title: 'Monthly Summary',
        message: 'Your average daily conversion value for this month is <b>50.4$</b>. It is below the average of the last six months.',
      },
    }
  },
  watch: {
    priceEdit(newValue) {
      const result = newValue
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, ',')
      // eslint-disable-next-line no-return-assign
      this.$nextTick(() => (this.priceEdit = result))
    },
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Projects / <span style="color: #C4C4C4">View Project</span>'
    // this.getOrders()
    this.getProject()
  },
  beforeDestroy() {
    // localStorage.removeItem('projectID')
  },
  created() {
    this.getProject()
  },
  methods: {
    hideModal() {
      this.$refs['send-message'].hide()
    },
    advancedEdit(projectID) {
      this.toast('Send Message', 'CheckCircleIcon', 'In Progress', 'success')
      // this.$router.push("/projects/view-project").then(() => {
      //   localStorage.setItem("projectID", projectID);
      // });
    },
    /**
     * When the location found
     * @param {Object} addressData Data of the found location
     * @param {Object} placeResultData PlaceResult object
     * @param {String} id Input container ID
     */
    getAddressData(addressData, placeResultData, id) {
      const addressLat = {
        lat: addressData.latitude,
        long: addressData.longitude,
        country: addressData.country,
        route: addressData.route,
        locality: addressData.locality,
      }
      this.installationAddress = addressLat
    },
    basicEdit(order) {
      this.installationGadgets = order.orderdetails
      this.$refs['edit-serial-number'].show()
    },
    cordinatesEdit(addressId) {
      this.$router.push(`/projects/edit-geolocation/${addressId}`)
    },
    getProject() {
      const projectID = localStorage.getItem('projectID')
      this.$store
        .dispatch('project/getProjectByID', { projectID })
        .then(res => {
          const { data } = res.data

          console.log(data, 'djdsjsks')

          const allPayments = data.payments.map((value, index) => {
            const serial = index
            return { ...value, serial }
          })

          console.log(allPayments, 'allPayments')
          this.allPayments = allPayments

          const miscellanClone = []
          let serial = 0
          data.installations.map(order => order.orderdetails.accessories.map((accessory, index) => {
            // datum.map(accessory => {

            const fil = miscellanClone.filter(element => element.name == accessory.name)
            if (fil.length != 0) {
              miscellanClone.forEach(element => {
                if (element.name == accessory.name) {
                  return element.count += 1
                }
              })
            } else {
              const dataIn = {
                name: accessory.name, price: accessory.price, rating: accessory.rating, count: 1, total: '', serial: serial += 1,
              }
              miscellanClone.push(dataIn)
            }
          }))
          this.miscellanClone = miscellanClone

          console.log(miscellanClone, 'miscellanClone')

          const payments = []
          data.payments.map(payment => {
            payments.push(Number(payment.amount))
          })
          const initialValue = 0
          const sumWithInitial = payments.reduce(
            (previousValue, currentValue) => previousValue + currentValue,
            initialValue,
          )

          this.allAmountsPaidIN = sumWithInitial.toFixed(2)
          this.orders = data
          this.project = data
          this.loaded = true
        })
    },

    getOrders: async () => {
      try {
        // const res = await axios
        //   .post(`https://devapi.bartumenergy.com/api/confirmpayment/${response.reference}/${window.location.pathname.split('/')[2]}`)
        // console.log(res)
        // this.orders = dat
        // console.log(this.order)
        // window.location.href = '/payment-success'
      } catch (err) {
        this.message = err?.response?.data?.message
        console.log(err.response.data)
      }
    },

    hideEditModal() {
      this.$refs['edit-product'].hide()
    },
    hideSerialNumberModal() {
      this.$refs['edit-serial-number'].hide()
      this.$refs['edit-coordinate'].hide()
    },
    edit(project) {
      this.productID = project.product.id
      this.productNameEdit = project.product.productname
      this.numBatteriesEdit = parseInt(project.product.numberofbatteries, 10)
      this.numPanelsEdit = parseInt(project.product.numberofpanels, 10)
      this.descriptionEdit = project.product.description
      this.priceEdit = parseFloat(project.product.price).toString()
      this.$refs['edit-product'].show()
    },
    sendMessage() {
      this.isLoading = true
      this.$refs.sendMessageValidation.validate().then(success => {
        if (success) {
          const form = {
            projectID: localStorage.getItem('projectID'),
            subject: this.subject,
            message: this.message,
          }
          this.$store
            .dispatch('project/sendMessage', { form })
            .then(res => {
              this.isLoading = false
              const { message } = res.data
              this.hideModal()
              this.toast('Send Message', 'CheckCircleIcon', message, 'success')
            })
            .catch(err => {
              this.isLoading = false
              this.toast(
                'Send Message',
                'SlashIcon',
                err.response.data.message,
                'danger',
              )
            })
        } else {
          this.isLoading = false
          this.toast(
            'Send Message Attempt',
            'AlertTriangleIcon',
            'You must fill in all required fields',
            'warning',
          )
        }
      })
    },
    editClient(clientID) {
      this.$router.push('/clients/edit-client').then(() => {
        localStorage.setItem('clientID', clientID)
      })
    },
    async editSerialNumbers() {
      const result = []
      this.installationGadgets.accessories.map(accessory => {
        result.push({ id: accessory.id, reff: accessory.serial })
      })
      this.installationGadgets.batteries.map(battery => {
        result.push({ id: battery.id, reff: battery.serial })
      })
      this.installationGadgets.gas.map(gas => {
        result.push({ id: gas.id, reff: gas.serial })
      })
      this.installationGadgets.inverter.map(inverter => {
        result.push({ id: inverter.id, reff: inverter.serial })
      })
      this.installationGadgets.pannel.map(pannel => {
        result.push({ id: pannel.id, reff: pannel.serial })
      })
      this.installationGadgets.street_light.map(street_light => {
        result.push({ id: street_light.id, reff: street_light.serial })
      })

      const token = localStorage.getItem('token')

      axios.defaults.headers.Authorization = `Bearer ${token}`

      try {
        const out = await axios.post(
          `${process.env.VUE_APP_BASE_URL}/updatestockreff    `,
          {
            details: JSON.stringify(result),
          },
        )
        this.toast(
          'Product Serial Numbers',
          'CheckCircleIcon',
          out.data.message,
          'success',
        )
        this.hideSerialNumberModal()
      } catch (err) {
        // alert(err.response.data?.message);

        this.message = err.response.data?.message

        if (err.response.data.message == 'Undefined property: stdClass::$reff') {
          this.toast(
            'Product Serial Numbers',
            'CheckCircleIcon',
            'Missing Field Input for Serial Numbers (Fill All)',
            'danger',
          )

          return
        }

        this.toast(
          'Product Serial Numbers',
          'CheckCircleIcon',
          err.response.data.message,
          'danger',
        )
      }
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
            description: this.descriptionEdit,
            price: parseFloat(this.priceEdit.replace(/,/g, '')),
          }
          this.$store
            .dispatch('product/editProduct', { form })
            .then(res => {
              this.isLoading = false
              const projectID = localStorage.getItem('projectID')
              this.loaded = false
              this.$store
                .dispatch('project/getProjectByID', { projectID })
                .then(res2 => {
                  const { message } = res.data
                  const { data } = res2.data
                  this.project = data
                  this.loaded = true
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
  },
}
</script>

<style>
[dir] .form-control:focus {
  border-color: #4D6D8A;
}

.total-cost {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
}

.order-cards {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  flex-wrap: wrap;
  column-gap: 10px;
  /* border: 2px solid red; */
}

.order-card {
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
    rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
  padding: 1rem 1rem;
  width: 17%;
}

.input-box {
  border: 1px solid #ccc;
}

.package-image {
  height: 70px;
  width: 100px;
}

.package_image {
  height: 100%;
}

.package-image-cont {
  display: flex;
  column-gap: 10px;
  padding-top: 1.5rem;
  padding-left: 1rem;

}

.package-text {
  color: #4D6D8A;
}

.package-categories {
  border: 0.1px solid #4D6D8A;
  box-shadow: 0px 15px 10px -15px #4D6D8A;
  margin-bottom: 10px;
  padding: 10px 5px;
}

.editserial-category {
  margin-top: 20px;
}

.margin-bottom {
  margin-bottom: 1.5rem;
}
</style>
