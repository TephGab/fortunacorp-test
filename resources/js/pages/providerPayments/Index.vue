<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div style="display: flex; justify-content: space-between; align-items: center">
                Provider payments history
                <!-- <form>
                  <input type="text" id="search" name="search" @keyup="search" placeholder="Search.." v-model="form.q">
                </form> -->
  
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
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                    <th class="text-nowrap"></th>
                    <!-- <tr>
                      <th class="text-nowrap">Code</th>
                      <th class="text-nowrap">Name | Role</th>
                      <th class="text-nowrap">Email | Phone</th>
                      <th class="text-nowrap">Balance</th>
                      <th class="text-nowrap">Depts</th>
                      <th class="text-nowrap">Actions</th>
                    </tr> -->
                  </thead>
                  <tbody v-for="payment in providerpayments.data" :key="payment.id">
                    <tr>
                      <td class="text-nowrap">
                        {{ payment.id }}
                      </td>
                     <td class="text-nowrap">
                        {{ formatDecimalNumber(payment.amount) }} RD$ payment
                        to  <span class="text-uppercase">
                            {{ payment.provider.name }}
                            </span>
                        by <span class="text-uppercase">
                            {{ payment.user.first_name }} {{ payment.user.last_name }} ( {{ payment.user.code }} )
                            </span>
                        new provider claim is
                            <span>
                              {{ payment.provider_new_claims }} RD$
                            </span>
                     </td>
                     <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(payment.created_at) }}
                    </td>
                    </tr>
                  </tbody>
                  <div v-show="isLoading"> 
                      <img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;"/>
                    </div>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
           
          </div>
  
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
              <Pagination :data="providerpayments" @pagination-change-page="getProviderPayments" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useProviderPayments from "../../services/providerpaymentservices";
  import useUtils from "../../services/utilsservices";
  import { useAbility } from '@casl/vue';
  
  export default {
    setup() {
      const { getProviderPayments, providerpayments, isLoading } = useProviderPayments();
      const { formatDecimalNumber, formatDate } = useUtils();
      const { can, cannot } = useAbility();
  
      const form = reactive({
        q: ''
      });

      onMounted(async() => {
        await getProviderPayments();
      })
      
    //   const search = async () => {
    //     if (form.q.length > 0) {
    //       axios.get("/api/users/search/" + form.q).
    //         then(response => { users.value = response.data }).
    //         catch(error => console.log(error))
    //     } else {
    //       getUsers()
    //     }
    //   }
  
      return {
        providerpayments,
        form,
        getProviderPayments,
        formatDate,
        formatDecimalNumber,
        isLoading,
        can,
        cannot,
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