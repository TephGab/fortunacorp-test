<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <h3 class="card-title">Replenishment</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">

                            <!-- Replenishment tabs -->
                            <div class="mt-2 mb-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                            aria-selected="true" @click="makeUserAccountReplenishment">System
                                            account</button>
                                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-profile" type="button" role="tab"
                                            aria-controls="nav-profile" aria-selected="false"
                                            @click="makeBankAccountReplenishment">Bank Account</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0"
                                        id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <!-- UseAcount tab -->
                                        <div class="col-md-12 text-center">

                                            <div class="d-flex justify-content-center align-items-center text-capitalize">
                                                <div class="mb-3 mt-1 col-md-5">
                                                    <label for="account" class="form-label">Select Agent</label>
                                                    <Select2 class="form-label" v-model="form.agent_id" :options="agents"
                                                        :settings="{ width: '100%', textTransform: 'uppercase' }"
                                                        :onSelect="getAgentDetails(form.agent_id)" />
                                                    <div v-if="errors.agent_id" class="form-text error text-danger"> {{ errors.agent_id[0] }}</div>
                                                </div>
                                            </div>

                                            <hr class="hr hr-blurry" />

                                            <div class="d-flex justify-content-space-between align-items-center">
                                                <div class="mb-3 col-md-6">
                                                    <label for="clientName" class="form-label">Amount</label>
                                                    <input type="number" class="form-control" v-model="form.amount" required>
                                                    <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="type" class="form-label text-gray-600">Currency</label>
                                                    <select class="form-control" id="currency" v-model="form.currency">
                                                        <option v-for="option in currencyOptions" :value="option.value"
                                                            :key="option.text">
                                                            {{ option.text }}
                                                        </option>
                                                    </select>
                                                    <div v-if="errors.currency" class="form-text error text-danger">{{ errors.currency[0] }}</div>
                                                </div>
                                            </div>

                                            <hr class="hr hr-blurry" />

                                            <div
                                                class="d-flex flex-column justify-content-space-bettween align-items-center">
                                                <div class="mb-3 mt-1 col-md-5" v-show="!form.bank_account">
                                                    <label for="account" class="form-label">Select envoy</label>
                                                    <Select2 class="form-label" v-model="form.envoy_id" :options="envoys"
                                                        :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                    <div v-if="errors.envoy_id" class="form-text error text-danger">{{ errors.envoy_id[0] }}</div>
                                                </div>

                                                <div class="mb-3 mt-1 col-md-5" v-if="form.bank_account">
                                                    <label for="account" class="form-label">Select agent bank
                                                        account</label>
                                                    <select class="form-control">
                                                        <option v-for="bank_account in agent.user_bank_accounts"
                                                            :key="bank_account.id">
                                                            {{ bank_account.bank_name }}
                                                        </option>
                                                    </select>
                                                    <!-- <Select2 class="form-label" v-model="form.envoy_id" :options="agent.user_bank_account"
                                        :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                    <div v-if="errors.envoy_id" class="form-text error text-danger">{{
                                        errors.envoy_id[0] }}</div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- UserAcoount End -->
                                        <!-- <button class="btn btn-warning btn-sm mt-2 mr-2" @click="createAgentDeposit(userAccount.depts)">Send</button>
              <button class="btn btn-secondary btn-sm mt-2" @click="depositInputHide">Cancel</button> -->
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <!-- <div class="mb-3 col-md-12 text-uppercase">
                    <label for="account" class="form-label">My Accounts</label>
                    <Select2 class="form-label" v-model="form.user_bank_account_id" :options="userBankAccountDeposits"
                      :settings="{ width: '100%', textTransform: 'uppercase' }" />
                    <div v-if="errors.user_bank_account_id" class="form-text error text-danger">{{
                      errors.user_bank_account_id[0] }}</div>
                  </div> -->

                                        <!-- <div class="mb-3 col-md-12 text-uppercase">
                    <label for="account" class="form-label">Fortuna Accounts</label>
                    <Select2 class="form-label" v-model="form.system_bank_account_id" :options="systemBankAccountDeposits"
                      :settings="{ width: '100%', textTransform: 'uppercase' }" />
                    <div v-if="errors.system_bank_account_id" class="form-text error text-danger">{{
                      errors.system_bank_account_id[0] }}</div>
                  </div> -->

                                        <!-- <div class="mb-3 col-md-12">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" v-model="form.dept_amount_deposit">
                  </div> -->
                                    </div>

                                    <!-- <div class="col-md-12">
                  <button class="btn btn-warning btn-sm mt-2 mr-2" @click="createAgentDeposit(userAccount.depts)"
                    :disabled="buttonIsDisabled || pendingDeposit['pendingDepositCount'] ? true : false">Send</button>
                  <button class="btn btn-secondary btn-sm mt-2" @click="depositInputHide"
                    :disabled="buttonIsDisabled || pendingDeposit['pendingDepositCount'] ? true : false">Cancel</button>
                </div> -->
                                </div>
                            </div>
                            <!-- End replenishment tabs -->




                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <!-- <button class="btn btn-success" @click.prevent="createReplenishment"> Replenish </button> -->
                        <button class="btn btn-success" @click.prevent="createReplenishment" :disabled="isLoading ? true : false">
                            Replenish
                            <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
                            <span v-show="isLoading" style="font-size: 10px;">Please wait ...</span>
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
import useReplenishments from "../../services/replenishmentservices";
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';
import { useRoute } from 'vue-router';

