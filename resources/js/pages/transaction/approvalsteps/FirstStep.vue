<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-md-12">
                        <h6 class="fw-bold mb-3">SENDER & RECEIVER INFOS</h6>
                    </div>
                </div>
                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Sender name</label>
                        <div v-for="sender in transactioninfo.sender" :key="sender.id"> {{ sender.name }}</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="clientPhone" class="form-label">Sender phone</label>
                        <div v-for="sender in transactioninfo.sender" :key="sender.id"> {{ sender.phone }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label">Receiver name</label>
                        <div v-for="receiver in transactioninfo.receiver" :key="receiver.id"> {{ receiver.name }} </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="clientPhone" class="form-label">Receiver phone</label>
                        <div v-for="receiver in transactioninfo.receiver" :key="receiver.id"> {{ receiver.phone }} </div>
                    </div>
                </div>

                <hr class="hr hr-blurry" />

                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-md-12 mt-1">
                        <h6 class="fw-bold mb-3">TRANSACTION DETAILS</h6>
                    </div>
                </div>

                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-4">
                        <label for="clientName" class="form-label">Sender amount</label>
                        <div> {{ formatDecimalNumber(transaction.sender_amount) }}
                            <span
                                v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">RD$</span>
                            <span v-else>HTG</span>
                        </div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="clientName" class="form-label">Receiver amount</label>
                        <div> 
                            {{ formatDecimalNumber(transaction.receiver_amount) }}
                            <span
                                v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">HTG <span class="text-muted text-xs text-italic">Business acc.</span>
                            </span>
                            <span v-else>RD$</span>
                        </div>
                        <div v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'"> 
                            {{ formatDecimalNumber(transaction.receiver_amount - transaction.p_to_p_fee) }}
                            <span
                                v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">HTG <span class="text-muted text-xs text-italic">Normal acc.</span>
                            </span>
                            <!-- <span v-else>RD$</span> -->
                        </div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="clientPhone" class="form-label">Type & Operation</label>
                        <div> {{ transaction.type }} ( {{ transaction.operation }} )</div>
                    </div>

                </div>

                <div class="d-flex justify-content-space-bettween align-items-center">
                    <div class="mb-3 col-md-6">
                        <label for="clientName" class="form-label" v-for="user in transactioninfo.user"
                            :key="user.id">Initialized by: {{ user.first_name }} {{ user.last_name }} -- <span
                                class="text-sm text-muted fw-normal" style="font-size: 10px;"> {{
                                    formatDate(transaction.created_at) }}</span></label>
                    </div>
                    <div class="mb-3 col-md-6" v-if="transaction.status != 'pending'">
                        <label for="clientName" class="form-label" v-for="aprover in transactioninfo.aprover"
                            :key="aprover.id">Approved by: {{ aprover.first_name }} {{ aprover.last_name }} -- <span
                                class="text-sm text-muted"> {{ formatDate(transaction.approved_date) }}</span></label>
                    </div>
                </div>

                <hr class="hr hr-blurry" />
                <div class="text-left">
                    <div class="col-md-12 fw-bold">Notes</div>
                    <!-- <label for="comment" class="form-label"></label> -->
                    <div class="mt-1" v-for="comment in transactioninfo.initiator_comment" :key="comment.id">
                        <div class="comment-widgets mb-3 col-md-12">

                            <div class="d-flex flex-row comment-row">
                                <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png"
                                            alt="user" width="40"></span></div>
                                <div class="comment-text w-100">
                                    <h6>
                                        <span :class="['text-capitalize inline-flex items-center rounded-md px-1 font-medium',
                                            comment.category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                                                comment.category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                                                    comment.category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                                        'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']"
                                            style="font-size: 10px;">
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
                                <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png"
                                            alt="user" width="40"></span></div>
                                <div class="comment-text w-100">
                                    <h6>
                                        <span :class="['text-capitalize inline-flex items-center rounded-md px-1 font-medium',
                                            comment.category == 'undefined' ? 'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500' :
                                                comment.category == 'important' ? 'bg-red-200 hover:bg-red-300 focus:outline-none text-red-500' :
                                                    comment.category == 'reminder' ? 'bg-indigo-200 hover:bg-indigo-300 focus:outline-none text-indigo-500' :
                                                        'bg-gray-200 hover:bg-gray-300 focus:outline-none text-black-500']"
                                            style="font-size: 10px;">
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
                </div>
                <hr />

                <div class="mb-3 col-md-12 ms-auto mt-3 text-left">
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
                    <textarea class="form-control border-top-0 rounded-top-0 shadow-none" v-model="form.comment" style="border-top-left-radius: 0px; border-top-right-radius: 0px;"></textarea>
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <div>
                        <button class="btn btn-sm btn-danger mr-3" @click=disapproveTransaction(transaction.id) :disabled="transaction.status == 'pending' ? false : true">
                            Disapprove
                            <img v-show="cancelButtonDisabled" class="img-fluid img-circle" src="/img/button_spinner.gif"
                                alt="button-spinner" />
                            <span v-show="cancelButtonDisabled" style="font-size: 10px;"
                                class="text-danger">Disapproving...</span>
                        </button>
                        <button class="btn btn-sm btn-success" @click=approveTransaction(transaction.id) :disabled="transaction.status == 'pending' ? false : true">
                            Approve
                            <img v-show="sendButtonDisabled" class="img-fluid img-circle" src="/img/button_spinner.gif"
                                alt="button-spinner" />
                            <span v-show="sendButtonDisabled" style="font-size: 10px;">Approving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue';
