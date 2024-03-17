<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent text-center">
              <h3 class="card-title">Loan payment details</h3>
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
  
                  <div v-if="loanRepayment && loanRepayment.user" class="d-flex justify-content-space-bettween align-items-center">
                      <div class="mb-3 col-md-6">
                          <label for="clientName" class="form-label">Agent name</label>
                          <div> {{ loanRepayment.user.first_name }} {{ loanRepayment.user.last_name  }}
                              <span> ( {{ loanRepayment.user.code }} ) </span>
                          </div>
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Agent location</label>
                          <div> {{ loanRepayment.user.location }}
                          </div>
                      </div>      
                  </div>
  
                  <hr class="hr hr-blurry" />
  
                  <div class="d-flex justify-content-center align-items-center">
                      <div class="col-md-12 mt-1">
                          <h6 class="fw-bold mb-3">LOAN DETAILS</h6>
                      </div>
                  </div>
  
                  <div class="d-flex justify-content-space-bettween align-items-center">
                      <div class="mb-3 col-md-6">
                          <label for="clientName" class="form-label">Amount</label>
                          <div> {{ loanRepayment.amount }} <span class="text-uppercase"> {{ loanRepayment.currency }} </span>
                          </div>
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Type</label>
                          <div>
                            <span v-if="loanRepayment.type == 'user_account'">Cash</span>
                            <span v-if="loanRepayment.type == 'user_bank_account'">Bank Transfert</span>
                          </div>
                      </div>      
                  </div>
  
                  <hr class="hr hr-blurry" />
  
                  <div class="d-flex justify-content-center align-items-center">
                      <div class="col-md-12 mt-1">
                          <h6 class="fw-bold mb-3">ENVOY INFOS</h6>
                      </div>
                  </div>
  
                  <div v-if="loanRepayment && loanRepayment.envoy" class="d-flex justify-content-space-bettween align-items-center">
                      <div class="mb-3 col-md-12">
                            <label for="clientName" class="form-label">Envoy name</label>
                            <div> {{ loanRepayment.envoy.first_name }} {{ loanRepayment.envoy.last_name }} </div>
                            <div> ( {{ loanRepayment.envoy.code }} ) </div>
                      </div>
                  </div>
              </div>
          </div>
             
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix text-center">
              <button class="btn btn-success" @click.prevent=confirmLoanRepayment(loanRepayment.id) :disabled="buttonIsDisabled ? true : false"> 
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
    import useAgentLoanRepay from "../../services/agentloanrepayservices";
    import useUtils from "../../services/utilsservices";
  
  export default {
    props: ['id', 'user'],
    setup(props) {
        const { getAgentLoanRepayment, loanRepayment, confirmAgentLoanRepayment} = useAgentLoanRepay();
        const { formatDecimalNumber, formatDate, isLoading, buttonIsDisabled } = useUtils();
        const errors = ref([]);
  
      onMounted(async () => {
        await getAgentLoanRepayment(props.id);
      })
  
      const confirmLoanRepayment = async (id) => {
        try{
          await confirmAgentLoanRepayment({'agent_loan_repay_id': id});
        }catch(error){
          errors.value = error.response.data.errors;
        }
      }
  
      return {
        loanRepayment,
        confirmLoanRepayment,
        buttonIsDisabled
      };
    }
  }
  </script>