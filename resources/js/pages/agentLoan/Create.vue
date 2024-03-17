<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <h3 class="card-title">New agent loan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">

                            <!-- Loan -->
                                <div class="col-md-12 text-center">

                                            <div class="d-flex justify-content-center align-items-center text-capitalize">
                                                <div class="mb-3 mt-1 col-md-5">
                                                    <label for="account" class="form-label">Select Agent</label>
                                                    <!-- <Select2 class="form-label" v-model="form.agent_id" :options="agents" :settings="{ width: '100%', textTransform: 'uppercase' }" :onSelect="getAgentDetails(form.agent_id)" /> -->
                                                    <Select2 class="form-label" v-model="form.agent_id" :options="agents" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                    <div v-if="errors && errors.agent_id" class="form-text error text-danger"> {{ errors.agent_id[0] }}</div>
                                                </div>
                                            </div>

                                            <hr class="hr hr-blurry" />

                                            <div class="d-flex flex-column justify-content-space-bettween align-items-center">
                                                <div class="mb-3 col-md-6 col-sm-12">
                                                    <label for="clientName" class="form-label">Amount</label>
                                                    <input type="number" class="form-control" v-model="form.amount" required>
                                                    <div v-if="errors && errors.amount" class="form-text error text-danger"> {{ errors.amount[0] }}</div>
                                                </div>
                                            </div>

                                            <hr class="hr hr-blurry" />

                                            <div class="d-flex flex-column justify-content-space-bettween align-items-center">
                                               
                                                <div class="mb-3 col-md-6 col-sm-12">
                                                    <label for="clientName" class="form-label">Percentage</label>
                                                    <div class="text-orange-500 form-text"><span class="fw-bold">{{ form.percentage }}%</span> represents the remaining portion of the commission allocated to selected agent on each transaction
                                                        <span><span class="fw-bold">{{ 100 - form.percentage }}%</span> will be used for debt repayment</span>
                                                    </div>
                                                    <input type="number" class="form-control" v-model="form.percentage" required :disabled="showPercentageInput ? false : true">
                                                    <div v-if="debtNotice" class="form-text error text-orange"> {{ debtNotice }}</div>
                                                    <div v-if="errors && errors.percentage" class="form-text error text-danger"> {{ errors.percentage[0] }}</div>
                                                </div>

                                            </div>
                                </div>
                            <!-- End loan -->

                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <button class="btn btn-success" @click.prevent="createLoan" :disabled="isLoading ? true : false"> Send
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
import { onMounted, ref, reactive, watch } from 'vue';
import useAgentLoan from "../../services/agentloanservices";
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';

export default {
    props: ['user', 'userrole'],
    emits: ['select'],
    setup(props, { emit }) {
        const { storeAgentLoan } = useAgentLoan();
        const { getAgents, agents, getAgent, agent } = useUsers();
        const { formatDecimalNumber, formatDate, isLoading } = useUtils();
        const { can, cannot } = useAbility();
        const showPercentageInput = ref(false);
        const debtNotice = ref();

        const errors = ref([]);

        const form = reactive({
            agent_id: '',
            amount: '',
            currency: 'pesos',
            percentage: '0',
        });

        onMounted(async () => {
            await getAgents();
        });

        const getAgentDetails = async (id) => {
            await getAgent({ 'agent_id': id});
            if (agent.value[0].user_account.depts && agent.value[0].user_account.depts > 0) {
                form.percentage = agent.value[0].loan_percentage;
                debtNotice.value = 'Selected agent has unpaid loan'
                showPercentageInput.value = false;
            } else {
                form.percentage = '0';
                debtNotice.value = '';
                showPercentageInput.value = true;
            }
        };

        const currencyOptions = ref([
            { text: 'RD$', value: 'pesos' },
        ]);

        const createLoan = async () => {
            try {
                isLoading.value = true;
                await storeAgentLoan({
                    'agent_id': form.agent_id,
                    'amount': form.amount,
                    'currency': form.currency,
                    'loan_type': form.loan_type,
                    'percentage': form.percentage,
                });
            } catch (error) {
                isLoading.value = false;
                errors.value = error.response.data.errors;
            }
        }

        watch([() => form.agent_id], async (values, oldValue) => {
        // values is an array containing the current values of watched properties
        // oldValue is an object containing the old values of watched properties
            const newValue = values[0]; // Extract the new value of form.agent_id
            if (newValue !== null) {
                await getAgentDetails(newValue);
            }
        });

        return {
            form,
            agents,
            errors,
            currencyOptions,
            createLoan,
            getAgent,
            agent,
            getAgentDetails,
            isLoading,
            showPercentageInput,
            debtNotice
        };
    }
}
</script>