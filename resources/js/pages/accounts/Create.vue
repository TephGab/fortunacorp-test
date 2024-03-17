<template>
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <h3 class="card-title">New Account</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                <div class="row">
                 
                  <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" v-model="form.name">
                    <div v-if="errors.name" class="form-text error text-danger">{{ errors.name[0] }}</div>
                  </div>
  
                  <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <vue-tel-input v-model="form.phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international"
                      :onlyCountries="['ht']"></vue-tel-input>
                    <div v-if="errors.phone" class="form-text error text-danger">{{ errors.phone[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                  <label for="type" class="form-label">Type</label>
                  <select class="form-control" id="type" v-model="form.type">
                    <option v-for="option in options" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                  <div v-if="errors.type" class="form-text error text-danger">{{ errors.type[0] }}</div>
                </div>
                
                <div class="mb-3 col-md-6">
                  <label for="type" class="form-label">Category</label>
                  <select class="form-control" id="type" v-model="form.category">
                    <option v-for="categoryOption in categoryOptions" :value="categoryOption.value" :key="categoryOption.text">
                      {{ categoryOption.text }}
                    </option>
                  </select>
                  <div v-if="errors.category" class="form-text error text-danger">{{ errors.type[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Balance</label>
                  <input type="text" class="form-control" id="balance" v-model="form.balance">
                  <div v-if="errors.balance" class="form-text error text-danger">{{ errors.balance[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="account" class="form-label">Select Operator</label>
                  <Select2 class="form-label" v-model="form.operator_id" :options="operators" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                  <div v-if="errors.operator_id" class="form-text error text-danger"> {{ errors.operator_id[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Full wallet?</label>
                  <div class="form-control border-0 p-0">
                    <input type="checkbox" id="checkbox" v-model="form.full_wallet" />
                  </div>
                </div>

                </div>

              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <button class="btn btn-primary mt-3 mr-3" @click.prevent="createAccount" :disabled="isButtonLoading ? true : false"> Submit
                <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
              </button>
              <!-- <button class="btn btn-primary" @click.prevent="createAccount">Submit </button>
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
  import useUsers from "../../services/usersservices";
  
  export default {
    props: ['user'],
    setup(props) {

      const { getOperators, operators } = useUsers();
      
      const form = reactive({
        name: '',
        phone: '',
        full_wallet: true,
        balance: 0,
        category: 'normal',
        type: 'moncash',
        operator_id: '',
      });

      const options = ref([
      { text: 'Moncash', value: 'moncash' },
      { text: 'Natcash', value: 'natcash' },
      ]);

      const categoryOptions = ref([
      { text: 'Business', value: 'business' },
      { text: 'Normal', value: 'normal' },
      ]);
  
      const { storeAccount, buttonIsLoading, isButtonLoading } = useAccounts();
      const errors = ref([]);
  
      onMounted(async() => {
        await getOperators();
      })
  
      const createAccount = async () => {
        try {
          isButtonLoading.value = true;
          await storeAccount({ ...form });
        } catch (error) {
          isButtonLoading.value = false;
          errors.value = error.response.data.errors;
        }
      }
  
      return {
        form,
        categoryOptions,
        options,
        createAccount,
        errors,
        buttonIsLoading,
        operators,
        isButtonLoading
      };
    }
  }
  </script>