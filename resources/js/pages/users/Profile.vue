<template>
  <div class="container">
    <div class="container-fluid"> <Breadcrumb /></div>
    <div class="main-body">
    
          <!-- Breadcrumb -->
          <nav class="main-breadcrumb m-0 p-0">
            <!-- <div class="container-fluid"> <Breadcrumb /></div> -->
          </nav>
          <!-- /Breadcrumb -->
    
          <div class="row gutters-sm">
            <!-- <div class="container-fluid"> </div> -->
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img v-if="user.sex === 'm'" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                    <img v-else src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="Admin" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4>{{ user.first_name }} {{ user.last_name }}</h4>
                      <p class="text-secondary mb-1 text-uppercase">{{ userrole.name }}</p>
                      <!-- <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p> -->
                      <!-- <button class="btn btn-sm btn-primary text-xs">Change password</button> -->
                      <!-- <button class="btn btn-outline-primary">Message</button> -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3 text-xs">
                <h6 class="card-title text-center text-sm m-1">Active sessions</h6>
                <ul class="list-group list-group-flush" v-for="user_session in user.user_visits" :key="user_session.id">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                      <span class="mr-2"> {{ user_session.browser_name }} </span>
                      <span v-if="user_session.device_type == 'desktop'" class="mr-2">
                        <i class="fas fa-desktop"></i>
                      </span>
                      <span v-if="user_session.device_type == 'phone'" class="mr-2">
                        <i class="fas fa-mobile-alt"></i>
                      </span>
                    </h6>
                    <span class="text-muted text-xs"> {{ formatDateCalendar(user_session.updated_at) }} </span>
                  </li>
                </ul>
                <button class="btn btn-secondary btn-sm m-2" @click="closeOtherSessions">Logout other sessions</button>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Code</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.code }}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.first_name }} {{ user.last_name }}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Email</h6>
                    </div>
                    <div class="col-sm-9 text-info">
                      {{ user.email }}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Phone</h6>
                    </div>
                    <div class="col-sm-9 text-info">
                      {{ user.phone}}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.address }}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Location</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.location }}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Default percentage</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.default_percentage }} %
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Current percentage</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.percentage }} %
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2" v-if="user && user.referred_by">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Reference</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      {{ user.referred_by.first_name }}  {{ user.referred_by.last_name }}
                    </div>
                  </div>
                  <hr>
                  <div class="row mt-2">
                    <div class="col-sm-3">
                      <h6 class="mb-0 fw-bold">Password</h6>
                    </div>

                    <div class="col-sm-9 text-secondary">
                        <button class="btn btn-sm btn-primary text-xs" value="Change password" v-show="!showInput" @click="inputShow">Change password</button>
                      
                        <div v-show="showInput">
                          <div class="form-group">
                            <label for="current_password">Current password</label>
                            <input type="password" id="current_password" class="form-control" v-model="form.current_password" />
                            <div v-if="errors && errors.current_password" class="form-text error text-danger">{{ errors.current_password[0] }}</div>
                          </div>
                          <div class="form-group">
                            <label for="password">New password</label>
                            <input type="password" id="password" class="form-control" v-model="form.new_password" />
                            <div v-if="errors && errors.new_password" class="form-text error text-danger">{{ errors.new_password[0] }}</div>
                          </div>
                          <div class="form-group">
                            <label for="confirm_password">Comfirm password</label>
                            <input type="password" id="confirm_password" class="form-control" v-model="form.confirm_password" />
                            <div v-if="errors && errors.confirm_password" class="form-text error text-danger">{{ errors.confirm_password[0] }}</div>
                          </div>
                          <div class="form-group mt-2">
                            <input class="btn btn-sm btn-primary text-xs mr-3" value="Update" @click="editPassword(user.id)">
                            <input class="btn btn-sm btn-secondary text-xs" value="Cancel" @click="inputHide">
                          </div>
                        </div>
                    </div>
                   
                      <!-- <div class="form-group mt-2" v-show="!showInput">
                        <input class="btn btn-primary mr-3" value="Change password" @click="inputShow">
                      </div> -->
                  </div>
                </div>
              </div>

              <div class="row gutters-sm" v-if="user && user.referrals && user.referrals.length > 0">
                <div class="col-sm-12 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="d-flex align-items-center mb-3 fw-bold">
                         Referrals <span class="ml-2 text-muted text-sm">{{ user.referrals.length }}</span>
                      </h5>

                      <div class="row align-items-center mb-2 text-center" v-for="(referral, index) in user.referrals" :key="index">
                        <div class="col-sm-1">
                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="40">
                          </div>
                        <div class="col-sm-6 text-left">
                          <h6 class="mb-0">{{ referral.first_name }} {{ referral.last_name }}</h6>
                        </div>
                        <div class="col-sm-5 text-xs text-muted text-left">
                          {{ formatDateCalendar(referral.created_at) }}
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>



            </div>
          </div>

    </div>
  </div>

