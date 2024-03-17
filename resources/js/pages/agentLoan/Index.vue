<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div class="text-center text-xs fw-bold mb-1">
                <div>FORTUNA CORPORATION</div>
                <div>AGENT LOANS</div>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center">
                <router-link v-if="userrole.name == 'super-admin' || userrole.name == 'system-engineer'" :to="{ name: 'agentloan.create' }" class="btn btn-sm btn-info float-left">
                    <i class='fas fa-plus'></i> New loan
                </router-link>
                <!-- <div class="text-right text-xs">
                  <span class="fw-bold">Total: {{ formatDecimalNumber(loans.sendMoneyTotal) }} RD$</span>
                </div> -->
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive table-hover">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th class="text-nowrap">#</th>
                      <th class="text-nowrap text-center">Agent</th>
                      <th class="text-nowrap text-center">Registred by</th>
                      <th class="text-nowrap text-right">Amount</th>
                      <th class="text-nowrap text-center">Status</th>
                      <th class="text-nowrap text-center">Date</th>
                      <th class="text-nowrap text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="loan in loans.data" :key="loan.id">
                      <td class="text-nowrap"> {{ loan.id }} </td>
                      <td class="text-nowrap text-center">
                        <div>{{ loan.receiver.first_name }} {{ loan.receiver.last_name }}</div>
                        <div>{{ loan.receiver.code }}</div>
                      </td>
                      <td class="text-nowrap text-center">
                        <div>{{ loan.admin.first_name }} {{ loan.admin.last_name }}</div>
                        <div>{{ loan.admin.code }}</div>
                      </td>
                      <td class="text-nowrap text-right"> 
                        <span class="fw-bold text-gray-600">{{ formatDecimalNumber(loan.amount) }} <span class="text-uppercase">RD$</span></span>
                      </td>
                      <td class="text-capitalize text-nowrap">
                          <div>
                              <span v-if="loan.status == 'pending'" class="badge badge-default text-warning"> {{ loan.status }} </span>
                              <span v-if="loan.status == 'confirmed'" class="badge badge-default text-success"> {{ loan.status }} </span>
                          </div>
                      </td>
                      <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(loan.created_at) }}
                      </td>
                      <td class="text-nowrap"> 
                     
                        <div v-if="userrole.name == 'agent'">
                            <button v-if="loan.status == 'pending' && loan.confirmed_by_receiver == 0" class="btn btn-info btn-sm" @click="agentLoanConfirm(loan.id, loan.receiver_id)">
                              <span v-show="!isLoading">
                                Confirm
                              </span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
    
                            <span v-if="loan.status == 'confirmed' && loan.confirmed_by_receiver == 1" class="text-xs text-success">
                            {{ loan.status }}
                            </span>
                        </div>

                        <div v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                    
                          <span v-if="loan.status == 'pending' && loan.confirmed_by_receiver == 0" class="text-xs text-info">
                            Waiting for agent to confirm
                          </span>
                        
                          <span v-if="loan.status == 'confirmed' && loan.confirmed_by_receiver == 1" class="text-xs text-success">
                            Confirmed
                          </span>
                        </div>
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
              <Pagination :data="loans" @pagination-change-page="getAgentLoans" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useAgentLoan from "../../services/agentloanservices";
  import useUtils from "../../services/utilsservices";
  import { useAbility } from '@casl/vue';
  import Swal from "sweetalert2";
  
  export default {
    props: ['user', 'userrole'],
    setup(props) {
      const { getAgentLoans, loans, confirmAgentLoan } = useAgentLoan();
      const { formatDecimalNumber, formatDate, isLoading } = useUtils();
      const { can, cannot } = useAbility();
      const errors = ref([]);

      const form = reactive({
        q: ''
      });
  
      onMounted(async() => {
       await getAgentLoans();
    //    await Echo.private('loan')
    //     .listen('ReplenishmentEvent', (e) => {
    //       getAgentLoans();
    //     });
      });

     const agentLoanConfirm = async (loanId, agentId) => {
            try {
                isLoading.value = true;
                await confirmAgentLoan({
                  'loan_id': loanId,
                  'agent_id': agentId,
                });
            } catch (error) {
                isLoading.value = false;
                errors.value = error.response.data.errors;
            }
      };
  
      return {
        props,
        getAgentLoans,
        loans,
        formatDecimalNumber,
        agentLoanConfirm,
        can,
        cannot,
        formatDate,
        isLoading,
        errors,
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