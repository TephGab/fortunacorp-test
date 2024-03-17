<template>
     <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3">
        <!-- TABLE: LATEST TRANSFERTS -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <div class="text-center text-xs fw-bold mb-1">
                <div>FORTUNA CORPORATION</div>
                <div>CASHINS</div>
              </div>
            <div style="display: flex; justify-content: space-between; align-items: center">
              <router-link :to="{ name: 'cashins.create' }" class="btn btn-sm btn-info float-left">
                <i class='fas fa-plus'></i> New cashin
              </router-link>
              <!-- <form>
                <input type="text" id="search" name="search" @keyup="search" placeholder="Search by number.." v-model="form.q">
              </form> -->
              <div>
                <div class="form-check form-check-inline border border-info rounded pl-1">
                  <i class="fas fa-sort-numeric-up" v-show="form.soldeSort == 'shuffled'"></i>
                  <i class="fas fa-sort-numeric-up" v-show="form.soldeSort == 'max_solde'"></i>
                  <i class="fas fa-sort-numeric-down" v-show="form.soldeSort == 'min_solde'"></i>
                  <select class="border-0 outline-0" v-model="form.soldeSort" @change="sort(form.soldeSort)">
                    <option v-for="optionSoldeSort in optionSoldeSorts" :value="optionSoldeSort.value" :key="optionSoldeSort.text">
                      {{ optionSoldeSort.text }}
                    </option>
                  </select>
                </div>

                <div class="form-check form-check-inline border border-info rounded pl-1">
                  <i class="fas fa-sort m-0 p-0"></i> 
                  <select class="border-0 outline-0 " v-model="form.type" @change="sort(form.type)">
                    <option v-for="option in options" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th class="text-nowrap">#</th>
                    <th class="text-nowrap text-right">Amount</th>
                    <th class="text-nowrap">Rate</th>
                    <th class="text-nowrap">Provider</th>
                    <th class="text-nowrap">Account</th>
                    <th class="text-nowrap">Type</th>
                    <th class="text-nowrap">Date</th>
                    <!-- <th>Actions</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="cashin in cashins.data" :key="cashin.id">
                    <td class="text-nowrap text-capitalize">{{ cashin.id }}</td>
                    <td class="text-nowrap text-nowrap fw-bold text-gray-600 text-right">
                      <span class="text-capitalize">{{ formatDecimalNumber(cashin.amount) }} RD$ </span>
                    </td>
                    <td>
                      <span class="text-muted fw-light" style="font-size: 12px;">({{ cashin.rate }})</span>
                    </td>
                    <td class="text-nowrap text-capitalize text-left text-xs">
                      <div>{{ cashin.provider.phone }}</div>
                      <div>({{ cashin.provider.name }})</div>
                    </td>
                    <td class="text-nowrap text-capitalize text-left text-xs">
                      <div>{{ cashin.account.phone }}</div> 
                      <div>({{ cashin.account.name }})</div>
                    </td>

                    <td class="text-nowrap text-capitalize">
                    <span :class="['badge', cashin.type == 'moncash' ? 'badge-danger' : cashin.type == 'natcash' ? 'badge-primary' : cashin.type == 'local' ? 'badge-secondary' : '']">{{cashin.type}}
                    </span>
                    </td>
                    <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(cashin.created_at) }}
                    </td>
                    <td class="text-nowrap">
                      <div class="sparkbar" data-color="#00a65a" data-height="20">
                         <!-- <router-link class-active="active" :to="{ name: 'cashins.edit', params: { id: cashin.id } }">
                          <i class="fas fa-edit mr-3" style="color:blueviolet;"></i>
                        </router-link> -->
                       <!-- <i class="fas fa-trash" style="color:red;" @click="deleteAccount(cashin.id)"></i> -->
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
          <!-- <div class="card-footer clearfix">
             
          </div> -->
          <!-- /.card-footer -->
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
            <Pagination :data="cashins" @pagination-change-page="getCashins" />
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>
      
<script>
import { onMounted, reactive, ref } from 'vue';
import useCashins from "../../services/cashinservices.js";
import useUtils from "../../services/utilsservices";

export default {
    props: ['user'],
    setup(props) {

        const { getCashins, cashins, isLoading, cashinSort } = useCashins();
        const { formatDecimalNumber, formatDate } = useUtils();

        const form = reactive({
          q: '',
          type: 'all',
          soldeSort: 'shuffled'
        });
        
        const options = ref([
        { text: 'All', value: 'all' },
        { text: 'Moncash', value: 'moncash' },
        { text: 'Natcash', value: 'natcash' },
        ]);

        const optionSoldeSorts = ref([
        { text: 'Shuffled', value: 'shuffled' },
        { text: 'Max Amount', value: 'max_solde' },
        { text: 'Min Amount', value: 'min_solde' },
        ]);

        onMounted(async () => {
            await getCashins();
        })

        const sort = async (type) => {
          const data = new FormData()
          data.append('type', form.type);
          data.append('solde_sort', form.soldeSort);
          cashinSort(data);
        }

        return {
            cashins,
            getCashins,
            form,
            options,
            optionSoldeSorts,
            sort,
            isLoading,
            formatDecimalNumber, 
            formatDate
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