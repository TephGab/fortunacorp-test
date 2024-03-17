<template>
  <div class="container-fluid">
    <div class="row">
      <div class="container-fluid"> <Breadcrumb /></div>
      <div class="col-md-12">
        <!-- <h4 style="margin-left: 1.2rem;">Latest News</h4> -->
        <ul class="timeline-3">
          <li>
            <span href="#!" class="fw-bold">INITIALIZED</span>
            <span class="float-end text-xs d-flex justify-content-space-between align-items-center text-center"
              v-for="user in transactioninfo.user" :key="user.id">
              <span class="round"><img src="/img/users/default-user.png" alt="user" width="17"></span>
              {{ user.first_name }} {{ user.last_name }} -- <span class="text-muted fw-normal" style="font-size: 10px;">
                {{ formatDate(transaction.created_at) }} </span></span>
            <div class="mt-2">

              <div class="col-md-12 col-sm-12">
                <h6 class="fw-bold mb-3">SENDER & RECEIVER INFOS</h6>
              </div>

              <div class="d-flex justify-content-space-between align-items-center text-center">
                <div class="mb-3 col-md-3">
                  <label for="clientName" class="form-label">Sender name</label>
                  <div v-for="sender in transactioninfo.sender" :key="sender.id"> {{ sender.name }}</div>
                </div>
                <div class="mb-3 col-md-3" style="border-right: 1px solid gray;">
                  <label class="form-label" for="clientPhone">Sender phone</label>
                  <div v-for="sender in transactioninfo.sender" :key="sender.id"> {{ sender.phone }}</div>
                </div>


                <div class="mb-3 col-md-3">
                  <label for="clientName" class="form-label">Receiver name</label>
                  <div v-for="receiver in transactioninfo.receiver" :key="receiver.id"> {{ receiver.name }} </div>
                </div>
                <div class="mb-3 col-md-3">
                  <label for="clientPhone" class="form-label">Receiver phone</label>
                  <div v-for="receiver in transactioninfo.receiver" :key="receiver.id"> {{ receiver.phone }} </div>
                </div>
              </div>

              <label for="comment" class="form-label">Notes</label>
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

              <hr class="hr hr-blurry" />

              <div class="d-flex justify-content-center align-items-center">
                <div class="col-md-12 mt-1">
                  <h6 class="fw-bold mb-3">TRANSACTION DETAILS</h6>
                </div>
              </div>

              <div class="d-flex justify-content-space-bettween align-items-center text-center mt-1">
                <div class="mb-3 col-md-4">
                  <label for="clientName" class="form-label">Sender amount</label>
                  <div> {{ transaction.sender_amount }}
                    <span
                      v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">RD$</span>
                    <span v-else>HTG</span>
                  </div>
                </div>
                <div class="mb-3 col-md-4">
                  <label for="clientName" class="form-label">Receiver amount</label>
                  <div> {{ transaction.receiver_amount }}
                    <span
                      v-if="transaction.operation == 'transfert' || transaction.operation == 'transfert_si'">HTG</span>
                    <span v-else>RD$</span>
                  </div>
                </div>
                <div class="mb-3 col-md-4">
                  <label for="clientPhone" class="form-label">Type & Operation</label>
                  <div> {{ transaction.type }} ( {{ transaction.operation }} )</div>
                </div>
              </div>
            </div>
          </li>
          <li>
            <a href="#" class="fw-bold">APPROVED</a>
            <a href="#" class="float-end text-xs d-flex justify-content-space-between align-items-center"
              v-for="approver in transactioninfo.approver" :key="approver.id">
              <span class="round"><img src="/img/users/default-user.png" alt="user" width="17"></span>
              {{ approver.first_name }} {{ approver.last_name }} -- <span class="text-muted fw-normal"
                style="font-size: 10px;"> {{ formatDate(transaction.approved_date) }} </span>
            </a>
            <div class="mt-2">
              <div class="col-md-12 col-sm-12">
                <!-- <h6 class="fw-bold mb-3">COMMENTS</h6> -->
              </div>
              <div class="d-flex justify-content-space-between align-items-center text-center mt-1">
              </div>
              <label for="comment" class="form-label">Notes</label>
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
                        <!-- <span class="label label-info">Pending</span> <span class="action-icons">
                                          <a href="#" data-abc="true"><i class="fa fa-pencil"></i></a>
                                          <a href="#" data-abc="true"><i class="fa fa-rotate-right"></i></a>
                                          <a href="#" data-abc="true"><i class="fa fa-heart"></i></a>
                                  </span> -->
                      </div>
                      <p class="m-b-5 m-t-10 text-sm">{{ comment.content }}</p>
                    </div>
                  </div>
                </div>

              </div>
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
                        <!-- <span class="label label-info">Pending</span> <span class="action-icons">
                                          <a href="#" data-abc="true"><i class="fa fa-pencil"></i></a>
                                          <a href="#" data-abc="true"><i class="fa fa-rotate-right"></i></a>
                                          <a href="#" data-abc="true"><i class="fa fa-heart"></i></a>
                                  </span> -->
                      </div>
                      <p class="m-b-5 m-t-10 text-sm">{{ comment.content }}</p>
                    </div>
                  </div>
                </div>

              </div>

            </div>
          </li>
          <li>
            <a href="#" class="fw-bold">COMPLETED</a>
            <a href="#" class="float-end text-xs d-flex justify-content-space-between align-items-center"
              v-for="concluder in transactioninfo.concluder" :key="concluder.id">
              <span class="round"><img src="/img/users/default-user.png" alt="user" width="17"></span>
              {{ concluder.first_name }} {{ concluder.last_name }} --
              <span class="text-muted fw-normal" style="font-size: 10px;"> {{ formatDate(transaction.completed_date) }}
              </span>
            </a>
            <div class="mt-2">
              <div class="col-md-12 col-sm-12">
                <h6 class="fw-bold mb-3">ACCOUNT DETAILS</h6>
              </div>
              <div class="d-flex justify-content-space-between align-items-center text-center mt-1">
                <div class="mb-3 col-md-6">
                  <label for="clientName" class="form-label">Account name</label>
                  <div v-for="account in transactioninfo.account" :key="account.id"> {{ account.name }} </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="clientName" class="form-label">Account number</label>
                  <div v-for="account in transactioninfo.account" :key="account.id"> {{ account.phone }} </div>
                </div>
              </div>

              <label for="comment" class="form-label">Notes</label>
              <div class="mt-1" v-for="comment in transactioninfo.concluder_comment" :key="comment.id">
                <div class="comment-widgets mb-3 col-md-12">

                  <div class="d-flex flex-row comment-row">
                    <div class="p-2 pt-0 mb-2"><span class="round"><img src="/img/users/default-user.png" alt="user" width="40"></span>
                    </div>
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
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue';
import useUtils from "../../../services/utilsservices";
import useTransaction from "../../../services/transactionservices";

