<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Provider payment</h3>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label for="account" class="form-label">Select Provider</label>
                                    <Select2 class="form-label text-uppercase" v-model="form.provider_id"
                                        :options="providers" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                    <div v-if="errors.provider_id" class="form-text error text-danger">{{
                                        errors.provider_id[0] }}</div>
                                </div>

                                 <div class="mb-3 col-md-12">
                                    <label for="phone" class="form-label">Amount</label>
                                    <div class="input-group text-sm">
                                    <input type="number" class="form-control border-right-0 rounded-start" v-model="form.amount">
                                    <span class="input-group-addon">
                                        <span class="form-control border-left-0 bg-light rounded-end pl-1 pr-1 pt-2 text-xs">RD$</span>
                                    </span>
                                    </div>
                                    <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div> 
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button class="btn btn-primary" @click.prevent=createPayment> Pay </button>
                        <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
                            alt="button-spinner" />
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
import useProviderPayments from "../../services/providerpaymentservices";

export default {
    props: ['id', 'user'],
    setup(props) {
        const { getProvidersToPay, providers, storeProviderPayment, buttonIsLoading } = useProviderPayments();
        const errors = ref([]);

        const form = reactive({
            amount: '',
            provider_id: '',
        });

        onMounted(async () => {
            await getProvidersToPay();
        })

         const createPayment = async () => {
            try {
                const data = new FormData();
                data.append('amount', form.amount);
                data.append('provider_id', form.provider_id)

                await storeProviderPayment(data);
            } catch (error) {
                errors.value = error.response.data.errors;
            }
        } 


        return {
            form,
            providers,
            createPayment,
            buttonIsLoading,
            errors
        };
    }
}
</script>