<template>
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid"> <Breadcrumb /></div>
            <div class="col-md-12">
                <!-- Edit mode -->
                <!-- TABLE: LATEST TRANSFERTS -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent bg-transparent">
                        <!-- Account Info -->

                        <!-- Account info End -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 m-0">
                        <div class="row text-gray-300">
                            <div class="col-md-12">
                                <!-- Timelime -->
                                <div class="m-4">

                                    <ol class="relative border-s border-gray-200 dark:border-gray-700">
                                        <!-- Initialized -->
                                        <li class="mb-10 ms-6 shadow-lg">
                                            <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300">
                                                <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </span>
                                            <h3 class="flex items-center mb-1 text-normal font-semibold text-gray-700">
                                                INITIALIZED
                                                <!-- <span class="text-orange-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded ms-3">Pending</span> -->
                                            </h3>

                                            <div class="relative overflow-x-auto">
                                                <table class="w-full text-sm text-center rtl:text-right text-gray-600 dark:text-gray-600">
                                                    <tbody>
                                                        <!-- Sender infos -->
                                                        <tr>
                                                            <th colspan="2" class="text-xs text-gray-800 uppercase  dark:text-gray-800 px-4 py-2 rounded-t-sm">
                                                                Sender Informations
                                                            </th>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Sender name
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Sender Phone
                                                            </td>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300" v-if="transaction && transaction.sender">
                                                            <td scope="row" class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.sender.name }}
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.sender.phone }}
                                                            </td>
                                                        </tr>
                                                        <!-- Receiver infos -->
                                                        <tr>
                                                            <th colspan="2"
                                                                class="text-xs text-gray-800 uppercase dark:text-gray-800 px-4 py-2 rounded-t-sm mt-4">
                                                                Receiver Informations
                                                            </th>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row"
                                                                class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Receiver name
                                                            </td>
                                                            <td scope="row"
                                                                class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Receiver Phone
                                                            </td>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300" v-if="transaction && transaction.receiver">
                                                            <td scope="row"
                                                                class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.receiver.name }}
                                                            </td>
                                                            <td scope="row"
                                                                class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.receiver.phone }}
                                                            </td>
                                                        </tr>

                                                        <!-- Transaction details-->
                                                        <tr>
                                                            <th colspan="2" class="text-xs text-gray-800 uppercase dark:text-gray-800 px-4 py-2 rounded-t-sm mt-4">
                                                                Transaction details
                                                            </th>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row"
                                                                class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Sender amount
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Receiver amount
                                                            </td>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row" class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.sender_amount }}
                                                                <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">RD$</span>
                                                                <span v-else>HTG</span>
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.receiver_amount }}
                                                                <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">HTG</span>
                                                                <span v-else>RD$</span>
                                                            </td>
                                                        </tr>

                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Type
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Operation
                                                            </td>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row"
                                                                class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.type }}
                                                            </td>
                                                            <td scope="row"
                                                                class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.operation }}
                                                            </td>
                                                        </tr>
                                                        <!-- End transaction details -->
                                                    </tbody>
                                                </table>
                                                <div class="col-md-12 text-left">
                                                    <span class="text-xs text-muted">
                                                        <i class="fa-solid fa-clock"></i>{{ formatDateCalendar(transaction.created_at) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on January 13th, 2022</time> -->
                                            <!-- <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Get access to over 20+ pages including a dashboard layout, charts, kanban board, calendar, and pre-order E-commerce & Marketing pages.</p> -->

                                        </li>
                                        <!-- Initialized end -->

                                        <!-- Approved -->
                                        <li class="mb-10 ms-6 shadow-lg" v-if="transaction.status == 'approved' || transaction.status == 'completed'">
                                            <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300">
                                                <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </span>
                                            <h3 class="flex items-center mb-1 text-normal font-semibold text-gray-700">
                                                APPROVED
                                                <!-- <span class="text-orange-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded ms-3">Pending</span> -->
                                            </h3>

                                            <div class="relative overflow-x-auto">
                                                <table class="w-full text-sm text-center rtl:text-right text-gray-600 dark:text-gray-600">
                                                    <tbody>
                                                        <tr>
                                                            <th colspan="2"
                                                                class="text-xs text-gray-800 uppercase dark:text-gray-800 px-4 py-2 rounded-t-sm mt-4">
                                                                Operator Infos
                                                            </th>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row"
                                                                class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                First name
                                                            </td>
                                                            <td scope="row"
                                                                class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Last name
                                                            </td>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row"
                                                                class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.operator.first_name }}
                                                            </td>
                                                            <td scope="row"
                                                                class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.operator.last_name }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="col-md-12 text-left">
                                                    <span class="text-xs text-muted">
                                                        <i class="fa-solid fa-clock"></i>{{ formatDateCalendar(transaction.approved_date) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on January 13th, 2022</time> -->
                                            <!-- <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Get access to over 20+ pages including a dashboard layout, charts, kanban board, calendar, and pre-order E-commerce & Marketing pages.</p> -->

                                        </li>
                                        <!-- Approved End -->

                                        <!-- Completed -->
                                        <li class="mb-10 ms-6 shadow-lg" v-if="transaction.status == 'completed'">
                                            <span class="absolute flex items-center justify-center w-3 h-3 bg-blue-100 rounded-full -start-1.5 ring-4 ring-white dark:ring-gray-300 dark:bg-blue-300">
                                                <svg class="w-2 h-2 text-blue-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </span>
                                            <h3 class="flex items-center mb-1 text-normal font-semibold text-gray-700">
                                                COMPLETED
                                                <!-- <span class="text-orange-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded ms-3">Pending</span> -->
                                            </h3>

                                            <div class="relative overflow-x-auto">
                                                <table class="w-full text-sm text-center rtl:text-right text-gray-600 dark:text-gray-600">
                                                    <tbody>
                                                        <tr>
                                                            <th colspan="2" class="text-xs text-gray-800 uppercase dark:text-gray-800 px-4 py-2 rounded-t-sm mt-4">
                                                                Account details
                                                            </th>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Account name
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-600 fw-bold whitespace-nowrap dark:text-gray px-2 py-1">
                                                                Account number
                                                            </td>
                                                        </tr>
                                                        <tr class="bg-white dark:bg-gray-300">
                                                            <td scope="row" class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.account.name }}
                                                            </td>
                                                            <td scope="row" class="font-medium text-gray-500 whitespace-nowrap dark:text-gray px-2 py-1">
                                                                {{ transaction.account.phone }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="col-md-12 text-left">
                                                    <span class="text-xs text-muted">
                                                        <i class="fa-solid fa-clock"></i>{{ formatDateCalendar(transaction.completed_at) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on January 13th, 2022</time> -->
                                            <!-- <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Get access to over 20+ pages including a dashboard layout, charts, kanban board, calendar, and pre-order E-commerce & Marketing pages.</p> -->

                                        </li>
                                        <!-- Completed End -->
                                    </ol>

                                </div>
                                <!-- Timeline End -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-right">
                                    <button class="btn btn-sm bg-gray-200 dark:bg-gray-200 hover:bg-gray-300 mr-4 mb-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop">View transaction history</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Transaction activits modal -->
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header row text-center">
                                    <div class="col-md-12 text-center">
                                        <h6 v-if="transaction" class="text-uppercase"> Fortuna Corporation </h6>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <h6 v-if="transaction" class="text-uppercase"> Transaction #{{ transaction.id }} </h6>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 v-if="transaction && transaction.user" class="text-capitalize"> AGENT: <div class="d-inline-block">{{ transaction.user.first_name }} {{ transaction.user.last_name }}</div> </h6>
                                    </div>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                        </div>
                        <div class="modal-body">
                            <!-- Vertical timeline history -->
                            <div v-if="transaction && transaction.transaction_activits">
                                <ol class="relative border-s border-gray-200 dark:border-gray-700" v-for="(activity, index) in transaction.transaction_activits" :key="activity.id">
                                    <li class="mb-10 ms-4">
                                        <!-- Initialized -->
                                        <div v-if="activity.step == 'initialized'">
                                            <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-700 dark:bg-gray-700">
                                            </div>
                                            <time class="mb-1 text-normal font-semibold leading-none text-gray-600 dark:text-gray-600 text-uppercase">{{ index + 1 }} {{ activity.step }}</time>
                                            <!-- <h3 class="text-normal font-normal text-gray-600 dark:text-gray text-capitalize" v-if="activity.step == 'initialized' || activity.step == 'resubmitted' || activity.step == 'canceled'">{{ activity.type }} ({{ activity.operation }})</h3> -->
                                            <div class="mb-4 text-base font-normal text-gray-600 dark:text-gray-500">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Sender name</div>
                                                        <div class="tr">{{ activity.sender.name }}</div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Sender Phone</div>
                                                        <div class="tr">{{ activity.sender.phone }}</div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Receiver name</div>
                                                        <div class="tr">{{ activity.receiver.name }}</div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Receiver phone</div>
                                                        <div class="tr">{{ activity.receiver.phone }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-800">Sender amount</div>
                                                        <div class="tr">{{ activity.sender_amount }}
                                                            <span v-if="activity.operation === 'transfert_si' || activity.operation === 'transfert_si'">RD$</span>
                                                            <span v-else>HTG</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-800">Receiver amount</div>
                                                        <div class="tr">{{ activity.receiver_amount }}
                                                            <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">HTG</span>
                                                            <span v-else>RD$</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 text-capitalize">
                                                        <div class="tr text-semibold text-gray-800">Type</div>
                                                        <div class="tr">{{ activity.type }}</div>
                                                    </div>

                                                    <div class="col-md-3 text-capitalize">
                                                        <div class="tr text-semibold text-gray-800">Operation</div>
                                                        <div class="tr">{{ activity.operation }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span class="text-xs text-muted">
                                                            <i class="fa-solid fa-clock"></i>{{ formatDateCalendar(activity.initialized_date) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Initialized -->

                                        <!-- Edited -->
                                        <div v-if="activity.step == 'edited'">
                                            <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-700 dark:bg-gray-700">
                                            </div>
                                            <time class="mb-1 text-normal font-semibold leading-none text-gray-600 dark:text-gray-600 text-uppercase">{{ index + 1 }} {{ activity.step }}</time>
                                            <!-- <h3 class="text-normal font-normal text-gray-600 dark:text-gray text-capitalize" v-if="activity.step == 'initialized' || activity.step == 'resubmitted' || activity.step == 'canceled'">{{ activity.type }} ({{ activity.operation }})</h3> -->
                                            <div class="mb-4 text-base font-normal text-gray-600 dark:text-gray-500">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Sender name</div>
                                                        <div :class="[isSenderNameDifferent(activity.sender.name) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']"> {{ activity.sender.name }}</div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Sender Phone</div>
                                                        <div :class="[isSenderPhoneDifferent(activity.sender.phone) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']"> {{ activity.sender.phone }}</div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Receiver name</div>
                                                        <div :class="[isReceiverNameDifferent(activity.receiver.name ) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']"> {{ activity.receiver.name }}</div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Receiver phone</div>
                                                        <div :class="[isReceiverPhoneDifferent(activity.receiver.phone) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']"> {{ activity.receiver.phone }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-800">Sender amount</div>
                                                        <div :class="[isSenderAmountDifferent(activity.sender_amount) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.sender_amount }}
                                                            <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">RD$</span>
                                                            <span v-else>HTG</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-800">Receiver amount</div>
                                                        <div :class="[isReceiverAmountDifferent(activity.receiver_amount) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.receiver_amount }}
                                                            <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">HTG</span>
                                                            <span v-else>RD$</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 text-capitalize">
                                                        <div class="tr text-semibold text-gray-800">Type</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.type }}</div>
                                                    </div>

                                                    <div class="col-md-3 text-capitalize">
                                                        <div class="tr text-semibold text-gray-800">Operation</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.operation }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span class="text-xs text-muted">
                                                            <i class="fa-solid fa-clock"></i>{{
                                                                formatDateCalendar(activity.edited_date) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edited -->

                                        <!-- Disapproved -->
                                        <div v-if="activity.step == 'disapproved'">
                                            <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-700 dark:bg-gray-700">
                                            </div>
                                            <time class="mb-1 text-normal font-semibold leading-none text-gray-600 dark:text-gray-600 text-uppercase">{{ index + 1 }} {{ activity.step }}</time>
                                            <!-- <h3 class="text-normal font-normal text-gray-600 dark:text-gray text-capitalize" v-if="activity.step == 'initialized' || activity.step == 'resubmitted' || activity.step == 'canceled'">{{ activity.type }} ({{ activity.operation }})</h3> -->
                                            <div class="mb-4 text-base font-normal text-gray-600 dark:text-gray-500">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="tr text-semibold text-gray-700">Operator code</div>
                                                        <div class="tr">{{ activity.operator.code }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="tr text-semibold text-gray-700">Operator first name
                                                        </div>
                                                        <div class="tr">{{ activity.operator.first_name }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="tr text-semibold text-gray-700">Operator last name</div>
                                                        <div class="tr">{{ activity.operator.last_name }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span class="text-xs text-muted">
                                                            <i class="fa-solid fa-clock"></i>{{
                                                                formatDateCalendar(activity.disapproved_date) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End disapproved -->

                                        <!-- Resubmitted -->
                                        <div v-if="activity.step == 'resubmitted'">
                                            <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-700 dark:bg-gray-700">
                                            </div>
                                            <time class="mb-1 text-normal font-semibold leading-none text-gray-600 dark:text-gray-600 text-uppercase">{{ index + 1 }} {{ activity.step }}</time>
                                            <!-- <h3 class="text-normal font-normal text-gray-600 dark:text-gray text-capitalize" v-if="activity.step == 'initialized' || activity.step == 'resubmitted' || activity.step == 'canceled'">{{ activity.type }} ({{ activity.operation }})</h3> -->
                                            <div class="mb-4 text-base font-normal text-gray-600 dark:text-gray-500">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Sender name</div>
                                                        <div :class="[isTransactionValueDifferent(activity.sender.name) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.sender.name }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Sender Phone</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.sender.phone }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Receiver name</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.receiver.name }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-700">Receiver phone</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.receiver.phone }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-800">Sender amount</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.sender_amount }}
                                                            <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">RD$</span>
                                                            <span v-else>HTG</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="tr text-semibold text-gray-800">Receiver amount</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.receiver_amount }}
                                                            <span v-if="transaction.operation === 'transfert_si' || transaction.operation === 'transfert_si'">HTG</span>
                                                            <span v-else>RD$</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 text-capitalize">
                                                        <div class="tr text-semibold text-gray-800">Type</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.type }}</div>
                                                    </div>

                                                    <div class="col-md-3 text-capitalize">
                                                        <div class="tr text-semibold text-gray-800">Operation</div>
                                                        <div :class="[isTransactionValueDifferent(activity) ? 'badge bg-red-100 text-normal rounded dark:bg-red-200 dark:text-gray-400' : '', 'tr']">
                                                            {{ activity.operation }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span class="text-xs text-muted">
                                                            <i class="fa-solid fa-clock"></i>{{ formatDateCalendar(activity.resubmitted_date) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Resumitted -->

                                        <!-- Approved -->
                                        <div v-if="activity.step == 'approved'">
                                            <div
                                                class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-700 dark:bg-gray-700">
                                            </div>
                                            <time
                                                class="mb-1 text-normal font-semibold leading-none text-gray-600 dark:text-gray-600 text-uppercase">{{
                                                    index + 1 }} {{ activity.step }}</time>
                                            <!-- <h3 class="text-normal font-normal text-gray-600 dark:text-gray text-capitalize" v-if="activity.step == 'initialized' || activity.step == 'resubmitted' || activity.step == 'canceled'">{{ activity.type }} ({{ activity.operation }})</h3> -->
                                            <div class="mb-4 text-base font-normal text-gray-600 dark:text-gray-500">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="tr text-semibold text-gray-700">Operator code</div>
                                                        <div class="tr">{{ activity.operator.code }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="tr text-semibold text-gray-700">Operator first name
                                                        </div>
                                                        <div class="tr">{{ activity.operator.first_name }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="tr text-semibold text-gray-700">Operator last name</div>
                                                        <div class="tr">{{ activity.operator.last_name }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span class="text-xs text-muted">
                                                            <i class="fa-solid fa-clock"></i>{{
                                                                formatDateCalendar(activity.approved_date) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Approved -->

                                        <!-- Completed -->
                                        <div v-if="activity.step == 'completed'">
                                            <div
                                                class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-700 dark:bg-gray-700">
                                        </div>
                                        <time
                                            class="mb-1 text-normal font-semibold leading-none text-gray-600 dark:text-gray-600 text-uppercase">{{
                                            index + 1 }} {{ activity.step }}</time>
                                        <!-- <h3 class="text-normal font-normal text-gray-600 dark:text-gray text-capitalize" v-if="activity.step == 'initialized' || activity.step == 'resubmitted' || activity.step == 'canceled'">{{ activity.type }} ({{ activity.operation }})</h3> -->
                                        <div class="mb-4 text-base font-normal text-gray-600 dark:text-gray-500">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="tr text-semibold text-gray-700">Account ID</div>
                                                    <div class="tr">{{ activity.account.id }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="tr text-semibold text-gray-700">Account name</div>
                                                    <div class="tr">{{ activity.account.name }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="tr text-semibold text-gray-700">Account phone</div>
                                                    <div class="tr">{{ activity.account.phone }}</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="text-xs text-muted">
                                                        <i class="fa-solid fa-clock"></i>{{
                                                        formatDateCalendar(activity.completed_date) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <!-- End Completed  -->

                                        <div class="row mb-5">
                                            <div class="col-md-12">
                                                <hr />
                                            </div>
                                        </div>

                                        <!-- Debit / Credit tab -->
                                            <!-- Agent Debit / Credit -->
                                            <div class="container" v-if="activity.step == 'completed'" v-show="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer' || userrole.name == 'agent'">
                                                <div class="row bg-gray-200">
                                                    <div class="col-md-12 text-center text-xs">AGENT</div>
                                                </div>
                                                <div class="row text-xs">
                                                    <div class="col">
                                                    <div class="row">
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">DEBIT</div>
                                                            <div v-if="activity.operation === 'transfert_si' || activity.operation === 'transfert'"> {{ activity.sender_amount }} RD$</div>
                                                            <div v-if="activity.operation === 'reception_si' || activity.operation === 'reception'"> 0.0 RD$</div>
                                                        </div>
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">CREDIT</div>
                                                            <div v-if="activity.operation === 'transfert_si' || activity.operation === 'transfert'"> 0.0 RD$</div>
                                                            <div v-if="activity.operation === 'reception_si' || activity.operation === 'reception'"> {{ activity.receiver_amount }} RD$</div>
                                                        </div>
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">RECHARGE</div>
                                                            <div> {{ activity.new_agent_investment }} RD$</div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="col">
                                                    <div class="row">
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">DEBIT</div>
                                                           <div>0.0 RD$</div>
                                                        </div>
                                                        <div class="col-md-4 text-center border-left">
                                                            <div class="fw-bold">CREDIT</div>
                                                            <div>{{ activity.new_agent_balance - activity.current_agent_balance }} RD$</div>
                                                        </div>
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">BALANCE</div>
                                                            <div>{{ activity.new_agent_balance }} RD$</div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Agent Debit / Credit -->

                                             <!-- Operator Credit -->
                                             <div class="container mt-5" v-if="activity.step == 'completed'" v-show="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer' || userrole.name == 'operator'">
                                                <div class="row bg-gray-200">
                                                    <div class="col-md-12 text-center text-xs">OPERATOR</div>
                                                </div>
                                                <div class="row text-xs">
                                                    <div class="col">
                                                    <div class="row">
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">DEBIT</div>
                                                            <div>0.0 RD$</div>
                                                        </div>
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">CREDIT</div>
                                                            <div>{{ formatDecimalNumber(activity.new_operator_balance - activity.current_operator_balance) }} RD$</div>
                                                        </div>
                                                        <div class="col-md-4 text-center">
                                                            <div class="fw-bold">BALANCE</div>
                                                            <div> {{ formatDecimalNumber(activity.new_operator_balance) }} RD$</div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Operator Credit -->

                                            <!-- Reference Credit -->
                                            <div class="container mt-5" v-if="activity.step == 'completed'" v-show="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                                                            <div class="row bg-gray-200">
                                                                <div class="col-md-12 text-center text-xs">REFERENCE</div>
                                                            </div>
                                                            <div class="row text-xs">
                                                                <div class="col">
                                                                <div class="row">
                                                                    <div class="col-md-4 text-center">
                                                                        <div class="fw-bold">DEBIT</div>
                                                                        <div>0.0 RD$</div>
                                                                    </div>
                                                                    <div class="col-md-4 text-center">
                                                                        <div class="fw-bold">CREDIT</div>
                                                                        <div>{{ formatDecimalNumber(activity.new_reference_balance - activity.current_reference_balance) }} RD$</div>
                                                                    </div>
                                                                    <div class="col-md-4 text-center">
                                                                        <div class="fw-bold">BALANCE</div>
                                                                        <div> {{ formatDecimalNumber(activity.new_reference_balance) }} RD$</div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>
                                            </div>
                                            <!-- End Reference Credit -->

                                            <!-- Account Credit -->
                                            <div class="container mt-5" v-if="activity.step == 'completed'" v-show="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                                                <div class="row bg-gray-200">
                                                    <div class="col-md-12 text-center text-xs">ACCOUNT</div>
                                                </div>
                                                <div class="row text-xs">
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center">
                                                                <div class="fw-bold">DEBIT</div>
                                                                <div v-if="activity.operation === 'transfert_si' || activity.operation === 'transfert'"> {{ formatDecimalNumber( activity.current_account_balance - activity.new_account_balance) }} HTG</div>
                                                                <div v-if="activity.operation === 'reception_si' || activity.operation === 'reception'"> 0.0 HTG</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="fw-bold">CREDIT</div>
                                                                <div v-if="activity.operation === 'transfert_si' || activity.operation === 'transfert'"> 0.0 HTG </div>
                                                                <div v-if="activity.operation === 'reception_si' || activity.operation === 'reception'">{{ formatDecimalNumber(activity.new_account_balance - activity.current_account_balance) }} </div>
                                                                <!-- <div>{{ formatDecimalNumber(activity.new_account_balance - activity.current_account_balance) }} HTG</div> -->
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="fw-bold">BALANCE</div>
                                                                <div> {{ formatDecimalNumber(activity.new_account_balance) }} HTG</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Account Credit -->

                                        <!-- End Debit / Credit tab -->
                                    </li>
                                </ol>
                            </div>
                            <!-- End vertical timeline history -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm bg-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Transaction activits modal end -->
    </div>
</div>
</template>
    
<script>
import { onMounted, reactive, ref } from 'vue';
import useTransactions from "../../services/transactionservices";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';

export default {
    props: ['id', 'user', 'userrole'],
    setup(props) {
        const { can, cannot } = useAbility();
        const { getTransactionDetails, transaction } = useTransactions();
        const { formatDecimalNumber, formatDateCalendar, formatDate } = useUtils();

        const form = reactive({
            rechargeOrProfitShow: '',
        });

        onMounted(async () => {
            await getTransactionDetails({ 'transaction_id': props.id })
            console.log(transaction.value.transaction_activits[0].sender_amount)
        })

        const isSenderNameDifferent = (name) => {
            if (transaction.value.transaction_activits[0].sender_name != name) {
                return true;
            }else{
                return false;
            }
        };

        const isSenderPhoneDifferent = (phone) => {
            if (transaction.value.transaction_activits[0].sender_phone != phone) {
                return true;
            }else{
                return false;
            }
        };

        const isSenderAmountDifferent = (amount) => {
            if (transaction.value.transaction_activits[0].sender_amount !== amount) {
                return true;
            }else{
                return false;
            }
        };

        const isReceiverNameDifferent = (name) => {
            if (transaction.value.transaction_activits[0].sender_name != name) {
                return true;
            }else{
                return false;
            }
        };

        const isReceiverPhoneDifferent = (phone) => {
            if (transaction.value.transaction_activits[0].sender_phone != phone) {
                return true;
            }else{
                return false;
            }
        };

        const isReceiverAmountDifferent = (amount) => {
            if (transaction.value.transaction_activits[0].sender_amount != amount) {
                return true;
            }else{
                return false;
            }
        };

        const isTransactionValueDifferent = (data) =>{
            return false;
        }

        return {
            form,
            can,
            cannot,
            formatDecimalNumber,
            formatDateCalendar,
            formatDate,
            transaction,
            isSenderNameDifferent,
            isSenderPhoneDifferent,
            isSenderAmountDifferent,
            isReceiverNameDifferent,
            isReceiverPhoneDifferent,
            isReceiverAmountDifferent,
            isTransactionValueDifferent,
            props
        }
    },
}
</script>