</template>

<script>
import { onMounted, ref, reactive } from 'vue';
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import Swal from "sweetalert2";

export default {
  props: ['user', 'userrole'],
  setup(props) {
    const { getUser, user, updateUser, updatePassword, getReference, reference, logoutOtherSessions, isLoading } = useUsers();
    const { buttonIsDisabled, formatDateCalendar, formatDate } = useUtils();  
    const showInput = ref(false);
    const errors = ref([]);
  
    const form = reactive({
      current_password: '',
      new_password: '',
      confirm_password: '',
    });

    onMounted(async () => {
      await getUser(props.user.id);
    
      await getReference(props.user.reference);
    })

    const editUser = async (id) => {
      await updateUser(id);
    }

    const inputShow = () => {
      showInput.value = true;
    }

    const inputHide = () => {
      showInput.value = false;
    }

    const closeOtherSessions = async () => {
      try {
        Swal.fire({
          text: "Other sessions will be closed!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Logout'
        }).then( async (result) => {
          if (result.isConfirmed) {
            await logoutOtherSessions({'user_id': props.user.id});
            await getUser(props.user.id);
            await Swal.fire({
              text: "Other sessions closed!",
              toast: true,
              position: 'top-right',
              icon: 'success',
              color: '#000',
              padding: '0',
              showConfirmButton: false,
              timer: 3500
            });
          }
        })
      } catch (error) {
        errors.value = error.response.data.errors;
      }
    }

    const editPassword = async (id) => {
        Swal.fire({
          text: "Confirm you want to change password",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then( async (result) => {
          if (result.isConfirmed) {
            try {
                buttonIsDisabled.value = true;
                await updatePassword(props.user.id, { ...form });
                await inputHide();
                await logoutOtherSessions({'user_id': props.user.id});
                await closeOtherSessions();
                await getUser(props.user.id);
                await Swal.fire({
                  text: "Password Changed!",
                  toast: true,
                  position: 'top-right',
                  icon: 'success',
                  color: '#000',
                  padding: '0',
                  showConfirmButton: false,
                  timer: 3500
                });
              } catch (error) {
              errors.value = error.response.data.errors;
              buttonIsDisabled.value = false;
            }
          }
        })
      
      // try {
      //   buttonIsDisabled.value = true;
      //   await updatePassword(props.user.id, { ...form });
      //   await inputHide();
      //   await logoutOtherSessions({'user_id': props.user.id});
      //   closeOtherSessions();
      // } catch (error) {
      //   errors.value = error.response.data.errors;
      //   buttonIsDisabled.value = false;
      // }
    }

    return {
      user,
      props,
      editUser,
      formatDate,
      showInput,
      inputShow,
      inputHide,
      form,
      editPassword,
      errors,
      getReference,
      reference,
      buttonIsDisabled,
      closeOtherSessions,
      formatDateCalendar,
      isLoading
    };
  }
}
</script>

<style scoped>
body{
    margin-top:20px;
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;    
}
.main-body {
    padding: 15px;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}
</style>