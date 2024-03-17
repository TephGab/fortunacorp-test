<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <!-- <h3 class="card-title">Cashout details</h3> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <!-- Deposit tabs -->
                                <div class="mt-2 mb-3">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true" @click="makeUserAccountDeposit">System account</button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false" @click="makeBankAccountDeposit">Bank Account</button>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <div class="row">
                                                <div class="mb-3 col-sm-12 col-md-6">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control" v-model="form.amount">
                                                <div v-if="errors.amount" class="form-text error text-danger">
                                                    {{ errors.amount[0] }}</div>
                                                </div>

                                                <div class="mb-3 col-sm-12 col-md-6">
                                                    <label for="account" class="form-label">Envoy</label>
                                                    <Select2 class="form-label" v-model="form.envoy_id" :options="envoys" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                    <div v-if="errors.envoy_id" class="form-text error text-danger">{{ errors.envoy_id[0] }}</div>
                                                </div>
                                                <div v-if="errors.deposit_in_process" class="form-text error text-danger">{{ errors.deposit_in_process[0] }}</div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                            aria-labelledby="nav-profile-tab">
                                            <div class="mb-3 col-md-12 text-uppercase">
                                                <label for="account" class="form-label">My Accounts</label>
                                                <Select2 class="form-label" v-model="form.user_bank_account_id"
                                                    :options="userBankAccountDeposits"
                                                    :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                <div v-if="errors.user_bank_account_id" class="form-text error text-danger">
                                                    {{ errors.user_bank_account_id[0] }}</div>
                                            </div>

                                            <div class="mb-3 col-md-12 text-uppercase">
                                                <label for="account" class="form-label">Fortuna Accounts</label>
                                                <Select2 class="form-label" v-model="form.system_bank_account_id"
                                                    :options="systemBankAccountDeposits"
                                                    :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                <div v-if="errors.system_bank_account_id" class="form-text error text-danger">{{ errors.system_bank_account_id[0] }}</div>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control"
                                                    v-model="form.amount">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button class="btn btn-warning btn-sm mt-2 mr-2" @click="editAgentDeposit(agentDeposit.id)" :disabled="buttonIsDisabled ? true : false">Update</button>
                                            <button class="btn btn-secondary btn-sm mt-2" :disabled="buttonIsDisabled ? true : false">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- End deposit tabs -->
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <!-- <button class="btn btn-success" @click.prevent=completeUserCashout(cashout.id) :disabled="cashout.status == 'pending' ? false : true"> Send </button> -->
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
import useUsers from "../../services/usersservices";
import useTransactions from "../../services/transactionservices";
import useUtils from "../../services/utilsservices";
import useAgentDeposits from "../../services/agentdepositsservices";
import Swal from "sweetalert2";

export default {
    props: ['id', 'user', 'userrole'],
    setup(props) {
        const { getUserAccount, userAccount, getUser, user, getUserBankAccountDeposit, userBankAccountDeposits, getSystemBankAccountDeposit,
                systemBankAccountDeposits, getEnvoys, envoys, isLoading } = useUsers();
        const { formatDate } = useTransactions();
        const { formatDecimalNumber, buttonIsDisabled, formatDateCalendar } = useUtils();
        const { getAgentDepositDetails, agentDeposit, updateAgentDeposit } = useAgentDeposits();
        const errors = ref([]);
        const depositType = ref('user_account');

        const form = reactive({
            amount: '',
            deposit_status: 'pending',
            deposit_user_account_id: '',
            deposit_system_account_id: '',
            deposit_currency: 'pesos',
            user_bank_account_id: '',
            system_bank_account_id: '',
            envoy_id: '',
        });

        onMounted(async () => {
            await getAgentDepositDetails({'deposit_id': props.id});
            if(agentDeposit.value.envoy_id){
                form.amount = agentDeposit.value.amount;
                form.envoy_id = agentDeposit.value.envoy_id.toString();
            }
            await getUser(props.user.id);
            await getEnvoys();
            if (props.user.id != 1) {
                await getUserAccount(props.user.id);
            }
            await getUserBankAccountDeposit();
            await getSystemBankAccountDeposit();
            await Echo.private('agentdeposit')
                .listen('AgentDepositEvent', (e) => {
                    getAgentDeposits();
                })
        })

        const editAgentDeposit = async (id) => {
            try {
                buttonIsDisabled.value = true;
                const data = new FormData();
                data.append('deposit_id', id);
                data.append('deposit_type', depositType.value);
                data.append('amount', form.amount);
                data.append('envoy_id', form.envoy_id);
                data.append('agent_id', props.user.id);
                await updateAgentDeposit(data);
                buttonIsDisabled.value = false;
            } catch (error) {
                errors.value = error.response.data.errors;
                buttonIsDisabled.value = false;
                if(error.response.data.errors){
                if (error.response.data.errors.deposit_in_process[0]) {
                        Swal.fire({
                        text: 'Deposit is already in process! contact administrator',
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

        const makeUserAccountDeposit = () => {
            depositType.value = 'user_account';
        }

        const makeBankAccountDeposit = () => {
            depositType.value = 'user_bank_account';
        }

        return {
            user,
            props,
            agentDeposit,
            userAccount,
            formatDate,
            form,
            errors,
            formatDecimalNumber,
            editAgentDeposit,
            userBankAccountDeposits,
            systemBankAccountDeposits,
            makeUserAccountDeposit,
            makeBankAccountDeposit,
            envoys,
            buttonIsDisabled,
            formatDateCalendar,
            isLoading
        };
    }
}
</script>