export default {
    props: ['user', 'agentid', 'amount', 'reqreplenishmentid'],
    emits: ['select'],
    setup(props, { emit }) {
        const { getReplenishments, replenishments, isLoading, storeReplenishment } = useReplenishments();
        const { getEnvoys, envoys, getAgents, agents, getAgent, agent } = useUsers();
        const { formatDecimalNumber, formatDate } = useUtils();
        const { can, cannot } = useAbility();
        const replenishmentType = ref('user_account');
        const route = useRoute();

        const errors = ref([]);
        const isAreqReplenish = ref(props.agentid == '0' ? false : true);

        const form = reactive({
            envoy_id: '8',
            agent_id: props.agentid == '0' ? '11' : props.agentid,
            bank_account: false,
            amount: props.amount == '0' ? '' : props.amount,
            currency: 'pesos',
        });

        onMounted(async () => {
            await getAgents();
            await getEnvoys();
        });

        const getAgentDetails = (id) => {
            getAgent({ 'agent_id': id });
        };

        const currencyOptions = ref([
            { text: 'PESOS', value: 'pesos' },
            // { text: 'US', value: 'us' },
            // { text: 'HTG', value: 'htg' },
        ]);

        const createReplenishment = async () => {
            try {
                await storeReplenishment({
                    'envoy_id': form.envoy_id,
                    'agent_id': form.agent_id,
                    'amount': form.amount,
                    'currency': form.currency,
                    'replenishment_type': replenishmentType.value,
                    'req_replenishment_id': props.reqreplenishmentid,
                    'isAreqReplenish': isAreqReplenish.value,
                    // 'req_agent_id': props.agentid,
                    // 'req_amount': props.amount,
                })
            } catch (error) {
                errors.value = error.response.data.errors;
                isLoading.value = false;
            }
        }

        const makeUserAccountReplenishment = () => {
            replenishmentType.value = 'user_account';
           // console.log(replenishmentType.value)
        }

        const makeBankAccountReplenishment = () => {
            replenishmentType.value = 'user_bank_account';
           // console.log(replenishmentType.value)
        }

        return {
            form,
            envoys,
            agents,
            errors,
            currencyOptions,
            createReplenishment,
            getAgent,
            agent,
            getAgentDetails,
            makeBankAccountReplenishment,
            makeUserAccountReplenishment,
            isLoading
        };
    }
}
</script>