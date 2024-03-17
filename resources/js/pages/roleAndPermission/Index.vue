<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div style="display: flex; justify-content: space-between; align-items: center">
               <div>
                 <router-link v-if="can('create', 'role')" :to="{ name: 'roleandpermission.create' }" class="btn btn-sm btn-info float-left mr-3">
                    <i class="fa-solid fa-shield"></i> Add role
                </router-link>


                <router-link v-if="can('create', 'permission')" :to="{ name: 'roleandpermission.createpermission' }" class="btn btn-sm btn-info float-left">
                    <i class="fa-solid fa-shield"></i> Add permission
                </router-link>
               </div>
                <!-- <form>
                  <input type="text" id="search" name="search" @keyup="search" placeholder="Search.." v-model="form.q">
                </form> -->
  
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive table-hover">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th class="text-center text-nowrap">#</th>
                      <th class="text-center text-nowrap"> Roles </th>
                      <th class="text-center text-nowrap">Permissions</th>
                      <th class="text-center text-nowrap">Actions</th>
                    </tr>
                  </thead>
                  <tbody v-for="role in roles" :key="role.id">
                    <tr>
                        <td class="text-center text-nowrap">
                            <span> {{ role.id }}</span>
                        </td>
                        <td class="text-center text-nowrap text-capitalize">
                           <span> {{ role.name }}</span>
                        </td>
                        <td class="text-center">
                           <label v-for="permission in role.permissions" :key="permission.id">
                            <span class="badge badge-info fw-normal text-capitalize mr-2"> {{ permission.name }} </span>
                           </label>
                        </td>
                        <!-- <td class="text-center text-nowrap">
                          <router-link class="ml-2" class-active="active" :to="{ name: 'roleandpermission.edit', params: { id: role.id } }">
                                <i class="fas fa-edit" style="color:blueviolet;"></i>
                          </router-link>
                        </td> -->
                      <td class="text-center text-nowrap">
                        <div class="sparkbar ml-3" data-color="#00a65a" data-height="20">
                          <div class="btn-group dropstart">
                            <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="fas fa-ellipsis-v" title="More" style="font-weight: bold; font-size: 1rem;"></i>
                            </button>
                            <ul class="dropdown-menu">
                              <li>
                                <router-link class="ml-2" class-active="active" :to="{ name: 'roleandpermission.edit', params: { id: role.id } }">
                                  <i class="fas fa-edit" style="color:blueviolet;"></i> Manage permissions
                                </router-link>
                              </li>
                              <li><hr class="dropdown-divider"></li>
                            </ul>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <!-- <tr v-show="isLoading"> 
                      <td><img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;"/> </td>
                    </tr> -->
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
           
          </div>
  
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
              <!-- <Pagination :data="users" @pagination-change-page="getUsers" /> -->
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
  import useUtils from "../../services/utilsservices";
  import useRoleAndPermission from "../../services/roleandpermissionservices";
  import { useAbility } from '@casl/vue';
  import Swal from "sweetalert2";
  
  export default {
    setup() {
      const { getUsers, users, destroyUser, idFormatter, isLoading, resetPassword } = useUsers();
      const { getPermissions, availablePermissions, storeRole, getRoles, roles } = useRoleAndPermission();
      const { formatDecimalNumber } = useUtils();
      const { can, cannot } = useAbility();
        
      const form = reactive({
        role: '',
      });
  
      onMounted(async() => {
       await getRoles();
      //  console.log(roles)
      })

      return {
        form,
        roles,
        can,
        cannot
      }
    },
  }
  </script>
    
  <style scoped>
  #search {
    width: 70%;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    /* font-size: 16px; */
    background-color: white;
    /* background-image: url('searchicon.png'); */
    background-position: 10px 10px;
    background-repeat: no-repeat;
    /* padding: 12px 20px 12px 40px; */
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
  }
  
  #search:focus {
    width: 100%;
  }
  </style>