<template>
    <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="referral_commission" role="tabpanel" aria-labelledby="referral_commission-tab">
      <div class="row bg-light">

        <div class="col-md-12 pl-4 pt-4" v-if="sortedActivities && sortedActivities.length > 0">
  
          <div v-for="(activities, index) in sortedActivities" :key="index">
            <div class="m-0 p-0" v-if="activities.table == 'referrals' && activities.transactions && activities.transactions.length > 0">
                <ol class="relative border-s border-gray-200 dark:border-gray-700 d-flex justify-content-between bg-gray-100 hover:bg-gray-200" v-for="(activity, index) in activities.transactions" :key="index">
                <!-- TRANSACTIONS LI -->
                  <li class="mb-2 ms-6" v-if="activities.table == 'referrals'">
                    <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                      <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                      </svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                    Transaction <span class="text-capitalize text-sm fw-normal ml-1"></span> <span class="text-success ml-1">#{{ activity.id }} </span>
                    </h3>
                    <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                      <div>Agent: <span>{{ activities.first_name }} {{ activities.last_name }}</span></div>
                    </p>
                    <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.completed_date) }}</time>
                  </li>
                  <li class="text-muted" v-if="activities.table == 'referrals'">
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
                          <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} </td>
                          <td class="text-nowrap text-right">
                            <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_reference_balance') - getLastEntryFromHistory(activity.transaction_activits, 'current_reference_balance')) }} RD$</div>
                          </td>
                          <td class="text-nowrap text-right fw-bold">
                            <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_reference_balance')) }} RD$</div>
                          </td>
                        <!-- Balance part for transfert end -->
                        </tr>
                        <tr v-else>
                          <!-- Balance Part for reception -->
                          <td> {{ formatDecimalNumber(0) }} </td>
                          <td class="text-nowrap text-right">
                            <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_reference_balance') - getLastEntryFromHistory(activity.transaction_activits, 'current_reference_balance')) }} RD$</div>
                          </td>
                          <td class="text-nowrap text-right fw-bold">
                            <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_reference_balance')) }} RD$</div>
                          </td>
                          <!-- End Balnce part for reception end -->
                        </tr>
                      </tbody>
                      <!-- End Debit / Credit -->
                    </table>
                  </li>
                <!-- END TRANSACTIONS LI -->
                </ol>
            </div>

            <div class="m-0 p-0"> 
              <ol class="relative border-s border-gray-200 dark:border-gray-700 d-flex justify-content-between bg-gray-100 hover:bg-gray-200">
                <!-- REFERRAL COMMISSION TO RECHARGE LI -->
                <li class="mb-2 ms-6" v-if="activities.table == 'profit_to_recharges' && activities.commission_category == 'referral_commission'">
                    <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                      <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                      </svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                      <span class="uppercase">{{ activities.commission_category }} TO RECHARGE </span> <span class="text-success ml-1">#{{ activities.id }} </span>
                    </h3>
                    <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                      <span class="fw-bold">{{ formatDecimalNumber(activities.amount) }} RD$ transferred to recharge</span>
                    </p>
                    <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activities.completed_date) }}</time>
                </li>

                <li class="text-muted" v-if="activities.table == 'profit_to_recharges' && activities.commission_category == 'referral_commission'">
                    <table class="table text-sm">
                      <thead>
                        <tr>
                          <th scope="col" class="text-nowrap text-right">Debit</th>
                          <th scope="col" class="text-nowrap text-right">Credit</th>
                          <th scope="col" class="text-nowrap text-right">Balance</th>
                        </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <td class="text-nowrap text-right"> {{ formatDecimalNumber(activities.amount) }} RD$</td>
                      <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                      <td class="text-nowrap text-right"> {{ formatDecimalNumber(activities.new_balance) }} RD$</td>
                  </tr>
                  </tbody>
                  </table>
                </li>
                <!-- END REFERRAL COMMISSION TO RECHARGE LI -->

                <!-- REFERRAL COMMISSION CASHOUT LI -->
                <li class="mb-2 ms-6" v-if="activities.table == 'cashouts' && activities.commission_category == 'referral_commission' && userrole.name == 'agent'">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                        <span class="uppercase">{{ activities.commission_category }} CASHOUT </span> <span class="text-success ml-1">#{{ activities.id }} </span>
                      </h3>
                      <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <span class="fw-bold">{{ formatDecimalNumber(activities.amount) }} RD$ cashout</span>
                      </p>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activities.receiver_confirmation_date) }}</time>
                </li>

                <li class="text-muted" v-if="activities.table == 'cashouts' && activities.commission_category == 'referral_commission' && userrole.name == 'agent'">
                      <table class="table text-sm">
                        <thead>
                          <tr>
                            <th scope="col" class="text-nowrap text-right">Debit</th>
                            <th scope="col" class="text-nowrap text-right">Credit</th>
                            <th scope="col" class="text-nowrap text-right">Balance</th>
                          </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-nowrap text-right"> {{ formatDecimalNumber(activities.amount) }} RD$</td>
                        <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                        <td class="text-nowrap text-right fw-bold">
                          <div>{{ formatDecimalNumber(getLastEntryFromHistory(activities.cashout_activits, 'new_user_balance')) }} RD$</div>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </li>
                <!-- END REFERRAL COMMISSION CASHOUT LI -->
              </ol>  
            </div>
          </div>
          
        </div>
  
        <div class="col-md-12 pl-4 pt-4" v-else>
          <div class="fw-bold text-center text-muted">
            No  referral commisons activities found
          </div>
          <!-- <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
            <p class="font-bold">Be Warned</p>
            <p>Something not ideal might be happening.</p>
          </div> -->
        </div>
      </div>
    </div>
  </template>
  
  <script>
    export default {
      props: ['userrole','sortedActivities', 'getLastEntryFromHistory', 'formatDecimalNumber', 'formatDateCalendar','formatDate',],
      setup(props) {
        return {
          props,
        }
      },
    }
  </script>