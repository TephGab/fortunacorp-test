<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3">
        <!-- TABLE: LATEST TRANSFERTS -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <div class="text-center text-xs fw-bold">
                <div>FORTUNA CORPORATION</div>
                <div>ACCOUNTS</div>
              </div>
            <div style="display: flex; justify-content: space-between; align-items: center">
              <router-link :to="{ name: 'accounts.create' }" class="btn btn-sm btn-info float-left"
                v-if="can('create', 'account')">
                <i class='fas fa-plus'></i> New account
              </router-link>
              <!-- <form>
                <input type="text" id="search" name="search" @keyup="search" placeholder="Search by number.." v-model="form.q">
              </form> -->
              <div>
                <div>
                  <select class="border-0 outline-0 text-xs fw-bold">
                    <option class="text-xs fw-bold"><span> Total Moncash : {{ formatDecimalNumber(totalSum.moncash) }} <span>HTG</span></span></option>
                    <option class="text-xs fw-bold"><span> Total Natcash : {{ formatDecimalNumber(totalSum.natcash) }} <span>HTG</span></span></option>
                    <option class="text-xs border-top fw-bold"><span> Sum : {{ formatDecimalNumber(totalSum.moncash + totalSum.natcash) }} <span>HTG</span></span></option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap">
                      <form>
                        <input type="text" id="search" name="searchbyname" @keyup="searchByName"
                          placeholder=" Search by name.." v-model="form.q_name">
                      </form>
                    </th>
                    <th class="text-nowrap">
                      <form>
                        <input type="text" id="search" name="searchbynumber" @keyup="searchByNumber"
                          placeholder=" Search by number.." v-model="form.q">
                      </form>
                    </th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                  </tr>
                  <tr>
                    <th class="text-nowrap">#</th>
                    <th class="text-nowrap">Name
                      <!-- <button @click="showAccountNameSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="accountNameSort" class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2 text-sm">
                        <i class="fas fa-sort-numeric-up" v-show="form.account_name_sort == 'asc'"></i>
                        <i class="fas fa-sort-numeric-down" v-show="form.account_name_sort == 'desc'"></i>
                        <select class="border-0 outline-0" v-model="form.account_name_sort" @change="sort(form.account_name_sort)">
                          <option v-for="accountNameOption in accountNameOptions" :value="accountNameOption.value" :key="accountNameOption.text">
                            {{ accountNameOption.text }}
                          </option>
                        </select>
                      </div> -->
                    </th>
                    <th class="text-nowrap">Phone</th>
                    <th class="text-nowrap text-right">Balance
                      <button @click="showBalanceSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="balanceSort"
                        class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2">
                        <i class="fas fa-sort-numeric-up" v-show="form.soldeSort == 'max_solde'"></i>
                        <i class="fas fa-sort-numeric-down" v-show="form.soldeSort == 'min_solde'"></i>
                        <select class="border-0 outline-0" v-model="form.soldeSort" @change="sort(form.soldeSort)">
                          <option v-for="optionSoldeSort in optionSoldeSorts" :value="optionSoldeSort.value" :key="optionSoldeSort.text">
                            {{ optionSoldeSort.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-nowrap d-flex justify-content-between" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                     <span class="m-0 p-0">
                      Operator
                      <button @click="showOperatorSort" class="m-0 p-0">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                     </span>
                      <Select2 class="js-example-basic-multiple js-states w-100 m-0 p-0 border-0" v-show="operatorSort" v-model="form.operator" :options="operators" :onSelect="sortOperator" :settings="{ width: '100%', textTransform: 'uppercase', margin: 0,  padding: 0, fontSize: '14px'}" />
                    </th>
                    <th class="text-nowrap">Full Wallet</th>
                    <th class="text-nowrap">Type
                      <button @click="showTypeSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="typeSort"
                        class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2">
                        <i class="fas fa-sort m-0 p-0"></i>
                        <select class="border-0 outline-0 " v-model="form.type" @change="sort(form.type)">
                          <option v-for="option in options" :value="option.value" :key="option.text">
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-nowrap"> Limit
                      <button @click="showTransactionLimitSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="transactionLimitSort" class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2 text-sm">
                        <i class="fas fa-sort-numeric-up" v-show="form.transactions_limit == 'asc'"></i>
                        <i class="fas fa-sort-numeric-down" v-show="form.transactions_limit == 'desc'"></i>
                        <select class="border-0 outline-0" v-model="form.transactions_limit" @change="sort(form.transactions_limit)">
                          <option v-for="transactionLimitOption in transactionLimitOptions" :value="transactionLimitOption.value" :key="transactionLimitOption.text">
                            {{ transactionLimitOption.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-nowrap text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="account in accounts.data" :key="account.id">
                    <td class="text-nowrap">{{ account.id }}</td>
                    <td class="text-nowrap"><span class="text-capitalize">{{ account.name }}</span></td>
                    <td class="text-nowrap">{{ account.phone }} 
                      <!-- <span class="text-xs text-muted"> ( {{ account.total_monthly_transactions }} transactions) </span> -->
                    </td>
                    <td class="text-nowrap text-right fw-bold text-gray-600">{{ formatDecimalNumber(account.balance) }} HTG</td>
                    <td class="text-nowrap" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                      <div class="text-capitalize text-emerald-700">{{ account.user.first_name }} {{ account.user.last_name }}</div>
                      <div class="text-capitalize text-emerald-700">({{ account.user.code }})</div>
                    </td>
                    <td class="text-nowrap">
                      <div v-if="account.full_wallet == 1">
                        <i class="fas fa-check" aria-hidden="true"></i>
                      </div>
                      <div v-else>
                        <i class="fas fa-minus" aria-hidden="true"></i>
                      </div>
                    </td>
                    <td class="text-capitalize text-nowrap">
                      <span :class="['badge', account.type == 'moncash' ? 'badge-danger' : account.type == 'natcash' ? 'badge-primary' : account.type == 'local' ? 'badge-secondary' : '']"> {{ account.type }}
                      </span>
                    </td>
                    <td class="text-nowrap text-xs">
                      <span class="text-success"> {{account.total_monthly_transactions }} Transactions</span>
                    </td>
                    <td class="text-center">
                      <div class="sparkbar ml-3" data-color="#00a65a" data-height="20">
                        <div class="btn-group dropstart">
                          <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" title="More" style="font-weight: bold; font-size: 1rem;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <router-link class="ml-2" class-active="active" :to="{ name: 'accounts.edit', params: { id: account.id } }">
                                <!-- <i class="fas fa-edit"></i> Edit Account -->
                                <i class="fas fa-info-circle"></i> Details
                              </router-link>
                            </li>
                            <!-- <li v-if="can('delete', 'account')">
                              <hr class="dropdown-divider">
                            </li>
                            <li v-if="can('delete', 'account')">
                              <button class="ml-2" @click="deleteAccount(account.id)">
                                <i class="fas fa-trash"></i> Delete account
                              </button>
                            </li> -->
                            <!-- v-if="can('transfert_between', 'account')" -->
                            <li>
                              <hr class="dropdown-divider">
                            </li>
                            <li>
                              <router-link class="ml-2" class-active="active" :to="{ name: 'accounttransferts.edit', params: { id: account.id } }">
                                <i class="fas fa-share"></i> Transfert
                              </router-link>
                            </li>
                          </ul>
                        </div>

                        <!-- <router-link class-active="active" :to="{ name: 'accounts.edit', params: { id: account.id } }" v-if="can('update', 'account')">
                          <i class="fas fa-edit mr-3" style="color:blueviolet;"></i>
                        </router-link>
                       <i class="fas fa-trash" style="color:red;" @click="deleteAccount(account.id)" v-if="can('delete', 'account')"></i>
                      -->
                      </div>
                    </td>
                  </tr>
                  <tr v-show="isLoading">
                    <td><img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->

            <hr/>

<div class="row mt-3">
  <div class="col-md-12">
    <table class="table table-light">
      <thead>
        <tr>
          <th class="text-center" scope="col">AVAILABLE ACCOUNTS</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="row" v-if="operatorAccounts && operatorAccounts.operator_accs && operatorAccounts.all_accounts">
              <div class="col-md-4" v-for="account in operatorAccounts.all_accounts" :key="account.id">
                <div class="mb-3 d-flex flex-row flex-nowrap">
                  <span class="mr-1"> <input type="checkbox" :value="account.id" v-model="selectedAccounts"> </span>
                  <span> <span class="fw-bold">{{ account.id }}</span> - {{ account.name }} ({{ account.phone }}) </span>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </tbody> 
    </table>
  </div>
</div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
            <Pagination :data="accounts" @pagination-change-page="getAccounts" />
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>
  
<script>
import { onMounted, reactive, ref, watch } from 'vue';
import useAccounts from "../../services/accountservices.js";
import useUtils from "../../services/utilsservices";
import useUsers from "../../services/usersservices";
import { useAbility } from '@casl/vue';

export default {
  props: ['id', 'user', 'userrole'],
  emits: ['select'],
  setup(props, { emit }) {
    const { can, cannot } = useAbility();
    const { getAccounts, accounts, isLoading, accountSort, destroyAccount, calculAccountBalanceSum,
           totalSum, getOperatorAccounts, operatorAccounts } = useAccounts();
    const { getOperators, operators } = useUsers();
    const { formatDecimalNumber } = useUtils();

    const errors = ref([]);

    const form = reactive({
      q: '',
      q_name: '',
      type: 'all',
      soldeSort: 'all',
      operator: 'all',
      account_name_sort: 'all',
      transactions_limit: 'all'
    });

    const balanceSort = ref(false);
    const typeSort = ref(false);
    const operatorSort = ref(false);
    const accountNameSort = ref(false);
    const transactionLimitSort = ref(false);
    const userAccounts = ref([]);
    const selectedAccounts = ref([]);

    const showBalanceSort = async () => {
      balanceSort.value = !balanceSort.value;
    }

    const showTypeSort = async () => {
      typeSort.value = !typeSort.value;
    }

    const showOperatorSort = async () => {
      operatorSort.value = !operatorSort.value;
    }

    const showAccountNameSort = async () => {
      accountNameSort.value = !accountNameSort.value;
    }

    const showTransactionLimitSort = async () => {
      transactionLimitSort.value = !transactionLimitSort.value;
    }

    const options = ref([
      { text: 'Shuffle', value: 'all' },
      { text: 'Moncash', value: 'moncash' },
      { text: 'Natcash', value: 'natcash' },
    ]);

    const optionSoldeSorts = ref([
      { text: 'Shuffle', value: 'all' },
      { text: 'Min amount', value: 'min_solde' },
      { text: 'Max amount', value: 'max_solde' },
    ]);

    const accountNameOptions = ref([
      { text: 'Shuffle', value: 'all' },
      { text: 'Asc', value: 'asc' },
      { text: 'Desc', value: 'desc' },
    ]);

    const transactionLimitOptions = ref([
      { text: 'Shuffle', value: 'all' },
      { text: 'Min', value: 'asc' },
      { text: 'Max', value: 'desc' },
    ]);

    onMounted(async () => {
      await getAccounts();
      await calculAccountBalanceSum();
      await getOperators();
      await accountSort({'type': form.type, 'solde_sort': form.soldeSort, 'operator': form.operator, 'account_name_sort': form.account_name_sort, 'transactions_limit': form.transactions_limit});
    })

    const sort = async (type) => {
      await accountSort({'type': form.type, 'solde_sort': form.soldeSort, 'operator': form.operator, 'account_name_sort': form.account_name_sort, 'transactions_limit': form.transactions_limit});
    }

    const updateAccountUser = async () => {
      await accountSort({'operator': form.operator, selectedAccounts: selectedAccounts.value});
    }

    const updateOperatorAccounts = async () => {
      try {
        const response = await axios.post('/api/updateoperatoraccounts', {
          'operator': form.operator,
          selectedAccounts: selectedAccounts.value // Sending updated accounts to Laravel
        });
        console.log('Update response:', response.data);
      } catch (error) {
        console.error('Error updating operator accounts:', error);
      }
    };

    const sortOperator = async (operator) => {
      await accountSort({'type': form.type, 'solde_sort': form.soldeSort, 'operator': form.operator, 'account_name_sort': form.account_name_sort, 'transactions_limit': form.transactions_limit});
      await getOperatorAccounts({'operator': form.operator});
        // Set default selected accounts from operator accounts
        if (operatorAccounts.value.operator_accs) {
          selectedAccounts.value = operatorAccounts.value.operator_accs.map(account => account.id);
        }

    watch(selectedAccounts, (newValue) => {
      updateOperatorAccounts();
      console.log('Updated selected accounts:', newValue);
    });
    }

    const searchByNumber = async () => {
      if (form.q.length > 0) {
        const data = new FormData()
        data.append('number', form.q);
        axios.post("/api/accountsearchbynumber", data).
          then(response => { accounts.value = response.data }).
          catch(error => console.log(error))
      } else {
        getAccounts();
      }
    }

    const searchByName = async () => {
      if (form.q_name.length > 0) {
        const data = new FormData()
        data.append('name', form.q_name);
        axios.post("/api/accountsearchbyname", data).
          then(response => { accounts.value = response.data }).
          catch(error => console.log(error))
      } else {
        getAccounts();
      }
    }

    const deleteAccount = async (id) => {
      await destroyAccount(id);
    }


    return {
      props,
      form,
      options,
      optionSoldeSorts,
      getAccounts,
      accounts,
      isLoading,
      searchByNumber,
      searchByName,
      sort,
      deleteAccount,
      can,
      cannot,
      totalSum,
      showBalanceSort,
      balanceSort,
      showTypeSort,
      typeSort,
      formatDecimalNumber,
      showOperatorSort,
      operatorSort,
      getOperators,
      operators,
      errors,
      sortOperator,
      showAccountNameSort,
      accountNameSort,
      accountNameOptions,
      showTransactionLimitSort,
      transactionLimitSort,
      transactionLimitOptions,
      userAccounts,
      operatorAccounts,
      selectedAccounts,
      updateAccountUser
    }
  },
}
</script>
  
<style scoped>
#search {
  width: 70%;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  /* font-size: 16px; */
  background-color: white;
  /* background-image: url('searchicon.png'); */
  background-position: 10px 10px;
  background-repeat: no-repeat;
  /* padding: 12px 20px 12px 40px; */
  -webkit-transition: width 0.4s ease-in-out;
  transition: width 0.4s ease-in-out;
}

#search:focus {
  width: 100%;
}
</style>