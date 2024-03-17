<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3">
        <!-- TABLE: LATEST TRANSFERTS -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
             <div class="text-center text-xs fw-bold">
                <div>FORTUNA CORPORATION</div>
                <div>CASHOUTS</div>
              </div>
            <div style="display: flex; justify-content: space-between; align-items: center">
              <router-link v-if="canCashout" class="btn btn-sm btn-info float-left" class-active="active" :to="{ name: 'cashout.create', params: { commission: 'commission' } }">
                <i class='fas fa-plus'></i> Cashout
              </router-link>

              <div v-if="!canCashout" class="text-warning text-xs">
                <i class="fa-solid fa-circle-info"></i> Pending cashout
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive table-hover">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap">
                      <form>
                        <input type="text" id="search" name="searchbyname" @keyup="searchByName"
                          placeholder=" Search by agent name.." v-model="form.q_name">
                      </form>
                    </th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                  </tr>
                  <tr>
                    <th class="text-nowrap">#</th>
                    <th class="text-nowrap">User</th>
                    <th class="text-nowrap text-right">Amount</th>
                    <th class="text-nowrap text-center">Status</th>
                    <th class="text-nowrap text-center">Date</th>
                    <th class="text-nowrap text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="cashout in cashouts.data" :key="cashout.id">
                    <td class="text-nowrap align-middle"> {{ cashout.id }} </td>
                    <td class="text-nowrap text-xs align-middle">
                      <div>{{ cashout.user.first_name }} {{ cashout.user.last_name }}</div>
                      <div> ({{ cashout.user.code }}) </div>
                    </td>
                    <td class="text-nowrap text-right fw-bold text-gray-600 align-middle">
                      {{ formatDecimalNumber(cashout.amount) }} RD$
                    </td>
                    <td class="text-capitalize text-nowrap align-middle text-center">
                      <div>
                        <span v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 0" class="badge badge-default text-info">
                           Waiting for confirmation 
                        </span>
                        <span v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="badge badge-default text-info"> 
                          Waiting for envoy to confirm
                        </span>
                        <span v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="badge badge-default text-info"> 
                          Waiting for receiver to confirm
                        </span>
                        <span v-if="cashout.status == 'confirmed' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 1 && cashout.completed_by_admin == 1" class="badge badge-default text-success"> 
                          Confirmed
                        </span>
                      </div>
                    </td>
                    <td class="text-nowrap text-xs text-muted align-middle text-center">
                      {{ formatDate(cashout.created_at) }}
                      <!-- {{ formatDate(cashout.receiver_confirmation_date ?? cashout.updated_at) }} -->
                    </td>
                    <td class="text-nowrap text-info align-middle text-center">
                      <div v-if="userrole.name == 'super-admin'">
                        <router-link class-active="active" :to="{ name: 'cashout.edit', params: { id: cashout.id } }" class="text-xs">
                          <button v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 0" class="btn btn-info btn-sm text-xs">
                            Confirm
                          </button>
                        </router-link>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for envoy to confirm
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for receiver to confirm
                        </div>
                      </div>

                      <div v-if="userrole.name == 'system-engineer'">
                        <router-link class-active="active" :to="{ name: 'cashout.edit', params: { id: cashout.id } }" class="text-xs">
                          <button v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 0" class="btn btn-info btn-sm text-xs">
                            Confirm
                          </button>
                        </router-link>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for envoy to confirm
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1 && cashout.user_role != 'system-engineer'" class="text-xs">
                          Waiting for receiver to confirm
                        </div>

                        <button v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1 && cashout.user_role == 'system-engineer'" class="btn btn-info btn-sm text-xs" @click="confirmUserCashout(cashout.id)">
                          Confirm
                        </button>
                      </div>

                      <div v-if="userrole.name == 'admin'">
                        <!-- <router-link class-active="active" :to="{ name: 'cashout.edit', params: { id: cashout.id } }" class="text-xs">
                          <button v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 0" class="btn btn-info btn-sm text-xs">
                            Confirm
                          </button>
                        </router-link> -->
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for envoy to confirm
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1 && cashout.user_role != 'admin'" class="text-xs">
                          Waiting for receiver to confirm
                        </div>
                        <button v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1 && cashout.user_role == 'admin'" class="btn btn-info btn-sm text-xs" @click="confirmUserCashout(cashout.id)">
                          Confirm
                        </button>
                      </div>

                      <div v-if="userrole.name == 'envoy'">
                        <button v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="btn btn-info btn-sm text-xs" @click="confirmUserCashout(cashout.id)">
                          Confirm
                        </button>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for receiver to confirm
                        </div>
                      </div>

                      <div v-if="userrole.name == 'agent'">
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 0">
                          Waiting for confirmations
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for envoy to confirm
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          <button class="btn btn-info btn-sm text-xs" @click="confirmUserCashout(cashout.id)">
                          Confirm
                        </button>
                        </div>
                      </div>

                      <div v-if="userrole.name == 'operator'">
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 0">
                          Waiting for confirmations
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 0 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          Waiting for envoy to confirm
                        </div>
                        <div v-if="cashout.status == 'pending' && cashout.confirmed_by_envoy == 1 && cashout.confirmed_by_user == 0 && cashout.completed_by_admin == 1" class="text-xs">
                          <button class="btn btn-info btn-sm text-xs" @click="confirmUserCashout(cashout.id)">
                          Confirm
                        </button>
                        </div>
                      </div>

                    </td>
                  </tr>
                  <!-- <tr v-show="isLoading"> 
                      <td><img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;"/> </td>
                    </tr>  -->
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
            <Pagination :data="cashouts" @pagination-change-page="getCashouts" />
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>
    
<script>
import { onMounted, reactive, ref } from 'vue';
import useCashouts from "../../services/cashoutservices";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';

export default {
  props: ['user', 'userrole'],
  setup(props) {
    const { getCashouts, cashouts, confirmCashout, isLoading, checkPendingCashout, pendingCashout } = useCashouts();
    const { formatDecimalNumber, formatDate } = useUtils();
    const canCashout = ref(false);
    const { can, cannot } = useAbility();
    const errors = ref([]);

    const form = reactive({
      q: '',
      q_name: '',
    });

    onMounted(async () => {
      await checkPendingCashout(props.user.id);
      await pendingCashout.value ? canCashout.value = false: canCashout.value = true;
      await getCashouts();
      await Echo.private('cashout')
        .listen('CashoutEvent', (e) => {
          getCashouts();
        })
    })

    const confirmUserCashout = async (id) => {
      try {
        const data = new FormData();
        data.append('cashout_id', id);
        await confirmCashout(data);
        await checkPendingCashout(props.user.id);
        await pendingCashout.value ? canCashout.value = false: canCashout.value = true;
        await getCashouts();
      } catch (error) {
        errors.value = error.response.data.errors;
      }
    }

    const searchByName = async () => {
      if (form.q_name.length > 0) {
        const data = new FormData()
        data.append('name', form.q_name);
        axios.post("/api/cashoutsearchbyname", data).
          then(response => { cashouts.value = response.data }).
          catch(error => console.log(error))
      } else {
        await getCashouts();
      }
    }

    return {
      form,
      props,
      cashouts,
      getCashouts,
      formatDecimalNumber,
      confirmUserCashout,
      can,
      cannot,
      formatDate,
      searchByName,
      canCashout,
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
}</style>