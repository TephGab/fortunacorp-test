<template>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <h3 class="card-title">
                New Permission
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                <!-- role -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="permission_name" class="form-label">Permission Name:</label>
                            <input id="permission_name" v-model="form.permission_name" type="text" class="form-control" required>
                            <div v-if="errors.permission_name" class="form-text error text-danger">{{ errors.permission_name[0] }}</div>
                        </div>
                    </div>
                <!-- role end -->
              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <button class="btn btn-primary" @click.prevent="createPermission"> Add </button>
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
  import useRoleAndPermission from "../../services/roleandpermissionservices";
  
  export default {
    props: ['user'],
    setup(props) {
    const { storePermission } = useRoleAndPermission();
    
      const form = reactive({
        permission_name: '',
      });
  
      const errors = ref([]);

      const createPermission = async () => {
      try {
        await storePermission({
            permission_name: form.permission_name,
        });
      } catch (error) {
        errors.value = error.response.data.errors;
      }
    }
  
      return {
        form,
        errors,
        createPermission,
      };
    }
  }
  </script>