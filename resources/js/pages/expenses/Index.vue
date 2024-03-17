<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!-- TABLE: LATEST TRANSFERTS -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <div style="display: flex; justify-content: space-between; align-items: center">
                <router-link :to="{ name: 'expenses.create' }" class="btn btn-sm btn-info float-left">
                  <i class='fas fa-plus'></i> New expense
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
                      <th class="text-nowrap">Expenses</th>
                      <th class="text-nowrap">Details</th>
                      <th class="text-nowrap">Date</th>
                      <!-- <th class="text-nowrap">Actions</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="expense in expenses.data" :key="expense.id">
                      <td v><span class="text-capitalize">{{ expense.id }}</span></td>
                      <td class="text-nowrap">{{ expense.amount }} 
                        <span>{{ expense.currency }} deducted from </span>
                        <span class="text-capitalize text-emerald-700">{{ expense.deduct_from }}</span>
                      </td>
                      <td class="text-nowrap">
                        <span class="fw-bold text-capitalize text-success">{{ expense.type }} <span v-if="expense.comment"> : </span> </span>
                        <span class="text-sm text-success text-capitalize">{{ expense.comment }}</span>
                      </td>
                      <td class="text-nowrap text-xs text-muted">
                        {{ formatDate(expense.created_at) }}
                      </td>
    
                      <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                            <!-- <router-link class-active="active" :to="{ name: 'expenses.edit', params: { id: expense.id } }">
                                <i class="fas fa-edit mr-3" style="color:blueviolet;"></i>
                            </router-link>
                            <i class="fas fa-trash" style="color:red;" @click="deleteProvider(expense.id)"></i> -->
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
              <Pagination :data="expenses" @pagination-change-page="getExpenses" />
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
      
    <script>
    import { onMounted, reactive, ref } from 'vue';
    import useExpenses from "../../services/expenseservices.js";
    import useUtils from "../../services/utilsservices";
    
    export default {
    
      setup() {
        const { getExpenses, expenses, isLoading } = useExpenses();
        const { formatDate } = useUtils();
    
        onMounted(async() => {
         await getExpenses();
        })
    
        return {
          getExpenses,
          expenses,
          isLoading,
          formatDate,
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