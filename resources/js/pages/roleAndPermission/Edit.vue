<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <h3 class="card-title">Add Permissions</h3>
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
                    <div class="mb-3 col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" v-model="role.name">
                    </div>

                    <div class="mb-3 col-md-6">
                        <div class="form-label fw-bold">Permissions</div>
                        <label v-for="permission in role.permissions" :key="permission.id">
                            <span class="badge badge-info fw-normal text-capitalize mr-2"> {{ permission.name }} 
                              <span class="bg-gray text-xs pr-1 pl-1" style="border-radius: 50%;" @click="unatachPermissionFromRole(permission.name)">
                                x
                              </span>
                            </span>
                        </label>

                         <!-- permisions -->
                            <div class="row">
                                <div class="mb-3 mt-3 col-md-12">
                                <div> <label for="permissions" class="form-label">Availble permissions</label></div>
                                <label class="m-2 text-capitalize fw-normal" v-for="permission in availablePermissionsOnRoleEdit" :key="permission.name">
                                    <input type="checkbox" v-model="selectedPermissions" :value="permission.name">
                                    {{ permission.name }}
                                </label>
                                </div>
                                <div v-if="errors.permissions" class="form-text error text-danger">{{ errors.permissions[0] }}</div>
                            </div>
                        <!-- permisions end -->
                        <div class="row">
                          <div>
                            <button class="btn btn-success btn-sm" @click.prevent="attachermissionToRole">Add permissions</button>
                          </div>
                        </div>
                    </div>
               </div>

               <!-- <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="role" class="form-label">Add permisssion</label>
                        <input type="text" class="form-control" v-model="form.permission_name">
                    </div>
               </div> -->

              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <!-- <button class="btn btn-primary btn-sm" @click.prevent="addPermissions">Update</button> -->
              <!-- v-if="can('add_super_admin', 'permission')" -->
              <button class="btn btn-primary ml-5 btn-sm" @click.prevent="addSuperAdminPermission">Add for super admin</button>
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
  import useUtils from "../../services/utilsservices";
  import useRoleAndPermission from "../../services/roleandpermissionservices";
  import { useAbility } from '@casl/vue';
  
  export default {
    props: ['id'],
    setup(props) {
      const { can, cannot } = useAbility();
      const { getRole, role, addNewPermisssions, addNewSuperAdminPermission, getAvailablePermissionsOnRoleEdit, availablePermissionsOnRoleEdit, addPermissionToRole, removePermissionFromRole } = useRoleAndPermission();
      const errors = ref([]);
      const selectedPermissions = ref([]);
      
      const form = reactive({
        permission_name: '',
      });

      onMounted(async() => {
        const data = new FormData();
        data.append('role_id', props.id);
        await getRole(data);
        await getAvailablePermissionsOnRoleEdit(data)
      });

      const addPermissions = async (data) => {
        try{
          const data = new FormData();
          data.append('role_id', props.id)
          //data.append('role_name', role.name)
          data.append('permission_name', form.permission_name)
          await addNewPermisssions(data);
          await getRole(data);
        }catch(error){
          errors.value = error.response.data.errors;
        }
      }

      const addSuperAdminPermission = async (data) => {
        try{
          const data = new FormData();
          data.append('role_id', props.id)
          data.append('permission_name', form.permission_name)
          await addNewSuperAdminPermission(data);
          await getRole(data);
        }catch(error){
          errors.value = error.response.data.errors;
        }
      }

      const attachermissionToRole = async () => {
        try {
          await addPermissionToRole({
              role_id: props.id,
              permissions: selectedPermissions.value,
          });
            const data = new FormData();
            data.append('role_id', props.id)
            await getRole(data);
            await getAvailablePermissionsOnRoleEdit(data)
        } catch (error) {
          errors.value = error.response.data.errors;
        }
      }

       const unatachPermissionFromRole = async (permission) => {
        try {
          await removePermissionFromRole({
              role_id: props.id,
              permission_name: permission,
          });
            const data = new FormData();
            data.append('role_id', props.id)
            await getRole(data);
            await getAvailablePermissionsOnRoleEdit(data)
        } catch (error) {
          errors.value = error.response.data.errors;
        }
      }

      // unatachPermissionFromRole
  
      return {
        form,
        role,
        addPermissions,
        addSuperAdminPermission,
        errors,
        selectedPermissions,
        availablePermissionsOnRoleEdit,
        attachermissionToRole,
        unatachPermissionFromRole,
        can,
        cannot
      };
    }
  }
  </script>