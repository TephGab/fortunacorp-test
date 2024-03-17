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
                        {{ activity.table }}<span class="text-capitalize text-sm fw-normal ml-1"> ({{ activity.operation }})</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                      </h3>
                      <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <span v-if="activity.operation == 'transfert_si' || activity.operation == 'transfert'">
                          <span class="fw-bold">{{ activity.sender_amount }} RD$ </span>
                            sent to <span>{{ activity.client.name }} </span>
                          <div> Agent: <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                          <div> Operator: <span>{{ activity.operator.first_name }} {{ activity.operator.last_name }}</span></div>
                        </span>
                        <span v-if="activity.operation == 'reception_si' || activity.operation == 'reception'">
                          <span class="fw-bold">{{ activity.sender_amount }} HTG </span>
                          <div>sent to <span>{{ activity.client.name }} </span></div>
                          <div>Agent: <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
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
                           <!-- Rechrge part for transfert -->
                            <td class="text-nowrap text-right">{{ formatDecimalNumber(activity.sender_amount) }} RD$ </td>
                            <td class="text-nowrap text-right"> {{  formatDecimalNumber(0) }} RD$</td>
                            <td class="text-nowrap text-right fw-bold" v-if="activity.transaction_activits">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_agent_investment')) }} RD$</div>
                            </td>
                           <!-- Recharge part for transfert end -->
                          </tr>
                          <tr v-else>
                            <!-- Recharge part for reception -->
                            <td> {{ formatDecimalNumber(0) }} </td>
                            <td>{{ formatDecimalNumber(activity.receiver_amount) }}</td>
                            <td class="text-nowrap text-right fw-bold" v-if="activity.transaction_activits">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.transaction_activits, 'new_agent_investment')) }} RD$</div>
                            </td>
                            <!-- End recharge part for reception end -->
                          </tr>
                        </tbody>
                        <!-- End Debit / Credit -->
                      </table>
                    </li>
                   <!-- END TRANSACTIONS LI -->

                   <!-- AGENT DEPOSITS LI -->
                    <li class="mb-2 ms-6" v-if="activity.table == 'agent_deposits'">
                      <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                        <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                      </span>
                      <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                        {{ activity.table }} <span class="text-capitalize text-sm fw-normal ml-1"> ({{ activity.operation }})</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                      </h3>
                      <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <span>
                          <span class="fw-bold">{{ activity.amount }} RD$ </span>
                          deposit by <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span>
                          <div>Envoy: {{ activity.envoy.first_name }} {{ activity.envoy.last_name }}</div>
                        </span>
                      </p>
                      <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.updated_at) }}</time>
                    </li>

                    <li class="text-muted" v-if="activity.table == 'agent_deposits'">
                      <!-- <div class="text-center fw-bold"> Recharge <span class="text-center text-capitalize text-xs fw-normal"> ({{ activity.status }}) </span></div> -->
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
                            <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                            <td class="text-nowrap text-right">{{ formatDecimalNumber(activity.amount) }} RD$</td>
                            <td class="text-nowrap text-right fw-bold" v-if="activity.agent_deposit_activits">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.agent_deposit_activits, 'new_agent_investment')) }} RD$</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </li>
                   <!-- END AGENT DEPOSIT LI -->

                    <!-- WITHDRAWS DEPOSITS LI -->
                    <li class="mb-2 ms-6" v-if="activity.table == 'withdraws'">
                        <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                          <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                          </svg>
                        </span>
                        <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                            {{ activity.table }} <span class="text-success ml-1">#{{ activity.id }} </span>
                        </h3>
                        <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        <span>
                          <span class="fw-bold">{{ activity.amount }} RD$ withdrawal</span>
                          <div>Envoy: {{ activity.envoy.first_name }} {{ activity.envoy.last_name }}</div>
                        </span>
                        </p>
                        <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
                    </li>

                    <li class="text-muted" v-if="activity.table == 'withdraws'">
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
                          <td class="text-nowrap text-right">{{ formatDecimalNumber(activity.amount) }} RD$</td>
                          <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                          <td class="text-nowrap text-right fw-bold" v-if="activity.send_money_activits">
                            <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.send_money_activits, 'new_agent_investment')) }} RD$</div>
                          </td>
                      </tr>
                      </tbody>
                      </table>
                    </li>
                    <!-- END WITHDRAWS LI -->

                    <!-- COMMISSION TO RECHARGE LI -->
                    <li class="mb-2 ms-6" v-if="activity.table == 'profit_to_recharges'">
                        <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                          <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                          </svg>
                        </span>
                        <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                            <span class="uppercase">{{ activity.commission_category }} TO RECHARGE</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                        </h3>
                        <p class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                          <span class="fw-bold">{{ activity.amount }} RD$ transferred to recharge</span>
                        </p>
                        <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.updated_at) }}</time>
                    </li>

                    <li class="text-muted" v-if="activity.table == 'profit_to_recharges'">
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
                          <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                          <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.amount) }} RD$</td>
                          <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.new_investment) }} RD$</td>
                      </tr>
                      </tbody>
                      </table>
                    </li>
                    <!-- END COMMISSION TO RECHARGE LI -->

                     <!-- AGENT LOANS LI -->
                      <li class="mb-2 ms-6" v-if="activity.table == 'agent_loans'">
                          <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                            <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                          </span>
                          <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                            <span class="uppercase"> {{ activity.table }}</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                          </h3>
                          <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                          <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                          <div><span class="fw-bold">Admin: </span> <span>{{ activity.admin.first_name }} {{ activity.admin.last_name }}</span></div>
                          <div><span class="fw-bold">Receiver: </span> <span>{{ activity.receiver.first_name }} {{ activity.receiver.last_name }}</span></div>
                          <div>
                            <span class="fw-bold">Note: </span> 
                            <span v-if="activity.comment">{{ activity.comment }}</span>
                            <span v-else> _ </span>
                          </div>
                          </div>
                          <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
                      </li>
            
                      <li class="text-muted" v-if="activity.table == 'agent_loans'">
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
                          <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                            <td class="text-nowrap text-right"> {{ formatDecimalNumber(activity.amount) }} RD$</td>
                            <td class="text-nowrap text-right fw-bold">
                              <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.agent_loan_activits, 'new_receiver_investment')) }} RD$</div>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                      </li>
                      <!-- END AGENT LOANS LI -->
  
                  </ol>
                  
                </div>

                <div class="col-md-12 pl-4 pt-4" v-else>
                  <div class="fw-bold text-center text-muted">
                    No recharge activities found
                  </div>
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