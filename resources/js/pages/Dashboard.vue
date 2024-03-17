<template>
  <!-- component -->
  <div class="container-fluid mb-2" style="position: relative; top: 15px;" v-if="userrole.name != 'super-admin'">
    <!-- Commision / Depts / Recharge section -->
    <div class="row p-0" v-if="user && user.user_account">
      <div class="col">
        <div class="antialiased w-full h-full bg-gray-700 text-gray-400 font-inter p-1 pb-0 rounded-xl">
          <div class="container mx-auto">
            <div>
              <div class="row">
                <div class="text-center text-orange-500 text-sm" v-if="user && user.user_account.depts > 0 && userrole.name == 'agent'">
                  <i class="fa-solid fa-circle-exclamation"></i> <span>Notice:</span> {{ 100 - user.loan_percentage }}% of each transaction's commission will be allocated directly to debt repayment.
                </div>
                <div class="text-right pr-1">
                  <button @click="showAmount" v-if="isAmountVisible">
                    <i class="fa-solid fa-eye-slash"></i>
                  </button>
                  <button @click="showAmount" v-else>
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-evenly gap-10 m-0 p-0" v-if="user && user.user_account">

                <!-- Commission -->
                  <div v-if="userrole.name == 'agent' || userrole.name == 'operator'" id="plan" class="rounded-xl text-center overflow-hidden w-full transform hover:shadow-2xl hover:scale-105 transition duration-200 ease-in bg-gray-600">
                    <div id="title" class="w-full py-2 border-b border-gray-800">
                      <h2 class="text-xs text-white-800 text-uppercase">Commission</h2>
                      <h3 class="font-bold font-normal text-light text-xs mt-1">
                        {{ amountBlurry(user.user_account.profits, isAmountVisible) }} RD$
                      </h3>
                    </div>
                    <div id="content" class="">
                      <div id="icon" class="my-2">
                          <router-link v-if="userrole.name == 'agent'"  class="btn btn-warning text-white-700 text-xs p-1" class-active="active" :to="{ name: 'profittorecharge.create', params: { commission: 'commission' } }">
                            <i class="fa-solid fa-rotate"></i> Transfer
                          </router-link>
                          
                          <router-link v-if="userrole.name != 'agent' && canCashout" class="btn btn-warning text-white-700 text-xs p-1" class-active="active" :to="{ name: 'cashout.create', params: { commission: 'commission' } }">
                            <i class="fa-solid fa-arrow-left"></i> Cashout
                          </router-link>

                          <div v-if="userrole.name != 'agent' && !canCashout" class="text-warning"> 
                            <i class="fa-solid fa-circle-info"></i> <span class="text-xs">Pending deposit</span>
                          </div>
                      </div>
                    </div>
                  </div>
                <!-- End Commission -->

                <!-- Referral Commission -->
                  <div id="plan" class="rounded-xl text-center overflow-hidden w-full transform hover:shadow-2xl hover:scale-105 transition duration-200 ease-in bg-gray-600">
                      <div id="title" class="w-full py-2 border-b border-gray-800">
                        <h2 class="text-xs text-white-800 text-uppercase">Referral Commission</h2>
                        <h3 class="font-bold font-normal text-light text-xs mt-1">
                          {{ amountBlurry(user.user_account.referral_commissions, isAmountVisible) }} RD$
                        </h3>
                      </div>
                      <div id="content" class="">
                        <div id="icon" class="my-2">
                          <router-link v-if="userrole.name == 'agent'"  class="btn btn-warning text-white-700 text-xs p-1" class-active="active" :to="{ name: 'profittorecharge.create', params: { commission: 'referral_commission' } }">
                            <i class="fa-solid fa-rotate"></i> Transfer
                          </router-link>
                          
                          <router-link v-if="userrole.name != 'agent' && canCashout" class="btn btn-warning text-white-700 text-xs p-1" class-active="active" :to="{ name: 'cashout.create', params: { commission: 'referral_commission' } }">
                            <i class="fa-solid fa-arrow-left"></i> Cashout
                          </router-link>

                          <div v-if="userrole.name != 'agent' && !canCashout" class="text-warning"> 
                            <i class="fa-solid fa-circle-info"></i> <span class="text-xs">Pending cashout</span>
                          </div>
                        </div>
                      </div>
                  </div>
                <!-- End Referral Commission -->

                <!-- Recharge -->
                  <div v-if="userrole.name == 'agent'"  id="plan" class="rounded-xl text-center overflow-hidden w-full transform hover:shadow-2xl hover:scale-105 transition duration-200 ease-in bg-gray-600">
                    <div id="title" class="w-full py-2 border-b border-gray-800">
                      <h2 class="text-xs text-white-800 text-uppercase">Recharge</h2>
                      <h3 class="font-bold font-normal text-light text-xs mt-1">
                        {{ amountBlurry(user.user_account.investments, isAmountVisible) }} RD$
                      </h3>
                    </div>
                    <div id="content" class="">
                      <div id="icon" class="my-2">
                          <router-link v-if="canDeposit" class="btn btn-warning text-white-700 text-xs p-1" class-active="active" :to="{ name: 'agentsdeposits.create' }">
                            <i class="fa-solid fa-plus"></i> Deposit
                          </router-link>
                          <div v-if="!canDeposit" class="text-warning"> 
                            <i class="fa-solid fa-circle-info"></i> <span class="text-xs">Pending deposit</span>
                          </div>
                      </div>
                    </div>
                  </div>
                <!-- Recharge  -->

                <!-- Debt -->
                  <div v-if="userrole.name == 'envoy'"  id="plan" class="rounded-xl text-center overflow-hidden w-full transform hover:shadow-2xl hover:scale-105 transition duration-200 ease-in bg-gray-600">
                    <div id="title" class="w-full py-2 border-b border-gray-800">
                      <h2 class="text-xs text-white-800 text-uppercase">Debt</h2>
                      <h3 class="font-bold font-normal text-light text-xs mt-1">
                        {{ amountBlurry(user.user_account.depts, isAmountVisible) }} RD$
                      </h3>
                    </div>
                    <div id="content" class="">
                      <div id="icon" class="my-2">
                          <router-link class="btn btn-warning text-white-700 text-xs p-1 m-1" class-active="active" :to="{ name: 'envoydeposits.create' }">
                            <i class="fa-solid fa-plus"></i> Deposit
                          </router-link>

                          <router-link class="btn btn-warning text-white-700 text-xs p-1 m-1" class-active="active" :to="{ name: 'envoytransferts.create' }">
                            <i class="fa-solid fa-share"></i> Transfer
                          </router-link>
                      </div>
                    </div>
                  </div>
                <!-- Debt  -->

              </div>

              <div class="d-flex justify-content-center w-100">
                <div v-if="userrole.name == 'agent'" class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 justify-evenly gap-10 mt-3 bg-gradient-to-b from-cyan-950 via-cyan-950 to-gray-700 bg-opacity-50 rounded-t-2xl shadow-xl" style="width: 99%">

                <!-- Cash in hand -->
                  <div v-if="userrole.name == 'agent'"  id="plan" class="rounded-lg text-center overflow-hidden w-full transform hover:scale-105 transition duration-200 ease-in bg-gray-650">
                    <div id="title" class="w-full py-2 text-xs">
                      <h2 class="text-uppercase text-muted">Cash in hand</h2>
                      <h3 class="font-bold font-normal mt-1">
                        {{ amountBlurry(user.user_account.cash_in_hand, isAmountVisible) }} RD$
                      </h3>
                    </div>
                  </div>
                <!-- Cash in hand  -->

                <!-- Capital -->
                  <div v-if="userrole.name == 'agent'"  id="plan" class="rounded-lg text-center overflow-hidden w-full transform hover:scale-105 transition duration-200 ease-in bg-gray-650">
                    <div id="title" class="w-full py-2 text-xs">
                      <h2 class="text-uppercase text-muted">Capital</h2>
                      <h3 class="font-bold font-normal mt-1">
                        {{ amountBlurry(user.user_account.capital, isAmountVisible) }} RD$
                      </h3>
                    </div>
                  </div>
                <!-- Capital  -->

                 <!-- Loan in hand -->
                 <div v-if="userrole.name == 'agent'" id="plan" class="rounded-lg text-center overflow-hidden w-full transform hover:scale-105 transition duration-200 ease-in bg-gray-650">
                    <div id="title" class="w-full py-2 text-xs">
                      <h2 class="text-uppercase text-muted">Debt</h2>
                      <h3 class="font-bold font-normal mt-1">
                        {{ amountBlurry(user.user_account.depts, isAmountVisible) }} RD$
                      </h3>
                      <div v-if="user && user.user_account.depts > 0 && userrole.name == 'agent'">
                        <router-link class="btn bg-gray-400 hover:bg-gray-400 hover:text-gray-800 text-white-700 text-xs m-1 mb-0 p-1" class-active="active" :to="{ name: 'agentloanrepay.create' }">
                          <i class="fa-solid fa-money-check"></i> Repay
                        </router-link>
                      </div>
                    </div>
                  </div>
                <!-- Loan in hand  -->

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
     <!-- End Commision / Depts / Recharge section -->
  </div>


  <div class="container-fluid" style="position: relative; top: 15px;">
    <div class="row">
      <div class="col-md-12">
        <!-- Daily rate -->
        <div class="card">
          <!-- User online -->
          <!-- <h1 v-if="user.online_status == 'online' || isOnline == 'online'">User is Online </h1>
          <h1 v-else>User is Offline </h1> -->
          <!-- user online end -->
          <div class="card-header text-center font-weight-bold m-0 p-0">
            <h5 class="m-0 p-1 h5 text-uppercase">Daily rate</h5>
          </div>
          <div class="card-body row m-0 p-2">
            <div class="col-lg-6 col-6 pl-0">
              <!-- small box -->
              <div class="small-box bg-light">
                <a href="#" class="small-box-footer">Transfert</a>
                <div class="inner text-center p-1">
                  <h4 class="d-flex justify-content-center">
                    <span v-show="showDailyRateSale">{{ dailyrateSale }} </span>
                    <span class="fw-normal" style="font-size: 15px;">
                      <input class="form-control form-control-sm" v-show="!showDailyRateSale" type="number" v-model="dailyrateSale" @keyup.enter="updateDailyRate" />
                      <button @click="saleRateShow">
                        <i class="fas fa-edit ml-2" v-if="can('approve', 'transaction')"></i>
                      </button>
                    </span>
                  </h4>

                </div>
                <!-- <a href="#" class="small-box-footer"><i class="fas fa-edit"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6 pr-0">
              <!-- small box -->
              <div class="small-box bg-light">
                <a href="#" class="small-box-footer">Reception </a>
                <div class="inner text-center p-1">
                  <h4 class="d-flex justify-content-center">
                    <span v-show="showDailyRatePurchase">{{ dailyratePurchase }} </span>
                    <span class="fw-normal" style="font-size: 15px;">
                      <input class="form-control form-control-sm" v-show="!showDailyRatePurchase" type="number"
                        v-model="dailyratePurchase" @keyup.enter="updateDailyRate" />
                      <button @click="purchaseRateShow">
                        <i class="fas fa-edit ml-2" v-if="can('approve', 'transaction')"></i>
                      </button>
                    </span>
                  </h4>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- End daily rate -->
      </div>
    </div>

    <!-- <hr class="hr" v-if="can('view', 'activity')"/> -->

    <!-- Global activity -->
    <div class="row">
      <div class="text-right">
        <div v-if="userrole.name == 'super-admin' || userrole.name == 'admin'">
          <label><i class="fa-regular fa-calendar-days"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs mr-2" v-model="form.selected_month" @change="sortChartByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionMonth" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
          <label><i class="fa-regular fa-calendar"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.selected_year" @change="sortChartByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionYear" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
        </div>
        <div v-else>
          <label><i class="fa-regular fa-calendar-days"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs mr-2" v-model="form.selected_month" @change="sortUserChartByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionMonth" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
          <label><i class="fa-regular fa-calendar"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.selected_year" @change="sortUserChartByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionYear" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="row" v-if="can('view', 'activity')">
      <div class="col-md-12 text-center mt-1 mb-2">
        <h5>MONTHLY USERS PERFORMANCES</h5>
      </div>
    </div>
    <!-- Info boxes -->
    <div class="row" v-if="can('view', 'activity')">
      <div class="col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-warning elevation-1">
            <!-- <i class="fas fa-users"></i> -->
            <img class="img-fluid" src="img/logo/logo.png" alt="Logo" />
          </span>

          <div class="info-box-content">
            <span class="info-box-text"> Local transactions </span>
            <span class="info-box-number">
              {{ totalTransaction.totalLocal }}
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-danger elevation-1">
            <img class="img-fluid" src="img/logo/moncash_logo.png" alt="Moncash_Logo" />
            <!-- <i class="fas fa-thumbs-up"></i> -->
          </span>

          <div class="info-box-content">
            <span class="info-box-text"> Moncash transactions</span>
            <span class="info-box-number"> {{ totalTransaction.totalMoncash }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->

      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-info elevation-1">
            <img class="img-fluid" src="img/logo/natcash_logo.png" alt="Natcash_Logo" />
            <!-- <i class="fas fa-money-bill"></i> -->
          </span>
          <!-- <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span> -->

          <div class="info-box-content">
            <span class="info-box-text"> Natcash transactions </span>
            <span class="info-box-number"> {{ totalTransaction.totalNatcash }} </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1">
            <i class="fas fa-users"></i>
            <!-- <img class="img-fluid" src="img/logo/logo.png" alt="Logo"/> -->
          </span>

          <div class="info-box-content">
            <span class="info-box-text"> New clients </span>
            <span class="info-box-number">{{ totalTransaction.totalClient }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row" v-if="can('view', 'activity')">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Monthly Transactions</h5>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="text-right">
                  <label class="text-xs">Chart type: </label>
                  <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.chart_type"
                    @change="chartType(form.chart_type)">
                    <option v-for="option in chartTypeOption" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                </div>
                <div class="chart">
                  <!-- Sales Chart Canvas -->
                  <canvas id="monthlyTransactionChart" height="180" style="height: 180px;"></canvas>
                </div>
                <!-- /.chart-responsive -->
              </div>
              <!-- /.col -->
              <!-- Removed part col 4 -->
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- ./card-body -->
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5 class="description-header text-xs"> {{ formatDecimalNumber(totalTransaction.totalRevenue) }} RD$
                  </h5>
                  <span class="description-text text-xs">REVENUE</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <!-- <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span> -->
                  <h5 class="description-header text-xs"> {{ formatDecimalNumber(totalTransaction.totalUserDept) }} RD$
                  </h5>
                  <span class="description-text text-xs">TO CLAIM</span>
                  <router-link :to="{ name: 'agentsdeposits.index' }">
                    <div class="text-xs text-warning" v-if="totalAgentDepositCount.pendingAgentDepositCount"> {{ totalAgentDepositCount.pendingAgentDepositCount }} pending deposits</div>
                    <div class="text-xs text-info" v-if="totalAgentDepositCount.completedAgentDepositCount"> {{ totalAgentDepositCount.completedAgentDepositCount }} deposits waiting for confirmation</div>
                  </router-link>
                  <!-- <div class="text-xs text-danger" v-if="totalAgentDepositCount.envoyOnlyConfirmedCashoutCount"> {{ totalAgentDepositCount.envoyOnlyConfirmedCashoutCount }} cashouts waiting for confirmation</div> -->
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span> -->
                  <h5 class="description-header text-xs">{{ formatDecimalNumber(totalTransaction.totalProviderClaim) }}RD$</h5>
                  <span class="description-text text-xs">DEBTS</span>
                  <router-link :to="{ name: 'providerpayments.create' }">
                    <div class="text-info">
                      <i class="fa-solid fa-hand-holding-dollar"></i>
                      <span class="text-capitalize"> New payment </span>
                    </div>
                  </router-link>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-3 col-6">
                <div class="description-block">
                  <!-- <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span> -->
                  <h5 class="description-header text-xs">{{ formatDecimalNumber(totalTransaction.totalUserBalance) }}RD$</h5>
                  <span class="description-text text-xs">USER BALANCES</span>
                  <router-link :to="{ name: 'cashout.index' }">
                    <div class="text-xs text-warning" v-if="totalCashoutsCount.pendingCashoutCount"> {{ totalCashoutsCount.pendingCashoutCount }} pending cashouts</div>
                    <div class="text-xs text-info" v-if="totalCashoutsCount.completedCashoutCount"> {{ totalCashoutsCount.completedCashoutCount }} completed cashouts</div>
                    <div class="text-xs text-danger" v-if="totalCashoutsCount.envoyOnlyConfirmedCashoutCount"> {{ totalCashoutsCount.envoyOnlyConfirmedCashoutCount }} cashouts waiting for confirmation</div>
                  </router-link>
                </div>
                <!-- /.description-block -->
              </div>
            </div>
            <hr class="hr-blurry" />
            <div class="row">

              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success text-xs"
                    v-if="totalTransaction.depositPercentageForSeletedMonth >= totalTransaction.depositPercentagPreviousMonth"
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-up"></i>
                    {{ formatDecimalNumber(totalTransaction.depositPercentage) }}%
                  </span>
                  <span class="description-percentage text-danger text-xs" v-else
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-down"></i>
                    {{ formatDecimalNumber(totalTransaction.depositPercentage) }}%
                  </span>
                  <h5 class="description-header text-xs fw-bold"> {{ formatDecimalNumber(totalTransaction.totalDeposit) }} RD$ </h5>
                  <span class="text-xs">DEPOSITS</span>
                </div>
                <!-- /.description-block -->
              </div>

              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success text-xs"
                    v-if="totalTransaction.investmentPercentageForSeletedMonth >= totalTransaction.investmentPercentagPreviousMonth"
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-up"></i>
                    {{ formatDecimalNumber(totalTransaction.investmentPercentage) }}%
                  </span>
                  <span class="description-percentage text-danger text-xs" v-else
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-down"></i>
                    {{ formatDecimalNumber(totalTransaction.investmentPercentage) }}%
                  </span>
                  <h5 class="description-header text-xs fw-bold"> {{ formatDecimalNumber(totalTransaction.totalInvestment)
                  }} RD$ </h5>
                  <span class="text-xs">RECHARGES</span>
                </div>
                <!-- /.description-block -->
              </div>

              <!-- <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success text-xs"
                    v-if="totalTransaction.cashoutPercentageForSeletedMonth >= totalTransaction.cashoutPercentagPreviousMonth"
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-up"></i>
                    {{ formatDecimalNumber(totalTransaction.cashoutPercentage) }}%
                  </span>
                  <span class="description-percentage text-danger text-xs" v-else
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-down"></i>
                    {{ formatDecimalNumber(totalTransaction.cashoutPercentage) }}%
                  </span>
                  <h5 class="description-header text-xs fw-bold"> {{ formatDecimalNumber(totalTransaction.totalCashout) }}
                    RD$ </h5>
                  <span class="text-xs">WITHDRAWAL</span>
                </div>
              </div> -->

            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>

    <div class="row" v-if="can('view', 'activity')">
      <div class="col-md-12">
        <!-- Top agent -->
        <div class="card">
          <div class="card-header text-center font-weight-bold m-0 p-0">
            <h5 class="m-0 p-1 h5 text-uppercase">Top Agents</h5>
          </div>
          <div class="card-body row people team-boxed">
            <div class="col-md-4 col-lg-4 item text-center mb-2" v-for="agent in totalTransaction.topAgents"
              :key="agent.id">
              <div class="box text-center bg-light shadow-sm pt-1">
                <div class="d-flex justify-content-center">
                  <img class="rounded-circle" src="/img/users/default-user.png">
                </div>
                <h3 class="name text-capitalize">{{ agent.first_name }} {{ agent.last_name }}</h3>
                <p class="title">{{ agent.code }}</p>
                <p><i class="fas fa-map-marker-alt text-red-400"></i> {{ agent.location }} </p>
                <p class="description">
                  <span> {{ agent.transaction_count }} </span> Transactions
                </p>
                <div class="social">
                  <!--  -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End top agent -->

        <!-- To operator -->
        <div class="card">
          <div class="card-header text-center font-weight-bold m-0 p-0">
            <h5 class="m-0 p-1 h5 text-uppercase">Top Operators</h5>
          </div>
          <div class="card-body row people team-boxed">
            <div class="col-md-4 col-lg-4 item text-center mb-2" v-for="operator in totalTransaction.topOperators"
              :key="operator.id">
              <div class="box text-center bg-light shadow-sm pt-1">
                <div class="d-flex justify-content-center">
                  <img class="rounded-circle" src="/img/users/default-user.png">
                </div>
                <h3 class="name text-capitalize">{{ operator.first_name }} {{ operator.last_name }}</h3>
                <p class="title">{{ operator.code }}</p>
                <p><i class="fas fa-map-marker-alt text-red-400"></i> {{ operator.location }} </p>
                <p class="description">
                  <span> {{ operator.transaction_count }} </span> Transactions
                </p>
                <div class="social">
                  <!--  -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End top operator -->
      </div>
    </div>

    <div class="row" v-if="can('view', 'activity')">
      <div class="col-md-12">
        <!-- Top agent -->
        <div class="card">
          <div class="card-header text-center font-weight-bold m-0 p-0">
            <h5 class="m-0 p-1 h5 text-uppercase">Top Clients</h5>
          </div>
          <div class="card-body row people team-boxed">
            <div class="col-md-4 col-lg-4 item text-center mb-2" v-for="client in totalTransaction.topClients"
              :key="client.id">
              <div class="box text-center bg-light shadow-sm pt-1">
                <h3 class="name text-capitalize">{{ client.name }} </h3>
                <p class="title text-primary">{{ client.phone }}</p>
                <p class="description">
                  <span> {{ client.transaction_count }} </span> Transactions
                </p>
                <div class="social">
                  <!--  -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End top agent -->
      </div>
    </div>

    <hr class="hr-blurry mb-3 mt-1" v-if="can('view', 'activity')" />

    <div class="row" v-if="can('view', 'activity')">
      <h5 class="m-0 h5 text-uppercase">User devices</h5>
      <div class="col-md-12">
        <!-- Top agent -->
        <div class="card">
          <!-- <div class="card-header text-center font-weight-bold m-0 p-0">
            <h5 class="m-0 p-1 h5 text-uppercase">User device</h5>
          </div> -->
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box bg-light">
                  <div class="inner">
                    <h3>{{ totalTransaction.userVisitOnPhone }}<sup style="font-size: 20px">Users</sup></h3>

                    <p>Connected on mobile phone</p>
                  </div>
                  <div class="icon">
                    <i class="ion fas fa-mobile-alt"></i>
                  </div>
                </div>
              </div>

              <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box bg-light">
                  <div class="inner">
                    <h3>{{ totalTransaction.userVisitOnDesktop }}<sup style="font-size: 20px">Users</sup></h3>

                    <p>Connected on desktop</p>
                  </div>
                  <div class="icon">
                    <i class="ion fas fa-desktop"></i>
                  </div>
                  <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- End top agent -->
      </div>
    </div>
    <!-- End global activity -->




    <hr class="hr" />




    <!-- User part -->
    <div class="row" v-if="cannot('view', 'useractivity')">
      <div class="col-md-12 text-center mt-1 mb-2">
        <h5>MY PERFORMANCE</h5>
      </div>
    </div>

    <!-- Info boxes -->
    <div class="row" v-if="cannot('view', 'useractivity')">
      <div class="col-md-4">
        <div class="info-box">
          <span class="info-box-icon bg-warning elevation-1">
            <!-- <i class="fas fa-users"></i> -->
            <img class="img-fluid" src="img/logo/logo.png" alt="Logo" />
          </span>

          <div class="info-box-content">
            <span class="info-box-text"> Local transactions </span>
            <span class="info-box-number">
              {{ totalUserTransaction.totalLocal }}
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-danger elevation-1">
            <img class="img-fluid" src="img/logo/moncash_logo.png" alt="Moncash_Logo" />
            <!-- <i class="fas fa-thumbs-up"></i> -->
          </span>

          <div class="info-box-content">
            <span class="info-box-text"> Moncash transactions</span>
            <span class="info-box-number"> {{ totalUserTransaction.totalMoncash }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->

      <div class="col-md-4">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-info elevation-1">
            <img class="img-fluid" src="img/logo/natcash_logo.png" alt="Natcash_Logo" />
            <!-- <i class="fas fa-money-bill"></i> -->
          </span>
          <!-- <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span> -->

          <div class="info-box-content">
            <span class="info-box-text"> Natcash transactions </span>
            <span class="info-box-number"> {{ totalUserTransaction.totalNatcash }} </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->

    </div>
    <!-- /.row -->

    <div class="row" v-if="cannot('view', 'useractivity')">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Monthly transactions</h5>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="text-right">
                  <label class="text-xs">Chart type: </label>
                  <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.chart_type"
                    @change="chartType(form.chart_type)">
                    <option v-for="option in chartTypeOption" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                </div>
                <div class="chart">
                  <!-- Sales Chart Canvas -->
                  <canvas id="monthlyUserTransactionChart" height="180" style="height: 180px;"></canvas>
                </div>
                <!-- /.chart-responsive -->
              </div>
              <!-- /.col -->
              <!-- Removed part col 4 -->
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- ./card-body -->
          <div class="card-footer p-0">
            <div class="row m-0 p-0">
              <div class="col-sm-6 col-md-2 col-lg-2 col-6">
                <div class="description-block border-right text-center p-1">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5
                    class="description-header text-xs fw-normal d-flex justify-content-between align-items-center bg-gray-200">
                    <span> COMPLETED TRANSFERT</span>
                    <button @click="extend('comp_trans')" v-if="!extendCompTransfert">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button @click="extend('comp_trans')" v-else>
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </h5>
                  <div class="description-text text-xs text-capitalize text-red-500" v-show="extendCompTransfert">
                    <div class="fw-bold"> Moncash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumCompletedMoncashTransfert) }} RD$
                  </div>
                  <div class="description-text text-xs text-capitalize text-blue-500" v-show="extendCompTransfert">
                    <div class="fw-bold"> Natcash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumCompletedNatcashTransfert) }} RD$
                  </div>
                  <div class="flex items-center justify-center" v-show="extendCompTransfert">
                    <hr class="w-1/2 border-t border-gray-500">
                  </div>
                  <div class="description-text text-xs text-capitalize fw-bold text-gray-600">
                    <div class="fw-bold" v-show="extendCompTransfert"> Total </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumCompletedTransfert) }} RD$
                  </div>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-6 col-md-2 col-lg-2 col-6">
                <div class="description-block border-right text-center p-1">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5
                    class="description-header text-xs fw-normal d-flex justify-content-between align-items-center bg-gray-200">
                    <span> COMPLETED RECEPTION</span>
                    <button @click="extend('comp_rec')" v-if="!extendCompReception">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button @click="extend('comp_rec')" v-else>
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </h5>
                  <div class="description-text text-xs text-capitalize text-red-500" v-show="extendCompReception">
                    <div class="fw-bold"> Moncash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumCompletedMoncashReception) }} RD$
                  </div>
                  <div class="description-text text-xs text-capitalize text-blue-500" v-show="extendCompReception">
                    <div class="fw-bold"> Natcash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumCompletedNatcashReception) }} RD$
                  </div>
                  <div class="flex items-center justify-center" v-show="extendCompReception">
                    <hr class="w-1/2 border-t border-gray-500">
                  </div>
                  <div class="description-text text-xs text-capitalize fw-bold text-gray-600">
                    <div class="fw-bold" v-show="extendCompReception"> Total </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumCompletedReception) }} RD$
                  </div>
                </div>
                <!-- /.description-block -->
              </div>

              <div class="col-sm-6 col-md-2 col-lg-2 col-6">
                <div class="description-block border-right text-center p-1">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5
                    class="description-header text-xs fw-normal d-flex justify-content-between align-items-center bg-gray-200">
                    <span> APPROVED TRANSFERT</span>
                    <button @click="extend('appr_trans')" v-if="!extendApprTransfert">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button @click="extend('appr_trans')" v-else>
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </h5>
                  <div class="description-text text-xs text-capitalize text-red-500" v-show="extendApprTransfert">
                    <div class="fw-bold"> Moncash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumApprovedMoncashTransfert) }} RD$
                  </div>
                  <div class="description-text text-xs text-capitalize text-blue-500" v-show="extendApprTransfert">
                    <div class="fw-bold"> Natcash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumApprovedNatcashTransfert) }} RD$
                  </div>
                  <div class="flex items-center justify-center" v-show="extendApprTransfert">
                    <hr class="w-1/2 border-t border-gray-500">
                  </div>
                  <div class="description-text text-xs text-capitalize fw-bold text-gray-600">
                    <div class="fw-bold" v-show="extendApprTransfert"> Total </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumApprovedTransfert) }} RD$
                  </div>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-6 col-md-2 col-lg-2 col-6">
                <div class="description-block border-right text-center p-1">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5
                    class="description-header text-xs fw-normal d-flex justify-content-between align-items-center bg-gray-200">
                    <span> APPROVED RECEPTION</span>
                    <button @click="extend('appr_rec')" v-if="!extendApprReception">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button @click="extend('appr_rec')" v-else>
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </h5>
                  <div class="description-text text-xs text-capitalize text-red-500" v-show="extendApprReception">
                    <div class="fw-bold"> Moncash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumApprovedMoncashReception) }} RD$
                  </div>
                  <div class="description-text text-xs text-capitalize text-blue-500" v-show="extendApprReception">
                    <div class="fw-bold"> Natcash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumApprovedNatcashReception) }} RD$
                  </div>
                  <div class="flex items-center justify-center" v-show="extendApprReception">
                    <hr class="w-1/2 border-t border-gray-500">
                  </div>
                  <div class="description-text text-xs text-capitalize fw-bold text-gray-600">
                    <div class="fw-bold" v-show="extendApprReception"> Total </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumApprovedReception) }} RD$
                  </div>
                </div>
                <!-- /.description-block -->
              </div>
              <div class="col-sm-6 col-md-2 col-lg-2 col-6">
                <div class="description-block border-right text-center p-1">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5
                    class="description-header text-xs fw-normal d-flex justify-content-between align-items-center bg-gray-200">
                    <span> PENDING TRANSFERT</span>
                    <button @click="extend('pen_trans')" v-if="!extendPenTransfert">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button @click="extend('pen_trans')" v-else>
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </h5>
                  <div class="description-text text-xs text-capitalize text-red-500" v-show="extendPenTransfert">
                    <div class="fw-bold"> Moncash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumPendingMoncashTransfert) }} RD$
                  </div>
                  <div class="description-text text-xs text-capitalize text-blue-500" v-show="extendPenTransfert">
                    <div class="fw-bold"> Natcash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumPendingNatcashTransfert) }} RD$
                  </div>
                  <div class="flex items-center justify-center" v-show="extendPenTransfert">
                    <hr class="w-1/2 border-t border-gray-500">
                  </div>
                  <div class="description-text text-xs text-capitalize fw-bold text-gray-600">
                    <div class="fw-bold" v-show="extendPenTransfert"> Total </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumPendingTransfert) }} RD$
                  </div>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-6 col-md-2 col-lg-2 col-6">
                <div class="description-block border-right text-center p-1">
                  <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                  <h5
                    class="description-header text-xs fw-normal d-flex justify-content-between align-items-center bg-gray-200">
                    <span> PENDING RECEPTION</span>
                    <button @click="extend('pen_rec')" v-if="!extendPenReception">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button @click="extend('pen_rec')" v-else>
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </h5>
                  <div class="description-text text-xs text-capitalize text-red-500" v-show="extendPenReception">
                    <div class="fw-bold"> Moncash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumPendingMoncashReception) }} RD$
                  </div>
                  <div class="description-text text-xs text-capitalize text-blue-500" v-show="extendPenReception">
                    <div class="fw-bold"> Natcash </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumPendingNatcashReception) }} RD$
                  </div>
                  <div class="flex items-center justify-center" v-show="extendPenReception">
                    <hr class="w-1/2 border-t border-gray-500">
                  </div>
                  <div class="description-text text-xs text-capitalize fw-bold text-gray-600">
                    <div class="fw-bold" v-show="extendPenReception"> Total </div>
                    {{ formatDecimalNumber(totalUserTransaction.sumPendingReception) }} RD$
                  </div>
                </div>
                <!-- /.description-block -->
              </div>

              <div class="row text-center bg-gray-100 m-0 p-0">
                <div class="col-sm-6 col-md-6 col-lg-6 col-6 mb-1 p-0">
                  <div class="description-block border-right text-center mb-1 p-0">
                    <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                    <h5 class="description-header text-sm fw-normal fw-bold text-gray-700"> Total transfert </h5>
                    <div class="description-text text-xs text-capitalize fw-bold text-gray-700">
                      {{ formatDecimalNumber(totalUserTransaction.sumTransfertTotalTransaction) }} RD$
                    </div>
                  </div>
                  <!-- /.description-block -->
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 col-6 mb-1 p-0">
                  <div class="description-block text-center mb-1 p-0">
                    <!-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> -->
                    <h5 class="description-header text-sm fw-normal fw-bold text-gray-700"> Total reception </h5>
                    <div class="description-text text-xs text-capitalize fw-bold text-gray-700">
                      {{ formatDecimalNumber(totalUserTransaction.sumReceptionTotalTransaction) }} RD$
                    </div>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
            </div>
            <!-- /.row -->
            <hr class="hr-blurry" />

            <div class="row">

              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success text-xs"
                    v-if="totalUserTransaction.depositPercentageForSeletedMonth >= totalUserTransaction.depositPercentagPreviousMonth"
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-up"></i>
                    {{ formatDecimalNumber(totalUserTransaction.depositPercentage) }}%
                  </span>
                  <span class="description-percentage text-danger text-xs" v-else
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-down"></i>
                    {{ formatDecimalNumber(totalUserTransaction.depositPercentage) }}%
                  </span>
                  <h5 class="description-header text-xs fw-bold"> {{
                    formatDecimalNumber(totalUserTransaction.totalDeposit) }} RD$ </h5>
                  <span class="text-xs">DEPOSITS</span>
                </div>
                <!-- /.description-block -->
              </div>

              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success text-xs"
                    v-if="totalUserTransaction.investmentPercentageForSeletedMonth >= totalUserTransaction.investmentPercentagPreviousMonth"
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-up"></i>
                    {{ formatDecimalNumber(totalUserTransaction.investmentPercentage) }}%
                  </span>
                  <span class="description-percentage text-danger text-xs" v-else
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-down"></i>
                    {{ formatDecimalNumber(totalUserTransaction.investmentPercentage) }}%
                  </span>
                  <h5 class="description-header text-xs fw-bold"> {{
                    formatDecimalNumber(totalUserTransaction.totalInvestment) }} RD$ </h5>
                  <span class="text-xs">RECHARGES</span>
                </div>
                <!-- /.description-block -->
              </div>

              <!-- <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success text-xs"
                    v-if="totalUserTransaction.cashoutPercentageForSeletedMonth >= totalUserTransaction.cashoutPercentagPreviousMonth"
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-up"></i>
                    {{ formatDecimalNumber(totalUserTransaction.cashoutPercentage) }}%
                  </span>
                  <span class="description-percentage text-danger text-xs" v-else
                    title="Percentage compared to the previous month">
                    <i class="fas fa-caret-down"></i>
                    {{ formatDecimalNumber(totalUserTransaction.cashoutPercentage) }}%
                  </span>
                  <h5 class="description-header text-xs fw-bold"> {{
                    formatDecimalNumber(totalUserTransaction.totalCashout) }} RD$ </h5>
                  <span class="text-xs">WITHDRAWAL</span>
                </div>
              </div> -->
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- End user part -->
  </div>
