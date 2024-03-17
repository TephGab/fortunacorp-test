<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <!-- <h3 class="card-title">Send Money</h3> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">

                            <!-- Send Money tabs -->
                            <div class="mt-2 mb-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                            aria-selected="true" @click="makeUserAccountSendMoney">System
                                            account</button>
                                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-profile" type="button" role="tab"
                                            aria-controls="nav-profile" aria-selected="false"
                                            @click="makeBankAccountSendMoney">Bank Account</button>
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
                                                    <div v-if="errors && errors.agent_id"
                                                        class="form-text error text-danger"> {{ errors.agent_id[0] }}</div>
                                                </div>
                                            </div>

                                            <hr class="hr hr-blurry" />

                                            <div class="row text-center">
                                                <div class="mb-3 col-md-6 col-sm-12">
                                                    <label for="clientName" class="form-label">Amount</label>
                                                    <input type="number" class="form-control" v-model="form.amount"
                                                        required>
                                                    <div v-if="errors && errors.amount" class="form-text error text-danger">
                                                        {{ errors.amount[0] }}</div>
                                                </div>
                                                <div class="mb-3 col-md-6 col-sm-12">
                                                    <label for="type" class="form-label text-gray-600">Currency</label>
                                                    <select class="form-control" id="currency" v-model="form.currency">
                                                        <option v-for="option in currencyOptions" :value="option.value"
                                                            :key="option.text">
                                                            {{ option.text }}
                                                        </option>
                                                    </select>
                                                    <div v-if="errors && errors.currency"
                                                        class="form-text error text-danger">{{ errors.currency[0] }}</div>
                                                </div>
                                            </div>

                                            <hr class="hr hr-blurry" />

                                            <div
                                                class="d-flex flex-column justify-content-space-bettween align-items-center">
                                                <!-- <div class="mb-3 mt-2 col-md-6">
                                                    <label for="account" class="form-label mr-2"> Deduct from envoy debt ?
                                                    </label>
                                                    <input type="checkbox" id="checkbox" v-model="form.use_envoy_debt" />
                                                </div> -->
                                                <div class="mb-3 mt-1 col-md-6" v-show="!form.bank_account">
                                                    <label for="account" class="form-label">Select envoy</label>
                                                    <Select2 class="form-label" v-model="form.envoy_id" :options="envoys"
                                                        :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                    <div v-if="errors && errors.envoy_id"
                                                        class="form-text error text-danger">{{ errors.envoy_id[0] }}</div>
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

                                                </div>
                                            </div>
                                        </div>
                                        <!-- UserAcoount End -->
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <!--  -->
                                    </div>
                                </div>
                            </div>
                            <!-- End replenishment tabs -->




                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <!-- <button class="btn btn-success" @click.prevent="createSendMoney"> Replenish </button> -->
                        <button class="btn btn-success" @click.prevent="createSendMoney"
                            :disabled="isLoading ? true : false">
                            Send
                            <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
                                alt="button-spinner" />
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
import useSendMoney from "../../services/sendmoneyservices";
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';
import { useRoute } from 'vue-router';

export default {
    props: ['user', 'agentid', 'amount', 'reqreplenishmentid'],
    emits: ['select'],
    setup(props, { emit }) {
        const { isLoading, storeSendMoney } = useSendMoney();
        const { getEnvoys, envoys, getAgents, agents, getAgent, agent } = useUsers();
        const { formatDecimalNumber, formatDate } = useUtils();
        const { can, cannot } = useAbility();
        const sendMoneyType = ref('user_account');
        const route = useRoute();

        const errors = ref([]);
        const isAreqReplenish = ref(props.agentid == '0' ? false : true);

        const form = reactive({
            envoy_id: '154',
            agent_id: '',
            // agent_id: props.agentid == '0' ? '' : props.agentid,
            bank_account: false,
            amount: '',
            // amount: props.amount == '0' ? '' : props.amount,
            currency: 'pesos',
            use_envoy_debt: true
        });

        onMounted(async () => {
            await getAgents();
            await getEnvoys();
        });

        const getAgentDetails = (id) => {
            getAgent({ 'agent_id': id });
        };

        const currencyOptions = ref([
            { text: 'RD$', value: 'pesos' },
            // { text: 'US', value: 'us' },
            // { text: 'HTG', value: 'htg' },
        ]);

        const createSendMoney = async () => {
            try {
                isLoading.value = true;
                // buttonIsDisabled.value = true;
                await storeSendMoney({
                    'envoy_id': form.envoy_id,
                    'agent_id': form.agent_id,
                    'amount': form.amount,
                    'currency': form.currency,
                    'send_money_type': sendMoneyType.value,
                    'use_envoy_debt': form.use_envoy_debt,
                })
            } catch (error) {
                isLoading.value = false;
                errors.value = error.response.data.errors;
            }
        }

        const makeUserAccountSendMoney = () => {
            sendMoneyType.value = 'user_account';
        }

        const makeBankAccountSendMoney = () => {
            sendMoneyType.value = 'user_bank_account';
        }

        return {
            form,
            envoys,
            agents,
            errors,
            currencyOptions,
            createSendMoney,
            getAgent,
            agent,
            getAgentDetails,
            makeBankAccountSendMoney,
            makeUserAccountSendMoney,
            isLoading
        };
    }
}
</script>