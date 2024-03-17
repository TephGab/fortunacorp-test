<template>
  <!-- AddSystemBankAccount Card -->
  <div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-md-12 col-lg-12 col-xl-12 m-0 p-0">
        <div class="card">
          <div class="card-body pb-0 m-0">
            <!-- Bank account -->
            <div class="row border border-solid border-gray-200 bg-gray-100 m-0 mb-1 pt-1 pb-1"
              v-for="(account, index) in bankAccounts" :key="index">
              <div class="mb-3 col-md-6">
                <label for="percentage" class="form-label">Bank name</label>
                <input type="text" class="form-control" v-model="account.bank_name">
                <div v-if="errors.bank_name" class="form-text error text-danger">{{ errors.bank_name[0] }}</div>
              </div>

              <div class="mb-3 col-md-6">
                <label for="percentage" class="form-label">Currency</label>
                <input type="text" class="form-control" v-model="account.currency">
                <div v-if="errors.currency" class="form-text error text-danger">{{ errors.currency[0] }}</div>
              </div>

              <div class="mb-3 col-md-6">
                <label for="percentage" class="form-label">Account name</label>
                <input type="text" class="form-control" v-model="account.account_name">
                <div v-if="errors.account_name" class="form-text error text-danger">{{ errors.account_name[0] }}</div>
              </div>

              <div class="mb-3 col-md-6">
                <label for="account_number" class="form-label">Account number</label>
                <input type="text" class="form-control" v-model="account.account_number">
                <div v-if="errors.account_number" class="form-text error text-danger">{{ errors.account_number[0] }}</div>
              </div>

              <div class="mb-3 col-md-6">
                <label for="account_number" class="form-label">Balance</label>
                <input type="number" class="form-control" v-model="account.account_balance">
                <div v-if="errors.account_balance" class="form-text error text-danger">{{ errors.account_balance[0] }}
                </div>
              </div>

              <div class="col-md-12 m-0 p-0 text-right">
                <span @click.prevent="removeBankAccount(index)">
                  <i class="fas fa-trash"></i><span class="text-xs"> Remove entry</span>
                </span>
              </div>
            </div>

            <div v-show="form.bankAccountSectionShow" class="col-md-12 m-0 p-0 text-right">
              <button class="btn bg-gray-300 btn-sm text-xs mt-2" @click.prevent="addBAnkAccount">
                <i class="fas fa-plus"></i> Add more bank accounts
              </button>
            </div>
            <!-- Bank account end -->
          </div>
          <div class="col-md-12">
            <hr class="m-2"/>
            <button class="btn btn-primary" @click="createSystemBankAccount">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- AddSystemBankAccount Card end -->
</template>
  
<script>
import { ref, reactive } from 'vue';
import useUtils from "../../services/utilsservices";
export default {
  props: ['user'],
  setup(props) {
    const { formatDecimalNumber, storeSystemBankAccount } = useUtils();
    const errors = ref([]);

    const form = reactive({
      bankAccountSectionShow: true
    });

    const state = reactive({
      bankAccounts:
        [{
          bank_name: '',
          account_number: '',
          account_name: '',
          account_balance: 0,
          currency: ''
        }]
    });

    const addBAnkAccount = () => {
      state.bankAccounts.push({
        bank_name: '',
        account_number: '',
        account_name: '',
        account_balance: '',
        currency: ''
      });
    };

    const removeBankAccount = (index) => {
      state.bankAccounts.splice(index, 1);
    };

    const createSystemBankAccount = async() => {
     await storeSystemBankAccount({bankAccounts: state.bankAccounts});
    }

    return {
      props,
      form,
      errors,
      formatDecimalNumber,
      bankAccounts: state.bankAccounts,
      addBAnkAccount,
      removeBankAccount,
      createSystemBankAccount
    }
  },
}
</script>