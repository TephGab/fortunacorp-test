<template>
    <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="debt" role="tabpanel" aria-labelledby="debt-tab">
      <div class="row bg-light">
        <div class="col-md-12 pl-4 pt-4" v-if="sortedActivities && sortedActivities.length > 0">
  
          <ol class="relative border-s border-gray-200 dark:border-gray-700 d-flex justify-content-between mb-1 bg-gray-100 hover:bg-gray-200" v-for="(activity, index) in sortedActivities" :key="index">
          
            <!-- COMMISSION CASHOUT PENDING LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'envoy_cashouts' && activity.commission_category == 'commission' && !activity.use_envoy_debt">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase">{{ activity.commission_category }} CASHOUT </span> <span class="text-success ml-1">#{{ activity.id }} </span>
                  <span class="text-normal ml-1 text-lowercase text-warning text-xs">(Waiting for agent confirmation)</span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                  <div> <span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$ </span></div>
                  <div> <span class="fw-bold">User: </span> <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                 <div> <span class="fw-bold">Admin: </span> <span>{{ activity.admin.first_name }} {{ activity.admin.last_name }}</span></div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_cashouts' && activity.commission_category == 'commission' && !activity.use_envoy_debt">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.cashout_activits, 'current_envoy_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END COMMISSION CASHOUT PENDING LI -->

             <!-- COMMISSION CASHOUT CONFIRMED LI -->
             <li class="mb-2 ms-6" v-if="activity.table == 'envoy_cashout_confirmed' && activity.commission_category == 'commission' && activity.use_envoy_debt">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase">{{ activity.commission_category }} CASHOUT </span> <span class="text-success ml-1">#{{ activity.id }} </span>
                  <span class="text-normal ml-1 text-lowercase text-success text-xs">(Confirmed)</span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                  <div> <span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$ </span></div>
                  <div> <span class="fw-bold">User: </span> <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                 <div> <span class="fw-bold">Admin: </span> <span>{{ activity.admin.first_name }} {{ activity.admin.last_name }}</span></div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_cashout_confirmed' && activity.commission_category == 'commission' && activity.use_envoy_debt">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.cashout_activits, 'new_envoy_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END COMMISSION CASHOUT CONFIRMED LI -->

            <!-- AGENT DEPOSITS LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'envoy_agent_deposits'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> AGENT DEPOSIT </span> <span class="text-success ml-1">#{{ activity.id }} </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span>  <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$ </span></div>
                 <div> <span class="fw-bold">Agent: </span> <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                 <div> <span class="fw-bold">Admin: </span> <span>{{ activity.admin.first_name }} {{ activity.admin.last_name }}</span></div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.envoy_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_agent_deposits'">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.agent_deposit_activits, 'new_envoy_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
           <!-- END AGENT DEPOSITS LI -->
  
           <!-- ENVOY DEPOSITS LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'envoy_deposits'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> ENVOY DEPOSIT </span> <span class="text-success ml-1">#{{ activity.id }} </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Admin: </span> <span>{{ activity.admin.first_name }} {{ activity.admin.last_name }}</span></div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_deposits'">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.envoy_deposit_activits, 'new_envoy_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
           <!-- END ENVOY DEPOSITS LI -->

           <!-- SEND MONEY PENDING LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'envoy_send_money' && !activity.use_envoy_debt">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> AGENT WITHDRAWALS </span> <span class="text-success ml-1">#{{ activity.id }} </span>
                  <span class="text-normal ml-1 text-lowercase text-warning text-xs">(Waiting for agent)</span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Agent: </span> <span>{{ activity.receiver.first_name }} {{ activity.receiver.last_name }}</span></div>
                 <div><span class="fw-bold">Admin: </span> <span>{{ activity.sender.first_name }} {{ activity.sender.last_name }}</span></div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_send_money' && !activity.use_envoy_debt">
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
                  <td class="text-nowrap text-right"> {{ formatDecimalNumber(0) }} RD$</td>
                  <td class="text-nowrap text-right fw-bold">
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.send_money_activits, 'current_envoy_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
           <!-- END SEND MONEY PENDING LI -->

            <!-- SEND MONEY CONFIRMED LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'envoy_send_money_confirmed'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> AGENT WITHDRAWALS </span> <span class="text-success ml-1">#{{ activity.id }} </span>
                  <span class="text-normal ml-1 text-lowercase text-success text-xs">(Confirmed)</span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Agent: </span> <span>{{ activity.receiver.first_name }} {{ activity.receiver.last_name }}</span></div>
                 <div><span class="fw-bold">Admin: </span> <span>{{ activity.sender.first_name }} {{ activity.sender.last_name }}</span></div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_send_money_confirmed'">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.send_money_activits, 'new_envoy_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
           <!-- END SEND MONEY CONFIRMED LI -->

           <!-- ENVOY TRANSFERT SENT LI -->
           <li class="mb-2 ms-6" v-if="activity.table == 'envoy_transfert_sent'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> ENVOY TRANSFERT (SENT)</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Receiver: </span> <span>{{ activity.receiver.first_name }} {{ activity.receiver.last_name }}</span></div>
                 <div>
                  <span class="fw-bold">Note: </span> 
                  <span v-if="activity.comment">{{ activity.comment }}</span>
                  <span v-else> _ </span>
                </div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_transfert_sent'">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.envoy_transfert_activits, 'new_sender_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
           <!-- ENVOY TRANSFERT SENT LI -->

            <!-- ENVOY TRANSFERT RECEIVED LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'envoy_transfert_received'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> ENVOY TRANSFERT (RECEIVED)</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Receiver: </span> <span>{{ activity.receiver.first_name }} {{ activity.receiver.last_name }}</span></div>
                 <div>
                  <span class="fw-bold">Note: </span> 
                  <span v-if="activity.comment">{{ activity.comment }}</span>
                  <span v-else> _ </span>
                </div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_transfert_received'">
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.envoy_transfert_activits, 'new_receiver_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- ENVOY TRANSFERT RECEIVED MONEY LI -->

            <!-- AGENT LOANS LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'agent_loans'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> LOAN </span> <span class="text-success ml-1">#{{ activity.id }} </span>
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
                    <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.agent_loan_activits, 'new_receiver_debt')) }} RD$</div>
                  </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END AGENT LOANS LI -->

            <!-- AGENT LOAN REPAY LI -->
            <li class="mb-2 ms-6" v-if="activity.table == 'agent_loan_repays'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> LOAN PAYMENT</span>
                  <span class="text-success ml-1">#{{ activity.id }} </span>
                  <span class="text-capitalize fw-normal ml-1"> (Deposit {{ activity.id }}) </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Agent: </span> <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                 <div><span class="fw-bold">Admin: </span> <span>{{ activity.admin.first_name }} {{ activity.admin.last_name }}</span></div>
                 <div><span class="fw-bold">Envoy: </span> <span>{{ activity.envoy.first_name }} {{ activity.envoy.last_name }}</span></div>
                 <div>
                  <span class="fw-bold">Note: </span> 
                  <span v-if="activity.comment">{{ activity.comment }}</span>
                  <span v-else> _ </span>
                </div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'agent_loan_repays'">
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
                  <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.agent_loan_repay_activits, 'new_agent_debt')) }} RD$</div>
                </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END AGENT LOAN REPAY LI -->

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
                  <div>{{ formatDecimalNumber(activity.new_agent_debt) }} RD$</div>
                </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END AGENT LOAN TRANSACTION REPAY LI -->

             <!-- ENVOY AGENT LOAN REPAY LI -->
             <li class="mb-2 ms-6" v-if="activity.table == 'envoy_agent_loan_repays'">
                <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300" style="top: -17px">
                  <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                  </svg>
                </span>
                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-500 dark:text-dark text-uppercase">
                  <span class="uppercase"> AGENT LOAN PAYMENT</span> <span class="text-success ml-1">#{{ activity.id }} </span>
                </h3>
                <div class="mb-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                 <div><span class="fw-bold">Amount: </span> <span class="fw-bold">{{ formatDecimalNumber(activity.amount) }} RD$</span></div>
                 <div><span class="fw-bold">Agent: </span> <span>{{ activity.user.first_name }} {{ activity.user.last_name }}</span></div>
                 <div>
                  <span class="fw-bold">Note: </span> 
                  <span v-if="activity.comment">{{ activity.comment }}</span>
                  <span v-else> _ </span>
                </div>
                </div>
                <time class="block mb-4 text-xs font-normal leading-none text-gray-300 dark:text-gray-400"> {{ formatDate(activity.receiver_confirmation_date) }}</time>
            </li>
  
            <li class="text-muted" v-if="activity.table == 'envoy_agent_loan_repays'">
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
                  <div>{{ formatDecimalNumber(getLastEntryFromHistory(activity.agent_loan_repay_activits, 'new_envoy_debt')) }} RD$</div>
                </td>
              </tr>
              </tbody>
              </table>
            </li>
            <!-- END EMVOY AGENT LOAN REPAY LI -->
  
          </ol>
          
        </div>
  
        <div class="col-md-12 pl-4 pt-4" v-else>
          <div class="fw-bold text-center text-muted">
            No dept activities found
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