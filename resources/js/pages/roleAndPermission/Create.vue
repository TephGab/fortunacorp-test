<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <h3 class="card-title">
                New Role / Permission
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                <!-- role -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="role_name" class="form-label">Role Name:</label>
                            <input id="role_name" v-model="form.role_name" type="text" class="form-control" required>
                            <div v-if="errors.role_name" class="form-text error text-danger">{{ errors.role_name[0] }}</div>
                        </div>
                    </div>
                <!-- role end -->

                 <!-- permisions -->
                    <div class="row">
                        <div class="mb-3 col-md-12">
                        <div> <label for="permissions" class="form-label">Permissions</label></div>
                        <label class="m-2 text-capitalize fw-normal" v-for="permission in availablePermissions" :key="permission.name">
                            <input type="checkbox" v-model="selectedPermissions" :value="permission.name">
                            {{ permission.name }}
                        </label>
                        <div v-if="errors.permissions" class="form-text error text-danger">{{ errors.permissions[0] }}</div>
                        </div>
                    </div>
                <!-- permisions end -->
              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <button class="btn btn-primary" @click.prevent="createRole"> Add </button>
              <!-- <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
                alt="button-spinner" /> -->
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
  import router from '../../router';
  
  export default {
    props: ['user'],
    setup(props) {
    // const { getPermissions, availablePermissions, storeRole } = useUsers();
    const { getPermissions, availablePermissions, storeRole, getRoles, roles } = useRoleAndPermission();
    
      const form = reactive({
        role_name: '',
      });

      const selectedPermissions = ref([]);
  
      const errors = ref([]);
  
      onMounted(async() => {
        await getRoles()
        await getPermissions();
      })

      const createRole = async () => {
      try {
        await storeRole({
            role_name: role_name.value,
            permissions: selectedPermissions.value,
        });
      } catch (error) {
        errors.value = error.response.data.errors;
      }
    }
  
      return {
        form,
        errors,
        availablePermissions,
        selectedPermissions,
        createRole,
        roles
      };
    }
  }
  </script>