<template>
  <!-- <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3"> -->
  <!-- Edit mode -->
  <!-- TABLE: LATEST TRANSFERTS -->
  <div class="card card-primary card-outline direct-chat direct-chat-primary">
    <div class="card-header border-transparent bg-transparent">
      <!-- Account Info -->
      <div class="text-center">
        <!-- component -->
        <div class="mbg-white w-full rounded-lg">
          <div>
            <h2 class="text-sm fw-bold">
              <span>{{ account.name }} </span>
            </h2>
            <p class="text-sm text-gray-500">
              <span> {{ account.phone }}</span>
            </p>
          </div>
          <div>
            <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1">
              <p class="text-gray-600">
                <span class="fw-bold">Type</span>
              </p>
              <p>
                <span :class="[account.type == 'moncash' ? 'text-red-500' : 'text-blue-500', 'text-capitalize']"> {{ account.type }} </span>
              </p>
            </div>

            <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-1">
              <p class="text-gray-600">
                <span class="fw-bold">Assigned to</span>
              </p>
              <p>
                <span :class="[account.balance == '0' ? 'text-red-500' : 'text-gray-500', 'text-capitalize']"> {{  account.user_first_name }} {{  account.user_last_name }} </span>
              </p>
            </div>

            <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-1 border-b border-gray-100">
              <p class="text-gray-600">
                <span class="fw-bold">Balance</span>
              </p>
              <p>
                <span :class="[account.balance == '0' ? 'text-red-500' : 'text-gray-500', 'text-capitalize']"> {{ account.balance }} HTG </span>
              </p>
            </div>

          </div>
        </div>
        <!-- </div> -->
        <div>
        </div>
      </div>
      <!-- Account info End -->
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0 m-0">
      <div class="row text-gray-300">
        <div class="col-md-12 text-center">
          Activities
        </div>
      </div>
      <div class="row text-right">
        <div>
          <label><i class="fa-regular fa-calendar-days"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs mr-2" v-model="form.selected_month"
            @change="sortByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionMonth" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
          <label><i class="fa-regular fa-calendar"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.selected_year"
            @change="sortByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionYear" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table m-0">
          <nav>
            <div class="nav nav-tabs fw-bold" id="nav-tab" role="tablist">
              <button class="nav-link active text-uppercase text-xs" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="transaction" aria-selected="true">
                Transactions </button>
              <button class="nav-link text-uppercase text-xs" id="transfert-tab" data-bs-toggle="tab" data-bs-target="#transfert" type="button" role="tab" aria-controls="transfert" aria-selected="false">
                Intern Transferts</button>
              <button class="nav-link text-uppercase text-xs" id="reception-tab" data-bs-toggle="tab" data-bs-target="#reception" type="button" role="tab" aria-controls="reception" aria-selected="false">
                Intern receptions </button>
              <button class="nav-link text-uppercase text-xs" id="cashin-tab" data-bs-toggle="tab" data-bs-target="#cashin" type="button" role="tab" aria-controls="cashin" aria-selected="false">
                Cashins </button>
              <button class="nav-link text-uppercase text-xs" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false" @click="showSoldeActivities">
                Solde </button>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <!-- Transaxction -->
            <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
              <div class="row bg-light">
                <div class="col-md-12 d-flex flex-row-reverse text-xs">
                  <div class="hover:bg-gray-50">
                    <p class="text-gray-600 d-flex flex-column mr-2">
                      <span class="fw-bold pr-1">Transfers <span class="text-muted">({{ accountActivities.transactionTransferCount }})</span></span>
                      <!-- <span :class="[accountActivities.totaTransfers == '0' ? 'text-red-500' : 'text-gray-500', 'text-capitalize']"> {{ accountActivities.totaTransfers }} RD$</span> -->
                    </p>
                  </div>

                  <div class="hover:bg-gray-50">
                    <p class="text-gray-600 d-flex flex-column">
                      <span class="fw-bold pr-1">Receptions <span class="text-muted">({{ accountActivities.transactionReceptionCount }})</span></span>
                      <!-- <span :class="[accountActivities.totalReceptions == '0' ? 'text-red-500' : 'text-gray-500', 'text-capitalize']"> {{ accountActivities.totalReceptions }} HTG</span> -->
                    </p>
                  </div>
                </div>
                <div class="col-md-12 pl-4 pt-4" v-if="accountActivities && accountActivities.transactions && accountActivities.transactions.data">

                  <ol class="relative border-s border-gray-200 dark:border-gray-700" v-for="transaction in accountActivities.transactions.data" :key="transaction.id">
                    <li class="mb-2 ms-6">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-capitalize">
                        {{ transaction.operation }} <span class="text-success ml-2"> #{{ transaction.id }}</span>
                        <!-- <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ms-3">Latest</span> -->
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <span v-if="transaction.operation == 'transfert_si' || transaction.operation == 'transfert'">
                          <div class="fw-bold"> <span class="fw-bold">Amount:</span> <span>{{ formatDecimalNumber(transaction.receiver_amount) }} HTG</span> </div>
                          <div> <span class="fw-bold">Receiver:</span> <span>{{ transaction.receiver_name }} </span></div>
                          <div> <span class="fw-bold">Agent:</span> <span>{{ transaction.agent_first_name }} {{ transaction.agent_last_name }}</span></div>
                          <div> <span class="fw-bold">Operator: </span><span>{{ transaction.operator_first_name }} {{ transaction.operator_last_name }}</span></div>
                        </span>
                        <span v-if="transaction.operation == 'reception_si' || transaction.operation == 'reception'">
                          <div> <span class="fw-bold">Amount:</span><span class="fw-bold">{{ formatDecimalNumber(transaction.sender_amount) }} HTG </span></div>
                          <div> <span class="fw-bold">Receiver:</span> <span>{{ transaction.receiver_name }} </span></div>
                          <div> <span class="fw-bold">Agent:</span> <span>{{ transaction.agent_first_name }} {{ transaction.agent_last_name }}</span></div>
                          <div> <span class="fw-bold">Operator:</span> <span>{{ transaction.operator_first_name }} {{ transaction.operator_last_name }}</span></div>
                        </span>
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(transaction.completed_date) }}</time>
                    </li>
                  </ol>
                </div>
              </div>
            </div>
            <!-- Transaction end  -->

            <!-- Intern transfer -->
            <div class="tab-pane fade" id="transfert" role="tabpanel" aria-labelledby="transfert-tab">
              <!-- Account Transasfert verticl timeline -->
              <div class="row bg-light">
                <div class="col-md-12 pl-4 pt-4"
                  v-if="accountActivities && accountActivities.accountTransferts && accountActivities.accountTransferts.data">

                  <ol class="relative border-s border-gray-200 dark:border-gray-700" v-for="accountTransfert in accountActivities.accountTransferts.data" :key="accountTransfert.id">
                    <li class="mb-2 ms-6">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-capitalize">
                        <span class="mr-1">Transfer</span><span class="fw-bold text-success">#{{ accountTransfert.id }}</span>

                        <!-- <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ms-3">Latest</span> -->
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <div> <span class="fw-bold mr-1">Amount:</span><span class="fw-bold">{{ formatDecimalNumber(accountTransfert.amount) }} HTG </span></div>
                        <div> <span class="fw-bold">To:</span><span>{{ accountTransfert.account_receiver_phone }} </span> <span>({{ accountTransfert.account_receiver_name }}) </span></div>
                        <div> <span class="fw-bold">By: </span><span>{{ accountTransfert.user_first_name }} {{ accountTransfert.user_last_name }}</span></div> 
                        <div class="fw-bold"> Counted: <span v-if="accountTransfert.countable">Yes</span> <span v-else>No</span></div>                      
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(accountTransfert.updated_at) }}</time>
                    </li>
                  </ol>
                </div>
              </div>
              <!-- Account Transfert vertical timeline end -->
            </div>
            <!-- Intern transfer end -->

            <!-- intern reception -->
            <div class="tab-pane fade" id="reception" role="tabpanel" aria-labelledby="reception-tab">
              <!-- Account Transasfert verticl timeline -->
              <div class="row bg-light">
                <div class="col-md-12 pl-4 pt-4"
                  v-if="accountActivities && accountActivities.receivedTransfers && accountActivities.receivedTransfers.data">

                  <ol class="relative border-s border-gray-200 dark:border-gray-700" v-for="accountReception in accountActivities.receivedTransfers.data" :key="accountReception.id">
                    <li class="mb-2 ms-6">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-capitalize">
                        <span class="mr-1">Reception</span><span class="fw-bold text-success">#{{ accountReception.id }}</span>

                        <!-- <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ms-3">Latest</span> -->
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <div> <span class="fw-bold mr-1">Amount: </span><span class="fw-bold">{{ formatDecimalNumber(accountReception.amount) }} HTG </span></div>
                        <div> <span class="fw-bold">From: </span><span>{{ accountReception.account_sender_phone }} </span> <span>({{ accountReception.account_sender_name }}) </span></div>
                        <div> <span class="fw-bold">By: </span><span>{{ accountReception.user_first_name }} {{ accountReception.user_last_name }}</span></div>                       
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(accountReception.updated_at) }}</time>
                    </li>
                  </ol>
                </div>
              </div>
              <!-- Account Transfert vertical timeline end -->
            </div>
            <!-- En inter reception -->

            <!-- Cashin -->
            <div class="tab-pane fade" id="cashin" role="tabpanel" aria-labelledby="cashin-tab">
              <!-- Cashin Vertical timeline -->
              <div class="row bg-light">
                <div class="col-md-12 pl-4 pt-4"
                  v-if="accountActivities && accountActivities.cashins && accountActivities.cashins.data">

                  <ol class="relative border-s border-gray-200 dark:border-gray-700" v-for="cashin in accountActivities.cashins.data" :key="cashin.id">
                    <li class="mb-2 ms-6">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-capitalize">
                        <span v-if="cashin.operation == 'reception_si'">Entree si</span>
                        <span v-if="cashin.operation == 'reception'">Entree</span> <span class="ml-1 text-success">#{{ cashin.id }}</span>
                        <!-- <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ms-3">Latest</span> -->
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <div v-if="cashin.operation == 'reception_si' || cashin.operation == 'reception'">
                          <div><span class="fw-bold mr-1">Amount:</span><span class="fw-bold">{{ formatDecimalNumber(cashin.amount / cashin.rate)}} HTG </span></div>
                          <div><span class="fw-bold">Provider:</span> <span>{{ cashin.provider_name }} </span></div>
                          <div><span class="fw-bold">Registred by:</span> <span>{{ cashin.user_first_name }} {{ cashin.user_last_name }}</span></div>
                        </div>
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(cashin.updated_at) }}</time>
                    </li>
                  </ol>
                </div>
              </div>
              <!-- End Cashin vertical timeline -->
            </div>
            <!-- End cashin -->

            <!-- Global -->
            <div v-show="soldeHistoryShow" class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="all" role="tabpanel" aria-labelledby="all-tab">
              <div class="row bg-light">
                <div class="col-md-12 pl-4 pt-4" v-if="sortedActivities && sortedActivities.length > 0">

                  <ol class="relative border-s border-gray-200 dark:border-gray-700 d-flex justify-content-between mb-1 bg-gray-100 hover:bg-gray-200" v-for="(activity, index) in sortedActivities" :key="index">

                    <!-- TRANSACTIONS LI -->
                    <li class="mb-2 ms-6" v-if="activity.table == 'transactions'">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                        {{ activity.table }} <span class="text-capitalize text-sm fw-normal ml-1"> ({{ activity.operation }})</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                      </h3>
                      <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <span v-if="activity.operation == 'transfert_si' || activity.operation == 'transfert'">
                          <span class="fw-bold">{{ formatDecimalNumber(activity.receiver_amount) }} HTG </span>
                            sent to <span>{{ activity.client.name }} </span>
                          <div> Agent: <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                          <div> Operator: <span>{{ activity.operator.first_name }} {{ activity.operator.last_name }}</span></div>
                        </span>
                        <span v-if="activity.operation == 'reception_si' || activity.operation == 'reception'">
                          <span class="fw-bold">{{ formatDecimalNumber(activity.sender_amount) }} HTG </span>
                          <div>sent to <span>{{ activity.client.name }} </span></div>
                          <div>Agent: <span>{{ activity.first_name }} {{ activity.last_name }}</span></div>
                          <div>Operator: <span>{{ activity.operator.first_name }} {{ activity.operator.last_name }}</span></div>
                        </span>
                      </p>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.completed_date) }}</time>
                    </li>
                    <li class="text-muted" v-if="activity.table == 'transactions'">
                      <table class="table text-sm">
                        <thead>
                          <tr>
                            <th scope="col" class="text-nowrap text-right">Debit</th>
                            <th scope="col" class="text-nowrap text-right">Credit</th>
                            <th scope="col" class="text-nowrap text-right">Balance</th>
                          </tr>
                        </thead>
                      
                        <!-- Debit / Credit -->
                        <tbody>
                          <tr v-if="activity.operation == 'transfert_si' || activity.operation == 'transfert'">
                          <!-- Balance part for transfert -->
                            <td class="text-nowrap text-right">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'current_account_balance') - getLastEntryFromHistory(activity.transaction_activits, 'new_account_balance')) }} HTG</div>
                            </td>
                            <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} </td>
                            <td class="text-nowrap text-right fw-bold">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_account_balance')) }} HTG</div>
                            </td>
                          <!-- Balance part for transfert end -->
                          </tr>
                          <tr v-else>
                            <!-- Balance Part for reception -->
                            <td> {{ formatDecimalNumber(0) }} </td>
                            <td class="text-nowrap text-right">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_account_balance') - getLastEntryFromHistory(activity.transaction_activits, 'current_account_balance')) }} HTG</div>
                            </td>
                            <td class="text-nowrap text-right fw-bold">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_account_balance')) }} HTG</div>
                            </td>
                            <!-- End Balnce part for reception end -->
                          </tr>
                        </tbody>
                        <!-- End Debit / Credit -->
                      </table>
                    </li>
                    <!-- END TRANSACTIONS LI -->

                    <!-- CASHIN LI -->
                    <li class="mb-2 ms-6" v-if="activity.table == 'cashins'">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                        CASHIN
                        <span v-if="activity.operation == 'reception_si'"> (Entree si)</span>
                        <span v-if="activity.operation == 'reception'"> (Entree)</span> 
                        <span class="text-success ml-1">#{{ activity.id }} </span>
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">

                          <div> Amount: <span class="fw-bold">{{ formatDecimalNumber(activity.amount / activity.rate) }} HTG </span></div>
                          <div> By: <span>{{ activity.user.first_name }} {{ activity.user.last_name }} </span></div>
                          <div> Provider: <span>{{ activity.provider.name }} </span></div>
                       
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.updated_at) }}</time>
                    </li>
                    <li class="text-muted" v-if="activity.table == 'cashins'">
                      <table class="table text-sm">
                        <thead>
                          <tr>
                            <th scope="col" class="text-nowrap text-right">Debit</th>
                            <th scope="col" class="text-nowrap text-right">Credit</th>
                            <th scope="col" class="text-nowrap text-right">Balance</th>
                          </tr>
                        </thead>
                      
                        <!-- Debit / Credit -->
                        <tbody>
                          <tr>
                            <!-- Balance Part for reception -->
                            <td> {{ formatDecimalNumber(0) }} </td>
                            <td class="text-nowrap text-right">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.cashin_activits, 'new_account_balance') - getLastEntryFromHistory(activity.cashin_activits, 'current_account_balance')) }} HTG</div>
                            </td>
                            <td class="text-nowrap text-right fw-bold">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.cashin_activits, 'new_account_balance')) }} HTG</div>
                            </td>
                            <!-- End Balnce part for reception end -->
                          </tr>
                        </tbody>
                        <!-- End Debit / Credit -->
                      </table>
                    </li>
                    <!-- END CASHIN LI -->

                    <!-- INTERN TRANSFERT SENT -->
                    <li class="mb-2 ms-6" v-if="activity.table == 'sent_transfers'">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                        INTERN TRANSFERT
                        <span class="text-success ml-1">#{{ activity.id }} </span>
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                          <div class="fw-bold"> Amount: <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} HTG </span></div>
                          <div class="fw-bold"> By: <span>{{ activity.user.first_name }} {{ activity.user.last_name }} </span></div>
                          <div class="fw-bold"> Receiver: <span>{{ activity.receiver_account.phone }} ({{ activity.receiver_account.name }})</span></div>
                          <div class="fw-bold"> Counted: <span v-if="activity.countable">Yes</span> <span v-else>No</span></div>
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.updated_at) }}</time>
                    </li>
                    <li class="text-muted" v-if="activity.table == 'sent_transfers'">
                      <table class="table text-sm">
                        <thead>
                          <tr>
                            <th scope="col" class="text-nowrap text-right">Debit</th>
                            <th scope="col" class="text-nowrap text-right">Credit</th>
                            <th scope="col" class="text-nowrap text-right">Balance</th>
                          </tr>
                        </thead>
                      
                        <!-- Debit / Credit -->
                        <tbody>
                          <tr>
                            <!-- Balance Part for reception -->
                            <td class="text-nowrap text-right">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.account_transfert_activits, 'current_sender_account_balance') - getLastEntryFromHistory(activity.account_transfert_activits, 'new_sender_account_balance')) }} HTG</div>
                            </td>
                            <td> {{ formatDecimalNumber(0) }} </td>
                            <td class="text-nowrap text-right fw-bold">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.account_transfert_activits, 'new_sender_account_balance')) }} HTG</div>
                            </td>
                            <!-- End Balnce part for reception end -->
                          </tr>
                        </tbody>
                        <!-- End Debit / Credit -->
                      </table>
                    </li>
                    <!-- END INTERN TRANSAFERT SENT -->

                     <!-- RECEIVERD INTERN TRANSFERT -->
                     <li class="mb-2 ms-6" v-if="activity.table == 'received_transfers'">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                        INTERN RECEPTION
                        <span class="text-success ml-1">#{{ activity.id }} </span>
                      </h3>
                      <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                          <div> <span class="fw-bold"> Amount:</span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} HTG </span></div>
                          <div> <span class="fw-bold">  By: </span><span>{{ activity.user.first_name }} {{ activity.user.last_name }} </span></div>
                          <div> <span class="fw-bold"> Sender :</span> <span>{{ activity.sender_account.phone }} ({{ activity.sender_account.name }})</span></div>
                      </div>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.updated_at) }}</time>
                    </li>
                    <li class="text-muted" v-if="activity.table == 'received_transfers'">
                      <table class="table text-sm">
                        <thead>
                          <tr>
                            <th scope="col" class="text-nowrap text-right">Debit</th>
                            <th scope="col" class="text-nowrap text-right">Credit</th>
                            <th scope="col" class="text-nowrap text-right">Balance</th>
                          </tr>
                        </thead>
                      
                        <!-- Debit / Credit -->
                        <tbody>
                          <tr>
                            <!-- Balance Part for reception -->
                            <td> {{ formatDecimalNumber(0) }} </td>
                            <td class="text-nowrap text-right">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.account_transfert_activits, 'new_receiver_account_balance') - getLastEntryFromHistory(activity.account_transfert_activits, 'current_receiver_account_balance')) }} HTG</div>
                            </td>
                            <td class="text-nowrap text-right fw-bold">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.account_transfert_activits, 'new_receiver_account_balance')) }} HTG</div>
                            </td>
                            <!-- End Balnce part for reception end -->
                          </tr>
                        </tbody>
                        <!-- End Debit / Credit -->
                      </table>
                    </li>
                    <!-- END RECEIVERD INTERN TRANSAFER-->
                 
                  </ol>
                  
                </div>

                <div class="col-md-12 pl-4 pt-4" v-else>
                  <div class="fw-bold text-center text-muted">
                    No activities found
                  </div>
                  <!-- <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                    <p class="font-bold">Be Warned</p>
                    <p>Something not ideal might be happening.</p>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- End Global -->
          </div>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
  </div>
  <!-- <div class="row">
        </div> -->
  <!-- </div>
    </div>
  </div> -->
