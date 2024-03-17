<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div style="display: flex; justify-content: space-between; align-items: center">
                <router-link :to="{ name: 'replenishment.create', params: { agentid: 0, amount: '0', reqreplenishmentid: '0' } }" v-if="can('make', 'replenishment')" class="btn btn-sm btn-info float-left">
                    <i class='fas fa-plus'></i> New replenishment
                </router-link>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive table-hover">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th class="text-nowrap">#</th>
                      <th class="text-nowrap">Replenishments</th>
                      <th class="text-nowrap">Status</th>
                      <th class="text-nowrap">Date</th>
                      <th class="text-nowrap">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="replenishment in replenishments.data" :key="replenishment.id">
                      <td class="text-nowrap"> {{ replenishment.id }} </td>
                      <td class="text-nowrap">
                        <span>{{ formatDecimalNumber(replenishment.amount) }} <span class="text-uppercase">{{ replenishment.currency }}</span></span>
                        <span> cash replenishment to </span>
                        <span class="fw-bold">{{ replenishment.receiver_first_name }} {{  replenishment.receiver_last_name }}</span>
                        <span> ({{ replenishment.receiver_code }}) </span> 
                      </td>
                      <td class="text-capitalize text-nowrap">
                          <div>
                              <span v-if="replenishment.status == 'pending'" class="badge badge-default text-warning"> {{ replenishment.status }} </span>
                              <span v-if="replenishment.status == 'completed'" class="badge badge-default text-info"> {{ replenishment.status }} </span>
                              <span v-if="replenishment.status == 'confirmed'" class="badge badge-default text-success"> {{ replenishment.status }} </span>
                          </div>
                      </td>
                      <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(replenishment.created_at) }}
                      </td>
                      <td class="text-nowrap"> 
                        <div v-if="userrole.name == 'agent'">
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 0 && replenishment.confirmed_by_envoy == 0" class="text-xs text-info">
                            <button v-if="replenishment.status == 'pending'" @click="replenishmentConfirm(replenishment.id, replenishment.receiver_id)">
                              <span v-show="!isLoading"><i class="fa fa-toggle-off text-warning" style="font-size: 20px;"></i></span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                              <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
                          </span>
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 0 && replenishment.confirmed_by_envoy == 1" class="text-xs text-info">
                            <button v-if="replenishment.status == 'pending'" @click="replenishmentConfirm(replenishment.id, replenishment.receiver_id)">
                              <span v-show="!isLoading"><i class="fa fa-toggle-off text-warning" style="font-size: 20px;"></i></span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
                          </span>
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 1 && replenishment.confirmed_by_envoy == 0" class="text-xs text-info">
                            Waiting for envoy to confirm
                          </span>
                          <span v-if="replenishment.status == 'confirmed' && replenishment.confirmed_by_receiver == 1 && replenishment.confirmed_by_envoy == 1" class="text-xs text-success">
                            Confirmed
                          </span>
                        </div>

                         <div v-if="userrole.name == 'envoy'">
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 0 && replenishment.confirmed_by_envoy == 0" class="text-xs text-info">
                            <button v-if="replenishment.status == 'pending'"  @click="replenishmentConfirm(replenishment.id, replenishment.receiver_id)">
                              <span v-show="!isLoading"><i class="fa fa-toggle-off text-warning" style="font-size: 20px;"></i></span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
                          </span>
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 1 && replenishment.confirmed_by_envoy == 0" class="text-xs text-info">
                            <button v-if="replenishment.status == 'pending'" @click="replenishmentConfirm(replenishment.id, replenishment.receiver_id)">
                              <span v-show="!isLoading"><i class="fa fa-toggle-off text-warning" style="font-size: 20px;"></i></span>
                              <div class="d-flex justify-content-center align-items-center">
                                <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
                              </div>
                            </button>
                          </span>
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 0 && replenishment.confirmed_by_envoy == 1" class="text-xs text-info">
                            Waiting for agent to confirm
                          </span>
                          <span v-if="replenishment.status == 'confirmed' && replenishment.confirmed_by_receiver == 1 && replenishment.confirmed_by_envoy == 1" class="text-xs text-success">
                            Confirmed
                          </span>
                        </div>

                        <div v-if="userrole.name == 'super-admin'">
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 0 && replenishment.confirmed_by_envoy == 0" class="text-xs text-info">
                            Waiting for confirmations
                          </span>
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 0 && replenishment.confirmed_by_envoy == 1" class="text-xs text-info">
                            Waiting for agent to confirm
                          </span>
                          <span v-if="replenishment.status == 'pending' && replenishment.confirmed_by_receiver == 1 && replenishment.confirmed_by_envoy == 0" class="text-xs text-info">
                            Waiting for envoy to confirm
                          </span>
                          <span v-if="replenishment.status == 'confirmed' && replenishment.confirmed_by_receiver == 1 && replenishment.confirmed_by_envoy == 1" class="text-xs text-success">
                            Confirmed
                          </span>
                        </div>
                        <!-- <router-link class-active="active" :to="{ name: 'replenishment.edit', params: { id: replenishment.id } }">
                          <button v-if="replenishment.status == 'pending'"><i class="fa fa-toggle-off text-warning" style="font-size: 20px;"></i></button>
                         
                          <button v-if="replenishment.status == 'completed' && can('confirm', 'replenishment')" class="btn btn-info btn-sm text-xs" @click="replenishmentConfirm(replenishment.id)">Confirm</button>
                          
                          <span v-if="replenishment.status == 'completed' && cannot('confirm', 'replenishment')" class="text-xs text-info">
                            Waiting for confirmation
                          </span>
                            <button v-if="replenishment.status == 'confirmed'" ><i class="fas fa-check text-success" style="font-size: 20px;"></i></button>
                        </router-link> -->
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
              <Pagination :data="replenishments" @pagination-change-page="getReplenishments" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useReplenishments from "../../services/replenishmentservices";
  import useUtils from "../../services/utilsservices";
  import { useAbility } from '@casl/vue';
  
  export default {
     props: ['user', 'userrole'],
    setup(props) {
      const { getReplenishments, replenishments, confirmReplenishment, replenishment, isLoading } = useReplenishments();
      const { formatDecimalNumber, formatDate } = useUtils();
      const { can, cannot } = useAbility();
      const errors = ref([]);

      const form = reactive({
        q: ''
      });
  
      onMounted(async() => {
       await getReplenishments();
       await Echo.private('replenishment')
        .listen('ReplenishmentEvent', (e) => {
          getReplenishments();
        });
      });
      
      const replenishmentConfirm = async (replenishment_id, receiver_id) => {
        isLoading.value = true;
        try{
          await confirmReplenishment({'replenishment_id': replenishment_id, 'agent_id': receiver_id});
          await getReplenishments();
          isLoading.value = false;
        }catch(error){
          isLoading.value = false;
          errors.value = error.response.data.errors;
        }
      }
  
      return {
        props,
        replenishments,
        getReplenishments,
        formatDecimalNumber,
        replenishmentConfirm,
        can,
        cannot,
        formatDate,
        isLoading
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