export default {
  props: ['transactioninfo', 'transaction', 'approvalData'],
  emits: ['approved-status'],
  setup(props, { emit }) {
    const { formatDecimalNumber } = useUtils();
    const { formatDate, updateTransactionStatus, getTransaction, transaction } = useTransaction();
    const form = reactive({
      approved_status: 'approved',
      disapproved_status: 'disapproved',
      comment: ''
    });

    onMounted(() => {

    });

    return {
      props,
      form,
      formatDecimalNumber,
      formatDate,
    }
  },
}
</script>

<style scoped>
ul.timeline-3 {
  list-style-type: none;
  position: relative;
}

ul.timeline-3:before {
  content: " ";
  background: #d4d9df;
  display: inline-block;
  position: absolute;
  left: 29px;
  width: 2px;
  height: 100%;
  z-index: 400;
}

ul.timeline-3>li {
  margin: 20px 0;
  padding-left: 42px;
}

ul.timeline-3>li:before {
  content: " ";
  background: white;
  display: inline-block;
  position: absolute;
  border-radius: 50%;
  border: 3px solid #22c0e8;
  left: 20px;
  width: 20px;
  height: 20px;
  z-index: 400;
}

/* Comment  */

.card-no-border .card {
  border: 0px;
  border-radius: 4px;
  -webkit-box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05);
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05)
}

.card-body {
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  padding: 1.25rem
}

.comment-widgets .comment-row:hover {
  background: rgba(0, 0, 0, 0.02);
  cursor: pointer;
}

.comment-widgets .comment-row {
  border-bottom: 1px solid rgba(120, 130, 140, 0.13);
  padding: 15px;
}

.comment-text:hover {
  visibility: hidden;
}

.comment-text:hover {
  visibility: visible;
}

.label {
  padding: 3px 10px;
  line-height: 13px;
  color: #ffffff;
  font-weight: 400;
  border-radius: 4px;
  font-size: 75%;
}

.round img {
  border-radius: 100%;
}

.label-info {
  background-color: #1976d2;
}

.label-success {
  background-color: green;
}

.label-danger {
  background-color: #ef5350;
}

.action-icons a {
  padding-left: 7px;
  vertical-align: middle;
  color: #99abb4;
}

.action-icons a:hover {
  color: #1976d2;
}

.mt-100 {
  margin-top: 100px
}

.mb-100 {
  margin-bottom: 100px
}

.date {
  font-size: 10px;
}</style>