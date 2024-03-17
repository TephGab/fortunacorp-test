<template>
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid"> <Breadcrumb /></div>
            <div class="col-md-12">
                <!-- Card -->
                   
                        <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                   <div v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">
                                        <div class="form-label">Amount</div>
                                        <div class="border-0 bg-light mb-1 p-2">
                                            <div> {{ formatDecimalNumber(transaction.sender_amount) }} RD$</div>
                                            <div class="text-muted"> Or </div>
                                            <div class="text-muted"> {{ formatDecimalNumber(transaction.receiver_amount) }} HTG <span class="text-muted text-xs text-italic"> Business acc.</span></div>
                                            <!-- <span class="text-muted"> {{ (transaction.sender_amount * transaction.rate) - transaction.p_to_p_fee }} HTG</span> -->
                                        </div>
                                        <div v-show="transaction.p_to_p_fee || transaction.p_to_p_fee == 0" class="text-center mt-2">
                                            <i class="fas fa-arrows-v"></i>
                                        </div>
                                        <div>Amount minus fees</div>
                                        <div class="form-control border-0" v-show="transaction.p_to_p_fee || transaction.p_to_p_fee == 0"> 
                                            {{ (formatDecimalNumber(transaction.receiver_amount - transaction.p_to_p_fee)) }} <span>HTG</span> <span class="text-muted text-xs text-italic"> Normal acc.</span>
                                         </div>
                                   </div>

                                   <div v-else>
                                        <div class="form-label">Amount to pay</div>
                                        <div class="form-control border-0"> {{ formatDecimalNumber(transaction.receiver_amount) }} 
                                            <span>RD$</span>
                                        </div>
                                        <div v-show="transaction.p_to_p_fee || transaction.p_to_p_fee == 0" class="text-center mt-2"><i class="fas fa-arrows-v"></i></div>
                                        <div class="form-label">Amount to receive</div>
                                        <div class="form-control border-0" v-show="transaction.p_to_p_fee || transaction.p_to_p_fee == 0"> {{ formatDecimalNumber(transaction.sender_amount - transaction.p_to_p_fee)  }}
                                            <span>HTG</span>
                                        </div>
                                   </div>

                                   <!-- <div v-show="transaction.p_to_p_fee" class="text-center mt-2"><i class="fas fa-arrows-v"></i></div> -->
                                   <!-- <div v-show="transaction.p_to_p_fee"> -->
                                    <!-- <label for="name" class="form-label mt-0">Amount minus {{ transaction.type }} fee </label> -->
                                    <!-- <div class="form-control border-0" v-show="transaction.operation == 'transfert_si'"> {{ transaction.receiver_amount - transaction.p_to_p_fee }}
                                        <div>Amount minus {{ transaction.type }} fee</div>
                                        <span v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">HTG</span>
                                    </div> -->
                                    <!-- <div class="form-control border-0" v-show="transaction.operation == 'reception_si'"> {{ transaction.sender_amount - transaction.p_to_p_fee  }}
                                        <span v-if="transaction.operation == 'reception' || transaction.operation == 'reception_si'">HTG</span>
                                    </div> -->
                                   <!-- </div> -->
                                   
                                    <!-- <input type="text" class="form-control" v-model="receiverAmount"> -->
                                    <!-- <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div>  -->
                                </div>

                                <div class="mb-3 col-md-6 text-capitalize">
                                <label for="account" class="form-label">Account Number</label>
                                <span class="text-xs text-success ml-1" v-if="accountTotalMonthlyTransactions && accountTotalMonthlyTransactions != 'no_transactions_yet'">{{ accountTotalMonthlyTransactions }} Transactions done</span>
                                <span class="text-xs text-success ml-1" v-if="accountTotalMonthlyTransactions == 'no_transactions_yet'">No transaction yet</span>
                                <Select2 v-model="form.account_id" :options="accountstransac"
                                    :settings="{ width: '100%', textTransform: 'uppercase' }" @select="onPhoneSelect"/>
                                <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div> 
                                <div v-if="errors.limit_reached" class="form-text error text-danger">{{ errors.limit_reached[0] }}</div>
                                </div>

                                <div class="text-center">
                                    <div v-if="errors.completed_transaction" class="form-text error text-danger">{{ errors.completed_transaction[0] }}</div> 
                                </div>

                                <div class="mb-3 col-md-12 ms-auto mt-3 text-left">
                                    <label for="comment" class="form-label">Notes</label>
                                    <select :class="['form-control border-bottom-0 outline-0 col-md-12 fw-bold shadow-none',
                                        form.comment_category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                                        form.comment_category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                                        form.comment_category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                        'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']" v-model="form.comment_category"  style="border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
                                        <option v-for="option in commentOptions" :value="option.value" :key="option.text">
                                        {{ option.text }}
                                        </option>
                                    </select>
                                    <textarea class="form-control border-top-0 rounded-top-0 shadow-none" v-model="form.comment" style="border-top-left-radius: 0px; border-top-right-radius: 0px;"></textarea>
                                </div>
                            </div>

                            <!-- <hr class="hr hr-blurry" /> -->

                            <div class="d-flex justify-content-center align-items-center mt-2">
                                <div>
                                    <button class="btn btn-sm btn-success" @click.prevent=completeTransaction(transaction.id) v-if="transaction.status == 'approved'" :disabled="sendButtonDisabled ? true : false">
                                        Send 
                                        <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                                        <span v-show="sendButtonDisabled" style="font-size: 10px;">Please wait while completing transaction...</span>
                                    </button>
                                    <button class="btn btn-sm btn-success" disabled v-else>Send
                                    </button>
                                </div>
                            </div>
                            
                        </form>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue';
