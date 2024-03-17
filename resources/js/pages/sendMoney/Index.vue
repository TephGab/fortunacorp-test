<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div class="text-center text-xs fw-bold mb-1">
                <div>FORTUNA CORPORATION</div>
                <div>AGENT WITHDRAWALS</div>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center">
                <router-link :to="{ name: 'sendmoney.create', params: { agentid: 0, amount: '0', reqreplenishmentid: '0' } }" v-if="can('make', 'replenishment')" class="btn btn-sm btn-info float-left">
                    <i class='fas fa-plus'></i> Send money
                </router-link>
                <div class="text-right text-xs">
                  <span class="fw-bold">Total: {{ formatDecimalNumber(sendmoneys.sendMoneyTotal) }} RD$</span>
                </div>
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
                      <th class="text-nowrap text-right">Withdrawal</th>
                      <th class="text-nowrap text-center">Status</th>
                      <th class="text-nowrap text-center">Date</th>
                      <th class="text-nowrap text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody v-if="sendmoneys.sendMoneys">
                    <tr v-for="sendmoney in sendmoneys.sendMoneys.data" :key="sendmoney.id">
                      <td class="text-nowrap"> {{ sendmoney.id }} </td>
                      <td class="text-nowrap text-center">
                        <span>{{ sendmoney.receiver_first_name }} {{  sendmoney.receiver_last_name }}</span>
                      </td>
                      <td class="text-nowrap text-right"> 
                        <span class="fw-bold text-gray-600">{{ formatDecimalNumber(sendmoney.amount) }} <span class="text-uppercase">RD$</span></span>
                      </td>
                      <td class="text-capitalize text-nowrap">
                          <div>
                              <span v-if="sendmoney.status == 'pending'" class="badge badge-default text-warning"> {{ sendmoney.status }} </span>
                              <span v-if="sendmoney.status == 'completed'" class="badge badge-default text-info"> {{ sendmoney.status }} </span>
                              <span v-if="sendmoney.status == 'confirmed'" class="badge badge-default text-success"> {{ sendmoney.status }} </span>
                          </div>
                      </td>
                      <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(sendmoney.created_at) }}
                      </td>
                      <td class="text-nowrap"> 
                        <div v-if="userrole.name == 'envoy'">
                            <button v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 0 && sendmoney.confirmed_by_envoy == 0" class="btn btn-info btn-sm" @click="sendMoneyConfirm(sendmoney.id, sendmoney.receiver_id)">
                              <span v-show="!isLoading">
                                Confirm
                              </span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
                          <span v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 0 && sendmoney.confirmed_by_envoy == 1" class="text-xs text-info">
                            Waiting for agent to confirm
                          </span>
                          <span v-if="sendmoney.status == 'confirmed' && sendmoney.confirmed_by_receiver == 1 && sendmoney.confirmed_by_envoy == 1" class="text-xs text-success">
                            Confirmed
                          </span>
                        </div>

                        <div v-if="userrole.name == 'agent'">
                            <button v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 0 && sendmoney.confirmed_by_envoy == 1" class="btn btn-info btn-sm" @click="sendMoneyConfirm(sendmoney.id, sendmoney.receiver_id)">
                              <span v-show="!isLoading">
                                Confirm
                              </span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
                  
                          <span v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 0 && sendmoney.confirmed_by_envoy == 0" class="text-xs text-info">
                            Waiting for envoy to confirm
                          </span>
                          <span v-if="sendmoney.status == 'confirmed' && sendmoney.confirmed_by_receiver == 1 && sendmoney.confirmed_by_envoy == 1" class="text-xs text-success">
                            Confirmed
                          </span>
                        </div>

                        <div v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                          <span v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 0 && sendmoney.confirmed_by_envoy == 0" class="text-xs text-info">
                            Waiting for confirmations
                          </span>
                          <span v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 0 && sendmoney.confirmed_by_envoy == 1" class="text-xs text-info">
                            Waiting for agent to confirm
                          </span>
                          <span v-if="sendmoney.status == 'pending' && sendmoney.confirmed_by_receiver == 1 && sendmoney.confirmed_by_envoy == 0" class="text-xs text-info">
                            Waiting for envoy to confirm
                          </span>
                          <span v-if="sendmoney.status == 'confirmed' && sendmoney.confirmed_by_receiver == 1 && sendmoney.confirmed_by_envoy == 1" class="text-xs text-success">
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
              <Pagination :data="sendmoneys" @pagination-change-page="getSendMoneys" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useSendMoney from "../../services/sendmoneyservices";
  import useUtils from "../../services/utilsservices";
  import { useAbility } from '@casl/vue';
  import Swal from "sweetalert2";
  
  export default {
    props: ['user', 'userrole'],
    setup(props) {
      const { getSendMoneys, sendmoneys, isLoading, confirmSendMoney } = useSendMoney();
      const { formatDecimalNumber, formatDate } = useUtils();
      const { can, cannot } = useAbility();
      const errors = ref([]);

      const form = reactive({
        q: ''
      });
  
      onMounted(async() => {
       await getSendMoneys();
    //    await Echo.private('sendmoney')
    //     .listen('ReplenishmentEvent', (e) => {
    //       getSendMoneys();
    //     });
      });
      
      const sendMoneyConfirm = async (send_money_id, receiver_id) => {
        isLoading.value = true;
        try{
          await confirmSendMoney({'send_money_id': send_money_id, 'agent_id': receiver_id});
          await getSendMoneys();
          isLoading.value = false;
        }catch(error){
          errors.value = error.response.data.errors;
          isLoading.value = false;
          if(error.response.data.errors){
                if (error.response.data.errors.insufficient_debt[0]) {
                        Swal.fire({
                        text: 'Amount can not exceed your available debt!',
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
        props,
        sendmoneys,
        getSendMoneys,
        formatDecimalNumber,
        sendMoneyConfirm,
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