<template>
  <div class="container-fluid">
    <div class="row">
      <div class="container-fluid"> <Breadcrumb /></div>
      <div class="col-md-12">
        <!--  -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <h3 class="card-title">
              New User
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
              <div class="row">
                <div class="mb-3 col-md-6 d-flex justify-content-center">
                  <div class="col-md-8 flex flex-col justify-center max-w-xs rounded-xl">
                    <img v-show="form.photoPreview" :src="form.photoPreview" alt="user_profile"
                      class="w-100 mx-auto rounded-full dark:bg-gray-200 aspect-square">
                    <img v-show="!form.photoPreview" src="/img/users/default-user.png" alt="user_profile"
                      class="w-100 mx-auto rounded-full dark:bg-gray-200 aspect-square">
                    <input type="file" accept="image/*" class="form-control" @change="imageSelected" />
                  </div>
                </div>
                <div class="col-md-6 m-0">
                  <div class="mb-3 col-md-12">
                    <label for="code" class="form-label text-gray-600">Code</label>
                    <input type="text" class="form-control" id="code" v-model="form.code">
                    <div v-if="errors.code" class="form-text error text-danger">{{ errors.code[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="email" class="form-label text-gray-600">Email</label>
                    <input type="email" class="form-control" id="email" v-model="form.email">
                    <div v-if="errors.email" class="form-text error text-danger">{{ errors.email[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="name" class="form-label text-gray-600">First name</label>
                    <input type="text" class="form-control" id="first_name" v-model="form.first_name">
                    <div v-if="errors.first_name" class="form-text error text-danger">{{ errors.first_name[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="name" class="form-label text-gray-600">Last name</label>
                    <input type="text" class="form-control" id="last_name" v-model="form.last_name">
                    <div v-if="errors.last_name" class="form-text error text-danger">{{ errors.last_name[0] }}</div>
                  </div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="phone" class="form-label text-gray-600">Phone</label>
                  <vue-tel-input v-model="form.phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international"
                    :onlyCountries="['ht', 'do']"></vue-tel-input>
                  <div v-if="errors.phone" class="form-text error text-danger">{{ errors.phone[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="address" class="form-label text-gray-600">Address</label>
                  <input type="text" class="form-control" id="address" v-model="form.address">
                  <div v-if="errors.address" class="form-text error text-danger">{{ errors.address[0] }}</div>
                </div>

                <div class="col-md-6 text-capitalize">
                  <label for="searchreference" class="form-label text-gray-600">Reference</label>
                  <Select2 class="form-label text-gray-600" v-model="form.reference" :options="references"
                    :settings="{ width: '100%', textTransform: 'uppercase' }" />
                  <div v-if="errors.reference" class="form-text error text-danger">{{ errors.reference[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="location" class="form-label text-gray-600">Location</label>
                  <input type="text" class="form-control" id="location" v-model="form.location">
                  <div v-if="errors.location" class="form-text error text-danger">{{ errors.location[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="percentage" class="form-label text-gray-600">Percentage</label>
                  <input type="number" class="form-control" id="percentage" v-model="form.percentage">
                  <div v-if="errors.percentage" class="form-text error text-danger">{{ errors.percentage[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="limit_min" class="form-label text-gray-600">Limit Min</label>
                      <div class="input-group">
                        <input type="number" class="form-control border-right-0 rounded-start" id="limit_min"
                          v-model="form.limit_min">
                        <span class="input-group-addon"><span
                            class="form-control border-left-0 bg-light rounded-end">RD$</span></span>
                      </div>
                      <div v-if="errors.limit_min" class="form-text error text-danger">{{ errors.limit_min[0] }}</div>
                    </div>

                    <div class="col-md-6">
                      <label for="limit_max" class="form-label text-gray-600">Limit Max</label>
                      <div class="input-group">
                        <input type="number" class="form-control border-right-0 rounded-start" id="limit_max"
                          v-model="form.limit_max">
                        <span class="input-group-addon"><span
                            class="form-control border-left-0 bg-light rounded-end">RD$</span></span>
                      </div>
                      <div v-if="errors.limit_max" class="form-text error text-danger">{{ errors.limit_max[0] }}</div>
                    </div>
                  </div>
                </div>

                <!-- Department -->
                <div class="mb-3 col-md-6">
                  <!-- <label for="department" class="form-label text-gray-600">Department</label>
                  <Select2 class="form-label text-gray-600" v-model="form.department_id" :options="departmentforuser"
                      :settings="{ width: '100%', textTransform: 'uppercase' }"/>
                  <div v-if="errors.department" class="form-text error text-danger">{{ errors.department[0] }}</div>  -->
                  <!-- <label for="roles" class="form-label text-gray-600">Department</label> -->
                  <!-- <select class="form-control" v-model="form.department">
                    
                    <option v-for="department in departmentforuser" :key="department.id">{{ department.name }}</option>
                  </select> -->
                  <label for="department" class="form-label text-gray-600">Department</label>
                  <select class="form-control" v-model="form.department_id">
                    <option v-for="option in departmentforuser" :value="option.id" :key="option.id">
                      {{ option.name }}
                    </option>
                  </select>
                </div>
                <!-- End department -->
                
                <div class="mb-3 col-md-6">
                  <label for="sex" class="form-label text-gray-600">Sex</label>
                  <select class="form-control" v-model="form.sex">
                    <option v-for="sex in sexs" :value="sex.value" :key="sex.value">
                      {{ sex.text }}
                    </option>
                  </select>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="password" class="form-label text-gray-600">Password</label>
                  <input type="password" class="form-control" id="password" v-model="form.password">
                  <div v-if="errors.password" class="form-text error text-danger">{{ errors.password[0] }}</div>
                </div>

                <!-- <hr class="hr" /> -->

                  <!-- Role and permission -->
                  <div class="row">
                   <!-- Section Title -->
                   <div class="col-md-12 mt-5 mb-3">
                    <div class="text-center">
                        <div class="w-full flex items-center justify-center space-x-4">
                            <div class="border-t border-gray-400 flex-grow"></div>
                            <h5 class="text-lg font-bold text-gray-500">ROLE & PERMISSIONS</h5>
                            <div class="border-t border-gray-400 flex-grow"></div>
                        </div>
                    </div>
                  </div>
                   <!-- Section tite end -->

                  <div class="mb-3 col-md-6">
                    <label for="roles" class="form-label text-gray-600">Role</label>
                    <select class="form-control" v-model="form.role" @change="getPermissionsForRole(form.role)">
                      <option v-for="role in roles" :key="role.id">{{ role.name }}</option>
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <div> <label for="permissions" class="form-label text-gray-600">Permissions</label></div>
                    <label class="m-2 text-capitalize fw-normal" v-for="permission in rolePermissions"
                      :key="permission.name">
                      <input type="checkbox" v-model="permissions" :value="permission.name">
                      {{ permission.name }}
                    </label>
                    <div v-if="errors.permissions" class="form-text error text-danger">{{ errors.permissions[0] }}</div>
                  </div>
                </div>
                <!-- Role and permission End -->

                <!-- <hr class="hr" /> -->

                <div class="row">
                   <!-- Section Title -->
                   <div class="col-md-12 mt-5 mb-3">
                    <div class="text-center">
                        <div class="w-full flex items-center justify-center space-x-4">
                            <div class="border-t border-gray-400 flex-grow"></div>
                            <h5 class="text-lg font-bold text-gray-500">USER ID</h5>
                            <div class="border-t border-gray-400 flex-grow"></div>
                        </div>
                    </div>
                  </div>
                   <!-- Section tite end -->
                  <!-- <div class="col-md-12 mt-5 mb-3 text-center">
                    <h5 class="fw-bold">USER ID</h5>
                  </div> -->

                  <div class="mb-3 col-md-6">
                    <label for="type" class="form-label text-gray-600">ID type</label>
                    <select class="form-control" @change="onChangeIdType(form.id_type)" v-model="form.id_type">
                      <option v-for="option in idOptions" :value="option.value" :key="option.text">
                        {{ option.text }}
                      </option>
                    </select>
                    <div v-if="errors.id_type" class="form-text error text-danger">{{ errors.id_type[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="form.id_type == 'national_id'">
                    <label for="national_id" class="form-label text-gray-600"> Enter ID card number</label>
                    <input type="text" class="form-control" v-model="form.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="form.id_type == 'passport'">
                    <label for="national_id" class="form-label text-gray-600"> Enter Passport number</label>
                    <input type="text" class="form-control" v-model="form.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="form.id_type == 'driver_licence'">
                    <label for="national_id" class="form-label text-gray-600"> Enter Driver licence number</label>
                    <input type="text" class="form-control" v-model="form.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="form.id_type == 'other'">
                    <label for="national_id" class="form-label text-gray-600"> Enter ID number</label>
                    <input type="text" class="form-control" v-model="form.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                </div>

                <hr class="hr mb-3" />

                <div class="col-md-12 text-right">
                  <button class="btn bg-gray-300 bg-gray-300 bg:hover-gray-400 btn-sm text-xs"
                    @click.prevent="showBankAccountSection">
                    <i class="fa-solid fa-credit-card"></i> Add bank Accounts
                  </button>
                </div>

                <div class="row" v-show="form.bankAccountSectionShow">
                   <!-- Section Title -->
                   <div class="col-md-12 mt-5 mb-3">
                    <div class="text-center">
                        <div class="w-full flex items-center justify-center space-x-4">
                            <div class="border-t border-gray-400 flex-grow"></div>
                            <h5 class="text-lg font-bold text-gray-500">BANK ACCOUNTS</h5>
                            <div class="border-t border-gray-400 flex-grow"></div>
                        </div>
                    </div>
                  </div>
                   <!-- Section tite end -->
                  <!-- <div class="col-md-12 mt-2 mb-2 text-center">
                    <h5 class="fw-bold">BANK ACCOUNTS</h5>
                  </div> -->
                </div>
                <!-- Bank account -->
                <div v-show="form.bankAccountSectionShow"
                  class="row border border-solid border-gray-200 bg-gray-100 m-0 mb-1 pt-1 pb-1"
                  v-for="(account, index) in bankAccounts" :key="index">
                  <div class="mb-3 col-md-6">
                    <label for="percentage" class="form-label text-gray-600">Bank name</label>
                    <input type="text" class="form-control" v-model="account.bank_name">
                    <div v-if="errors.bank_name" class="form-text error text-danger">{{ errors.bank_name[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="type" class="form-label text-gray-600">Currency</label>
                    <select class="form-control" id="currency" v-model="account.currency">
                      <option v-for="option in currencyOptions" :value="option.value" :key="option.text">
                        {{ option.text }}
                      </option>
                    </select>
                    <div v-if="errors.currency" class="form-text error text-danger">{{ errors.currency[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="percentage" class="form-label text-gray-600">Account name</label>
                    <input type="text" class="form-control" v-model="account.account_name">
                    <div v-if="errors.account_name" class="form-text error text-danger">{{ errors.account_name[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="account_number" class="form-label text-gray-600">Account number</label>
                    <input type="number" class="form-control" v-model="account.account_number">
                    <div v-if="errors.account_number" class="form-text error text-danger">{{ errors.account_number[0] }}
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

            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button class="btn btn-primary" @click.prevent=createUser>Submit </button>
            <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
              alt="button-spinner" />
          </div>

        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>
  
<script>
import { onMounted, reactive, ref } from 'vue';
import useUsers from "../../services/usersservices";
import useRoleAndPermission from "../../services/roleandpermissionservices";
import useDepartments from "../../services/departmentservices";
import router from '../../router';

export default {
  props: ['user'],
  setup(props) {
    const { getPermissions, availablePermissions, storeRole, getRoles, roles, getPermissionsForRole, rolePermissions } = useRoleAndPermission();
    const { storeUser, searchReference, references, buttonIsLoading, user, storeUserBankAccount } = useUsers();
    const { getDepartmentsForUser, departmentforuser } = useDepartments();
    const errors = ref([]);
    const form = reactive({
      code: '',
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      address: '',
      location: '',
      limit_min: '',
      limit_max: '',
      percentage: 25,
      password: 1111,
      role: 'agent',
      reference: '',
      registered_by: '',
      avatar: '',
      photoPreview: '',
      id_type: 'national_id',
      id_number: '',
      id_picture: 'no_id.png',
      bankAccountSectionShow: false,
      department_id: 1,
      sex: 'm',
    });

    const state = reactive({
      bankAccounts:
        [{
          bank_name: '',
          account_number: '',
          account_name: '',
          account_balance: 0,
          currency: 'pesos'
        }]
    })

    const permissions = ref([]);
    // const availablePermissions = ref([]);

    onMounted(() => {
      getRoles();
      getPermissions();
      searchReference();
      getDepartmentsForUser();
      getPermissionsForRole(form.role);
    })

    const currencyOptions = ref([
      { text: 'RD$', value: 'pesos' },
      { text: 'US', value: 'us' },
      { text: 'HTG', value: 'htg' },
    ]);

    const idOptions = ref([
      { text: 'Passport', value: 'passport' },
      { text: 'National ID', value: 'national_id' },
      { text: 'Driver licence', value: 'driver_licence' },
      { text: 'Other', value: 'other' },
    ]);

    const imageSelected = (e) => {
      form.avatar = e.target.files[0];
      let reader = new FileReader();
      reader.readAsDataURL(form.avatar);
      reader.onload = e => {
        form.photoPreview = e.target.result;
      };
    }

    const addBAnkAccount = () => {
      state.bankAccounts.push({
        bank_name: '',
        account_number: '',
        account_name: '',
        account_balance: 0,
        currency: 'pesos'
      });
    };

    const removeBankAccount = (index) => {
      state.bankAccounts.splice(index, 1);
    };

    const onChangeIdType = (e) => {
      console.log(e.target.value);
    };

    const createUser = async () => {
      axios.post("/api/users", {
        "code": form.code,
        "first_name": form.first_name,
        "last_name": form.last_name,
        "sex": form.sex,
        "email": form.email,
        "phone": form.phone,
        "address": form.address,
        "location": form.location,
        "limit_min": form.limit_min,
        "limit_max": form.limit_max,
        "percentage": form.percentage,
        "password": form.password,
        "role": form.role,
        "reference": form.reference,
        "registered_by": form.registered_by,
        "avatar": form.avatar,
        "id_type": form.id_type,
        "id_number": form.id_number,
        "id_picture": form.id_picture,
        permissions: permissions.value,
        "department_id": form.department_id,
        bankAccounts: state.bankAccounts,
      })
        .then((response) => {
          // if (form.bankAccountSectionShow) {
          //   storeUserBankAccount({ bankAccounts: state.bankAccounts, user_id: response.data.id });
          // }
          router.push({ name: 'users.index' });
        })
        .catch((error) => {
          errors.value = error.response.data.errors;
        });
    }
    const showBankAccountSection = async () => {
      form.bankAccountSectionShow = !form.bankAccountSectionShow
    }

    const sexs = ref([
      { text: 'Male', value: 'm' },
      { text: 'Female', value: 'f' },
    ])

    return {
      form,
      createUser,
      references,
      errors,
      buttonIsLoading,
      roles,
      availablePermissions,
      permissions,
      imageSelected,
      bankAccounts: state.bankAccounts,
      addBAnkAccount,
      removeBankAccount,
      showBankAccountSection,
      currencyOptions,
      idOptions,
      onChangeIdType,
      getPermissionsForRole,
      rolePermissions,
      departmentforuser,
      sexs
    };
  }
}
</script>