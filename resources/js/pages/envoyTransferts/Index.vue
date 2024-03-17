<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div class="text-center text-xs fw-bold">
                <div>FORTUNA CORPORATION</div>
                <div>ENVOY TRANSFERTS</div>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center">
                <router-link :to="{ name: 'envoytransferts.create' }" class="btn btn-sm btn-info float-left">
                  <i class='fas fa-plus'></i> New transfert
                </router-link>
                <!-- <form>
                  <input type="text" id="search" name="search" @keyup="search" placeholder="Search by number.." v-model="form.q">
                </form> -->
                <div>
                  <!-- <div class="form-check form-check-inline border border-info rounded pl-1">
                    <i class="fas fa-sort-numeric-up" v-show="form.soldeSort == 'max_solde'"></i>
                    <i class="fas fa-sort-numeric-down" v-show="form.soldeSort == 'min_solde'"></i>
                    <select class="border-0 outline-0" v-model="form.soldeSort" @change="sort(form.soldeSort)">
                      <option v-for="optionSoldeSort in optionSoldeSorts" :value="optionSoldeSort.value" :key="optionSoldeSort.text">
                        {{ optionSoldeSort.text }}
                      </option>
                    </select>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th class="text-nowrap">Sender</th>
                      <th class="text-nowrap">Receiver</th>
                      <th class="text-nowrap text-right">Amount</th>
                      <th class="text-nowrap">Note</th>
                      <th class="text-nowrap">Status</th>
                      <th class="text-nowrap">Date</th>
                      <th class="text-nowrap">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="transfert in envoyTransferts.data" :key="transfert.id">
                      <td class="text-nowrap align-middle"><span class="text-capitalize">{{ transfert.id }}</span></td>
                      <td class="text-nowrap text-xs align-middle">
                        <div class="text-capitalize text-emerald-700">{{ transfert.user.first_name }} {{ transfert.user.last_name }}</div>
                        <div class="text-capitalize text-emerald-700">({{ transfert.user.code }})</div>
                      </td>
                      <td class="text-nowrap text-xs align-middle">
                        <div class="text-capitalize text-emerald-700">{{ transfert.receiver.first_name }} {{ transfert.receiver.last_name }}</div>
                        <div class="text-capitalize text-emerald-700">({{ transfert.receiver.code }})</div>
                      </td>
                      <td class="text-nowrap text-right fw-bold text-gray-600 align-middle">{{ formatDecimalNumber(transfert.amount) }} 
                        <span v-if="transfert.currency == 'pesos'">RD$</span>
                        <span v-if="transfert.currency == 'us'">US </span>
                        <span v-if="transfert.currency == 'htg'">HTG </span>
                      </td>
                      <td class="text-muted text-xs fw-normal align-middle">
                        <div v-if="transfert.comment ">{{ transfert.comment }}</div>
                        <div v-else> _ </div>
                      </td>
                    <td class="text-capitalize text-nowrap align-middle">
                      <div>
                        <span v-if="transfert.status == 'pending' && transfert.confirmed_by_receiver == 0" class="badge badge-default text-info"> Waiting for confirmation 
                        </span>
                        <span v-if="transfert.status == 'confirmed' && transfert.confirmed_by_receiver == 1" class="badge badge-default text-success"> {{ transfert.status }} 
                        </span>
                      </div>
                    </td>
                    <td class="text-nowrap text-xs text-muted align-middle align-middle">
                       {{ formatDate(transfert.updated_at) }} 
                    </td>
    
                    <td class="text-nowrap align-middle">
                      <div v-if="userrole.name == 'envoy' && user.id == transfert.receiver_id">
                        <div v-if="transfert.status == 'pending' && transfert.confirmed_by_receiver == 0" class="badge badge-default text-primary">
                          <button class="btn btn-sm btn-success text-xs" @click="confirmTransfert(transfert.id)">Confirm</button>
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
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <!--  -->
            </div>
            <!-- /.card-footer -->
          </div>
  
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
              <Pagination :data="envoyTransferts" @pagination-change-page="getEnvoyTransferts" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
      
    <script>
    import { onMounted, reactive, ref } from 'vue';
    import useEnvoyDeposits from "../../services/envoydepositservices.js";
    import useUtils from "../../services/utilsservices";
    
    export default {
    props: ['user', 'userrole'],
      setup(props) {
        const { getEnvoyTransferts, envoyTransferts, confirmEnvoyTransfert,  isLoading } = useEnvoyDeposits();
        const { formatDecimalNumber, formatDate } = useUtils();

        onMounted(async() => {
         await getEnvoyTransferts();
         await Echo.private('envoytransfert')
          .listen('EnvoyTransfertEvent', (e) => {
            getEnvoyTransferts();
          })
        })

        const confirmTransfert = async(id) => {
         await confirmEnvoyTransfert({'transfert_id': id});
         await getEnvoyTransferts();
        }
    
        return {
          getEnvoyTransferts,
          envoyTransferts,
          isLoading,
          formatDate,
          props,
          confirmTransfert,
          formatDecimalNumber,
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