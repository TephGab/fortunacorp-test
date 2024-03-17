<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
            <div class="card card-primary card-outline direct-chat direct-chat-primary">
                <div class="card-header border-transparent">
                    <div class="text-center text-xs text-muted text-uppercase">
                        <div>
                            <span v-if="form.balance_amount_cashout != 0 && form.balance_amount_cashout != ''">{{ form.commission_category }}</span>
                            CASHOUT 
                        </div>
                    </div>
                </div>
            
                <div class="card-body">
                    <div class="row text-center m-2 align-items-center text-xs" v-if="user && user.user_account">
                        <div :class="[form.commission_category === 'commission' ? 'shadow-xl bg-gray-200 p-2 border border-orange-300 text-sm' : 'text-muted', 'col d-flex justify-content-center align-items-center']" v-if="userrole.name === 'operator'">
                            <div class="text-right">
                                <span class="text-xs">COMMISION:</span> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.profits) }} RD$</span>

                                <div v-if="form.commission_category === 'commission' && form.balance_amount_cashout != 0 && form.balance_amount_cashout != ''">
                                    <div>
                                        <i class="fa-solid fa-circle-down text-warning fw-bold"></i> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.profits - form.balance_amount_cashout) }} RD$</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xs-12 col-md col-lg" v-if="userrole.name === 'operator'">
                            <button class="btn btn-default" @click="commissionCategorySwitch"><i class="fa-solid fa-right-left"></i></button>
                        </div>

                        <div :class="[form.commission_category === 'referral_commission' ? 'shadow-xl bg-gray-200 p-2 border border-orange-300 text-sm' : 'text-muted', 'col d-flex justify-content-center align-items-center']">
                            <div class="text-right">
                                <span class="text-xs">REFERRAL_COM.:</span> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.referral_commissions) }} RD$</span>

                                <div v-if="form.commission_category === 'referral_commission' && form.balance_amount_cashout != 0 && form.balance_amount_cashout != ''">
                                    <div>
                                        <i class="fa-solid fa-circle-down text-warning fw-bold"></i> <span class="fw-bold text-right">{{ formatDecimalNumber(user.user_account.referral_commissions - form.balance_amount_cashout) }} RD$</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="mt-2 mb-3">
                        <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true" @click="makeUserAccountCashout">System account</button>
                            <!-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false"
                            @click="makeBankAccountCashout">Bank Account</button> -->
                        </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            
                            <div class="mb-3 col-md-12" v-if="user && user.user_account">
                                <label for="amount" class="form-label">Amount</label>
                                <!-- @input="checkAmountLimit" -->
                                <input type="number" class="form-control" v-model="form.balance_amount_cashout">
                                <div v-if="isAmountLimitExceeded" class="form-text error text-danger"> {{ amountLimitdFeedbackMessage }}</div>
                                <div v-if="errors.balance_amount_cashout" class="form-text error text-danger">{{  errors.balance_amount_cashout[0] }}</div>
                            </div>

                        </div>
                        </div>
                    </div>
                </div>
              
                <div class="card-footer clearfix text-center" v-if="user && user.user_account">
                        <button v-if="canCashout" class="btn btn-warning btn-sm" 
                            @click="createCashout(form.commission_category == 'commission' ? user.user_account.profits : user.user_account.referral_commissions, 
                            form.balance_amount_cashout)" :disabled="isLoading || canCashout == false || isAmountLimitExceeded ? true : false">
                            Request cashout
                            <img v-if="isLoading" class="img-fluid img-circle d-inline" src="/img/button_spinner.gif" alt="button-spinner" />
                        </button>
                        <button v-else class="btn btn-warning btn-sm" disabled>Pending Cashout</button>
                </div>
    
            </div>
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref, watch} from 'vue';
  import useUsers from "../../services/usersservices";
  import useCashouts from "../../services/cashoutservices";
  import useUtils from "../../services/utilsservices";
  import Swal from "sweetalert2";
  
  export default {
    props: ['user', 'userrole', 'commission'],
    setup(props) {
        const { getUser, user } = useUsers();
        const { storeCashout, checkPendingCashout, pendingCashout } = useCashouts();
        const { isLoading, formatDecimalNumber } = useUtils();
        const cashoutType = ref('user_account');
        const canCashout = ref(false);
        const errors = ref([]);
        const isAmountLimitExceeded = ref(false);
        const amountLimitdFeedbackMessage = ref('');
    
        const form = reactive({
            balance_amount_cashout: '',
            cashout_status: 'pending',
            cashout_user_account_id: '',
            cashout_system_account_id: '',
            user_bank_account_id: '',
            system_bank_account_id: '',
            cashout_currency: 'pesos',
            commission_category: '',
        });
    
        onMounted(async() => {
            await getUser(props.user.id);
            //await props.commission == 'commission' ? form.commission_category = 'commission' : form.commission_category = 'referral_commission';
            await props.userrole.name != 'operator' ? form.commission_category = 'referral_commission' : form.commission_category = 'commission';
            await checkPendingCashout(props.user.id);
            await pendingCashout.value ? canCashout.value = false: canCashout.value = true;
        });

        const createCashout = async (commission, amount) => {
        Swal.fire({
            text: "Perform cashout request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
            }).then(async(result) => {
            if (result.isConfirmed) {
                try {
                    if(amount <= commission){
                        await storeCashout({'cashout_type': cashoutType.value,
                                            'balance_amount_cashout': amount,
                                            'cashout_status': form.cashout_status,
                                            'cashout_user_account_id': form.cashout_user_account_id,
                                            'cashout_system_account_id': form.cashout_system_account_id,
                                            'user_bank_account_id': form.user_bank_account_id,
                                            'system_bank_account_id': form.system_bank_account_id,
                                            'cashout_currency': form.cashout_currency,
                                            'commission_category': form.commission_category,
                                        });
                        await pendingCashout.value ? canCashout.value = false: canCashout.value = true;
                        isLoading.value = false;
                    }
                    else{
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
                        if (error.response.data.errors) {
                            if (error.response.data.errors.insufficient_balance[0]) {
                                Swal.fire({
                                text: 'Insufficient Commission!',
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
            });
        }

        const makeUserAccountCashout = () => {
            cashoutType.value = 'user_account';
        }

        const makeBankAccountCashout = () => {
            cashoutType.value = 'user_bank_account';
        }

        const commissionCategorySwitch = () => {
            form.commission_category === 'commission' ? form.commission_category = 'referral_commission' :  form.commission_category = 'commission';
            form.balance_amount_cashout = '';
            isAmountLimitExceeded.value = false;
            amountLimitdFeedbackMessage.value = '';
        }

        const checkAmountLimit = () => {
            switch (form.commission_category) {
                case 'commission':
                    if (form.balance_amount_cashout >= user.value.user_account.profits) {
                        form.balance_amount_cashout = user.value.user_account.profits;
                        isAmountLimitExceeded.value = true;
                        amountLimitdFeedbackMessage.value = 'Max commission amount reached!';
                    }else{
                        isAmountLimitExceeded.value = false;
                        amountLimitdFeedbackMessage.value = '';
                    }
                    break;
                case 'referral_commission':
                    if (form.balance_amount_cashout >= user.value.user_account.referral_commissions) {
                        form.balance_amount_cashout = user.value.user_account.referral_commissions;
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

        watch([() => form.commission_category, () => form.balance_amount_cashout], () => {
            checkAmountLimit();
        });
  
      return {
        form,
        props,
        errors,
        user,
        isLoading,
        formatDecimalNumber,
        makeUserAccountCashout,
        makeBankAccountCashout,
        createCashout,
        canCashout,
        checkAmountLimit,
        isAmountLimitExceeded,
        amountLimitdFeedbackMessage,
        commissionCategorySwitch,
      };
    }
  }
  </script>