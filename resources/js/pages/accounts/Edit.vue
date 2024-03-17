<template>
  <!-- Edit mode -->
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12 mt-2">
          <div class="card-header border-transparent">
              <!-- <h6>Edit mode</h6> -->
              <button v-if="userrole.name == 'super-admin' || userrole.name == 'system-engineer'" @click="editModeSwicth" style="width: 110px;" class="d-flex align-items-center fw-bold text-sm"> <span>Edit mode </span>
                 <img v-if="editMode" class="img-fluid img-responsive ml-1" style="width: 30%;" src="/img/switch-on.png" alt="switch-on"/>
                 <img v-if="!editMode" class="img-fluid img-responsive ml-1" style="width: 30%;" src="/img/switch-off.png" alt="switch-on"/>
              </button>
          </div>
          <!-- Card -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary" v-if="editMode">
            <div class="card-header border-transparent">
              <h3 class="card-title">New Account</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                <div class="row">
                 
                  <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" v-model="account.name">
                    <div v-if="errors.name" class="form-text error text-danger">{{ errors.name[0] }}</div>
                  </div>
  
                  <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <vue-tel-input v-model="account.phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international" :onlyCountries="['ht']"></vue-tel-input>
                    <div v-if="errors.phone" class="form-text error text-danger">{{ errors.phone[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                  <label for="type" class="form-label">Type</label>
                  <select class="form-control" id="type" v-model="account.type">
                    <option v-for="option in options" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                  <div v-if="errors.type" class="form-text error text-danger">{{ errors.type[0] }}</div>
                </div>
                
                <div class="mb-3 col-md-6">
                  <label for="type" class="form-label">Category</label>
                  <select class="form-control" id="type" v-model="account.category">
                    <option v-for="categoryOption in categoryOptions" :value="categoryOption.value" :key="categoryOption.text">
                      {{ categoryOption.text }}
                    </option>
                  </select>
                  <div v-if="errors.category" class="form-text error text-danger">{{ errors.type[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Balance</label>
                  <input type="number" class="form-control" id="balance" v-model="account.balance">
                  <div v-if="errors.balance" class="form-text error text-danger">{{ errors.balance[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="account" class="form-label">Select Operator</label>
                  <Select2 class="form-label" v-model="account.user_id" :options="operators" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                  <div v-if="errors.operator_id" class="form-text error text-danger"> {{ errors.operator_id[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Full wallet?</label>
                  <div class="form-control border-0 p-0">
                    <input type="checkbox" id="checkbox" v-model="account.full_wallet" :checked="account.full_wallet == 1 ? true : false"/>
                  </div>
                </div>

                </div>

              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <!-- <button class="btn btn-primary" @click.prevent=editAccount(account.id)>Submit </button>
              <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" /> -->
              <button class="btn btn-primary mt-3 mr-3" @click.prevent="editAccount(account.id)" :disabled="isButtonLoading ? true : false"> Update
                <img v-show="isButtonLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner"/>
              </button>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->

          <!-- Account Activity -->
            <Accountactivity :accountId = props.id v-if="!editMode"/>
          <!-- Account Activity End -->
        </div>
      </div>
     </div>
     <!-- Edit mode End -->
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useAccounts from "../../services/accountservices";
  import useUsers from "../../services/usersservices";
  import Accountactivity from '../accountActivity/Index.vue';
  
  export default {
    props: ['id', 'user', 'userrole'],
    components: {
      Accountactivity,
    },
    setup(props) {
      const { getAccount, account, updateAccount, buttonIsLoading, isButtonLoading } = useAccounts();
      const { getOperators, operators } = useUsers();
      const errors = ref([]);
      const editMode = ref(false);

      const form = reactive({
        name: '',
        phone: '',
        full_wallet: true,
        solde: 0,
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
  
      onMounted(async() => {
        await getAccount(props.id);
        await getOperators();
      })
  
      const editAccount = async (id) => {
        try {
            isButtonLoading.value = true;
            await updateAccount(id);
        } catch (error) {
            errors.value = error.response.data.errors;
        }
      }

      const editModeSwicth = async () => {
        editMode.value = !editMode.value;
      }
  
      
      return {
        props,
        account,
        form,
        categoryOptions,
        options,
        editAccount,
        errors,
        buttonIsLoading,
        operators,
        editMode,
        editModeSwicth,
        isButtonLoading
      };
    }
  }
  </script>