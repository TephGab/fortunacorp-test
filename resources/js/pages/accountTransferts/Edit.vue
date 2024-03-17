<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Account Transfert</h3>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                            <div class="row text-center">
                               
                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Amount</label>
                                    <div class="input-group text-sm">
                                    <input type="number" class="form-control border-right-0 rounded-start" v-model="form.amount">
                                    <span class="input-group-addon">
                                        <span class="form-control border-left-0 bg-light rounded-end pl-1 pr-1 pt-2 text-xs">HTG</span>
                                    </span>
                                    </div>
                                    <div v-if="errors.amount" class="form-text error text-danger">{{ errors.amount[0] }}</div> 
                                </div>
                                    
                                    <div class="mb-3 col-md-6">
                                        <label for="phone" class="form-label">From</label>
                                        <div class="form-control text-uppercase">{{ account.phone }} __ {{ account.name }} ( {{ account.type }} )</div>
                                    </div>

                                    <!-- <hr> -->

                                    <div class="mb-3 col-md-12">
                                        <label for="account" class="form-label">To</label>
                                        <Select2 class="form-label text-uppercase" v-model="form.account_receiver_id" :options="accountstransferts"
                                            :settings="{ width: '100%', textTransform: 'uppercase' }" />
                                        <div v-if="errors.account_receiver_id" class="form-text error text-danger">{{ errors.account_receiver_id[0] }}</div> 
                                    </div>

                                    <div v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'" class="mb-3 col-md-12 text-left">
                                        <label for="countable" class="form-label">Count?</label>
                                        <div class="text-uppercase">
                                            <input type="checkbox" id="checkbox" v-model="form.countable" />
                                        </div>
                                    </div>
                               </div> 
                           
                        </form>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button @click.prevent=transfertAccount(account.id) class="btn btn-primary" :disabled="isLoading ? true : false">
                              <div class="d-flex justify-content-center align-items-center">
                               <span v-show="!isLoading"> Transfert </span>
                               <img v-show="isLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
                                alt="button-spinner" />
                               <span v-show="isLoading" style="font-size: 10px;"> Transferring ...</span>
                              </div>
                        </button>
                        <!-- <button class="btn btn-primary" @click.prevent=transfertAccount(account.id)> Transfert </button>
                        <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
                            alt="button-spinner" /> -->
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
import useAccounts from "../../services/accountservices";
import useAccountTransferts from "../../services/accounttransfertservices";
import useUtils from "../../services/utilsservices";
import Swal from "sweetalert2";

export default {
    props: ['id', 'user', 'userrole'],
    setup(props) {

        const { getAccount, account, buttonIsLoading } = useAccounts();
        const { getAccountsTransferts, accountstransferts, storeAccountTransfert } = useAccountTransferts();
        const { isLoading } = useUtils();
        const errors = ref([]);

        const form = reactive({
            amount: '',
            account_receiver_id: '',
            countable: true,
        });

        onMounted(async () => {
            await getAccount(props.id);
            const data = new FormData();
            data.append('type', account.value.type)
            await getAccountsTransferts(data);
        })

        const transfertAccount = async (id) => {
            try {
                isLoading.value = true;
                const data = new FormData();
                data.append('amount', form.amount);
                data.append('account_sender_id', props.id);
                data.append('account_receiver_id', form.account_receiver_id);
                data.append('countable', form.countable == true ? 1 : 0);
                await storeAccountTransfert(data);
                isLoading.value = false;
                // d.append('type', account.value.type)
                // await getAccountsTransferts(d);
            } catch (error) {
                isLoading.value = false;
                errors.value = error.response.data.errors;
                if(error.response.data.errors){
                if (error.response.data.errors.limit_reached[0]) {
                        Swal.fire({
                        text: 'Account Transfert limit reached!',
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


        return {
            account,
            form,
            transfertAccount,
            accountstransferts,
            errors,
            buttonIsLoading,
            isLoading
        };
    }
}
</script>