<template>
  <div class="container-fluid">
    <div class="row">
      <div class="container-fluid"> <Breadcrumb /></div>
      <div class="col-md-12">
       
        <!-- Card -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <div class="card-tools">
              <!-- <span><span class="text-bold">Daily rate:</span> {{ dailyrateSale }} HTG</span> -->
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
              <div class="row">

                <div class="col-md-12 mt-1 mb-1">
                  <h5 class="fw-bold">SENDER INFOS</h5>
                </div>

                <div class="mb-3 col-md-6 ms-auto">
                  <label for="phone" class="form-label">Sender Phone</label>
                  <vue-tel-input v-model="form.sender_phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international" :onlyCountries="['ht', 'do']" @keyup="checkSender(form.sender_phone)"></vue-tel-input>
                  <div v-if="errors.sender_phone" class="form-text error text-danger">{{ errors.sender_phone[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Sender Fullname</label>
                  <input type="text" class="form-control" v-model="form.sender_name" :disabled="isClientSenderExist ? 'disabled' : 0">
                  <div v-if="errors.sender_name" class="form-text error text-danger">{{ errors.sender_name[0] }}</div>
                </div>

                <div class="col-md-12 mt-1 mb-1">
                  <h5 class="fw-bold">RECEIVER INFOS</h5>
                </div>

                <div class="mb-3 col-md-6 ms-auto">
                  <label for="phone" class="form-label">Receiver Phone</label>
                  <vue-tel-input v-model="form.receiver_phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international" :onlyCountries="['ht', 'do']" @keyup="checkReceiver(form.receiver_phone)"></vue-tel-input>
                  <div v-if="errors.receiver_phone" class="form-text error text-danger">{{ errors.receiver_phone[0] }} </div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Receiver Fullname</label>
                  <input type="text" class="form-control" v-model="form.receiver_name" :disabled="isClientReceiverExist ? 'disabled' : 0">
                  <div v-if="errors.receiver_name" class="form-text error text-danger">{{ errors.receiver_name[0] }}</div>
                </div>

              </div>

              <hr class="hr hr-blurry" />

              <!-- Transaction details -->
              <div class="row">

                <div class="col-md-12 mt-1 mb-1">
                  <h5 class="fw-bold">TRANSACTION DETAILS</h5>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="type" class="form-label">Type</label>
                  <select class="form-control" id="type" v-model="form.type" @change="enableSenderAmount(form.type)">
                    <option v-for="option in options" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                  <div v-if="errors.type" class="form-text error text-danger">{{ errors.type[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="operation" class="form-label">Operation</label>
                  <div class="mb-3 col-md-12">
                    <div class="mb-3 col-md-12" v-for="role in user.roles" :key="role.id">
                      <div class="form-check form-check-inline" v-show="role.name == 'super-admin'">
                        <input class="form-check-input" type="radio" name="operation" id="transfert" value="transfert"
                          v-model="form.operation" @change="enableSenderAmount(form.operation)">
                        <label class="form-check-label" for="transfert">Transfert</label>
                      </div>
                      <div class="form-check form-check-inline" v-show="role.name == 'super-admin'">
                        <input class="form-check-input" type="radio" name="operation" id="reception" value="reception"
                          v-model="form.operation" @change="enableSenderAmount(form.operation)">
                        <label class="form-check-label" for="reception">Reception</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="operation" id="transfertsi"
                          value="transfert_si" v-model="form.operation" @change="enableSenderAmount(form.operation)">
                        <label class="form-check-label" for="transfertsi">Transfert Si</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="operation" id="receptionsi"
                          value="reception_si" v-model="form.operation" @change="enableSenderAmount(form.operation)">
                        <label class="form-check-label" for="reception">Reception Si</label>
                      </div>
                    </div>

                    <div v-if="errors.operation" class="form-text error text-danger">{{ errors.operation[0] }}</div>
                  </div>
                </div>

                <div class="mb-3 col-md-12">
                  <label for="amount" class="form-label">Sender amount</label>
                  <div class="d-flex justify-content-between">

                    <!-- Transfert -->
                    <div class="input-group text-sm">
                      <input type="number" class="form-control border-right-0 rounded-start text-sm" id="sender_amount" v-model="form.sender_amount" @keyup="currencyConverterPesosToHTG(form.sender_amount, form.operation, form.type)" :disabled="senderAmountIsEnabled ? 0 : 'disabled'">
                      <span class="input-group-addon">
                        <span class="form-control border-left-0 bg-light rounded-end pt-2" v-show="pesosToHtg" style="font-size: 10px; padding-left: 2px; padding-right: 2px;">RD$</span>
                        <span class="form-control border-left-0 bg-light rounded-end pt-2" v-show="!pesosToHtg" style="font-size: 10px; padding-left: 2px; padding-right: 2px;">HTG</span>
                      </span>
                    </div>
                    <!--  -->

                    <i class="fas fa-exchange-alt ml-1 mr-1 mt-2"></i>

                    <!-- Reception -->
                    <div class="input-group text-sm">

                    <input type="text" class="form-control border-right-0 rounded-start text-sm" v-bind:value="formatDecimalNumber(receiverAmountMinusP2pFee)" disabled v-if="form.operation == 'transafert' || form.operation == 'transafert_si'"/>
                     <input type="text" class="form-control border-right-0 rounded-start text-sm" v-bind:value="formatDecimalNumber(receiverAmountMinusP2pFee)" disabled v-else/>
                      <span class="input-group-addon">
                        <span class="form-control border-left-0 bg-light rounded-end pt-2" v-show="pesosToHtg" style="font-size: 10px; padding-left: 2px; padding-right: 2px;">HTG</span>
                        <span class="form-control border-left-0 bg-light rounded-end pt-2" v-show="!pesosToHtg" style="font-size: 10px; padding-left: 2px; padding-right: 2px;">RD$</span>
                      </span>
                    </div>
                    <!--  -->

                    <div class="form-control" style="width: 40px" @click="showTransactionDetail"> 
                      <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </div>

                  </div>
                  <div v-if="errors.sender_amount" class="form-text error text-danger">{{ errors.sender_amount[0] }}</div>
                </div>

              </div>

              <hr class="hr hr-blurry" />
              <!-- Transaction details end  -->

              <!-- Other details -->
              <div class="row mt-3">
                 <div>
                    <div class="form-label">Add note</div>
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
              <!-- Other details end  -->

              <button class="btn btn-primary mt-3 mr-3" @click.prevent="createTransaction" :disabled="sendButtonDisabled ? true : false"> Submit
                <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
              </button>

              <button class="btn bg-gray-300 mt-3" @click.prevent="cancelTransaction" :disabled="sendButtonDisabled ? true : false">
                  Cancel
              </button>
            </form>
            <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <moncashmodal :senderAmount="form.sender_amount" :operation="form.operation" :type="form.type"
                    :moncashTransfertSiCashOutFee="moncashTransfertSiCashOutFee"
                    :moncashTransfertSiP2PFee="moncashTransfertSiP2PFee" :receiverAmount="receiverAmount"
                    :fortunaFee="fortunaFee" :dailyrateSale="dailyrateSale" :dailyratePurchase="dailyratePurchase" 
                    :receiverAmountMinusP2pFee="receiverAmountMinusP2pFee">
                  </moncashmodal>

                  <natcashmodal :senderAmount="form.sender_amount" :operation="form.operation" :type="form.type"
                    :natcashTransfertSiCashOutFee="natcashTransfertSiCashOutFee"
                    :natcashTransfertSiP2PFee="natcashTransfertSiP2PFee" :receiverAmount="receiverAmount"
                    :fortunaFee="fortunaFee" :dailyrateSale="dailyrateSale" :dailyratePurchase="dailyratePurchase" 
                    :receiverAmountMinusP2pFee="receiverAmountMinusP2pFee">
                  </natcashmodal>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, ref, onMounted, watch } from 'vue';
import useTransactions from "../../services/transactionservices";
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import Swal from "sweetalert2";

export default {
  props: ['user'],
  setup(props) {

    const { storeTransaction, currencyConverterPesosToHTG, receiverAmount, senderAmount, amountHtgNet, fortunaFee,
            getDailyRate, dailyrateSale, dailyratePurchase, isButtonLoading, checkIfClientSenderExist, checkIfClientReceiverExist,
            client, isClientSenderExist, isClientReceiverExist, moncashTransfertSiP2PFee, moncashTransfertSiCashOutFee,
            moncashReceptionSi, natcashTransfertSiP2PFee, natcashTransfertSiCashOutFee, receiverAmountMinusP2pFee, cancelTransaction,
            sendButtonDisabled } = useTransactions();
    const { getUser, user } = useUsers();
    const { formatDecimalNumber } = useUtils();

    const form = reactive({
      sender_name: '',
      sender_phone: '',
      receiver_name: '',
      receiver_phone: '',
      status: 'Pending',
      sender_amount: 0,
      receiver_amount: 0,
      qr_code: false,
      type: 'moncash',
      operation: '',
      comment: '',
      comment_category: 'undefined',
      rate: ''
    });

    const errors = ref([]);
    const senderAmountIsEnabled = ref(false);
    const pesosToHtg = ref(true);

    const options = ref([
      { text: 'Moncash', value: 'moncash' },
      { text: 'Natcash', value: 'natcash' },
      // { text: 'Local', value: 'local' }
    ]);

    const commentOptions = ref([
    { text: 'Undefined', value: 'undefined' },
    { text: 'Important', value: 'important' },
    { text: 'Reminder', value: 'reminder' },
    ]);

    let myModal = '';

    onMounted(() => {
      getDailyRate()
      getUser(props.user.id);
    })

    const showTransactionDetail = async () => {
      myModal = await new bootstrap.Modal(document.getElementById('transactionDetailModal'), {
        backdrop: true
      });
      await myModal.show();
    }

    const enableSenderAmount = (dataStatus) => {
      if (dataStatus == 'transfert' || dataStatus == 'transfert_si') {
        form.sender_amount = 0;
        form.rate = dailyrateSale.value;
        receiverAmount.value = 0;
        senderAmount.value = 0;
        senderAmountIsEnabled.value = true;
        pesosToHtg.value = true;
      }
      if (dataStatus == 'reception' || dataStatus == 'reception_si') {
        form.sender_amount = 0;
        form.rate = dailyratePurchase.value;
        receiverAmount.value = 0;
        senderAmount.value = 0;
        senderAmountIsEnabled.value = true;
        pesosToHtg.value = false;
      }
      if (dataStatus == 'moncash' || dataStatus == 'natcash') {
        if (dataStatus == 'transfert' || dataStatus == 'transfert_si' || dataStatus == 'reception' || dataStatus == 'reception_si') {
          form.sender_amount = 0;
          receiverAmount.value = 0;
          senderAmount.value = 0;
          senderAmountIsEnabled.value = true;
          pesosToHtg.value = false;
        }
      }
    }

    const checkSender = async (phone) => {
      if (phone.length >= 10) {
        await checkIfClientSenderExist(phone);
        if (client.value[0]) {
          form.sender_name = await client.value[0].name;
        }
        else {
          form.sender_name = '';
        }
      }
    }
    const checkReceiver = async (phone) => {
      if (phone.length >= 10) {
        await checkIfClientReceiverExist(phone);
        if (client.value[0]) {
          form.receiver_name = await client.value[0].name;
        }
        else {
          form.receiver_name = '';
        }
      }
    }

  const createTransaction = async () => {
    try {
      const confirmed = await Swal.fire({
        title: 'Confirm submission',
        text: 'Submit transaction?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit'
      });

      if (confirmed.isConfirmed) {
        sendButtonDisabled.value = true;
        isButtonLoading.value = true;

        try {
          const data = new FormData();
          data.append('sender_name', form.sender_name);
          data.append('sender_phone', form.sender_phone);
          data.append('receiver_name', form.receiver_name);
          data.append('receiver_phone', form.receiver_phone);
          data.append('status', form.status);
          data.append('sender_amount', form.sender_amount);
          if (form.operation == 'reception' || form.operation == 'reception_si') {
            data.append('receiver_amount', receiverAmountMinusP2pFee.value);
          } else {
            data.append('receiver_amount', receiverAmount.value);
          }
          if (form.type == 'moncash') {
            data.append('p_to_p_fee', moncashTransfertSiP2PFee.value);
          } else {
            data.append('p_to_p_fee', natcashTransfertSiP2PFee.value);
          }
          data.append('rate', form.rate);
          data.append('qr_code', form.qr_code);
          data.append('comment', form.comment);
          data.append('comment_category', form.comment_category);
          data.append('type', form.type);
          data.append('operation', form.operation);
          data.append('fortuna_fee', fortunaFee.value);

          // Call the API to store the transaction
          await storeTransaction(data);

          // On successful transaction
          Swal.fire('Success', 'Transaction created successfully!', 'success');
        } catch (error) {
          isButtonLoading.value = false;
          sendButtonDisabled.value = false;
          errors.value = error.response.data.errors;

          if (error.response.data.errors) {
            if(error.response.data.errors.transaction_interval && error.response.data.errors.transaction_interval[0]){
            Swal.fire({
              title: 'Transaction in process',
              text: 'Please wait at least 10 seconds between transactions.',
              position: 'center',
              icon: 'error',
              color: '#000',
              padding: '0',
              showConfirmButton: true,
              });
          }
        if(error.response.data.errors.user_debt_not_allowed_pending_withdraw && error.response.data.errors.user_debt_not_allowed_pending_withdraw[0]){
            Swal.fire({
              text: 'Transaction not allowed. Deposit required!',
              position: 'center',
              icon: 'error',
              color: '#000',
              padding: '0',
              showConfirmButton: true,
              });
        }
        if(error.response.data.errors.user_debt_not_allowed && error.response.data.errors.user_debt_not_allowed[0]){
            Swal.fire({
              text: 'Transaction not allowed. Deposit required!',
              position: 'center',
              icon: 'error',
              color: '#000',
              padding: '0',
              showConfirmButton: true,
              });
        }
        if(error.response.data.errors.transaction_in_process && error.response.data.errors.transaction_in_process[0]){
            Swal.fire({
              title: 'Transaction in process!',
              text: 'Transaction can not be updated!',
              position: 'center',
              icon: 'error',
              color: '#000',
              padding: '0',
              showConfirmButton: true,
              });
        }
        if(error.response.data.errors.identical_transaction && error.response.data.errors.identical_transaction[0]){
            Swal.fire({
              title: 'Identical transactions',
              text: 'Transaction was not stored as it was identical to the latest one',
              position: 'center',
              icon: 'error',
              color: '#000',
              padding: '0',
              showConfirmButton: true,
              });
        }
            // Add other error handling scenarios here
          }
        } finally {
          isButtonLoading.value = false;
          sendButtonDisabled.value = false;
        }
      } else {
        // If not confirmed, show a cancellation message
        Swal.fire('Canceled', 'Transaction creation canceled.', 'info');
      }
    } catch (error) {
      console.error('Error creating transaction:', error);
      Swal.fire('Error', 'An error occurred while transaction submission.', 'error');
    }
  };

    watch([() => form.type], async (values, oldValue) => {
      const newValue = values[0];
      if (newValue !== null) {
        form.sender_amount = '';
        form.receiver_amount = '';
        receiverAmountMinusP2pFee.value = 0;
        receiverAmount.value = 0;
        senderAmount.value = 0;
        senderAmountIsEnabled.value = true;
      }
    });

    return {
      form,
      options,
      commentOptions,
      createTransaction,
      errors,
      currencyConverterPesosToHTG,
      receiverAmount,
      receiverAmountMinusP2pFee,
      senderAmount,
      amountHtgNet,
      fortunaFee,
      dailyrateSale,
      dailyratePurchase,
      showTransactionDetail,
      isButtonLoading,
      sendButtonDisabled,
      checkSender,
      checkReceiver,
      client,
      isClientSenderExist,
      isClientReceiverExist,
      user,
      moncashTransfertSiP2PFee,
      moncashTransfertSiCashOutFee,
      natcashTransfertSiP2PFee,
      natcashTransfertSiCashOutFee,
      moncashReceptionSi,
      enableSenderAmount,
      senderAmountIsEnabled,
      pesosToHtg,
      cancelTransaction,
      formatDecimalNumber,
    };
  }
}
</script>

<style scoped>
/* Add your custom styles here */
.swal2-title {
  font-size: 15px; /* Change this value for title font size */
}

.swal2-content {
  font-size: 10px; /* Change this value for content font size */
}
</style>