import useUtils from "../../../services/utilsservices";
import useTransaction from "../../../services/transactionservices";
import Swal from "sweetalert2";

export default {
    props: ['transactioninfo', 'transaction', 'approvalData'],
    emits: ['approved-status', 'disapproved-status'],
    setup(props, { emit }) {
        const { formatDecimalNumber, sendButtonDisabled, cancelButtonDisabled } = useUtils();
        const { formatDate, updateTransactionStatus, getTransaction, transaction, isButtonLoading } = useTransaction();
        const form = reactive({
            approved_status: 'approved',
            disapproved_status: 'disapproved',
            comment: '',
            comment_category: 'undefined',
        });
        const errors = ref([]);

        const commentOptions = ref([
            { text: 'Undefined', value: 'undefined' },
            { text: 'Important', value: 'important' },
            { text: 'Reminder', value: 'reminder' },
        ]);

        const approveTransaction = async (id) => {
            try {
                sendButtonDisabled.value = true
                const data = new FormData()
                data.append('transaction_id', id);
                data.append('status', form.approved_status);
                data.append('comment', form.comment);
                data.append('comment_category', form.comment_category);
                await updateTransactionStatus(data);
                await Swal.fire({
                    text: "Transactions marked as approved!",
                    toast: true,
                    position: "top-right",
                    icon: "success",
                    color: "#000",
                    padding: "0",
                    showConfirmButton: false,
                    timer: 4500,
                });
                sendButtonDisabled.value = false;
                await emit('approved-status', transaction);
            } catch (error) {
                isButtonLoading.value = false;
                sendButtonDisabled.value = false;
                errors.value = error.response.data.errors;
            }
        }
        const disapproveTransaction = async (id) => {
            try {
                cancelButtonDisabled.value = true;
                const data = new FormData()
                data.append('transaction_id', id);
                data.append('status', form.disapproved_status);
                data.append('comment', form.comment);
                data.append('comment_category', form.comment_category);
                await updateTransactionStatus(data);
                await Swal.fire({
                    text: "Transactions marked as disapproved!",
                    toast: true,
                    position: "top-right",
                    icon: "error",
                    color: "#000",
                    padding: "0",
                    showConfirmButton: false,
                    timer: 4500,
                });
                cancelButtonDisabled.value = false;
                await emit('disapproved-status', transaction);
            } catch (error) {
                isButtonLoading.value = false;
                cancelButtonDisabled.value = false;
                errors.value = error.response.data.errors;
            }
        }

        return {
            props,
            form,
            formatDecimalNumber,
            formatDate,
            approveTransaction,
            disapproveTransaction,
            isButtonLoading,
            commentOptions,
            sendButtonDisabled,
            cancelButtonDisabled,
            errors
        }
    },
}
</script>