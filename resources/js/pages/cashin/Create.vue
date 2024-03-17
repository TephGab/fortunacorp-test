<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3">
        <!--  -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <h3 class="card-title">New provider</h3>
          </div>

          <!-- /.card-header -->
          <div class="card-body">
            <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <h5 class="fw-bold">PROVIDERS INFO</h5>
                </div>

                <div class="mb-3 col-md-6 ms-auto">
                  <label for="phone" class="form-label">Phone Number</label>
                  <vue-tel-input v-model="form.provider_phone" :inputOptions="{ placeholder: 'Ex: 34000000' }"
                    mode="international" :onlyCountries="['ht', 'do']"
                    @keyup="checkProvider(form.provider_phone)"></vue-tel-input>
                  <div v-if="errors.provider_phone" class="form-text error text-danger">{{ errors.provider_phone[0] }}
                  </div>
                </div>

                <div class="mb-3 col-md-6" v-show="form.provider_phone.length > 14">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" v-model="form.provider_name"
                    :disabled="isProviderExist ? 'disabled' : 0" required>
                  <div v-if="errors.provider_name" class="form-text error text-danger">{{ errors.provider_name[0] }}</div>
                </div>

                <div class="mb-3 col-md-6" v-show="form.provider_phone.length > 14">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" v-model="form.provider_email"
                    :disabled="isProviderExist ? 'disabled' : 0" required>
                  <div v-if="errors.provider_email" class="form-text error text-danger">{{ errors.provider_email[0] }}
                  </div>
                </div>

                <div class="mb-3 col-md-6" v-show="form.provider_phone.length > 14">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" v-model="form.provider_address"
                    :disabled="isProviderExist ? 'disabled' : 0" required>
                  <div v-if="errors.provider_address" class="form-text error text-danger">{{ errors.provider_address[0] }}
                  </div>
                </div>
              </div>
              <hr class="hr hr-blurry" />
              <div class="row">

                <div class="mb-3 col-md-12">
                  <h5 class="fw-bold">ACCOUNT DETAILS</h5>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="type" class="form-label">Type</label>
                  <select class="form-control" id="type" v-model="form.type" @change="enableSenderAmount(form.type)"
                    required>
                    <option v-for="option in options" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                  <div v-if="errors.type" class="form-text error text-danger">{{ errors.type[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="operation" class="form-label">Operation</label>
                  <div class="mb-3 col-md-12">
                    <div class="mb-3 col-md-12">

                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="operation" id="reception" value="reception"
                          v-model="form.operation" @change="enableSenderAmount(form.operation)" required>
                        <label class="form-check-label" for="reception">Entree</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="operation" id="receptionsi"
                          value="reception_si" v-model="form.operation" @change="enableSenderAmount(form.operation)"
                          required>
                        <label class="form-check-label" for="reception">Entree Si</label>
                      </div>
                    </div>

                    <div v-if="errors.operation" class="form-text error text-danger">{{ errors.operation[0] }}</div>
                  </div>
                </div>

                <div class="mb-3 col-md-6 text-capitalize">
                  <label for="account" class="form-label">Account Number</label>
                  <Select2 class="form-label" v-model="form.account_id" :options="accountscashin" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                  <div v-if="errors.account_id" class="form-text error text-danger">{{ errors.account_id[0] }}</div>
                </div>


                <div class="mb-3 col-md-12">
                  <label for="amount" class="form-label">Sender amount</label>
                  <div class="d-flex justify-content-between">

                    <!-- Transfert -->
                    <div class="input-group">
                      <input type="number" class="form-control border-right-0 rounded-start" id="amount"
                        v-model="form.amount" @keyup="cachinHtgToPesos(form.amount, form.operation, form.type)"
                        :disabled="senderAmountIsEnabled ? 0 : 'disabled'" required>
                      <span class="input-group-addon">
                        <span class="form-control border-left-0 bg-light rounded-end">HTG</span>
                      </span>
                    </div>
                    <!--  -->

                    <i class="fas fa-arrow-right ml-1 mr-1 mt-2"></i>

                    <!-- Reception -->
                    <div class="input-group">
                      <input type="number" class="form-control border-right-0 rounded-start" id="amount"
                        v-model="receiverAmount" disabled />
                      <span class="input-group-addon">
                        <span class="form-control border-left-0 bg-light rounded-end">RD$</span>
                      </span>
                    </div>
                    <!--  -->

                    <!-- <div class="form-control" style="width: 40px" @click="showTransactionDetail"> 
                      <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </div> -->

                  </div>
                  <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div>
                </div>


              </div>
            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button class="btn btn-primary mt-3 mr-3" @click.prevent="createCashin" :disabled="sendButtonDisabled ? true : false"> Submit
              <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
            </button>

              <!-- <button class="btn bg-gray-300 mt-3" @click.prevent="cancelTransaction" :disabled="sendButtonDisabled ? true : false">
                  Cancel
              </button> -->
            <!-- <button class="btn btn-primary" @click.prevent=createCashin>Submit </button>
            <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" /> -->
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
import useCashin from "../../services/cashinservices.js";
import useTransactionUtils from '../../services/transactionutils/transactionutils';

export default {
  props: ['user'],
  setup(props) {
    const form = reactive({
      provider_name: '',
      provider_phone: '',
      provider_email: '',
      provider_address: '',
      provider_claim: '',
      account_id: '',
      amount: '',
      type: 'moncash',
      operation: '',
      status: 'completed',
    });

    const { storeCashin, getAccountsCashin, checkIfProviderExist, cachinHtgToPesos,
            isProviderExist, providercashin, accountscashin, receiverAmount, 
            receiverAmountMinusP2pFee, buttonIsLoading, sendButtonDisabled, isButtonLoading } = useCashin();
    const { getDailyRate, dailyratePurchase } = useTransactionUtils();
    const errors = ref([]);
    const senderAmountIsEnabled = ref(false);

    const options = ref([
      { text: 'Moncash', value: 'moncash' },
      { text: 'Natcash', value: 'natcash' }
    ]);

    onMounted(async () => {
      await getAccountsCashin({'account_type': form.type});
      await getDailyRate();
    })

    const checkProvider = async (phone) => {
      if (phone.length >= 15) {
        await checkIfProviderExist(phone);
        if (providercashin.value[0]) {
          form.provider_name = await providercashin.value[0].name;
          form.provider_email = await providercashin.value[0].email;
          form.provider_address = await providercashin.value[0].address
          form.provider_claim = await providercashin.value[0].claim;
        }
        else {
          form.provider_name = '';
          form.provider_email = '';
          form.provider_address = '';
        }
      }
    }

    const createCashin = async () => {
      try {
        sendButtonDisabled.value = true;
        isButtonLoading.value = true;
        const data = new FormData();
        data.append('provider_name', form.provider_name);
        data.append('provider_phone', form.provider_phone);
        data.append('provider_email', form.provider_email);
        data.append('provider_address', form.provider_address);
        data.append('provider_claim', form.provider_claim);
        data.append('account_id', form.account_id);
        data.append('amount', receiverAmountMinusP2pFee.value);
        data.append('type', form.type);
        data.append('operation', form.operation);
        data.append('status', form.status);
        data.append('rate', dailyratePurchase.value);

        await storeCashin(data);
        // sendButtonDisabled.value = false;
        // isButtonLoading.value = false;

      } catch (error) {
        sendButtonDisabled.value = false;
        isButtonLoading.value = false;
        errors.value = error.response.data.errors;
      }
    }

    const enableSenderAmount = async (dataStatus) => {
      await getAccountsCashin({'account_type': form.type});
      if (dataStatus == 'reception' || dataStatus == 'reception_si') {
        form.sender_amount = '';
        receiverAmount.value = 0;
        senderAmountIsEnabled.value = true;
      }
      if (dataStatus == 'moncash' || dataStatus == 'natcash') {
        if (dataStatus == 'reception' || dataStatus == 'reception_si') {
          form.sender_amount = '';
          receiverAmount.value = 0;
          senderAmountIsEnabled.value = true;
        }
      }
    }

    return {
      form,
      createCashin,
      accountscashin,
      checkProvider,
      isProviderExist,
      cachinHtgToPesos,
      receiverAmount,
      enableSenderAmount,
      senderAmountIsEnabled,
      options,
      errors,
      buttonIsLoading,
      sendButtonDisabled,
      isButtonLoading,
      getAccountsCashin
    };
  }
}
</script>