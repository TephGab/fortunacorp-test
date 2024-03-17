<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div style="display: flex; justify-content: space-between; align-items: center">
                Transferts History
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
                  <tbody v-for="transfert in allaccountstransferts.data" :key="transfert.id">
                    <tr>
                      <td class="text-nowrap">
                        {{ transfert.id }}
                      </td>
                     <td class="text-nowrap">
                        Transfert of {{ formatDecimalNumber(transfert.amount) }} HTG from 
                        <span class="text-uppercase">
                          {{ transfert.account_sender_phone }} ( {{ transfert.account_sender_name }} )
                        </span>
                        to <span class="text-uppercase">
                            {{ transfert.account_receiver_phone }} ( {{ transfert.account_receiver_name }} )
                          </span>
                        by <span class="text-uppercase">
                          {{ transfert.user.first_name }} {{ transfert.user.last_name }} ( {{ transfert.user.code }} )
                          </span>
                     </td>
                     <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(transfert.created_at) }}
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
              <Pagination :data="allaccountstransferts" @pagination-change-page="getAllAccountsTransferts" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useAccountTransferts from "../../services/accounttransfertservices";
  import useUtils from "../../services/utilsservices";
  import { useAbility } from '@casl/vue';
  
  export default {
    setup() {
      const { getAllAccountsTransferts, allaccountstransferts, isLoading } = useAccountTransferts();
      const { formatDecimalNumber, formatDate } = useUtils();
      const { can, cannot } = useAbility();
  
      const form = reactive({
        q: ''
      });

      onMounted(async() => {
        await getAllAccountsTransferts();
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
        allaccountstransferts,
        form,
        getAllAccountsTransferts,
        isLoading,
        formatDecimalNumber, 
        formatDate,
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