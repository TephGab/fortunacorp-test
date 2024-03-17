<template>
  <div class="container-fluid">
    <div class="row">
      <div class="container-fluid"> <Breadcrumb /></div>
      <div class="col-md-12">
        <!-- TABLE: LATEST TRANSFERTS -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <div class="d-flex justify-content-between">
              <router-link :to="{ name: 'user.create' }" class="btn btn-sm btn-info float-left">
                <i class='fas fa-user-plus'></i> New user
              </router-link>
              <div class="card-tools" v-if="can('export_pdf', 'transaction')">
                <div v-show="!isButtonLoading">
                  <label class="form-label text-success cursor-pointer text-lg" @click.prevent="showOptionPdf" title="Export as PDF"><i class="fa-solid fa-file-pdf"></i></label>
                  <select class="border-0 outline-0 text-gray-500 text-xs" v-show="showPdfOption" v-model="form.pdf_export_option"
                    @change="pdfExport(form.pdf_export_option)">
                    <option v-for="option in pdfOptions" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                </div>
                <div v-show="isButtonLoading">
                  Generating PDF ... <img class="img-fluid img-circle" src="/img/spinner.gif" alt="button-spinner"
                    style="width: 20px; display: inline" />
                </div>
              </div>
              <form>
                <input type="text" id="search" name="search" @keyup="search" placeholder="Search.." v-model="form.q">
              </form>

              <!-- <div>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div> -->
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive table-hover">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th class="text-center text-nowrap">Code</th>
                    <th class="text-center text-nowrap">Name | Role
                      <button @click="showRoleSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="roleSort"
                        class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2">
                        <!-- <i class="fas fa-circle text-warning" v-show="form.type == 'moncash'"></i>
                        <i class="fas fa-circle text-info" v-show="form.type == 'natcash'"></i> -->
                        <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.role" @change="sortRole(form.role)">
                          <option>
                            <span> all </span>
                          </option>
                          <option v-for="role in roles" :value="role.name" :key="role.name">
                            <span v-if="role.name != 'super-admin'" class="text-capitalize">{{ role.name }}</span>
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-center text-nowrap">Email | Phone</th>
                    <th class="text-right text-nowrap">
                      C | R | R | D
                      <button @click="showBRDRSort">
                        <i class="fas fa-sort text-gray-500"></i>
                      </button>
                      <div v-show="brdrSort" class="form-check form-check-inline border border-info rounded mr-1 pl-2 pr-2">
                        <!-- <i class="fas fa-circle text-warning" v-show="form.type == 'moncash'"></i>
                        <i class="fas fa-circle text-info" v-show="form.type == 'natcash'"></i> -->
                        <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.brdr_name" @change="sortBrdr(form.brdr_name)">
                          <option v-for="option in options" :value="option.value" :key="option.text">
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </th>
                    <th class="text-center text-nowrap">Device</th>
                    <th class="text-center text-nowrap">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users.data" :key="user.id">
                    <td class="text-capitalize text-center text-nowrap">{{ user.code }}</td>
                    <td class="text-left text-nowrap">
                      <div class="d-flex align-items-center">
                        <img :src="'/img/users/' + user.avatar" alt="user" style="width: 45px; height: 45px" class="rounded-circle"/>
                          <span style="opacity: 0.7; position: relative; top: 13px; right: 7px;">
                            <span v-if="user.online_status == 'online'">🟢</span>
                            <span v-else>🔴</span>
                          </span>
                         
                        <div class="ms-2">
                          <p class="fw-normal mb-1"><span class="text-capitalize">{{ user.first_name }}</span> <span class="text-uppercase">{{  user.last_name }}</span></p>
                          <p class="text-muted mb-0 text-capitalize">{{ user.roles[0].name }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="text-center text-nowrap">
                      <!-- <span class="fw-normal mb-1 text-info">{{ user.email }}</span> -->
                      <div>
                        <div class="ms-3">
                          <p class="fw-normal mb-1 text-info">{{ user.email }}</p>
                          <p class="mb-0 text-info">{{ user.phone }}</p>
                        </div>
                      </div>
                    </td>
                  
                    <td class="text-nowrap text-right">
                      <select class="border-none text-right" v-model="form.brdr_name">
                      <option class="text-right" value="profits" v-if="user && user.user_account"><span> Comission : </span> {{ formatDecimalNumber(user.user_account.profits) }} RD$</option>
                      <option class="text-right" value="referral_commissions" v-if="user && user.user_account"><span> Referral com. : </span> {{ formatDecimalNumber(user.user_account.referral_commissions) }} RD$</option>
                      <option class="text-right" value="investments" v-if="user && user.user_account"><span> Recharge :</span> {{ formatDecimalNumber(user.user_account.investments) }} RD$</option>
                      <option class="text-right" value="depts" v-if="user && user.user_account"><span> Debt :</span> {{ formatDecimalNumber(user.user_account.depts) }} RD$</option>
                      <!-- <option value="replenishments" v-if="user && user.user_account"><span> Replenishment :</span> {{ formatDecimalNumber(user.user_account.replenishments) }} RD$</option> -->
                    </select>
                    </td>

                    <td class="text-center text-nowrap">
                       <span v-if="user.user_visits[0]">
                              <span v-if="user.user_visits[0].device_type == 'desktop'">
                                <i class="fas fa-desktop"></i>
                              </span>
                              <span v-if="user.user_visits[0].device_type == 'phone'">
                                <i class="fas fa-mobile-alt"></i>
                              </span>
                        </span>
                        <span v-else class="text-danger">
                          --
                        </span>
                    </td>
                    <td class="text-center text-nowrap">
                      <div class="sparkbar text-center" data-color="#00a65a" data-height="20">
                        <!-- <i class="fas fa-trash" style="color:red;" @click="deleteUser(user.id)"></i> -->
                        <div class="btn-group dropstart">
                          <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" title="More" style="font-weight: bold; font-size: 1rem;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li v-if="can('update', 'user')">
                              <router-link class="ml-2" class-active="active" :to="{ name: 'user.show', params: { id: user.id } }">
                                <i class="fas fa-edit" style="color:blueviolet;"></i> Update user
                              </router-link>
                            </li>
                            <li v-if="can('reset', 'password')"><hr class="dropdown-divider"></li>
                            <li v-if="can('reset', 'password')" @click="resetUserPassword(user.id)">
                             <button class="ml-2">
                                <i class="fa-solid fa-unlock-keyhole"></i> Reset password
                             </button>
                            </li>
                            <li v-if="can('reset', 'password')"><hr class="dropdown-divider"></li>
                            <!-- <li v-if="can('reset', 'password')"> -->
                              <router-link class="ml-2" class-active="active" :to="{ name: 'userativities.show', params: { id: user.id } }">
                                <i class="fas fa-file"></i> View Activities
                              </router-link>
                            <!-- </li> -->
                            <!-- <li v-if="can('make', 'replenishment')">
                                <router-link :to="{ name: 'replenishment.create', params: { agentid: required_replenishment.user_id, amount: required_replenishment.required_amount, reqreplenishmentid: required_replenishment.id } }" v-if="can('make', 'replenishment')" class="ml-2">
                                    <i class='fas fa-plus'></i>
                                     Send
                                </router-link>
                            </li> -->
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr v-show="isLoading"> 
                    <td><img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;"/> </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
         
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
            <Pagination :data="users" @pagination-change-page="getUsers" />
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
import { useAbility } from '@casl/vue';
import Swal from "sweetalert2";

export default {
  setup() {
    const { getUsers, users, destroyUser, idFormatter, isLoading, resetPassword, allowAndDisallowAgentDebt,
            getRoles, roles, sortByRole, sortByBRDR, generatePdf, isButtonLoading } = useUsers();
    const { formatDecimalNumber } = useUtils();
    const { can, cannot } = useAbility();

    const form = reactive({
      q: '',
      role: 'all',
      brdr_name: 'profits',
      pdf_export_option: 'interval',
    });


    const roleSort = ref(false);
    const brdrSort = ref(false);
    const passwordReset = ref(1111);
    const errors = ref([]);

    onMounted(async() => {
      await getUsers();
      await getRoles();
      await Echo.private('useronline')
      .listen('UserOnlineStatusUpdated', (e) => {
        // isOnline.value = e.status;
        getUsers();
      });
    })

    const showPdfOption = ref(false);
    
    const deleteUser = async (id) => {
      await destroyUser(id);
    }

    const resetUserPassword = async (id) => {
      try{
        Swal.fire({
          title: 'Password reset',
          text: "User password will be set to 1111",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Reset'
        }).then((result) => {
          if (result.isConfirmed) {
            const data = new FormData();
            data.append('user_id', id)
            data.append('new_password', passwordReset.value)
            resetPassword(data);
            Swal.fire(
              'Reinitialised!',
              'User password is set to 1111',
              'success'
            )
          }
        })
        } catch (error) {
        errors.value = error.response.data.errors;
      }
    }
    
    const search = async () => {
      if (form.q.length > 0) {
        axios.get("/api/users/search/" + form.q).
          then(response => { users.value = response.data }).
          catch(error => console.log(error))
      } else {
        getUsers()
      }
    }

    const debtAllowDisallow = async (id, debtAllowed) => {
      try{
        Swal.fire(debtAllowed ? {
          title: 'Disallow Debt posibility',
          text: "Agent won't be able to make any transaction higher then current recharge",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Disallow'
        }:{
          title: 'Allow Debt posibility',
          text: "Agent will be able to make transation without recharge",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Allow'
        }).then((result) => {
          if (result.isConfirmed) {
            allowAndDisallowAgentDebt({'agent_id': id});
          }
        })
        } catch (error) {
        errors.value = error.response.data.errors;
      }
    }

    const showRoleSort = async () => {
      roleSort.value = !roleSort.value;
    }

    const sortRole = async (role) => {
      await sortByRole({'role': role});
    }

    const showBRDRSort = async () => {
      brdrSort.value = !brdrSort.value;
    }

    const sortBrdr = async (brdr_name) => {
     await sortByBRDR({'brdr_name': brdr_name});
    }

    const options = ref([
      { text: 'All', value: 'all' },
      { text: 'Commision', value: 'profits' },
      { text: 'Referral Com.', value: 'referral_commissions' },      
      { text: 'Recharge', value: 'investments' },
      { text: 'Debt', value: 'depts' },
    ]);

    const pdfOptions = ref([
      { text: 'Select Interval', value: 'interval' },
      { text: 'All', value: 'all' },
      { text: 'Agents', value: 'agent' },
      { text: 'Operator', value: 'operator' },
      { text: 'Admin', value: 'admin' },
      { text: 'System Ingineer', value: 'system_engineer' },
    ]);

    const showOptionPdf = async () => {
      showPdfOption.value = true;
    }

    const pdfExport = (pdf_export_option) => {
      const data = new FormData();
      data.append('pdf_export_option', pdf_export_option);
      generatePdf(data);
      showPdfOption.value = false;
    }

    return {
      users,
      form,
      search,
      getUsers,
      deleteUser,
      idFormatter,
      formatDecimalNumber,
      isLoading,
      resetUserPassword,
      can,
      cannot,
      debtAllowDisallow,
      errors,
      sortRole,
      roles,
      showRoleSort,
      roleSort,
      options,
      brdrSort,
      showBRDRSort,
      sortBrdr,
      pdfExport,
      pdfOptions,
      showPdfOption,
      isButtonLoading,
      showOptionPdf,
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