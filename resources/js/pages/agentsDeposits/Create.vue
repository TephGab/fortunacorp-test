<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <h3 v-if="user && user.user_account" class="fw-bold text-xs">RECHARGE</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">

                            <!-- Expense tabs -->
                            <div class="mt-2 mb-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true" @click="makeUserAccountDeposit">System account</button>
                                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false" @click="makeBankAccountDeposit">Bank Account</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <!-- UseAcount tab -->
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="clientName" class="form-label">Amount</label>
                                                <input type="number" class="form-control" v-model="form.dept_amount_deposit">
                                                <div v-if="errors && errors.dept_amount_deposit" class="form-text error text-danger">{{ errors.dept_amount_deposit[0] }}</div>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="type" class="form-label text-gray-600">Currency</label>
                                                <select class="form-control" id="currency" v-model="form.currency" required>
                                                    <option v-for="option in currencyOptions" :value="option.value" :key="option.text">
                                                        {{ option.text }}
                                                    </option>
                                                </select>

                                                <div v-if="errors.currency" class="form-text error text-danger">{{  errors.currency[0] }}</div>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label for="account" class="form-label">Envoy</label>
                                                <Select2 class="form-label" v-model="form.envoy_id" :options="envoys" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                <div v-if="errors && errors.envoy_id" class="form-text error text-danger">{{ errors.envoy_id[0] }}</div>
                                            </div>

                                            <!-- <hr class="hr hr-blurry"/> -->

                                            <div class="mb-3 col-md-12">
                                                <label for="type" class="form-label text-gray-600">Note</label>
                                                <textarea class="form-control" placeholder="Add note" v-model="form.comment"></textarea>
                                                <div v-if="errors.comment" class="form-text error text-danger">{{ errors.comment[0] }}</div>
                                            </div>
                                        </div>
                                        <!-- UserAcoount End -->
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    </div>
                                </div>
                            </div>
                            <!-- End expense tabs -->
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <button class="btn btn-primary mt-3 mr-3" v-show="canDeposit" @click.prevent="createAgentDeposit()" :disabled="isButtonLoading || canDeposit == false ? true : false"> Submit
                            <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
                            <span v-show="isButtonLoading" style="font-size: 10px;">Please wait ...</span>
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
import { onMounted, reactive, ref } from 'vue';
import useAgentDeposits from "../../services/agentdepositsservices";
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";

export default {
    props: ['user'],
    setup(props) {
        const { storeAgentDeposit, checkPendingDeposit, pendingDeposit } = useAgentDeposits();
        const { getUser, user, getEnvoys, envoys } = useUsers();
        const { formatDecimalNumber, isLoading, sendButtonDisabled, isButtonLoading } = useUtils();
        const errors = ref([]);
        const depositType = ref('user_account');
        const canDeposit = ref(false);

        const form = reactive({
            bank_account: false,
            dept_amount_deposit: '',
            envoy_id: '',
            currency: 'pesos',
            comment: '',
            deposit_type: 'user_account',
            deposit_status: 'pending',
            deposit_user_account_id: '',
            deposit_system_account_id: '',
            deposit_currency: 'pesos',
            user_bank_account_id: '',
            system_bank_account_id: '',
        });

        onMounted(async () => {
            await getUser(props.user.id);
            await getEnvoys();
            await checkPendingDeposit(props.user.id)
            await pendingDeposit.value ? canDeposit.value = false: canDeposit.value = true;
        })

        const currencyOptions = ref([
            { text: 'RD$', value: 'pesos' },
        ]);

        const createAgentDeposit = async () => {
        try {
            isButtonLoading.value = true;
            await storeAgentDeposit({
                'deposit_type': depositType.value,
                'dept_amount_deposit': form.dept_amount_deposit,
                'deposit_status': form.deposit_status,
                'deposit_user_account_id': form.deposit_user_account_id,
                'deposit_system_account_id': form.deposit_system_account_id,
                'user_bank_account_id': form.user_bank_account_id,
                'system_bank_account_id': form.system_bank_account_id,
                'deposit_currency': form.deposit_currency,
                'envoy_id': form.envoy_id,
            });
            await checkPendingDeposit(props.user.id)
            await pendingDeposit.value ? canDeposit.value = false: canDeposit.value = true;
            isButtonLoading.value = false;
        } catch (error) {
            errors.value = error.response.data.errors;
            isButtonLoading.value = false;
        }
        }

        const makeUserAccountDeposit = () => {
            depositType.value = 'user_account';
        }

        const makeBankAccountDeposit = () => {
            depositType.value = 'user_bank_account';
        }

        return {
            form,
            errors,
            currencyOptions,
            createAgentDeposit,
            isLoading,
            user,
            formatDecimalNumber,
            sendButtonDisabled,
            isButtonLoading,
            sendButtonDisabled,
            envoys,
            canDeposit,
            makeUserAccountDeposit,
            makeBankAccountDeposit
        };
    }
}
</script>