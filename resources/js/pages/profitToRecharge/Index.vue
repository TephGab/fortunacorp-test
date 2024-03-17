<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">

            <div class="card-header border-transparent">
              <!-- <div class="text-center text-xs fw-bold">
                <div>FORTUNA CORPORATION</div>
                <div>AGENT DEPOSITS</div>
              </div> -->
            <div style="display: flex; justify-content: space-between; align-items: center">
              <router-link class="btn btn-sm btn-info float-left" class-active="active" :to="{ name: 'profittorecharge.create', params: { commission: 'commission' } }">
                <i class='fas fa-plus'></i> Commission to recharge
              </router-link>
      
              <div class="text-right text-xs">
                  <span>Total {{ formatDecimalNumber(profitToRecharges.transfertTotal) }} RD$</span>
              </div>
            </div>
          </div>

            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive table-hover">
                <table class="table m-0">
                  <thead>
                    <!-- <tr v-if="profitToRecharges.transfertTotal" class="text-right text-xs">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-nowrap"> Total {{ formatDecimalNumber(profitToRecharges.transfertTotal) }}</th>
                    </tr> -->
                    <tr>
                      <th class="text-nowrap">#</th>
                      <th class="text-nowrap text-right">Amount</th>
                      <th class="text-nowrap text-right">Commission</th> 
                      <th class="text-nowrap text-right">Recharge</th> 
                      <th class="text-nowrap">Type</th> 
                      <th class="text-nowrap">Date</th> 
                      <!-- <th class="text-nowrap">Status</th>
                      <th class="text-nowrap">Actions</th> -->
                    </tr>
                  </thead>
                  <tbody v-if="profitToRecharges.transferts && profitToRecharges.transferts.data">
                    <tr v-for="recharge in profitToRecharges.transferts.data" :key="recharge.id">
                      <td class="text-nowrap"> {{ recharge.id }} </td>
                      <td class="text-nowrap text-right">
                        <span>{{ formatDecimalNumber(recharge.amount) }} <span class="text-uppercase"> RD$</span></span>
                      </td>
                      <td class="text-nowrap text-right">
                        <span> from {{ formatDecimalNumber(recharge.current_balance) }}</span>
                        <span> to {{ formatDecimalNumber(recharge.new_balance) }}</span>
                        <span class="text-uppercase"> RD$</span>
                      </td>
                      <td class="text-nowrap text-right">
                        <span> from {{ formatDecimalNumber(recharge.current_investment) }}</span>
                        <span> to {{ formatDecimalNumber(recharge.new_investment) }}</span>
                        <span class="text-uppercase"> RD$</span>
                      </td>
                      <td class="text-nowrap text-xs text-uppercase">
                        {{ recharge.commission_category }}
                      </td>
                      <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(recharge.completed_date) }}
                      </td>
                    </tr>
                    <!-- <tr v-show="isLoading"> 
                      <td><img class="img-fluid img-circle" src="img/spinner.gif" alt="Spinner" style="witdh: 50px;"/> </td>
                    </tr>  -->
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
              <Pagination :data="profitToRecharges" @pagination-change-page="getProfitToRecharges" />
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
    props: ['user', 'userrole'],
    setup(props) {
      const { getProfitToRecharges, profitToRecharges, isLoading } = useUsers();
      const { formatDecimalNumber, formatDate } = useUtils();
      const { can, cannot } = useAbility();
      const errors = ref([]);

      const form = reactive({
        q: ''
      });
  
      onMounted(async() => {
       await getProfitToRecharges();
    //    await Echo.private('recharge')
    //     .listen('ReplenishmentEvent', (e) => {
    //       getProfitToRecharges();
    //     });
      });
      
      return {
        props,
        profitToRecharges,
        getProfitToRecharges,
        formatDecimalNumber,
        can,
        cannot,
        formatDate,
        isLoading,
        errors,
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