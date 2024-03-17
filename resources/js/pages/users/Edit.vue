<template>
  <div class="container-fluid">
    <div class="row">
      <div class="container-fluid"> <Breadcrumb /></div>
      <div class="col-md-12">
        <!--  -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <h3 class="card-title">Edit User</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
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
                    <input type="text" class="form-control" id="code" v-model="user.code">
                    <div v-if="errors.code" class="form-text error text-danger">{{ errors.code[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="email" class="form-label text-gray-600">Email</label>
                    <input type="email" class="form-control" id="email" v-model="user.email">
                    <div v-if="errors.email" class="form-text error text-danger">{{ errors.email[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="name" class="form-label text-gray-600">First name</label>
                    <input type="text" class="form-control" id="first_name" v-model="user.first_name">
                    <div v-if="errors.first_name" class="form-text error text-danger">{{ errors.first_name[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="name" class="form-label text-gray-600">Last name</label>
                    <input type="text" class="form-control" id="last_name" v-model="user.last_name">
                    <div v-if="errors.last_name" class="form-text error text-danger">{{ errors.last_name[0] }}</div>
                  </div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="phone" class="form-label text-gray-600">Phone</label>
                  <vue-tel-input v-model="user.phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international"
                    :onlyCountries="['ht', 'do']"></vue-tel-input>
                  <div v-if="errors.phone" class="form-text error text-danger">{{ errors.phone[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="address" class="form-label text-gray-600">Address</label>
                  <input type="text" class="form-control" id="address" v-model="user.address">
                  <div v-if="errors.address" class="form-text error text-danger">{{ errors.address[0] }}</div>
                </div>

                <div class="col-md-6 text-capitalize">
                  <label for="searchreference" class="form-label text-gray-600">Reference</label>
                  <Select2 class="form-label text-gray-600" v-model="user.reference" :options="references" :settings="{ width: '100%', textTransform: 'uppercase' }" />
                  <div v-if="errors.reference" class="form-text error text-danger">{{ errors.reference[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="location" class="form-label text-gray-600">Location</label>
                  <input type="text" class="form-control" id="location" v-model="user.location">
                  <div v-if="errors.location" class="form-text error text-danger">{{ errors.location[0] }}</div>
                </div>

                <!-- <div class="mb-3 col-md-6">
                  <label for="percentage" class="form-label text-gray-600">Current percentage</label>
                  <input type="number" class="form-control" id="percentage" v-model="user.percentage">
                  <div v-if="errors.percentage" class="form-text error text-danger">{{ errors.percentage[0] }}</div>
                </div> -->

                <div class="mb-3 col-md-6">
                  <label for="percentage" class="form-label text-gray-600">Default percentage</label>
                  <input type="number" class="form-control" id="default_percentage" v-model="user.default_percentage">
                  <div v-if="errors.default_percentage" class="form-text error text-danger">{{ errors.default_percentage[0] }}</div>
                </div>

                <div class="mb-3 col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="limit_min" class="form-label text-gray-600">Limit Min</label>
                      <div class="input-group">
                        <input type="number" class="form-control border-right-0 rounded-start" id="limit_min" v-model="user.limit_min">
                        <span class="input-group-addon"><span class="form-control border-left-0 bg-light rounded-end">RD$</span></span>
                      </div>
                      <div v-if="errors.limit_min" class="form-text error text-danger">{{ errors.limit_min[0] }}</div>
                    </div>

                    <div class="col-md-6">
                      <label for="limit_max" class="form-label text-gray-600">Limit Max</label>
                      <div class="input-group">
                        <input type="number" class="form-control border-right-0 rounded-start" id="limit_max" v-model="user.limit_max">
                        <span class="input-group-addon"><span class="form-control border-left-0 bg-light rounded-end">RD$</span></span>
                      </div>
                      <div v-if="errors.limit_max" class="form-text error text-danger">{{ errors.limit_max[0] }}</div>
                    </div>
                  </div>
                </div>

                <div class="mb-3 col-md-6">
                  <label for="sex" class="form-label text-gray-600">Sex</label>
                  <select class="form-control" v-model="user.sex">
                    <option v-for="sex in sexs" :value="sex.value" :key="sex.value">
                      {{ sex.text }}
                    </option>
                  </select>
                </div>

                <!-- Department -->
                <div class="mb-3 col-md-6">
                  <label for="department" class="form-label text-gray-600">Department</label>
                  <select class="form-control text-capitalize" v-model="form.department_id">
                    <option v-for="department in departmentsNoPaginate" :key="department.id" :value="department.id">{{ department.name }}</option>
                  </select>
                </div>
                <!-- End department -->

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
                    <div v-for="role in user.roles" :key="role.id">
                      <select class="form-control text-capitalize" v-model="form.role_id"
                        @change="providePermissionsOnChange(role.id)">
                        <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <div class="form-label fw-bold">Current permissions</div>
                    <label v-for="permission in currentPermissionsOnUserEdit" :key="permission.id">
                      <span v-if="permission" class="badge badge-info fw-normal text-capitalize mr-2"> {{ permission.name
                      }}
                        <span class="bg-gray text-xs pr-1 pl-1" style="border-radius: 50%;" @click.prevent="unatachPermissionFromUser(permission.name)">
                          x
                        </span>
                      </span>
                      <span v-else>No permisssion yet</span>
                    </label>

                    <!-- permisions -->
                    <div class="row">
                      <div class="mb-3 mt-3 col-md-12">
                        <div> <label for="permissions" class="form-label">Availble permissions</label></div>
                        <label class="m-2 text-capitalize fw-normal" v-for="permission in availablePermissionsOnUserEdit"
                          :key="permission.name">
                          <input type="checkbox" v-model="selectedPermissions" :value="permission.name">
                          {{ permission.name }}
                        </label>
                      </div>
                      <div v-if="errors.permissions" class="form-text error text-danger">{{ errors.permissions[0] }}</div>
                    </div>
                    <!-- permisions end -->
                    <!-- <div class="row">
                      <div>
                        <button class="btn btn-success btn-sm">Add permissions</button>
                      </div>
                    </div> -->
                  </div>
                </div>
                <!-- </div> -->
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

                  <div class="mb-3 col-md-6">
                    <label for="type" class="form-label text-gray-600">ID type</label>
                    <select class="form-control" @change="onChangeIdType(user.id_type)" v-model="user.id_type">
                      <option v-for="option in idOptions" :value="option.value" :key="option.text">
                        {{ option.text }}
                      </option>
                    </select>
                    <div v-if="errors.id_type" class="form-text error text-danger">{{ errors.id_type[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="user.id_type == 'national_id'">
                    <label for="national_id" class="form-label text-gray-600"> Enter ID card number</label>
                    <input type="text" class="form-control" v-model="user.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="user.id_type == 'passport'">
                    <label for="national_id" class="form-label text-gray-600"> Enter Passport number</label>
                    <input type="text" class="form-control" v-model="user.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="user.id_type == 'driver_licence'">
                    <label for="national_id" class="form-label text-gray-600"> Enter Driver licence number</label>
                    <input type="text" class="form-control" v-model="user.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6" v-if="user.id_type == 'other'">
                    <label for="national_id" class="form-label text-gray-600"> Enter ID number</label>
                    <input type="text" class="form-control" v-model="user.id_number">
                    <div v-if="errors.id_number" class="form-text error text-danger">{{ errors.id_number[0] }}</div>
                  </div>

                </div>

                <hr class="hr mb-3" />

                <!-- <div class="col-md-12 text-right">
                  <button class="btn bg-gray-300 bg-gray-300 bg:hover-gray-400 btn-sm text-xs"
                    @click.prevent="showBankAccountSection">
                    <i class="fa-solid fa-credit-card"></i> Add bank Accounts
                  </button>
                </div> -->

                <!-- v-show="form.bankAccountSectionShow" -->
                <div class="row">
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

                </div>
                <!-- Bank account -->
                <!-- v-show="form.bankAccountSectionShow" -->
                <div class="row border border-solid border-gray-200 bg-gray-100 m-0 mb-1 pt-1 pb-1"
                  v-for="(account, index) in user.user_bank_accounts" :key="index">
                  <div class="mb-3 col-md-6">
                    <label for="percentage" class="form-label text-gray-600">Bank name</label>
                    <input type="text" class="form-control" v-model="account.bank_name">
                    <div v-if="errors.bank_name" class="form-text error text-danger">{{ errors.bank_name[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="type" class="form-label text-gray-600">Currency</label>
                    <select class="form-control" id="currency" v-model="account.currency">
                      <option v-for="option in currencyOptions" :value="option.text" :key="option.text">
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

                  <!-- <div class="col-md-12 m-0 p-0 text-right">
                    <span @click.prevent="removeBankAccount(index)">
                      <i class="fas fa-trash"></i><span class="text-xs"> Remove entry</span>
                    </span>
                  </div> -->
                </div>

                <!-- <div v-show="form.bankAccountSectionShow" class="col-md-12 m-0 p-0 text-right">
                  <button class="btn bg-gray-300 btn-sm text-xs mt-2" @click.prevent="addBAnkAccount">
                    <i class="fas fa-plus"></i> Add more bank accounts
                  </button>
                </div> -->
                <!-- Bank account end -->
              </div>
              <!-- </div> -->


            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button class="btn btn-primary" @click.prevent=editUser(user.id)>Update</button>
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
import useUsers from "../../services/usersservices";
import useRoleAndPermission from "../../services/roleandpermissionservices";
import useDepartments from "../../services/departmentservices";

export default {
  props: ['id', 'user'],
  setup(props) {
    const { getPermissions, availablePermissions, getAvailablePermissionsOnRoleEdit } = useRoleAndPermission();
    const { getUser, user, updateUser, getRoles, roles, searchReference, references, getAvailablePermissionsOnUserEdit, availablePermissionsOnUserEdit,
            geCurrentPermissionsOnUserEdit, currentPermissionsOnUserEdit, removePermissionFromUser } = useUsers();
    const { getDepartmentNoPaginate, departmentsNoPaginate } = useDepartments();
    const errors = ref([]);
    const permissions = ref([]);
    const selectedPermissions = ref([]);

    const form = reactive({
      photoPreview: '',
      avatar: '',
      role_name: '',
      role_id: '1',
      department_id: '1',
      currencys: [],
    });

    const currencyOptions = ref([
      { text: 'RD$', value: 'pesos' },
      { text: 'US', value: 'us' },
      { text: 'HTG', value: 'htg' },
    ]);

    onMounted(async () => {
      await getUser(props.id);
      await getPermissions();
      await getRoles();
      const data = new FormData();
      data.append('user_id', props.id);
      data.append('role_id', user.value.roles[0].id);
      await getDepartmentNoPaginate();
      if (user) {
        form.role_id = user.value.roles[0].id;
        form.department_id = user.value.department.id;
        console.log(user.value.user_bank_accounts);
      }
      await geCurrentPermissionsOnUserEdit(data);
      await getAvailablePermissionsOnUserEdit(data)
      await searchReference();
    })

    const editUser = async (id) => {
      try {
        await updateUser(
          id, {
          "code": user.value.code,
          "first_name": user.value.first_name,
          "last_name": user.value.last_name,
          "email": user.value.email,
          "sex": user.value.sex,
          "phone": user.value.phone,
          "address": user.value.address,
          "location": user.value.location,
          "limit_min": user.value.limit_min,
          "limit_max": user.value.limit_max,
          // "percentage": user.value.percentage,
          "default_percentage": user.value.default_percentage,
          "password": user.value.password,
          "reference": user.value.reference,
          "registered_by": user.value.registered_by,
          "avatar": user.value.avatar,
          "id_type": user.value.id_type,
          "id_number": user.value.id_number,
          "id_picture": user.value.id_picture,
          permissions: selectedPermissions.value,
          "department_id": form.department_id,
          "role_id": form.role_id,
          bankAccounts: user.value.user_bank_accounts,
        }
        );
      } catch (error) {
        errors.value = error.response.data.errors;
      }
    }

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

    const unatachPermissionFromUser = async (permission) => {
        try {
          await removePermissionFromUser({
              user_id: props.id,
              permission_name: permission,
          });
          const data = new FormData();
          data.append('user_id', props.id);
          data.append('role_id', user.value.roles[0].id);
          await geCurrentPermissionsOnUserEdit(data);
          await getAvailablePermissionsOnUserEdit(data)
        } catch (error) {
          errors.value = error.response.data.errors;
        }
      }

    const providePermissionsOnChange = (role) => {
      const data = new FormData();
      data.append('user_id', props.id);
      data.append('role_id', form.role_id);
      getAvailablePermissionsOnUserEdit(data)
      geCurrentPermissionsOnUserEdit(data);
    }

    const sexs = ref([
      { text: 'Male', value: 'm' },
      { text: 'Female', value: 'f' },
    ])

    return {
      form,
      user,
      editUser,
      roles,
      references,
      errors,
      availablePermissions,
      currentPermissionsOnUserEdit,
      getAvailablePermissionsOnRoleEdit,
      imageSelected,
      idOptions,
      departmentsNoPaginate,
      permissions,
      availablePermissionsOnUserEdit,
      providePermissionsOnChange,
      selectedPermissions,
      currencyOptions,
      unatachPermissionFromUser,
      sexs
    };
  }
}
</script>