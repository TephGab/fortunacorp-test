<template>
  <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="recharge" role="tabpanel" aria-labelledby="recharge-tab">
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
                <span class="fw-bold">{{ formatDecimalNumber(activity.sender_amount) }} RD$ </span>
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
                  <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} </td>
                  <td class="text-nowrap text-right">
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_agent_balance') - getLastEntryFromHistory(activity.transaction_activits, 'current_agent_balance')) }} RD$</div>
                  </td>
                  <td class="text-nowrap text-right fw-bold">
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_agent_balance')) }} RD$</div>
                  </td>
                 <!-- Balance part for transfert end -->
                </tr>
                <tr v-else>
                  <!-- Balance Part for reception -->
                  <td> {{ formatDecimalNumber(0) }} </td>
                  <td class="text-nowrap text-right">
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_agent_balance') - getLastEntryFromHistory(activity.transaction_activits, 'current_agent_balance')) }} RD$</div>
                  </td>
                  <td class="text-nowrap text-right fw-bold">
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_agent_balance')) }} RD$</div>
                  </td>
                  <!-- End Balnce part for reception end -->
                </tr>
              </tbody>
              <!-- End Debit / Credit -->
            </table>
          </li>
          <!-- END TRANSACTIONS LI -->

          <!-- COMMISSION TO RECHARGE LI -->
          <li class="mb-2 ms-6" v-if="activity.table == 'profit_to_recharges' && activity.commission_category == 'commission'">
              <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                </svg>
              </span>
              <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                <span class="uppercase">{{ activity.commission_category }} TO RECHARGE </span> <span class="text-success ml-1">#{{ activity.id }} </span>
              </h3>
              <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$ transferred to recharge</span>
              </p>
              <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.updated_at) }}</time>
          </li>

          <li class="text-muted" v-if="activity.table == 'profit_to_recharges' && activity.commission_category == 'commission'">
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
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.amount) }} RD$</td>
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.new_balance) }} RD$</td>
            </tr>
            </tbody>
            </table>
          </li>
          <!-- END COMMISSION TO RECHARGE LI -->

          <!-- COMMISSION CASHOUT LI -->
          <li class="mb-2 ms-6" v-if="activity.table == 'cashouts' && activity.commission_category == 'commission'">
              <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                </svg>
              </span>
              <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                <span class="uppercase">{{ activity.commission_category }} CASHOUT </span> <span class="text-success ml-1">#{{ activity.id }} </span>
              </h3>
              <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$ cashout</span>
              </p>
              <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
          </li>

          <li class="text-muted" v-if="activity.table == 'cashouts' && activity.commission_category == 'commission'">
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
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.amount) }} RD$</td>
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                <td class="text-nowrap text-right fw-bold">
                  <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.cashout_activits, 'new_user_balance')) }} RD$</div>
                </td>
            </tr>
            </tbody>
            </table>
          </li>
          <!-- END COMMISSION CASHOUT LI -->

           <!-- AGENT LOAN REPAY TRANSACTION LI -->
           <li class="mb-2 ms-6" v-if="activity.table == 'agent_loan_transaction_repays'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase">LOAN PAYMENT</span>  
                  <span class="text-success ml-1">#{{ activity.id }} </span>
                  <span class="text-capitalize fw-normal ml-1"> (Transaction  {{ activity.transaction.id }}) </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Agent: </span> <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                 <!-- <div><span class="fw-bold">Transaction: </span> <span>{{ activity.transaction.id }}</span></div> -->
                 <!-- <div>
                  <span class="fw-bold">Note: </span> 
                  <span v-if="activity.comment">{{ activity.comment }}</span>
                  <span v-else> _ </span>
                </div> -->
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'agent_loan_transaction_repays'">
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
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.amount) }} RD$</td>
                <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                <td class="text-nowrap text-right fw-bold">
                  <div>{{ formatDecimalNumber(activity.new_agent_commission) }} RD$</div>
                </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END AGENT LOAN TRANSACTION REPAY LI -->







            <!-- Operator part -->
          
            <!-- End Operator part -->

        </ol>
        
      </div>

      <div class="col-md-12 pl-4 pt-4" v-else>
        <div class="fw-bold text-center text-muted">
          No agent commisons activities found
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