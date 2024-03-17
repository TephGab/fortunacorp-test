<template>
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid"> <Breadcrumb /></div>
            <div class="col-md-12">

                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent">
                        <nav class="mb-20 flex justify-center">
                            <ol class="flex">
                                <li class="relative w-[150px] text-center text-sm 
                    after:content-[''] after:absolute after:left-[50%] after:top-[200%] after:w-5 after:h-5
                    after:bg-indigo-500 after:rounded-full after:z-50">
                                    <span class="fw-bold"> INITIALIZED <i class="fas fa-check-circle text-primary"></i></span>
                                </li>
                                <li class="relative w-[150px] text-center text-sm 
                    before:content-[''] before:absolute before:left-[-50%] before:top-[calc(200%+0.5rem)] before:w-full before:h-1
                    after:bg-gray-300 before:bg-gray-300
                    after:content-[''] after:absolute after:left-[50%] after:top-[200%] after:w-5 after:h-5 after:rounded-full after:z-50"
                                    :class="{
                                        'stepFull':
                                            currentStep >= 1 && currentStep <= 3
                                    }">
                                    <span class="fw-bold" v-if="transaction.status == 'approved' || transaction.status == 'completed'">
                                        APPROVED <i class="fas fa-check-circle text-primary"></i>
                                    </span>
                                    <span class="fw-bold" v-else> APPROVATION </span>
                                </li>

                                <li class="relative w-[150px] text-center text-sm
                    before:content-[''] before:absolute before:left-[-50%] before:top-[calc(200%+0.5rem)] before:w-full before:h-1
                   after:bg-gray-300 before:bg-gray-300
                    after:content-[''] after:absolute after:left-[50%] after:top-[200%] after:w-5 after:h-5 after:bg-gray-300 after:rounded-full after:z-50"
                                    :class="{
                                        'stepFull':
                                            currentStep >= 2 && currentStep <= 3
                                    }">
                                    <span class="fw-bold" v-if="transaction.status == 'completed'"> 
                                        COMPLETED <i class="fas fa-check-circle text-primary"></i></span>
                                    <span class="fw-bold" v-else> COMPLETION </span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- components dynamiques -->
                        <component @approved-status="refresh" @disapproved-status="disapprovedRefresh" @completed-status="completedRefresh" :transactioninfo="transactioninfo" :transaction="transaction"
                            v-bind:is="formSteps[currentStep]" v-bind:approvalData="approvalData">
                        </component>

                    </div>

                    <div class="card-footer border-transparent">
                        <div class="py-3 flex items-center justify-between" v-if="currentStep < 3">
                            <button type="submit"
                                class="btn btn-sm border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                v-on:click="previousStep" v-if="currentStep > 0">
                                <i class="fas fa-chevron-circle-left mt-1 mr-1"></i>Previous
                            </button>

                            <button type="submit" v-show="currentStep < 2"
                                class="btn btn-sm border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                v-if="transaction.status != 'pending' && transaction.status != 'disapproved'" v-on:click="nextStep">
                                Next
                                <i class="fas fa-chevron-circle-right mt-1"></i>
                            </button>
                            <router-link v-show="currentStep == 2 || transaction.status == 'disapproved'" class="btn btn-sm border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                            class-active="active" :to="{ name: 'transaction.index'}">
                                <p> Finish </p>
                            </router-link>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
  
<script>
import { onMounted, reactive, ref } from 'vue';
import useUtils from "../../services/utilsservices";
import useTransactions from "../../services/transactionservices";

import FirstStepVue from './approvalsteps/FirstStep.vue';
import SecondStepVue from './approvalsteps/SecondStep.vue';
import ThirdStepVue from './approvalsteps/ThirdStep.vue';
import FinalStepVue from './approvalsteps/FinalStep.vue';
import useForm from './../../composable/form.js';

export default {
    props: ['id'],
    setup(props) {
        const { formatDecimalNumber } = useUtils();
        const { getTransaction, transaction, getTransactionInfo, transactioninfo, isButtonLoading, setViewTransaction } = useTransactions();
        const { approvalData } = useForm();
        const currentStep = ref(0);

        const formSteps = [
            FirstStepVue,
            SecondStepVue,
            ThirdStepVue,
            FinalStepVue,
        ];
        const nextStep = () => {
            currentStep.value++;
        }
        const previousStep = () => {
            currentStep.value--;
        }

        const completedRefresh = async () => {
            await getTransaction(props.id);
            const data = new FormData()
            data.append('transaction_id', transaction.value.id)
            data.append('user_id', transaction.value.user_id)
            data.append('client_id', transaction.value.client_id)
            data.append('sender_id', transaction.value.sender)
            data.append('receiver_id', transaction.value.receiver)
            data.append('approver_id', transaction.value.approved_by)
            data.append('concluder_id', transaction.value.completed_by)
            data.append('account_id', transaction.value.account_id)
            await getTransactionInfo(data);
            await nextStep();
        }

        const refresh = () => {
            getTransaction(props.id);
            nextStep();
        }

        const disapprovedRefresh = () => {
            getTransaction(props.id);
        }

        onMounted(async () => {
            await setViewTransaction({'transaction_id': props.id});
            await getTransaction(props.id);
            if(transaction.value.status == 'approved'){
                currentStep.value = 1;
            }
            if(transaction.value.status == 'disapproved'){
                currentStep.value = 0;
            }
            if(transaction.value.status == 'completed'){
                currentStep.value = 2;
            }
            const data = new FormData()
            data.append('transaction_id', transaction.value.id)
            data.append('user_id', transaction.value.user_id)
            data.append('client_id', transaction.value.client_id)
            data.append('sender_id', transaction.value.sender)
            data.append('receiver_id', transaction.value.receiver)
            data.append('approver_id', transaction.value.approved_by)
            data.append('concluder_id', transaction.value.completed_by)
            data.append('account_id', transaction.value.account_id)
            await getTransactionInfo(data);
        });

        return {
            formSteps,
            currentStep,
            nextStep,
            previousStep,
            formatDecimalNumber,
            transaction,
            transactioninfo,
            isButtonLoading,
            approvalData,
            refresh,
            completedRefresh,
            disapprovedRefresh
        }
    },
}
</script>