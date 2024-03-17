<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div class="text-center text-xs fw-bold">
                  <div>FORTUNA CORPORATION</div>
                  <div>AGENT LOAN PAYMENT</div>
                </div>
              <div style="display: flex; justify-content: space-between; align-items: center">
                <router-link v-if="userrole.name == 'agent' && canDeposit"  class="btn btn-sm btn-info float-left" class-active="active" :to="{ name: 'agentloanrepay.create' }">
                  <i class='fas fa-plus'></i> New loan payment
                </router-link>
                <div v-if="userrole.name == 'agent' && !canDeposit" class="text-warning text-xs">
                  <i class="fa-solid fa-circle-info"></i> Pending payment
                </div>
                <!--<form>
                    <input type="text" id="search" name="search" @keyup="search" placeholder="Search.." v-model="form.q">
                  </form>-->
                <!-- <div>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div> -->
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive table-hover">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th class="text-nowrap"></th>
                      <th class="text-nowrap" v-if="userrole.name != 'agent'">
                        <form>
                          <input type="text" id="search" name="searchbyname" @keyup="searchByName"
                            placeholder=" Search by agent name.." v-model="form.q_name">
                        </form>
                      </th>
                      <th class="text-nowrap"></th>
                      <th class="text-nowrap" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                      </th>
                      <th class="text-nowrap"></th>
                      <th class="text-nowrap"></th>
                      <th class="text-nowrap"></th>
                    </tr>
                    <tr>
                      <th class="text-nowrap text-center">#</th>
                      <th class="text-nowrap text-center" v-if="userrole.name != 'agent'">Agent</th>
                      <th class="text-nowrap text-right">Amount</th>
                      <th class="text-nowrap text-center" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">Envoy</th>
                      <th class="text-nowrap text-center">Status</th>
                      <th class="text-nowrap text-center">Date</th>
                      <th class="text-nowrap text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody v-for="loanRepayment in  loanRepayments.data" :key="loanRepayment.id">
                    <tr>
                      <td class="text-nowrap align-middle text-center"> {{ loanRepayment.id }} </td>
                      <td v-if="userrole.name != 'agent'" class="text-nowrap text-center align-middle"> 
                        <div>{{ loanRepayment.user.first_name }} {{ loanRepayment.user.last_name }}</div>
                        <div>({{ loanRepayment.user.code }})</div>
                      </td>
                      <td class="text-nowrap text-right align-middle">
                        <span class="text-gray-600 fw-bold">{{ formatDecimalNumber(loanRepayment.amount) }} RD$</span>
                        <div v-if="errors.deposit_in_process" class="form-text error text-danger">{{ errors.deposit_in_process[0] }}</div>
                      </td>
                      <td class="text-nowrap text-center text-xs align-middle" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                        <div>{{ loanRepayment.envoy.first_name }} {{ loanRepayment.envoy.last_name }}</div>
                        <div>({{ loanRepayment.envoy.code }})</div>
                      </td>
                      <td class="text-capitalize text-nowrap text-center align-middle">
                        <div>
                          <span v-if="loanRepayment.status == 'pending' && loanRepayment.confirmed_by_envoy == 0 && loanRepayment.confirmed_by_receiver == 0" class="badge badge-default text-primary"> Waiting for confirmations </span>
                          <span v-if="loanRepayment.status == 'pending' && loanRepayment.confirmed_by_envoy == 1 && loanRepayment.confirmed_by_receiver == 0" class="badge badge-default text-primary"> Waiting for admin to confirm </span>
                          <span v-if="loanRepayment.status == 'pending' && loanRepayment.confirmed_by_envoy == 0 && loanRepayment.confirmed_by_receiver == 1" class="badge badge-default text-primary"> Waiting for envoy to confirm </span>
                          <span v-if="loanRepayment.status == 'confirmed' && loanRepayment.confirmed_by_envoy == 1 && loanRepayment.confirmed_by_receiver == 1" class="badge badge-default text-success"> {{ loanRepayment.status }} </span>
                        </div>
                      </td>
                      <td class="text-nowrap text-xs text-muted text-center align-middle">
                        {{ formatDate(loanRepayment.created_at) }}
                      </td>
                      <td class="text-nowrap text-center align-middle">
                        <div v-if="userrole.name == 'super-admin' || userrole.name == 'system-engineer'">
                          <div v-if="loanRepayment.status == 'pending' && loanRepayment.confirmed_by_envoy == 1 && loanRepayment.confirmed_by_receiver == 0" class="badge badge-default text-primary">
                            <router-link class-active="active" :to="{ name: 'agentloanrepay.show', params: { id: loanRepayment.id } }">
                              <i class="fa fa-toggle-off text-warning" v-if="loanRepayment.status == 'pending'" style="font-size: 20px;"></i>
                              <i class="fas fa-check text-success" v-if="loanRepayment.status == 'confirmed'" style="font-size: 20px;"></i>
                            </router-link>
                          </div>
                        </div>
                        <div v-if="userrole.name == 'envoy'">
                          <div v-if="loanRepayment.status == 'pending' && loanRepayment.confirmed_by_envoy == 0 && loanRepayment.confirmed_by_receiver == 0">
                            <router-link class-active="active" :to="{ name: 'agentloanrepay.show', params: { id: loanRepayment.id } }">
                              <i class="fa fa-toggle-off text-warning" v-if="loanRepayment.status == 'pending'" style="font-size: 20px;"></i>
                              <i class="fas fa-check text-success" v-if="loanRepayment.status == 'confirmed'" style="font-size: 20px;"></i>
                            </router-link>
                          </div>
                        </div>
                        <!-- <div v-if="userrole.name == 'agent'">
                          <router-link v-if="loanRepayment.status == 'pending'" :to="{ name: 'agentloanrepay.modify', params: { id: loanRepayment.id } }" class="btn btn-info btn-sm text-xs mr-1">
                            <i class='fas fa-edit'></i> Modify
                          </router-link>
                          <button v-if="loanRepayment.status == 'pending'" class="btn btn-danger btn-sm text-xs" @click="loanRepaymentCancel(loanRepayment.id)">Cancel</button>
                        </div> -->
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
              <Pagination :data="loanRepayments" @pagination-change-page="getAgentLoanRepayments" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
      
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useAgentLoanRepay from "../../services/agentloanrepayservices";
  import useUtils from "../../services/utilsservices";
  import Swal from "sweetalert2";
  
  export default {
    props: ['user', 'userrole'],
    setup(props) {
      const { getAgentLoanRepayments, loanRepayments, isLoading} = useAgentLoanRepay();
      const { formatDecimalNumber, formatDate } = useUtils();
      const canDeposit = ref(true);
      const errors = ref([]);
  
      const form = reactive({
        q: '',
        q_name: '',
      });
  
      onMounted(async () => {
        //await checkPendingDeposit(props.user.id)
        //await pendingDeposit.value ? canDeposit.value = false: canDeposit.value = true;
        await getAgentLoanRepayments();
        // await Echo.private('agentdeposit')
        //   .listen('AgentDepositEvent', (e) => {
        //     getAgentLoanRepayments();
        //   })
      })
  
      const searchByName = async () => {
        if (form.q_name.length > 0) {
          const data = new FormData()
          data.append('name', form.q_name);
          axios.post("/api/agentdepositsearchbyname", data).
            then(response => { loanRepayments.value = response.data }).
            catch(error => console.log(error))
        } else {
          await getAgentLoanRepayments();
        }
      }
  
      const loanRepaymentCancel = async (id) => {
              try {
                Swal.fire({
                text: "Cancel deposit?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No',
              }).then(async(result) => {
                if (result.isConfirmed) {
                  //await cancelAgentDeposit({ 'deposit_id': id });
                 // await checkPendingDeposit(props.user.id)
                //   await pendingDeposit.value ? canDeposit.value = false: canDeposit.value = true;
                  if(errors){
                  if (errors.deposit_in_process[0]) {
                          Swal.fire({
                          text: 'Deposit is already in process! contact administrator',
                          position: 'center',
                          icon: 'error',
                          color: '#000',
                          padding: '0',
                          showConfirmButton: true,
                          });
                      }
                  }
                }
              })
            } catch (error) {
              errors.value = error.response.data.errors;
              if(error.response.data.errors){
                  if (error.response.data.errors.deposit_in_process[0]) {
                          Swal.fire({
                          text: 'Deposit is already in process! contact administrator',
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
  
      return {
        form,
        props,
        errors,
        loanRepayments,
        getAgentLoanRepayments,
        formatDecimalNumber,
        formatDate,
        searchByName,
        loanRepaymentCancel,
        canDeposit
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