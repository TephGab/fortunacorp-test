<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <h3 class="card-title">
                New department
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                <!-- role -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="departmentName" class="form-label">Department Name:</label>
                            <input id="departmentName" v-model="form.departmentName" type="text" class="form-control" required>
                        </div>
                    </div>
                <!-- role end -->
              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <button class="btn btn-primary" @click.prevent="createDepartment"> Add </button>
              <!-- <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif"
                alt="button-spinner" /> -->
            </div>
  
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useDepartments from "../../services/departmentservices";
  import router from '../../router';
  
  export default {
    props: ['user'],
    setup(props) {
    const { storeDepartment } = useDepartments();
    
      const form = reactive({
        departmentName: '',
      });
  
      const errors = ref([]);
  
    //   onMounted(async() => {
    //     await getRoles()
    //     await getPermissions();
    //   })

      const createDepartment = async () => {
        try {
            const data = new FormData();
            data.append('department_name', form.departmentName)
            await storeDepartment(data);
        } catch (error) {
            errors.value = error.response.data.errors;
        }
        }
  
      return {
        form,
        errors,
        createDepartment,
      };
    }
  }
  </script>