</template>

<script>
import { onMounted, ref, reactive } from 'vue';
import useTransactions from "./../services/transactionservices";
import useUtils from "./../services/utilsservices";
import useDashboard from "./../services/dashboardservices";
import useCashouts from "../services/cashoutservices";
import useAgentDeposits from "../services/agentdepositsservices";
import useUsers from "../services/usersservices";
import { useAbility } from '@casl/vue';

export default {
  props: ['user', 'userrole'],
  setup(props) {
    const { getNotifications, notifications, getDailyRate, dailyRateUpdate, dailyrateSale, 
            dailyratePurchase } = useTransactions();
    const { getTotalTransaction, totalTransaction, monthlyTransactionRecapChart, getTotalUserTransaction,
            totalUserTransaction, monthlyUserTransactionRecapChart } = useDashboard();
    const { formatDecimalNumber } = useUtils();
    const { getCashouts, getTotalCashoutsCount, totalCashoutsCount, checkPendingCashout, pendingCashout } = useCashouts();
    const { getAgentDeposits, agentDeposits, getTotalAgentDepositsCount, totalAgentDepositCount , checkPendingDeposit, pendingDeposit} = useAgentDeposits();
    const { getUser, user } = useUsers();
    const { can, cannot } = useAbility();
    const showDailyRateSale = ref(true);
    const showDailyRatePurchase = ref(true);
    const canDeposit = ref(false);
    const canCashout = ref(false);
    const errors = ref([]);
    let myModal = '';
    const isOnline = ref('');
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;

    const form = reactive({
      selected_year: currentYear,
      selected_month: currentMonth,
      chart_type: "bar",
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

    const chartTypeOption = ref([
      { text: 'Line', value: 'line' },
      { text: 'Bar', value: 'bar' }
    ]);

    const chartType = async (chart_type) => {
     await monthlyTransactionRecapChart({ 'selected_year': form.selected_year, 'chart_type': chart_type });
     await monthlyUserTransactionRecapChart({ 'selected_year': form.selected_year, 'chart_type': chart_type });
    }

    const addBankAccountModal = async () => {
      myModal = await new bootstrap.Modal(document.getElementById('bankAccountModal'), {
        backdrop: true
      });
      await myModal.show();
    }

    onMounted(async () => {
    // try {
          // Add the current year
          optionYear.value.push({ text: currentYear.toString(), value: currentYear.toString() });
          // Add the past 5 years
          for (let i = 1; i <= 5; i++) {
            const pastYear = currentYear - i;
            optionYear.value.push({ text: pastYear.toString(), value: pastYear.toString() });
          }
           getUser(props.user.id);
          await checkPendingDeposit(props.user.id)
          pendingDeposit.value ? canDeposit.value = false: canDeposit.value = true;
          await checkPendingCashout(props.user.id);
          pendingCashout.value ? canCashout.value = false: canCashout.value = true;
           getTotalTransaction({ 'selected_year': form.selected_year, 'selected_month': form.selected_month });
           getTotalUserTransaction({ 'selected_year': form.selected_year, 'selected_month': form.selected_month });
           monthlyTransactionRecapChart({ 'selected_year': form.selected_year, 'chart_type': form.chart_type });
           monthlyUserTransactionRecapChart({ 'selected_year': form.selected_year, 'chart_type': form.chart_type });
           getTotalCashoutsCount();
           getTotalAgentDepositsCount();
           getDailyRate();
        await Echo.private('transaction')
        .listen('TransactionEvent', (e) => {
          getTotalTransaction({ 'selected_year': form.selected_year, 'selected_month': form.selected_month });
          monthlyTransactionRecapChart({ 'selected_year': form.selected_year, 'chart_type': form.chart_type });
          getTotalUserTransaction({ 'selected_year': form.selected_year, 'selected_month': form.selected_month });
          monthlyUserTransactionRecapChart({ 'selected_year': form.selected_year, 'chart_type': form.chart_type });
          getNotifications();
        });
        await Echo.private('cashout')
        .listen('CashoutEvent', (e) => {
          getTotalCashoutsCount();
          getCashouts();
        })
        await Echo.private('agentdeposit')
        .listen('AgentDepositEvent', (e) => {
          getTotalAgentDepositsCount();
          getAgentDeposits();
        })
        await Echo.private('envoydeposit')
        .listen('EnvoyDepositEvent', (e) => {
          getAgentDeposits();
          checkPendingDeposit(props.user.id);
        })
      // } catch (error) {
      //   console.error('Error in mounted hook:', error);
      // }
      
      // await Echo.private('useronline')
      // .listen('UserOnlineStatusUpdated', (e) => {
      // isOnline.value = e.status;
      // console.log('okk');
      // console.log(e);
      //});
    });

    const saleRateShow = () => {
      showDailyRateSale.value = !showDailyRateSale.value;
    }

    const sortChartByYear = async (year, month) => {
      await getTotalTransaction({ 'selected_year': year, 'selected_month': month });
      await monthlyTransactionRecapChart({ 'selected_year': year, 'chart_type': form.chart_type });
    }

    const sortUserChartByYear = async (year, month) => {
      await getTotalUserTransaction({ 'selected_year': year, 'selected_month': month });
      await monthlyUserTransactionRecapChart({ 'selected_year': year, 'chart_type': form.chart_type });
    }

    const extendCompTransfert = ref(false);
    const extendCompReception = ref(false);
    const extendApprTransfert = ref(false);
    const extendApprReception = ref(false);
    const extendPenTransfert = ref(false);
    const extendPenReception = ref(false);

    const extend = (ext) => {
      if (ext == 'comp_trans') {
        extendCompTransfert.value = !extendCompTransfert.value;
      } else if (ext == 'comp_rec') {
        extendCompReception.value = !extendCompReception.value;
      } else if (ext == 'appr_trans') {
        extendApprTransfert.value = !extendApprTransfert.value;
      } else if (ext == 'appr_rec') {
        extendApprReception.value = !extendApprReception.value;
      } else if (ext == 'pen_trans') {
        extendPenTransfert.value = !extendPenTransfert.value;
      }
      else if (ext == 'pen_rec') {
        extendPenReception.value = !extendPenReception.value;
      }
    }

    const purchaseRateShow = () => {
      showDailyRatePurchase.value = !showDailyRatePurchase.value;
    }

    const isAmountVisible = ref(false);
    
    const showAmount = () => {
      isAmountVisible.value = !isAmountVisible.value;
    }

    const amountBlurry = (amount, isAmountVisible) => {
      if(isAmountVisible){
        return formatDecimalNumber(amount);
      }else{
        if(amount && amount != ''){
          if (amount == 0) {
          return '*.**'
          }else{
            let strNumber = JSON.stringify(amount);
            let replacedString = strNumber.replace(/\d/g, '*');
            return replacedString;
            // let strNumber = amount.toString();
            // return strNumber.replace(/\d/g, '*');
          }
        }else{
          return '***'
        }
      }
    }

    const updateDailyRate = async () => {
      await dailyRateUpdate();
      await getDailyRate();
      showDailyRateSale.value = true;
      showDailyRatePurchase.value = true;
    }

    return {
      form,
      user,
      props,
      errors,
      can,
      cannot,
      totalTransaction,
      totalUserTransaction,
      dailyrateSale,
      dailyratePurchase,
      saleRateShow,
      showDailyRateSale,
      purchaseRateShow,
      showDailyRatePurchase,
      updateDailyRate,
      formatDecimalNumber,
      addBankAccountModal,
      totalCashoutsCount,
      totalAgentDepositCount,
      isOnline,
      extend,
      extendCompTransfert,
      extendCompReception,
      extendApprTransfert,
      extendApprReception,
      extendPenTransfert,
      extendPenReception,
      optionYear,
      sortChartByYear,
      sortUserChartByYear,
      optionMonth,
      chartType,
      chartTypeOption,
      showAmount,
      amountBlurry,
      isAmountVisible,
      canDeposit,
      canCashout
    }
  },
}
</script>

<style scoped>
.team-boxed {
  color: #313437;
}

.team-boxed p {
  color: #7d8285;
}

.team-boxed h2 {
  font-weight: bold;
  margin-bottom: 40px;
  padding-top: 40px;
  color: inherit;
}

@media (max-width:767px) {
  .team-boxed h2 {
    margin-bottom: 25px;
    padding-top: 25px;
    font-size: 24px;
  }
}

.team-boxed .people {
  padding: 50px 0;
}

.team-boxed .item {
  text-align: center;
}

.team-boxed .item .box {
  text-align: center;
  background-color: #fff;
}

.team-boxed .item .name {
  font-weight: bold;
  margin-top: 1px;
  color: inherit;
}

.team-boxed .item .title {
  text-transform: uppercase;
  font-weight: bold;
  color: #d0d0d0;
  letter-spacing: 2px;
  font-size: 12px;
}

.team-boxed .item .description {
  font-size: 15px;
  margin-top: 1px;
  margin-bottom: 1px;
}

.team-boxed .item img {
  max-width: 65px;
}

.team-boxed .social {
  font-size: 10px;
  color: #a2a8ae;
}

.team-boxed .social a {
  color: inherit;
  margin: 0 10px;
  display: inline-block;
  opacity: 0.7;
}

.team-boxed .social a:hover {
  opacity: 1;
}

.custom-gradient {
    background: linear-gradient(to bottom, rgba(8, 51, 68, 0.5), rgba(8, 51, 68, 1)); /* Your RGB colors with opacity */
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
  }
</style>