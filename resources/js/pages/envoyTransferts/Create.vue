<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <div class="row text-center m-2 align-items-center text-xs" v-if="user && user.user_account">
                        <div class="shadow-xl bg-gray-200 p-2 border border-orange-300 text-sm text-muted col d-flex justify-content-center align-items-center">
                            <div class="text-right">
                                <span class="text-xs">DEBT:</span> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.depts) }} RD$</span>

                                <div v-if="form.amount != 0 && form.amount != ''">
                                    <div>
                                        <i class="fa-solid fa-circle-down text-warning fw-bold"></i> <span class="fw-bold">{{ formatDecimalNumber(user.user_account.depts - form.amount) }} RD$</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- <h3 v-if="user && user.user_account" class="fw-bold">CURRENT DEBT: {{ formatDecimalNumber(user.user_account.depts) }} RD$</h3> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">

                            <!-- Expense tabs -->
                            <div class="mt-2 mb-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true" @click="makeUserAccountExpense">System account</button>
                                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false" @click="makeBankAccountExpense">Bank Account</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active p-3 pb-1 bg-light border border-light border-top-0"
                                        id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <!-- UseAcount tab -->
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="clientName" class="form-label">Amount</label>
                                                <input type="number" class="form-control" v-model="form.amount" required>
                                                <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div>
                                                <div v-if="isAmountLimitExceeded" class="form-text error text-danger"> {{ amountLimitdFeedbackMessage }}</div>
                                                <div v-if="errors.insufficient_system_funds" class="form-text error text-danger">{{ errors.insufficient_system_funds[0] }}</div>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="type" class="form-label text-gray-600">Currency</label>
                                                <select class="form-control" id="currency" v-model="form.currency" required>
                                                    <option v-for="option in currencyOptions" :value="option.value" :key="option.text">
                                                        {{ option.text }}
                                                    </option>
                                                </select>

                                                <div v-if="errors.currency" class="form-text error text-danger">{{ errors.currency[0] }}</div>
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label for="account" class="form-label">Envoy</label>
                                                <Select2 class="form-label" v-model="form.envoy_id" :options="envoys" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                                <div v-if="errors && errors.envoy_id" class="form-text error text-danger">{{ errors.envoy_id[0] }}</div>
                                            </div>

                                            <!-- <hr class="hr hr-blurry" /> -->

                                            <div class="mb-3 col-md-12">
                                                <label for="type" class="form-label text-gray-600">Note</label>
                                                <textarea class="form-control" placeholder="Add note" v-model="form.comment"></textarea>
                                                <div v-if="errors.comment" class="form-text error text-danger">{{ errors.comment[0] }}</div>
                                            </div>
                                        </div>
                                        <!-- UserAcoount End -->
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <!--  -->
                                    </div>
                                </div>
                            </div>
                            <!-- End expense tabs -->
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <button class="btn btn-primary mt-3 mr-3" @click.prevent="createTransfert" :disabled="isLoading || canTransfert == false || isAmountLimitExceeded ? true : false"> Submit
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
import { onMounted, reactive, ref, watch } from 'vue';
import useEnvoyDeposits from "../../services/envoydepositservices.js";
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import Swal from "sweetalert2";

export default {
    props: ['user'],
    setup(props) {
        const { storeEnvoyTransfert, isLoading, isButtonLoading, sendButtonDisabled } = useEnvoyDeposits();
        const { getUser, user, getEnvoys, envoys } = useUsers();
        const { formatDecimalNumber } = useUtils();
        const errors = ref([]);
        const showOption = ref(true);
        const isAmountLimitExceeded = ref(false);
        const amountLimitdFeedbackMessage = ref('');
        const canTransfert = ref(false);

        const form = reactive({
            bank_account: false,
            amount: '',
            currency: 'pesos',
            comment: '',
            transfert_type: 'user_account'
        });

        onMounted(async () => {
            await getUser(props.user.id);
            await getEnvoys();
        })

        const currencyOptions = ref([
            { text: 'RD$', value: 'pesos' },
        ]);

        const createTransfert = async () => {
            try {
                sendButtonDisabled.value = true;
                isButtonLoading.value = true;
                await storeEnvoyTransfert({
                    'amount': form.amount,
                    'currency': form.currency,
                    'type': form.type,
                    'comment': form.comment,
                    'envoy_id': form.envoy_id,
                    'transfert_type': form.transfert_type,
                })
            } catch (error) {
                sendButtonDisabled.value = false;
                isButtonLoading.value = false;
                isLoading.value = false;
                errors.value = error.response.data.errors;
                if (error.response.data.errors) {
                    if(error.response.data.errors.amount && error.response.data.errors.amount[0]){
                        Swal.fire({
                            text: error.response.data.errors.amount[0],
                            position: 'center',
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                }
            }
        }

        const checkAmountLimit = () => {
            if (form.amount >= user.value.user_account.depts) {
                form.amount = user.value.user_account.depts;
                isAmountLimitExceeded.value = true;
                amountLimitdFeedbackMessage.value = 'Max amount reached!';
                canTransfert.value = false;
                }else{
                isAmountLimitExceeded.value = false;
                amountLimitdFeedbackMessage.value = '';
                canTransfert.value = true;
            }
        };

        watch([() => form.amount], () => {
            checkAmountLimit();
        });

        return {
            form,
            errors,
            currencyOptions,
            createTransfert,
            isLoading,
            showOption,
            user,
            formatDecimalNumber,
            sendButtonDisabled,
            isButtonLoading,
            sendButtonDisabled,
            envoys,
            checkAmountLimit,
            isAmountLimitExceeded,
            amountLimitdFeedbackMessage,
            canTransfert
        };
    }
}
</script>