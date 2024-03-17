<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <!-- TABLE: LATEST TRANSFERTS -->
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header border-transparent">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <!-- <router-link :to="{ name: 'required_replenishments.create' }" class="btn btn-sm btn-info float-left">
                                <i class="fa-solid fa-building"></i> Add required_replenishment
                            </router-link> -->
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
                                        <th class="text-nowrap">#</th>
                                        <th class="text-nowrap">Required Replenishments</th>
                                        <th class="text-nowrap">Date</th>
                                        <th class="text-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-for="required_replenishment in required_replenishments.data"
                                    :key="required_replenishment.id">
                                    <tr>
                                        <td class="text-center text-nowrap">
                                            {{ required_replenishment.id }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ formatDecimalNumber(required_replenishment.required_amount) }} PESOS required
                                            for
                                            <span class="text-uppercase">
                                                {{ required_replenishment.user.first_name }} {{
                                                    required_replenishment.user.last_name }} ( {{ required_replenishment.user.code }} )

                                            </span>
                                        </td>
                                        <td class="text-nowrap text-xs text-muted">
                                            {{ formatDate(required_replenishment.created_at) }}
                                        </td>
                                        <td>
                                            <div class="sparkbar ml-3" data-color="#00a65a" data-height="20">
                                                <div class="btn-group dropstart">
                                                    <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v" title="More"
                                                            style="font-weight: bold; font-size: 1rem;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li v-if="can('make', 'replenishment')">
                                                            <!-- <router-link :to="{ name: 'replenishment.create' }" v-if="can('make', 'replenishment')">
                                                                <i class='fas fa-plus'></i> View details
                                                            </router-link> -->
                                                        </li>
                                                        <li v-if="can('make', 'replenishment')">
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li v-if="can('make', 'replenishment')">
                                                            <router-link :to="{ name: 'replenishment.create', params: { agentid: required_replenishment.user_id, amount: required_replenishment.required_amount, reqreplenishmentid: required_replenishment.id } }" v-if="can('make', 'replenishment')" class="ml-2">
                                                                <!-- <i class='fas fa-plus'></i> -->
                                                                 Replenish now
                                                            </router-link>
                                                        </li>
                                                    </ul>
                                                </div>
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
                        <Pagination :data="required_replenishments" @pagination-change-page="getRequiredReplenishments" />
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>
    
<script>
import { onMounted, reactive, ref } from 'vue';
import useReplenishments from "../../../services/replenishmentservices";
import useUtils from "../../../services/utilsservices";
import { useAbility } from '@casl/vue';
import Swal from "sweetalert2";

export default {
    props: ['user'],
    setup(props, { emit }) {
        const { getRequiredReplenishments, required_replenishments } = useReplenishments();
        const { formatDecimalNumber, formatDate } = useUtils();
        const { can, cannot } = useAbility();

        const form = reactive({
            role: '',
        });

        onMounted(async () => {
            await getRequiredReplenishments();
        })

        return {
            form,
            getRequiredReplenishments,
            required_replenishments,
            formatDecimalNumber,
            formatDate,
            can,
            cannot
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
}</style>