</template>
  
<script>
import { onMounted, reactive, ref } from 'vue';
import useAccountActivities from "../../services/accountactivityservices.js";
import useAccounts from "../../services/accountservices.js";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';

export default {
  props: ['accountId'],
  setup(props) {
    const { can, cannot } = useAbility();

    const { getAccount, account } = useAccounts();
    const { getAccountActivities, accountActivities } = useAccountActivities();
    const { formatDecimalNumber, formatDateCalendar, formatDate } = useUtils();

    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;

    const form = reactive({
      selected_year: currentYear,
      selected_month: currentMonth,
    });
    const optionYear = ref([]);
    const optionMonth = ref([
      { text: 'January', value: '1' },
      { text: 'February', value: '2' },
      { text: 'March', value: '3' },
      { text: 'April', value: '4' },
      { text: 'May', value: '5' },
      { text: 'June', value: '6' },
      { text: 'July', value: '7' },
      { text: 'August', value: '8' },
      { text: 'September', value: '9' },
      { text: 'October', value: '10' },
      { text: 'November', value: '11' },
      { text: 'December', value: '12' }
    ]);

        // All in one
        const sortedActivities = ref([]);

        const fetchSortedActivities = async (user_id, selected_year, selected_month) => {
          try {
            const response = await axios.post("/api/accountglobalactivities", { 'account_id': props.accountId, 'selected_year': form.selected_year, 'selected_month': form.selected_month });

            // console.log(response)
            const data = response.data;

            // Convert object to array of objects
            const activitiesArray = Object.values(data).map(item => ({
              ...item, // Spread all properties from 'item'
              date: item.completed_date || item.updated_at ||  '',
            }));

            // Sort activities by date
            const sorted = activitiesArray.sort((a, b) => {
              const dateA = new Date(a.date);
              const dateB = new Date(b.date);
              return dateA - dateB;
            });

          sortedActivities.value = sorted;
        } catch (error) {
          console.error('Error fetching activities:', error);
        }
      };

      const getLastEntry = (obj) => {
      const entries = Object.entries(obj);
      return entries.length > 0 ? entries[entries.length - 1] : null;
      };

      const getLastEntryFromHistory = (array, fieldName) => {
        if (Array.isArray(array) && array.length > 0) {
          const lastObject = array[array.length - 1];
          if (typeof lastObject === "object" && fieldName in lastObject) {
            return lastObject[fieldName];
          }
        }
        return null; // Return null if the array is empty or fieldName is not found in the last object
      };
      // All in one end

    onMounted(async () => {
      // Add the current year
      optionYear.value.push({ text: currentYear.toString(), value: currentYear.toString() });
      // Add the past 5 years
      for (let i = 1; i <= 5; i++) {
        const pastYear = currentYear - i;
        optionYear.value.push({ text: pastYear.toString(), value: pastYear.toString() });
      }
      await getAccount(props.accountId);
      await getAccountActivities({ 'account_id': props.accountId, 'selected_year': form.selected_year, 'selected_month': form.selected_month });
      //await fetchSortedActivities(props.accountId, currentYear, currentMonth);
    })

    const sortByYear = async (year, month) => {
      await getAccountActivities({ 'account_id': props.accountId, 'selected_year': year, 'selected_month': month });
      //await fetchSortedActivities(props.accountId, currentYear, currentMonth);
    }

    const soldeHistoryShow = ref(false);

    const showSoldeActivities = async () => {
      await fetchSortedActivities(props.accountId, currentYear, currentMonth);
      soldeHistoryShow.value = await true;
    }

    return {
      form,
      getAccount,
      account,
      can,
      cannot,
      formatDecimalNumber,
      formatDateCalendar,
      formatDate,
      sortByYear,
      optionYear,
      optionMonth,
      accountActivities,
      getAccountActivities,
      getLastEntry,
      getLastEntryFromHistory,
      sortedActivities,
      showSoldeActivities,
      soldeHistoryShow
    }
  },
}
</script>