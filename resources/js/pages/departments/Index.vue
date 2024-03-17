<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!-- TABLE: LATEST TRANSFERTS -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <router-link :to="{ name: 'departments.create' }" class="btn btn-sm btn-info float-left">
                                <i class="fa-solid fa-building"></i> Add department
                            </router-link>
                            <!-- <form>
                            <input type="text" id="search" name="search" @keyup="search" placeholder="Search.." v-model="form.q">
                            </form> -->

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive table-hover">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-nowrap">#</th>
                                        <th class="text-center text-nowrap"> Department name </th>
                                        <th class="text-center text-nowrap"> Action </th>
                                    </tr>
                                </thead>
                                <tbody v-for="department in departments.data" :key="department.id">
                                    <tr>
                                        <td class="text-center text-nowrap">
                                            {{ department.id }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            {{ department.name }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <div class="sparkbar" data-color="#00a65a" data-height="20">
                                                <router-link class-active="active"
                                                    :to="{ name: 'departments.edit', params: { id: department.id } }">
                                                    <i class="fas fa-edit mr-3" style="color:blueviolet;"></i>
                                                </router-link>
                                                <!-- <i class="fas fa-trash" style="color:red;" @click="deleteProvider(provider.id)"></i> -->
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
                        <Pagination :data="departments" @pagination-change-page="getDepartments" />
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
import useDepartments from "../../services/departmentservices";
import useUtils from "../../services/utilsservices";
import useRoleAndPermission from "../../services/roleandpermissionservices";
import { useAbility } from '@casl/vue';
import Swal from "sweetalert2";

export default {
    setup() {
        const { getDepartments, departments } = useDepartments();
        const { can, cannot } = useAbility();

        const form = reactive({
            role: '',
        });

        onMounted(async () => {
            await getDepartments();
        })

        return {
            form,
            getDepartments,
            departments,
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