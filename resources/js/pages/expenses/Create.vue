<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent text-center">
                        <h3 class="card-title">Add expense</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">

                            <!-- Expense tabs -->
                            <div class="mt-2 mb-3">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                            aria-selected="true" @click="makeUserAccountExpense">System account</button>
                                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-profile" type="button" role="tab"
                                            aria-controls="nav-profile" aria-selected="false"
                                            @click="makeBankAccountExpense">Bank Account</button>
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
                                                <div v-if="errors.amount" class="form-text error text-danger">{{
                                                    errors.amount[0] }}</div>
                                                <div v-if="errors.insufficient_system_funds"
                                                    class="form-text error text-danger">{{
                                                        errors.insufficient_system_funds[0] }}</div>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="type" class="form-label text-gray-600">Currency</label>
                                                <select class="form-control" id="currency" v-model="form.currency" required>
                                                    <option v-for="option in currencyOptions" :value="option.value"
                                                        :key="option.text">
                                                        {{ option.text }}
                                                    </option>
                                                </select>

                                                <div v-if="errors.currency" class="form-text error text-danger">{{
                                                    errors.currency[0] }}</div>
                                            </div>

                                            <!-- <hr class="hr hr-blurry" /> -->

                                            <div class="mb-3 col-md-6">
                                                <div class="d-flex justify-content-between">
                                                    <label for="type" class="form-label text-gray-600">Category</label>
                                                    <button type="button" class="btn btn-default btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        @click="optionShow">
                                                        Add category
                                                    </button>
                                                </div>
                                                <select class="form-control" v-model="form.type" v-show="showOption">
                                                    <option v-for="expensetype in expensetypes"
                                                        :value="expensetype.category" :key="expensetype.id">
                                                        {{ expensetype.category }}
                                                    </option>
                                                </select>

                                                <div v-show="!showOption">
                                                    <input type="text" class="form-control" placeholder="Add new category"
                                                        v-model="form.type" disabled>
                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">New
                                                                    category</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Add new category" v-model="form.type">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="modal-footer">
        <button type="button" class="btn btn-primary" @click="closeModal (form.type)">Ok</button>
      </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="errors.type" class="form-text error text-danger">{{
                                                    errors.type[0] }}</div>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="type" class="form-label text-gray-600">Deduct from</label>
                                                <select class="form-control" id="currency" v-model="form.deduct_from">
                                                    <option v-for="option in deductFromOptions" :value="option.value"
                                                        :key="option.text">
                                                        {{ option.text }}
                                                    </option>
                                                </select>
                                                <div v-if="errors.deduct_from" class="form-text error text-danger">{{
                                                    errors.deduct_from[0] }}</div>
                                            </div>

                                            <!-- <hr class="hr hr-blurry"/> -->

                                            <div class="mb-3 col-md-12">
                                                <label for="type" class="form-label text-gray-600">Note</label>
                                                <textarea class="form-control" placeholder="Add note"
                                                    v-model="form.comment"></textarea>
                                                <div v-if="errors.comment" class="form-text error text-danger">{{
                                                    errors.comment[0] }}</div>
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
                            <!-- End expense tabs -->
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix text-center">
                        <button class="btn btn-success" @click.prevent="createExpense" :disabled="isLoading ? true : false">
                            Done
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
import useExpenses from "../../services/expenseservices.js";

export default {
    props: ['user'],
    setup(props) {
        const { getExpensetypes, expensetypes, storeExpense, isLoading } = useExpenses();
        const errors = ref([]);
        const showOption = ref(true);

        const form = reactive({
            bank_account: false,
            amount: '',
            currency: 'pesos',
            deduct_from: 'system_revenues',
            type: 'Fuel',
            comment: ''
        });

        onMounted(async () => {
            await getExpensetypes();
        })


        const currencyOptions = ref([
            { text: 'PESOS', value: 'pesos' },
            // { text: 'US', value: 'us' },
            // { text: 'HTG', value: 'htg' },
        ]);

        const typeOptions = ref([
            { text: 'FUEL', value: 'fuel' },
            { text: 'OTHER', value: 'other' },
            // { text: 'HTG', value: 'htg' },
        ]);

        const deductFromOptions = ref([
            { text: 'SYSTEM FUNDS', value: 'system_funds' },
            { text: 'SYSTEM REVENUES', value: 'system_revenues' },
            { text: 'PERSONAL FUNDS', value: 'personal_funds' },
        ]);

        const createExpense = async () => {
            try {
                await storeExpense({
                    'amount': form.amount,
                    'currency': form.currency,
                    'deduct_from': form.deduct_from,
                    'type': form.type,
                    'comment': form.comment,
                })
            } catch (error) {
                errors.value = error.response.data.errors;
                isLoading.value = false;
            }
        }


        const closeModal = async (type) => {
            // form.type = type;
            // console.log(type)
        };

        const optionShow = async (type) => {
            showOption.value = !showOption.value;
        };



        return {
            form,
            errors,
            currencyOptions,
            deductFromOptions,
            typeOptions,
            createExpense,
            isLoading,
            expensetypes,
            closeModal,
            showOption,
            optionShow
        };
    }
}
</script>