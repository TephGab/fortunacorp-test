<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3">
        <!--  -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent text-center">
            <h3 class="card-title">Agent deposit details</h3>
            <!-- <div class="card-tools">
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
            <div class="col-md-12 text-center">
               
                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-md-12 mt-1">
                        <h6 class="fw-bold mb-3">AGENTS INFOS</h6>
                    </div>
                </div>

                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Agent name</label>
                        <div> {{ agentDepositUser.first_name }} {{ agentDepositUser.last_name  }}
                            <span> ( {{ agentDepositUser.code }} ) </span>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                      <label for="clientName" class="form-label">Agent location</label>
                        <div> {{ agentDepositUser.location }}
                        </div>
                    </div>      
                </div>

                <hr class="hr hr-blurry" />

                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-md-12 mt-1">
                        <h6 class="fw-bold mb-3">DEPOSIT DETAILS</h6>
                    </div>
                </div>

                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Amount</label>
                        <div> {{ agentDeposit.amount }} <span class="text-uppercase"> {{ agentDeposit.currency }} </span>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                      <label for="clientName" class="form-label">Type</label>
                        <div>
                          <span v-if="agentDeposit.type == 'user_account'">Cash</span>
                          <span v-if="agentDeposit.type == 'user_bank_account'">Bank Transfert</span>
                        </div>
                    </div>      
                </div>

                <hr class="hr hr-blurry" />

                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-md-12 mt-1">
                        <h6 class="fw-bold mb-3">ENVOY INFOS</h6>
                    </div>
                </div>

                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-12">
                        <label for="clientName" class="form-label">Envoy name</label>
                          <div> {{ envoyDeposit.first_name }} {{ envoyDeposit.last_name }} <span> ( {{ envoyDeposit.code }} ) </span>
                          </div>
                    </div>
                    <!-- <div class="mb-3 col-md-6">
                      <label for="clientName" class="form-label">Type</label>
                        <div>
                          <span v-if="agentDeposit.type == 'user_account'">Cash</span>
                          <span v-if="agentDeposit.type == 'user_bank_account'">Bank Transfert</span>
                        </div>
                    </div>       -->
                </div>
            </div>
        </div>
           
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix text-center">
            <button class="btn btn-success" @click.prevent=confirmDeposit(agentDeposit.id) :disabled="buttonIsDisabled ? true : false"> 
              Confirm <img v-show="buttonIsDisabled" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
            </button>
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
  import useAgentDeposits from "../../services/agentdepositsservices";
  import useUtils from "../../services/utilsservices";

export default {
  props: ['id', 'user'],
  setup(props) {
    const { getAgentDeposit, getAgentDepositDetails, agentDeposit, 
      getEnvoyDeposit, envoyDeposit, agentDepositUser, confirmAgentDeposit, isLoading, buttonIsDisabled } = useAgentDeposits();
    const errors = ref([]);

    onMounted(async() => {
      const data = new FormData();
      data.append('deposit_id', props.id);
      await getAgentDepositDetails(data);
      await getEnvoyDeposit(props.id)
    })

    const confirmDeposit = async (id) => {
      try{
        const data = new FormData();
        data.append('agent_deposit_id', id);
        await confirmAgentDeposit(data);
      }catch(error){
        errors.value = error.response.data.errors;
      }
    }

    return {
      agentDeposit,
      envoyDeposit,
      agentDepositUser,
      confirmDeposit,
      buttonIsDisabled
    };
  }
}
</script>