import useUtils from "../../../services/utilsservices";
import useTransaction from "../../../services/transactionservices";
import useAccounts from "../../../services/accountservices.js";
import usePreventPageReload from '../../../services/pageReloadServices'
import Swal from "sweetalert2";

export default {
    props: ['transactioninfo', 'transaction', 'approvalData'],
    emits: ['completed-status'],
    setup(props, { emit }) {
        usePreventPageReload();
        const { formatDecimalNumber, } = useUtils();
        const { formatDate, updateTransactionStatusCompleted, getAccountsTransac, transaction, accountstransac, isLoading, isButtonLoading, sendButtonDisabled} = useTransaction();
        const { getAccountTotalMonthlyTransactions, accountTotalMonthlyTransactions } = useAccounts();
        const form = reactive({
            amount: props.transaction.receiver_amount,
            account_id: '',
            comment: '',
            comment_category: 'undefined',
            completed_status: 'completed',
        });
        const errors = ref([]);

        const commentOptions = ref([
        { text: 'Undefined', value: 'undefined' },
        { text: 'Important', value: 'important' },
        { text: 'Reminder', value: 'reminder' },
        ]);

        onMounted(async() => {
            const data = new FormData();
            data.append('type', props.transaction.type)
           //data.append('operation', props.transaction.operation)
            await getAccountsTransac(data);
        });

        const onPhoneSelect = async (eventPayload) => {
           await getAccountTotalMonthlyTransactions(form.account_id);
           // console.log(accountTotalMonthlyTransactions);
        }

        const completeTransaction = async (id) =>{
            try {
            sendButtonDisabled.value = true
            const data = new FormData()
            data.append('transaction_id', id);
            data.append('status', form.completed_status);
            data.append('amount', form.amount);
            data.append('account_id', form.account_id);
            data.append('transaction_operation', props.transaction.operation);
            data.append('comment', form.comment);
            data.append('comment_category', form.comment_category);
            await updateTransactionStatusCompleted(data);
            await emit('completed-status', transaction);
            isButtonLoading.value = false;
            sendButtonDisabled.value = false;
            } catch (error) {
                isButtonLoading.value = false;
                sendButtonDisabled.value = false;
                errors.value = error.response.data.errors;
                if (error.response.data.errors) {
                    if(error.response.data.errors.debt_not_allowed && error.response.data.errors.debt_not_allowed[0]){
                        Swal.fire({
                            title: error.response.data.message,
                            text: error.response.data.errors.debt_not_allowed[0],
                            position: 'center',
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                    if(error.response.data.errors.account_id && error.response.data.errors.account_id[0]){
                        Swal.fire({
                            icon: 'error',
                            text: 'Please check account first!',
                            icon: 'error',
                            showConfirmButton: true,
                        })
                    }
                    if(error.response.data.errors.completed_transaction && error.response.data.errors.completed_transaction[0]){
                        Swal.fire({
                            icon: 'error',
                            text: 'This transaction has already been completed!',
                            icon: 'error',
                            showConfirmButton: true,
                        })
                    }
                    if (error.response.data.errors.limit_reached && error.response.data.errors.limit_reached[0]) {
                        Swal.fire({
                            title: error.response.data.message,
                            text: error.response.data.errors.limit_reached[0],
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                    if(error.response.data.errors.transfert_si_undefined_issue && error.response.data.errors.transfert_si_undefined_issue[0]){
                        Swal.fire({
                            title: error.response.data.message,
                            text: error.response.data.errors.transfert_si_undefined_issue[0],
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                    if(error.response.data.errors.insufficient_account_balance && error.response.data.errors.insufficient_account_balance[0]){
                        Swal.fire({
                            title: error.response.data.message,
                            text: error.response.data.errors.insufficient_account_balance[0],
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                    if(error.response.data.errors.throw_execetion && error.response.data.errors.throw_execetion[0]){
                        Swal.fire({
                            text: error.response.data.errors.throw_execetion[0],
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
    
                }       
            }
        }

        return {
            props,
            form,
            formatDecimalNumber,
            formatDate,
            accountstransac,
            completeTransaction,
            isButtonLoading,
            commentOptions,
            errors,
            sendButtonDisabled,
            onPhoneSelect,
            accountTotalMonthlyTransactions
        }
    },
}
</script>