<template>
     <div class="container-fluid">
        <div class="row">
        <div class="col-md-12 mt-3">
          <!--  -->
          <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header border-transparent">
              <h3 class="card-title">Edit Provider</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                <div class="row">
                 
                  <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" v-model="provider.name">
                    <div v-if="errors.name" class="form-text error text-danger">{{ errors.name[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" v-model="provider.email">
                    <div v-if="errors.email" class="form-text error text-danger">{{ errors.email[0] }}</div>
                  </div>
  
                  <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <vue-tel-input v-model="provider.phone" :inputOptions="{ placeholder: 'Ex: 34000000' }" mode="international"
                      :onlyCountries="['ht']"></vue-tel-input>
                    <div v-if="errors.phone" class="form-text error text-danger">{{ errors.phone[0] }}</div>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" v-model="provider.address">
                    <div v-if="errors.address" class="form-text error text-danger">{{ errors.address[0] }}</div>
                  </div>

                </div>

              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <button class="btn btn-primary" @click.prevent=editProvider(provider.id)> Update </button>
                <img v-show="buttonIsLoading" class="img-fluid img-circle" src="/img/button_spinner.gif" alt="button-spinner" />
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
</template>
    
  <script>
  import { onMounted, reactive, ref } from 'vue';
  import useProviders from "../../services/providerservices";
  
  export default {
    props: ['id'],
    setup(props) {
  
      const { getProvider, provider, updateProvider, buttonIsLoading } = useProviders();
      const errors = ref([]);
  
      onMounted(async() => {
        await getProvider(props.id);
      })
  
      const editProvider = async (id) => {
        try {
            await updateProvider(id);
        } catch (error) {
            errors.value = error.response.data.errors;
        }
      }
  
      
      return {
        provider,
        editProvider,
        buttonIsLoading,
        errors
      };
    }
  }
  </script>