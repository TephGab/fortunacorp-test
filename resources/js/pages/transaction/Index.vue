<template>
  <div class="container-fluid">
    <div class="row">
      <div class="container-fluid"> <Breadcrumb /></div>
      <div class="col-md-12 mt-3">
        <!-- TABLE: LATEST TRANSFERTS -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <!-- {{ notifications.unread }} -->
          <div class="card-header border-transparent bg-light">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <router-link :to="{ name: 'transaction.create' }" class="btn btn-sm btn-info float-left mr-4" v-if="can('create', 'transaction')">
                <i class="fas fa-money-check"></i> <span style="font-size: 11px;">New transaction</span>
              </router-link>
              <!-- <router-link :to="{ name: 'profile' }" class="btn btn-sm text-danger fw-bold" v-if="can('create', 'transaction') && user.limit_max <= userAccount.depts">
                Debt limit reached, deposit required!
              </router-link>
              <router-link :to="{ name: 'profile' }" class="btn btn-sm text-warning fw-bold" v-if="can('create', 'transaction') && user.limit_min <= userAccount.depts && user.limit_max > userAccount.depts">
                Debt limit almost reached, consider making a deposit!
              </router-link> -->

              <div class="card-tools" v-if="can('export_pdf', 'transaction')">
                <div v-show="!isButtonLoading">
                  <label class="form-label text-success cursor-pointer text-lg" @click.prevent="showOptionPdf"
                    title="Export as PDF"><i class="fa-solid fa-file-pdf"></i></label>
                  <select class="border-0 outline-0 text-gray-500 text-xs" v-show="showPdfOption"
                    v-model="form.pdf_export_option" @change="pdfExport(form.pdf_export_option)">
                    <option v-for="option in pdfOptions" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                </div>
                <div v-show="isButtonLoading">
                  Generating PDF ... <img class="img-fluid img-circle" src="/img/spinner.gif" alt="button-spinner"
                    style="width: 20px; display: inline" />
                </div>
              </div>

              <div>
                <label><i class="fa-regular fa-calendar-days"></i></label>
                <select class="border-0 outline-0 text-gray-500 text-xs mr-2" v-model="form.selected_month"
                  @change="sortTransactions(form.selected_year, form.selected_month)">
                  <option v-for="option in optionMonth" :value="option.value" :key="option.text">
                    {{ option.text }}
                  </option>
                </select>
                <label><i class="fa-regular fa-calendar"></i></label>
                <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.selected_year"
                  @change="sortTransactions(form.selected_year, form.selected_month)">
                  <option v-for="option in optionYear" :value="option.value" :key="option.text">
                    {{ option.text }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table text-sm">
                <thead>
                  <tr class="bg-light text-sm">
                    <th colspan="2" class="text-nowrap">
                      <div class="text-warning fw-normal" v-if="form.q_id">"{{ form.q_id }}"</div>
                      <input type="text" class="search fw-normal" v-model="form.q_id" @keyup="searchTransactions('id')" placeholder=" Search by id.. ">
                    </th>
                    <th colspan="2" class="text-nowrap">
                      <div class="text-warning fw-normal" v-if="form.q_name">"{{ form.q_name }}"</div>
                      <input type="text" class="search fw-normal" v-model="form.q_name" @keyup="searchTransactions('name')" placeholder=" Search by name.. ">
                    </th>
                    <th colspan="2" class="text-nowrap">
                      <div class="text-warning fw-normal" v-if="form.q_phone">"{{ form.q_phone }}"</div>
                      <input type="text" class="search fw-normal" v-model="form.q_phone" @keyup="searchTransactions('phone')" placeholder=" Search by phone.. ">
                    </th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                    <!-- <th class="text-nowrap"></th> -->
                    <th
                      v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'system-engineer'"
                      class="text-nowrap"></th>
                    <th></th>
                    </tr>
                  <tr>
                    <th class="text-nowrap">Trans. ID </th>
                    <th class="text-nowrap text-center">Client Name | Phone </th>
                    <!-- <th class="text-nowrap">Client Phone </th> -->
                    <th class="text-nowrap">Type
                      <button @click="showTypeSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="typeSort"
                        class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2">
                        <!-- <i class="fas fa-circle text-warning" v-show="form.type == 'moncash'"></i>
                        <i class="fas fa-circle text-info" v-show="form.type == 'natcash'"></i> -->
                        <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.type"
                          @change="sort(form.type)">
                          <option v-for="option in options" :value="option.value" :key="option.text">
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-nowrap text-center"> Sender amount
                      <button @click="showAmountSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="amountSort"
                        class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2">
                        <!-- <i class="fas fa-sort-numeric-up" v-show="form.soldeSort == 'max_solde'"></i>
                        <i class="fas fa-sort-numeric-down" v-show="form.soldeSort == 'min_solde'"></i> -->
                        <select class="border-0 outline-0" v-model="form.soldeSort" @change="sort(form.soldeSort)"
                          style="font-size: 12px;">
                          <option v-for="optionSoldeSort in optionSoldeSorts" :value="optionSoldeSort.value"
                            :key="optionSoldeSort.text">
                            {{ optionSoldeSort.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <!-- rate -->
                    <th class="text-nowrap text-center"> Rate </th> 
                    
                    <th class="text-nowrap text-center"> Receiver amount </th>
                    <th class="text-nowrap text-center">Status
                      <button @click="showStatusSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="statusSort"
                        class="form-check form-check-inline border border-info rounded m-0 pl-2 pr-2">
                        <!-- <i class="fas fa-info m-0 p-0"></i> -->
                        <select class="border-0 outline-0" v-model="form.sort_status" @change="sort(form.sort_status)" style="font-size: 12px;">
                          <option v-for="option in optionStatus" :value="option.value" :key="option.text">
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-nowrap text-center" v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'system-engineer'"> Operator </th>
                    <th class="text-nowrap text-center">Date</th>
                    <th class="text-nowrap text-center">Actions</th>
                  </tr>
                </thead>
                <tbody v-for="transaction in transactions.data" :key="transaction.id">
                  <tr :class="[minutesSincePending(transaction.created_at, transaction.status) > 30 ? longPendingClass : 'hover:bg-gray-300', 'text-nowrap']">
                    <td class="align-middle">{{ transaction.id }}</td>
                    <td class="text-center text-nowrap text-uppercase align-middle">
                        <div class="ms-3 text-center">
                          <p class="fw-normal mb-1">{{ transaction.receiver_name }}</p>
                          <p class="mb-0"> {{ transaction.receiver_phone }} </p>
                        </div>
                    </td>
                    <td class="text-capitalize text-nowrap align-middle">
                      <span :class="['badge', transaction.type == 'moncash' ? 'badge-danger' : transaction.type == 'natcash' ? 'badge-primary' : transaction.type == 'local' ? 'badge-secondary' : '']">{{ transaction.type }}
                      </span>
                    </td>
                    <td class="text-right text-nowrap align-middle fw-bold text-gray-600">
                      <div class="sparkbar" data-color="#00a65a" data-height="20">
                         {{ formatDecimalNumber(transaction.sender_amount) }}
                        <span v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'"> RD$  </span>
                        <span v-else> HTG </span>
                      </div>
                      <!-- <div class="sparkbar" data-color="#00a65a" data-height="20">
                         {{ formatDecimalNumber(transaction.sender_amount) }}
                        <span v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">
                          RD$ <span class="text-muted fw-light" style="font-size: 12px;">(Rate {{ transaction.rate }}
                            HTG)</span>
                        </span>
                        <span v-else>
                          HTG <span class="text-muted fw-light" style="font-size: 12px;">(Rate {{ transaction.rate }}
                            RD$)</span>
                        </span>
                      </div> -->
                    </td>
                    <td class="text-right text-nowrap align-middle">
                      <span class="text-muted fw-light" style="font-size: 12px;">({{ transaction.rate }})</span>
                    </td>
                    <td class="text-nowrap align-middle">
                      <div class="sparkbar" data-color="#00a65a" data-height="20">
                        <div v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">
                          <div class="text-gray-600">
                            <div class="text-right fw-bold">{{ formatDecimalNumber((transaction.receiver_amount)) }} <span>HTG</span></div>
                            <div class="text-muted text-xs text-italic text-right" v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'operator' || authUserRole == 'system-engineer'"> Business acc.</div>
                          </div>
                          <!-- <div class="fw-bolder text-center border-top m-0" v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'operator' || authUserRole == 'system-engineer'">
                            <p class="border-top m-0 w-100 border-gray-600"></p>
                          </div> -->
                          <div class="text-secondary border-top border-gray-600" v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'operator' || authUserRole == 'system-engineer'">
                            <div class="text-right fw-bold">{{ formatDecimalNumber((transaction.receiver_amount - transaction.p_to_p_fee)) }} <span>HTG</span> </div>
                            <div class="text-muted text-xs text-italic text-right"> Normal acc.</div>
                          </div>
                        </div>
                        <span v-else class="text-right">
                          {{ formatDecimalNumber(transaction.receiver_amount) }} RD$
                        </span>
                      </div>
                    </td>
                    <td class="text-capitalize text-nowrap align-middle text-center">
                      <div style="display: flex; justify-content: center">
                        <span v-if="transaction.status == 'pending'" class="badge badge-default text-warning"> {{ transaction.status }} </span>
                        <span v-if="transaction.status == 'approved'" class="badge badge-default text-primary"> {{ transaction.status }} </span>
                        <span v-if="transaction.status == 'disapproved'" class="badge badge-default text-danger"> {{ transaction.status }} !</span>
                        <span v-if="transaction.status == 'completed'" class="badge badge-default text-success"> {{ transaction.status }} </span>
                        <span v-if="can('approve', 'transaction')">
                          <router-link v-if="transaction.status == 'pending'" class-active="active" :to="{ name: 'transactionsteps.show', params: { id: transaction.id } }">
                            <i class="fa fa-toggle-off text-warning" style="font-size: 20px;"></i>
                          </router-link>
                          <router-link v-if="transaction.status == 'approved'" class-active="active" :to="{ name: 'transactionsteps.show', params: { id: transaction.id } }">
                            <i class="fa fa-toggle-on text-primary" style="font-size: 20px;"></i>
                          </router-link>
                          <router-link v-if="transaction.status == 'disapproved'" class-active="active" :to="{ name: 'transactionsteps.show', params: { id: transaction.id } }">
                            <i class="fa fa-ban text-danger" style="font-size: 20px;"></i>
                          </router-link>
                          <router-link v-if="transaction.status == 'completed'" class-active="active" :to="{ name: 'transactionsteps.show', params: { id: transaction.id } }">
                            <i class="fa fa-check text-success" style="font-size: 20px;"></i>
                          </router-link>
                        </span>
                      </div>
                    </td>
                    <td v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'system-engineer'" class="text-nowrap text-xs text-red text-uppercase align-middle" v-if="transaction.operator_id == 1">
                      No Operator
                    </td>
                    <td v-show="authUserRole == 'super-admin' || authUserRole == 'admin' || authUserRole == 'system-engineer'" class="text-nowrap text-xs text-dark text-uppercase align-middle text-center" v-else>
                      <div>{{ transaction.operator_first_name }} {{ transaction.operator_last_name }} </div>
                      <div> ({{ transaction.operator_code }}) </div>
                    </td>
                    <td class="text-nowrap text-xs text-muted align-middle">
                      {{ formatDate(transaction.created_at) }}
                    </td>
                    <td class="align-middle text-center">
                      <div class="sparkbar" data-color="#00a65a" data-height="20">
                        <div class="btn-group dropstart">
                          <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" title="More" style="font-weight: bolder; font-size: 1.2rem;"></i>
                          </button>
                          <ul class="dropdown-menu fw-bold">
                            <li>
                              <router-link class="btn btn-block" class-active="active" :to="{ name: 'transaction.details', params: { id: transaction.id } }" title="Transaction details">
                                <i class="fa-solid fa-circle-info"></i> Details
                              </router-link>
                            </li>
                            <li v-show="transaction.status == 'disapproved'">
                              <hr class="dropdown-divider">
                            </li>
                            <li>
                              <router-link v-show="transaction.status == 'disapproved'" v-if="can('review', 'transaction')" class="btn btn-danger btn-block" class-active="active" :to="{ name: 'transaction.show', params: { id: transaction.id } }">
                                Review
                              </router-link>
                            </li>
                            <li v-show="transaction.status == 'pending' && !transaction.viewed">
                              <hr class="dropdown-divider">
                            </li>
                            <li>
                              <router-link v-show="transaction.status == 'pending' && !transaction.viewed"
                                v-if="can('review', 'transaction')" class="btn btn-block" class-active="active"
                                :to="{ name: 'transaction.show', params: { id: transaction.id } }"
                                title="Modify Transaction">
                                <i class="fa-solid fa-edit"></i> Modify
                              </router-link>
                            </li>
                            <li v-show="transaction.status == 'pending' && !transaction.viewed">
                              <hr class="dropdown-divider">
                            </li>
                            <button class="btn btn-block" v-show="transaction.status == 'pending' && !transaction.viewed"
                              type="button" title="Cancel Transaction" @click="transactionCancel(transaction.id)"
                              v-if="can('review', 'transaction')">
                              <i class="fa-solid fa-xmark"></i> Cancel
                            </button>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
                <div v-show="isLoading">
                  <img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;" />
                </div>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.card-body -->

        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto mr-5">
            <Pagination :data="transactions" @pagination-change-page="getTransactions" class="mr-5" />
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue';
import useTransactions from "../../services/transactionservices";
import useUsers from "../../services/usersservices";
import { useAbility } from '@casl/vue';
import moment from 'moment';
import useUtils from "../../services/utilsservices";
// import Breadcrumb from '../../components/breadcrumb/Breadcrumbs.vue';
import jsPDF from 'jspdf';
import Swal from "sweetalert2";

export default {
  props: ['user'],
  // components: {
  //   Breadcrumb
  // },
  setup(props) {
    const { can, cannot } = useAbility();
    const { getTransactions, transactions, transaction, getNotifications,
      notifications, updateTransaction, updateTransactionStatus, formatDate,
      getDailyRate, dailyrateSale, dailyRateUpdate, transactionSort, isLoading,
      generatePdf, transactionsPdf, isButtonLoading, setMonthAndYear, agentCancelTransaction } = useTransactions();
    const { getUserAccount, userAccount, getAuthUserRole, authUserRole } = useUsers();
    const { formatDecimalNumber } = useUtils();

    const errors = ref([]);
    const showDailyRate = ref(true);
    const typeSort = ref(false);
    const amountSort = ref(false);
    const statusSort = ref(false);
    const searchType = ref('');
    const showPdfOption = ref(false);
    const longPendingClass = ref('bg-red-200 hover:bg-red-300');
    let intervalId;
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;
    const isSearching = ref(false);


    const form = reactive({
      q_id: null,
      q_name: null,
      q_phone: null,
      status: 'completed',
      user_id: props.user.id,
      sort_status: 'all',
      type: 'all',
      soldeSort: 'max_solde',
      pdf_export_option: 'interval',
      selected_year: currentYear,
      selected_month: currentMonth,
    });

    const options = ref([
      { text: 'All', value: 'all' },
      { text: 'Moncash', value: 'moncash' },
      { text: 'Natcash', value: 'natcash' },
    ]);

    const pdfOptions = ref([
      { text: 'Select Interval', value: 'interval' },
      { text: 'All', value: 'all' },
      { text: 'Today', value: 'today' },
      { text: 'Last 7 days', value: 'seven_days' },
      { text: 'This Month', value: 'this_month' },
      { text: 'Last Month', value: 'last_month' },
    ]);

    const optionSoldeSorts = ref([
      { text: 'Max solde', value: 'max_solde' },
      { text: 'Min solde', value: 'min_solde' },
    ]);

    const optionStatus = ref([
      { text: 'All', value: 'all' },
      { text: 'Pending', value: 'pending' },
      { text: 'Approved', value: 'approved' },
      { text: 'Disapproved', value: 'disapproved' },
    ]);

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

    onMounted(async () => {
      // Add the current year
      optionYear.value.push({ text: currentYear.toString(), value: currentYear.toString() });

      // Add the past 5 years
      for (let i = 1; i <= 5; i++) {
        const pastYear = currentYear - i;
        optionYear.value.push({ text: pastYear.toString(), value: pastYear.toString() });
      }
      await setMonthAndYear({ 'selected_year': form.selected_year, 'selected_month': form.selected_month });
      await getTransactions();
      await getAuthUserRole();
      console.log(authUserRole.value)
      await getDailyRate();
      await getNotifications();
      await Echo.private('transaction')
        .listen('TransactionEvent', (e) => {
          if (!isSearching) {
            getTransactions();
          }
          setMonthAndYear({ 'selected_year': form.selected_year, 'selected_month': form.selected_month });
          getNotifications();
        })
    });

    const minutesSincePending = (date, status) => {
      if (status == 'pending') {
        const currentDateTime = moment(); // Current date using moment.js
        const diffInMinutes = currentDateTime.diff(date, 'minutes');
        return diffInMinutes;
      } else {
        return 0;
      }
    }

    const showOptionPdf = async () => {
      showPdfOption.value = true;
    }
    const showTypeSort = async () => {
      typeSort.value = !typeSort.value;
    }

    const showAmountSort = async () => {
      amountSort.value = !amountSort.value;
    }

    const showStatusSort = async () => {
      statusSort.value = !statusSort.value;
    }

    const searchTransactions = async (searchType) => {
      try {
        let searchParam;
        let searchValue;
        isSearching.value = true;
        switch (searchType) {
          case 'id':
            searchParam = 'q_id';
            searchValue = form.q_id;
            break;
          case 'name':
            searchParam = 'q_name';
            searchValue = form.q_name;
            break;
          case 'phone':
            searchParam = 'q_phone';
            searchValue = form.q_phone;
            break;
          default:
            return;
        }

        if (searchValue && searchValue.length > 0) {
          const response = await axios.post("/api/transactions/search", {
            [searchParam]: searchValue,
            status: 'pending'
          });
          transactions.value = response.data;
        } else {
          getTransactions();
        }
      } catch (error) {
        console.error(error);
      }
    };

    const updateDailyRate = async () => {
      const data = new FormData()
      data.append('daily_rate_sale', dailyrateSale.value)
      await dailyRateUpdate(data);
      await getDailyRate();
      showDailyRate.value = true;
    }

    const sort = async (type) => {
      const data = new FormData()
      data.append('type', form.type);
      data.append('status', form.sort_status);
      data.append('solde_sort', form.soldeSort);
      transactionSort(data);
    }

    const rateShow = () => {
      if (showDailyRate.value == true) {
        showDailyRate.value = false;
      } else {
        showDailyRate.value = true;
      }
    }

    const pdfExport = (pdf_export_option) => {
      const data = new FormData();
      data.append('pdf_export_option', pdf_export_option);
      generatePdf(data);
      showPdfOption.value = false;
    }

    const sortTransactions = async (year, month) => {
      await setMonthAndYear({ 'selected_year': year, 'selected_month': month });
    }

    const transactionCancel = async (transactionId) => {
      Swal.fire({
        title: "Cancel transaction?",
        showCancelButton: true,
        confirmButtonText: "Yes",
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            await agentCancelTransaction({ 'transaction_id': transactionId });
            await getTransactions();
          } catch (error) {
            errors.value = error.response.data.errors;
            if (error.response.data.errors) {
              if (error.response.data.errors.transaction_in_process) {
                if (error.response.data.errors.transaction_in_process[0]) {
                  Swal.fire({
                    title: 'Transaction in process!',
                    text: 'Transaction in process contact administrator',
                    position: 'center',
                    icon: 'error',
                    color: '#000',
                    padding: '0',
                    showConfirmButton: true,
                  });
                }
              }
            }
          }
        }
      });
    };


    return {
      dailyrateSale,
      transactions,
      getTransactions,
      form,
      notifications,
      can,
      cannot,
      formatDate,
      transaction,
      isLoading,
      updateDailyRate,
      showDailyRate,
      rateShow,
      sort,
      options,
      optionSoldeSorts,
      optionStatus,
      props,
      showTypeSort,
      typeSort,
      showAmountSort,
      amountSort,
      showStatusSort,
      statusSort,
      searchType,
      searchTransactions,
      longPendingClass,
      minutesSincePending,
      //userAccount,
      authUserRole,
      pdfExport,
      pdfOptions,
      isButtonLoading,
      showPdfOption,
      showOptionPdf,
      setMonthAndYear,
      sortTransactions,
      optionMonth,
      optionYear,
      transactionCancel,
      formatDecimalNumber,
      isSearching,
    }
  },
}
</script>

<style scoped>
.search {
  width: 100%;
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

.search:focus {
  width: 100%;
}</style>