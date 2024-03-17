<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
                <div class="text-center text-xs text-muted" v-if="form.commission_category != ''">
                    <div class="uppercase">{{ form.commission_category }} TO RECHARGE</div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row text-center m-1 p-0 align-items-center text-xs" v-if="user && user.user_account">
                        <div :class="[form.commission_category === 'commission' ? 'shadow-xl bg-gray-200 p-1 border border-orange-300 text-sm' : 'text-muted', 'col d-flex justify-content-center align-items-center']">
                            <div class="text-right m-0 p-0">
                              <span class="text-xs">COMMISION:</span> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.profits) }} RD$</span>

                                <div v-if="form.commission_category === 'commission' && form.profits_transfert != 0 && form.profits_transfert != ''">
                                    <div>
                                      <i class="fa-solid fa-circle-down text-warning fw-bold"></i> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.profits - form.profits_transfert) }} RD$</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xs-12 col-md col-lg">
                            <button class="btn btn-default" @click="commissionCategorySwitch"><i class="fa-solid fa-right-left"></i></button>
                        </div>

                        <div :class="[form.commission_category === 'referral_commission' ? 'shadow-xl bg-gray-200 p-1 border border-orange-300 text-sm' : 'text-muted', 'col d-flex justify-content-center align-items-center']">
                            <div class="text-right m-0 p-0">
                               <span class="text-xs">REFERRAL_COM.:</span> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.referral_commissions) }} RD$</span>

                                <div v-if="form.commission_category === 'referral_commission' && form.profits_transfert != 0 && form.profits_transfert != ''">
                                    <div>
                                      <i class="fa-solid fa-circle-down text-warning fw-bold"></i> <span class="fw-bold text-right">{{ formatDecimalNumber(user.user_account.referral_commissions - form.profits_transfert) }} RD$</span>
                                    </div>
                                </div>
                            </div>
                        </div>
              </div>
              <hr/>

              <div class="row text-center m-2 m-2" v-if="user && user.user_account">
                  <div class="col d-flex justify-content-center align-items-center">
                    <div class="text-right">
                       RECHARGE: <span class="fw-bold">{{ formatDecimalNumber(user.user_account.investments) }} RD$</span>
                      <div v-if="form.profits_transfert != 0 || form.profits_transfert != ''">
                        <i class="fa-solid fa-circle-up text-success fw-bold"></i> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.investments + form.profits_transfert) }} RD$</span>
                      </div>
                    </div>
                  </div>
              </div>

              <hr/>
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                    <div class="row">
                      <div class="mb-3 col-md-12" v-if="user && user.user_account">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" v-model="form.profits_transfert">
                        <div v-if="isAmountLimitExceeded" class="form-text error text-danger"> {{ amountLimitdFeedbackMessage }}</div>
                        <div v-if="errors.profits_transfert" class="form-text error text-danger">{{  errors.profits_transfert[0] }}</div>
                      </div>
                    </div>
              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix text-center" v-if="user && user.user_account">
              <button v-if="canTransfer" class="btn btn-warning btn-sm" @click="transfertToRecharge(user.id, form.commission_category == 'commission' ? user.user_account.profits : user.user_account.referral_commissions, form.profits_transfert)" :disabled="isLoading || canTransfer == false ? true : false">
                Transfer
                <img v-if="isLoading" class="img-fluid img-circle d-inline" src="/img/button_spinner.gif" alt="button-spinner" />
              </button>
            </div>
  
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref, watch } from 'vue';
  import useUsers from "../../services/usersservices";
  import useUtils from "../../services/utilsservices";
  import Swal from "sweetalert2";
  import router from '../../router';
  
  export default {
    props: ['user', 'userrole', 'commission'],
    setup(props) {
        const { getUser, user, transfertProfitsToRecharge } = useUsers();
        const { isLoading, formatDecimalNumber } = useUtils();
        const canTransfer = ref(false);
        const isAmountLimitExceeded = ref(false);
        const amountLimitdFeedbackMessage = ref('');
    
      const form = reactive({
        profits_transfert: '',
        commission_category: ''
      });
  
      const errors = ref([]);
  
      onMounted(async() => {
        await getUser(props.user.id);
        await props.commission == 'commission' ? form.commission_category = 'commission' : form.commission_category = 'referral_commission';
        canTransfer.value = await true;
      })

      const transfertToRecharge = async (user_id, user_commission, profits_transfert) => {
        Swal.fire({
            text: "Transfert commission to racharge?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
            }).then(async(result) => {
            if (result.isConfirmed) {
                try {
                    if(profits_transfert <= user_commission){
                        await transfertProfitsToRecharge({'user_id':user_id, 'user_commission':user_commission, 'profits_transfert':profits_transfert, 'commission_category': form.commission_category,});
                        isLoading.value = false;
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: 'Insufficient commission!',
                            showConfirmButton: true,
                        });
                        isLoading.value = false;
                    }
                } catch (error) {
                    isLoading.value = false;
                    errors.value = error.response.data.errors;
                    if(error.response.data.errors){
                        if(error.response.data.errors.profits_transfert[0]){
                        Swal.fire({
                            icon: 'error',
                            text: 'Insufficient commission!',
                            showConfirmButton: true,
                            timer: 10500
                            });
                        }
                    }
                }
            }
            });
      }

      const commissionCategorySwitch = () => {
            form.commission_category === 'commission' ? form.commission_category = 'referral_commission' :  form.commission_category = 'commission';
            form.profits_transfert = '';
            isAmountLimitExceeded.value = false;
            amountLimitdFeedbackMessage.value = '';
      }

      const checkAmountLimit = () => {
            switch (form.commission_category) {
                case 'commission':
                    if (form.profits_transfert >= user.value.user_account.profits) {
                        form.profits_transfert = user.value.user_account.profits;
                        isAmountLimitExceeded.value = true;
                        amountLimitdFeedbackMessage.value = 'Max commission amount reached!';
                    }else{
                        isAmountLimitExceeded.value = false;
                        amountLimitdFeedbackMessage.value = '';
                    }
                    break;
                case 'referral_commission':
                    if (form.profits_transfert >= user.value.user_account.referral_commissions) {
                        form.profits_transfert = user.value.user_account.referral_commissions;
                        isAmountLimitExceeded.value = true;
                        amountLimitdFeedbackMessage.value = 'Max referral commission amount reached!';
                    }else{
                        isAmountLimitExceeded.value = false;
                        amountLimitdFeedbackMessage.value = '';
                    }
                    break;
                default:
                    break;
            }
        };

        watch([() => form.commission_category, () => form.profits_transfert], () => {
            checkAmountLimit();
        });

  
      return {
        form,
        errors,
        user,
        isLoading,
        transfertToRecharge,
        formatDecimalNumber,
        checkAmountLimit,
        isAmountLimitExceeded,
        amountLimitdFeedbackMessage,
        commissionCategorySwitch,
        canTransfer
      };
    }
  }
  </script>