<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent text-center">
              <h3 class="card-title">Cashout details</h3>
              <!-- <div class="card-tools">
              </div> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
              <div class="col-md-12 text-center">
                 
                  <div class="d-flex justify-content-center align-items-center">
                      <div class="col-md-12 mt-1">
                          <h6 class="fw-bold mb-3">USER INFOS</h6>
                      </div>
                  </div>
  
                  <div class="d-flex justify-content-space-bettween align-items-center text-capitalize">
                      <div class="mb-3 col-md-6">
                          <label for="clientName" class="form-label">User name</label>
                          <div> {{ cashoutUser.first_name }} {{ cashoutUser.last_name  }}
                              <span class="text-uppercase"> ( {{ cashoutUser.code }} ) </span>
                          </div>
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">User location</label>
                          <div> {{ cashoutUser.location }}
                          </div>
                      </div>      
                  </div>
  
                  <hr class="hr hr-blurry" />
  
                  <div class="d-flex justify-content-center align-items-center">
                      <div class="col-md-12 mt-1">
                          <h6 class="fw-bold mb-3">CASHOUT DETAILS</h6>
                      </div>
                  </div>
  
                  <div class="d-flex justify-content-space-bettween align-items-center">
                      <div class="mb-3 col-md-6">
                          <label for="clientName" class="form-label">Amount</label>
                          <div> {{ cashout.amount }} <span class="text-uppercase"> {{ cashout.currency }} </span>
                          <div v-if="cashoutUser && cashoutUser.user_account" class="text-muted text-xs"> (Current balance  {{ cashoutUser.user_account.profits }} RD$)</div>
                          <div v-if="errors && errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div>
                        </div>
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Type</label>
                          <div>
                            <span v-if="cashout.type == 'user_account'">Cash</span>
                            <span v-if="cashout.type == 'user_bank_account'">Bank Transfert</span>
                          </div>
                      </div>      
                  </div>
  
                  <hr class="hr hr-blurry" />
  
                  <div class="d-flex flex-column justify-content-space-bettween align-items-center">
                    <!-- <div class="mb-3 mt-2 col-md-6">
                      <label for="account" class="form-label mr-2"> Deduct from envoy debt ? </label>
                      <input type="checkbox" id="checkbox" v-model="form.use_envoy_debt" />
                    </div> -->
                    <!-- <h6 class="fw-bold mb-3">SELECT ENVOY</h6> -->
                    <div class="mb-3 mt-1 col-md-5" v-show="!form.user_in_person" v-if="cashout.user_role != 'envoy'">
                        <label for="account" class="form-label">Select envoy</label>
                        <Select2 class="form-label" v-model="form.envoy_id" :options="envoys" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                        <div v-if="errors && errors.envoy_id" class="form-text error text-danger">{{ errors.envoy_id[0] }}</div>
                    </div>
                  </div>
              </div>
          </div>
             
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix text-center">
              <button class="btn btn-success" @click.prevent=completeUserCashout(cashout.id) :disabled="cashout.status == 'pending' ? false : true"> Send </button>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
    import { onMounted, ref, reactive } from 'vue';
    import  useCashouts from "../../services/cashoutservices";
    import useUsers from "../../services/usersservices";
    import router from "../../router";
    import useUtils from "../../services/utilsservices";
    import Swal from "sweetalert2";
  
  export default {
    props: ['id', 'user'],
    setup(props) {
        const { getCashoutDetails, cashout, cashoutUser, completeCashout, isLoading } = useCashouts();
        const { getEnvoys, envoys, getUser, user, } = useUsers();
        const { formatDecimalNumber, formatDate } = useUtils();
        const errors = ref([]);

        const form = reactive({
          envoy_id: '',
          user_in_person: false,
          use_envoy_debt: true,
        });
  
      onMounted(async() => {
        const data = new FormData();
        data.append('cashout_id', props.id);
        await getCashoutDetails(data);
        await getEnvoys();
      });
  
      const completeUserCashout = async (id) => {
        try{
          const data = new FormData();
          data.append('cashout_id', id);
          data.append('envoy_id', form.envoy_id);
          if(form.use_envoy_debt == true){
            data.append('use_envoy_debt', 1);
          }else{
            data.append('use_envoy_debt', 0);
          }
          
          await completeCashout(data);
          router.push({ name: 'cashout.index' });
        }catch(error){
          errors.value = error.response.data.errors;
          if(error.response.data.errors){
            if(error.response.data.errors.insufficient_debts[0]){
              Swal.fire({
                text: 'Insufficient debts for selected cashout type. Select envoy!',
                position: 'center',
                icon: 'error',
                color: '#000',
                padding: '0',
                showConfirmButton: true,
                timer: 10500
                });
            }
          }
        }
      }
  
      return {
        form,
        envoys,
        cashout,
        cashoutUser,
        completeUserCashout,
        errors,
        user,
        formatDecimalNumber
      };
    }
  }
  </script>