<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!--  -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Edit Department</h3>
                    </div>

                    <!-- /.card-header -->s
                    <div class="card-body">
                        <form class="ml-3 mr-3 mb-3" enctype="multipart/form-data">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Department name</label>
                                    <input type="text" class="form-control" id="name" v-model="department.name">
                                    <div v-if="errors.name" class="form-text error text-danger">{{ errors.name[0] }}</div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button class="btn btn-primary" @click.prevent="editDepartment(department.id)"> Update </button>
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
import useDepartments from "../../services/departmentservices";
import useUtils from "../../services/utilsservices";

export default {
    props: ['id'],
    setup(props) {
        const { getDepartment, updateDepartment, department } = useDepartments();
        const { buttonIsLoading } = useUtils();
        const errors = ref([]);

        onMounted(async () => {
            await getDepartment(props.id);
        })

        const editDepartment = async (id) => {
            try {
                await updateDepartment(id);
            } catch (error) {
                errors.value = error.response.data.errors;
            }
        }

        return {
            department,
            editDepartment,
            buttonIsLoading,
            errors
        };
    }
}
</script>