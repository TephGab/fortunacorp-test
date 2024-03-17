<template>
  <div class="container-fluid">

    <div class="row">
     
      <div class="col-md-12 mt-3">
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
                  <vue-tel-input v-model="form.sender_phone" :inputOptions="{ placeholder: 'Ex: 34000000' }"
                    mode="international" :onlyCountries="['ht', 'do']"
                    @keyup="checkSender(form.sender_phone)"></vue-tel-input>
                  <div v-if="errors.sender_phone" class="form-text error text-danger">{{ errors.sender_phone[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Sender Fullname</label>
                  <input type="text" class="form-control" v-model="form.sender_name" />
                  <div v-if="errors.sender_name" class="form-text error text-danger">{{ errors.sender_name[0] }}</div>
                </div>

                <div class="col-md-12 mt-1 mb-1">
                  <h5 class="fw-bold">RECEIVER INFOS</h5>
                </div>

                <div class="mb-3 col-md-6 ms-auto">
                  <label for="phone" class="form-label">Receiver Phone</label>
                  <vue-tel-input v-model="form.receiver_phone" :inputOptions="{ placeholder: 'Ex: 34000000' }"
                    mode="international" :onlyCountries="['ht', 'do']"
                    @keyup="checkReceiver(form.receiver_phone)"></vue-tel-input>
                  <div v-if="errors.receiver_phone" class="form-text error text-danger">{{ errors.receiver_phone[0] }}
                  </div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Receiver Fullname</label>
                  <input type="text" class="form-control" v-model="form.receiver_name" />
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
                    <div class="input-group">
                      <input type="number" class="form-control border-right-0 rounded-start" id="sender_amount"
                        v-model="form.sender_amount"
                        @keyup="currencyConverterPesosToHTG(form.sender_amount, form.operation, form.type)">
                      <span class="input-group-addon">
                        <span class="form-control border-left-0 bg-light rounded-end" v-show="pesosToHtg">RD$</span>
                        <span class="form-control border-left-0 bg-light rounded-end" v-show="!pesosToHtg">HTG</span>
                      </span>
                    </div>
                    <!--  -->

                    <i class="fas fa-exchange-alt ml-1 mr-1 mt-2"></i>

                    <!-- Reception -->
                    <div class="input-group">
                      <input type="number" class="form-control border-right-0 rounded-start" id="amount"
                        v-model="receiverAmountMinusP2pFee" disabled
                        v-if="form.operation == 'transafert' || form.operation == 'transafert_si'" />
                      <input type="number" class="form-control border-right-0 rounded-start" id="amount"
                        v-model="receiverAmount" disabled v-else />
                      <span class="input-group-addon">
                        <span class="form-control border-left-0 bg-light rounded-end" v-show="pesosToHtg">HTG</span>
                        <span class="form-control border-left-0 bg-light rounded-end" v-show="!pesosToHtg">RD$S</span>
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

              <div class="col-md-12 fw-bold">Notes</div>
              <div class="mt-1" v-for="comment in transactioninfo.initiator_comment" :key="comment.id">
                <div class="comment-widgets mb-3 col-md-12">
                  <div class="d-flex flex-row comment-row">
                    <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png" alt="user"
                          width="40"></span></div>
                    <div class="comment-text w-100">
                      <h6>
                        <span :class="['text-capitalize inline-flex items-center rounded-md px-1 font-medium',
                          comment.category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                          comment.category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                          comment.category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']" style="font-size: 10px;">
                          {{ comment.category }}
                        </span>
                      </h6>
                      <p class="fw-bold text-sm"> {{ comment.user.first_name }} {{ comment.user.last_name }} </p>
                      <div class="comment-footer">
                        <span class="date text-muted text-xs">{{ formatDate(comment.created_at) }}</span>
                      </div>
                      <p class="m-b-5 m-t-10 text-sm">{{ comment.content }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- <div class="col-md-12 fw-bold">Operator notes</div> -->
              <div class="mt-1" v-for="comment in transactioninfo.approver_comment" :key="comment.id">
                <div class="comment-widgets mb-3 col-md-12">
                  <div class="d-flex flex-row comment-row">
                    <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png" alt="user"
                          width="40"></span></div>
                    <div class="comment-text w-100">
                      <h6>
                        <span :class="['text-capitalize inline-flex items-center rounded-md px-1 font-medium',
                          comment.category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                          comment.category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                          comment.category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']" style="font-size: 10px;">
                          {{ comment.category }}
                        </span>
                      </h6>
                      <p class="fw-bold text-sm"> {{ comment.user.first_name }} {{ comment.user.last_name }} </p>
                      <div class="comment-footer">
                        <span class="date text-muted text-xs">{{ formatDate(comment.created_at) }}</span>
                      </div>
                      <p class="m-b-5 m-t-10 text-sm">{{ comment.content }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-1" v-for="comment in transactioninfo.disapprover_comment" :key="comment.id">
                <div class="comment-widgets mb-3 col-md-12">
                  <div class="d-flex flex-row comment-row">
                    <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png" alt="user"
                          width="40"></span></div>
                    <div class="comment-text w-100">
                      <h6>
                        <span :class="['text-capitalize inline-flex items-center rounded-md px-1 font-medium',
                          comment.category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                          comment.category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                          comment.category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']" style="font-size: 10px;">
                          {{ comment.category }}
                        </span>
                      </h6>
                       <p class="fw-bold text-sm"> {{ comment.user.first_name }} {{ comment.user.last_name }} </p>
                      <div class="comment-footer">
                        <span class="date text-muted text-xs">{{ formatDate(comment.created_at) }}</span>
                      </div>
                      <p class="m-b-5 m-t-10 text-sm">{{ comment.content }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-1" v-for="comment in transactioninfo.concluder_comment" :key="comment.id">
                <div class="comment-widgets mb-3 col-md-12">
                  <div class="d-flex flex-row comment-row">
                    <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png" alt="user"
                          width="40"></span></div>
                    <div class="comment-text w-100">
                      <h6>
                        <span :class="['text-capitalize inline-flex items-center rounded-md px-1 font-medium',
                          comment.category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                          comment.category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                          comment.category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']" style="font-size: 10px;">
                          {{ comment.category }}
                        </span>
                      </h6>
                      <p class="fw-bold text-sm"> {{ comment.user.first_name }} {{ comment.user.last_name }} </p>
                      <div class="comment-footer">
                        <span class="date text-muted text-xs">{{ formatDate(comment.created_at) }}</span>
                      </div>
                      <p class="m-b-5 m-t-10 text-sm">{{ comment.content }}</p>
                    </div>
                  </div>
                </div>
              </div>
              



              <hr>

              <!-- Other details -->
              <div class="row mt-3">
                <div>
                  <label for="comment" class="form-label">Add note</label>
                  <select :class="['form-control border-bottom-0 outline-0 col-md-12 fw-bold shadow-none',
                    form.comment_category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                      form.comment_category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                        form.comment_category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                          'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']"
                    v-model="form.comment_category"
                    style="border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
                    <option v-for="option in commentOptions" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                  <textarea class="form-control border-top-0 rounded-top-0 shadow-none" v-model="form.comment"
                    style="border-top-left-radius: 0px; border-top-right-radius: 0px;"></textarea>
                </div>
              </div>
              <!-- Other details end  -->

              <!-- <button class="btn btn-primary mt-3" @click.prevent=reviewTransaction>Resubmit
                <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
              </button> -->

              <button class="btn btn-primary mt-3 mr-3" @click.prevent=reviewTransaction :disabled="sendButtonDisabled ? true : false"> Resubmit
                <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
              </button>

              <!-- <button class="btn bg-gray-300 mt-3" @click.prevent="cancelTransaction" :disabled="sendButtonDisabled ? true : false">
                  Cancel
              </button> -->
            </form>
            <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="exampleModalLabel"
              aria-hidden="true">
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
import { onMounted, reactive, ref } from 'vue';
import useTransactions from "../../services/transactionservices";
import useUsers from "../../services/usersservices";
import Swal from "sweetalert2";
import { useAbility } from '@casl/vue';

export default {
  props: ['id', 'user'],
  setup(props) {

    const { reviewDisapprovedTransaction, getTransaction, transaction, formatDate, getTransactionInfo, transactioninfo, updateTransactionStatus,
      currencyConverterPesosToHTG, receiverAmount, senderAmount, amountHtgNet, fortunaFee,
      getDailyRate, dailyrateSale, dailyratePurchase, isButtonLoading, checkIfClientSenderExist, checkIfClientReceiverExist,
      client, isClientSenderExist, isClientReceiverExist, moncashTransfertSiP2PFee, moncashTransfertSiCashOutFee,
      moncashReceptionSi, natcashTransfertSiP2PFee, natcashTransfertSiCashOutFee, receiverAmountMinusP2pFee, sendButtonDisabled } = useTransactions();
    const { getUser, user } = useUsers();
    const { can } = useAbility();

    const errors = ref([]);
    const senderAmountIsEnabled = ref(false);
    const pesosToHtg = ref(true);

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

    const showTransactionDetail = async () => {
      myModal = await new bootstrap.Modal(document.getElementById('transactionDetailModal'), {
        backdrop: true
      });
      await myModal.show();
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

    onMounted(async () => {
      await getTransaction(props.id);
      const data = new FormData()
      data.append('transaction_id', transaction.value.id)
      data.append('user_id', transaction.value.user_id)
      data.append('client_id', transaction.value.client_id)
      data.append('sender_id', transaction.value.sender)
      data.append('receiver_id', transaction.value.receiver)
      data.append('approver_id', transaction.value.approved_by)
      await getTransactionInfo(data);
      await getUser(props.user.id);
      form.sender_phone = transactioninfo.value.sender[0].phone;
      form.sender_name = transactioninfo.value.sender[0].name;
      form.receiver_phone = transactioninfo.value.receiver[0].phone;
      form.receiver_name = transactioninfo.value.receiver[0].name;
      form.type = transaction.value.type;
      form.operation = transaction.value.operation;
      form.sender_amount = transaction.value.sender_amount;
      form.rate = transaction.value.rate;
      if (transaction.value.operation == 'transfert' || transaction.value.operation == 'transfert_si') {
        pesosToHtg.value = true;
      }
      if (transaction.value.operation == 'reception' || transaction.value.operation == 'reception_si') {
        pesosToHtg.value = false;
      }
      await currencyConverterPesosToHTG(transaction.value.sender_amount, transaction.value.operation, transaction.value.type)
    })

    const reviewTransaction = async () => {
      try {
        sendButtonDisabled.value = true;
        isButtonLoading.value = true;
        const data = new FormData()
        data.append('transaction_id', transaction.value.id);
        data.append('sender_name', form.sender_name);
        data.append('sender_phone', form.sender_phone);
        data.append('receiver_name', form.receiver_name);
        data.append('receiver_phone', form.receiver_phone);
        data.append('status', form.status);
        data.append('sender_amount', form.sender_amount);
        if(form.operation == 'reception' || form.operation == 'reception_si'){
          data.append('receiver_amount', receiverAmountMinusP2pFee.value);
        }else{
          data.append('receiver_amount', receiverAmount.value);
        }
        data.append('rate', form.rate);
        data.append('qr_code', form.qr_code);
        data.append('comment', form.comment);
        data.append('comment_category', form.comment_category);
        data.append('type', form.type);
        data.append('operation', form.operation);
        data.append('fortuna_fee', fortunaFee.value)

        await reviewDisapprovedTransaction(data);
        
      } catch (error) {
        isButtonLoading.value = false;
        sendButtonDisabled.value = false;
        errors.value = error.response.data.errors;
        if(error.response.data.errors){
       if(error.response.data.errors.transaction_interval){
        if(error.response.data.errors.transaction_interval[0]){
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
      }
       if(error.response.data.errors.user_debt_not_allowed_pending_withdraw){
        if(error.response.data.errors.user_debt_not_allowed_pending_withdraw[0]){
          Swal.fire({
            text: 'Transaction not allowed. Deposit required!',
            position: 'center',
            icon: 'error',
            color: '#000',
            padding: '0',
            showConfirmButton: true,
            });
        }
       }
       if(error.response.data.errors.transaction_in_process){
        if(error.response.data.errors.transaction_in_process[0]){
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
       }
       
       }
      }
    }

    const editTransaction = async (id) => {
      await updateTransactionStatus(id, { ...form });
    }

    return {
      form,
      transaction,
      formatDate,
      getTransactionInfo,
      transactioninfo,
      can,
      editTransaction,
      options,
      commentOptions,
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
      checkSender,
      checkReceiver,
      reviewTransaction,
      sendButtonDisabled
    };
  }
}
</script>