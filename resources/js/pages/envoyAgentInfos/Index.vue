<template>
  <div class="container-fluid">
    <div class="row">
    <!-- Envoy view -->
       <div class="col-md-12 mt-3">
        <!-- TABLE: LATEST TRANSFERTS -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
          <div class="card-header border-transparent">
            <!-- <div class="d-flex justify-content-between">
              <router-link :to="{ name: 'agent.create' }" class="btn btn-sm btn-info float-left">
                <i class='fas fa-agent-plus'></i> New agent
              </router-link>
              <div class="card-tools" v-if="can('export_pdf', 'transaction')">
                <div v-show="!isButtonLoading">
                  <label class="form-label text-success cursor-pointer text-lg" @click.prevent="showOptionPdf" title="Export as PDF"><i class="fa-solid fa-file-pdf"></i></label>
                  <select class="border-0 outline-0 text-gray-500 text-xs" v-show="showPdfOption" v-model="form.pdf_export_option"
                    @change="pdfExport(form.pdf_export_option)">
                    <option v-for="option in pdfOptions" :value="option.value" :key="option.text">
                      {{ option.text }}
                    </option>
                  </select>
                </div>
                <div v-show="isButtonLoading">
                  Generating PDF ... <img class="img-fluid img-circle" src="/img/spinner.gif" alt="button-spinner"
                    style="width: 20px; display: inline" />
                </div>
              </div>
              <form>
                <input type="text" id="search" name="search" @keyup="search" placeholder="Search.." v-model="form.q">
              </form>
            </div> -->
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive table-hover">
              <table class="table m-0">
                <thead>
                  <tr>
                     <!-- <th class="text-center text-nowrap">#</th> -->
                     <th class="text-center text-nowrap w-100" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                        Envoy
                        <Select2 class="text-center text-nowrap" v-model="form.envoy_id" :options="envoys" :settings="{ width: '100%', textTransform: 'uppercase', margin: 0,  padding: 0, fontSize: '14px'}" />
                     </th>
                      <th class="text-center text-nowrap">Agent
                        <!-- <Select2 class="text-center text-nowrap" v-model="form.agent_id" :options="agents" :settings="{ width: '100%', textTransform: 'uppercase', margin: 0,  padding: 0, fontSize: '14px'}" /> -->
                      </th>
                      <th class="text-center text-nowrap">Capital</th>
                      <th class="text-center text-nowrap">Debt</th>
                      <th class="text-center text-nowrap">Cash in hand</th>
                      <th class="text-center text-nowrap" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">Actions</th>
                  </tr>
                </thead>
                <tbody v-for="envoy in agentInfos.data" :key="envoy.id">
                  <tr v-for="agent in envoy.envoy_agents" :key="agent.id">
                    <td class="text-capitalize text-center text-nowrap" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer' && agent && agent.envoy">
                        <div> {{ agent.envoy.first_name }} {{ agent.envoy.last_name }}</div>
                        <div class="text-gray-500"> {{ agent.envoy.code }}</div>
                    </td>
                    <td class="text-capitalize text-center text-nowrap">
                        <div>{{ agent.first_name }} {{ agent.last_name }}</div>
                        <div class="text-gray-500">{{ agent.code }}</div>
                    </td>
                    <td class="text-capitalize text-center text-nowrap fw-bold" v-if="agent && agent.user_account">{{ formatDecimalNumber(agent.user_account.capital) }} RD$</td>
                    <td class="text-capitalize text-center text-nowrap fw-bold" v-if="agent && agent.user_account">{{ formatDecimalNumber(agent.user_account.depts) }} RD$</td>
                    <td class="text-capitalize text-center text-nowrap fw-bold" v-if="agent && agent.user_account">{{ formatDecimalNumber(agent.user_account.cash_in_hand) }} RD$</td>
                    <td class="text-center fw-bold" v-if="userrole.name == 'super-admin' || userrole.name == 'admin' || userrole.name == 'system-engineer'">
                      <div class="sparkbar ml-3" data-color="#00a65a" data-height="20">
                        <div class="btn-group dropstart">
                          <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" title="More" style="font-weight: bold; font-size: 1rem;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <button class="ml-2" @click="openVerificationBalanceModal(agent.user_account.profits, agent.user_account.referral_commissions, agent.user_account.investments, agent.user_account.cash_in_hand, agent.user_account.capital, agent.user_account.depts, agent.first_name, agent.last_name, agent.code)">
                                Verification balance
                              </button>
                              <!-- <button class="ml-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Verification balance
                              </button> -->
                             <!-- <button class="ml-2">Verification balance</button> -->
                            </li>
                          </ul>
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
         
        </div>


        <!-- Modal -->
        <div class="modal fade" id="agentVerification" tabindex="-1" aria-labelledby="agentVerificationLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="text-center w-100 fw-bold">
                <div> {{ agentVerif.agentFirstName }} {{ agentVerif.agentLastName }}</div>
                <div class="text-muted"> {{ agentVerif.agentCode }} </div>
                <!-- <h5 class="modal-title" id="agentVerificationLabel">Agent Code</h5> -->
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
              </div>
              <div class="modal-body">
                              <!-- Table -->
                              <table class="table caption-top">
                                <!-- <caption>List of users</caption> -->
                                <thead>
                                  <tr>
                                    <th scope="col" class="text-nowrap text-right"></th>
                                    <th scope="col" class="text-nowrap text-right">Debit</th>
                                    <th scope="col" class="text-nowrap text-right">Credit</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th scope="row" class="text-nowrap text-right">RECHARGE</th>
                                    <td class="text-nowrap text-right">{{ formatDecimalNumber(agentVerif.recharge) }}</td>
                                    <td class="text-nowrap text-right"></td>
                                  </tr>
                                  <tr>
                                    <th scope="row" class="text-nowrap text-right">COMMISSION</th>
                                    <td class="text-nowrap text-right">{{ formatDecimalNumber(agentVerif.commission) }}</td>
                                    <td class="text-nowrap text-right"></td>
                                  </tr>
                                  <tr>
                                    <th scope="row" class="text-nowrap text-right">REF. COMMISSION</th>
                                    <td class="text-nowrap text-right">{{ formatDecimalNumber(agentVerif.commission_ref) }}</td>
                                    <td class="text-nowrap text-right"></td>
                                  </tr>
                                  <tr>
                                    <th scope="row" class="text-nowrap text-right">CASH IN HAND</th>
                                    <td class="text-nowrap text-right">{{ formatDecimalNumber(agentVerif.cash_in_hand) }}</td>
                                    <td class="text-nowrap text-right"></td>
                                  </tr>
                                  <tr>
                                    <th scope="row" class="text-nowrap text-right">CAPITAL</th>
                                    <td class="text-nowrap text-right"></td>
                                    <td class="text-nowrap text-right">{{ formatDecimalNumber(agentVerif.capital) }}</td>
                                  </tr>
                                  <tr class="text-nowrap text-right">
                                    <th scope="row" class="text-nowrap text-right">DEBT</th>
                                    <td class="text-nowrap text-right"></td>
                                    <td class="text-nowrap text-right">{{ formatDecimalNumber(agentVerif.debt) }}</td>
                                  </tr>
                                  <tr>
                                    <th scope="row" class="text-nowrap text-right">TOTAL</th>
                                    <th class="text-nowrap text-right"> {{ formatDecimalNumber(agentVerif.totalDebit) }}</th>
                                    <th class="text-nowrap text-right"> {{ formatDecimalNumber(agentVerif.totalCredit) }}</th>
                                  </tr>
                                </tbody>
                              </table>
                              <!-- End table -->
              </div>
            </div>
          </div>
        </div>
        <!-- End modal -->



        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 overflow-auto">
            <Pagination :data="agentInfos" @pagination-change-page="getEnvoyAgentInfos" />
          </div>
        </div>
        <!-- /.card -->
      </div>
     <!-- End envoy view -->

    <!-- All agent (To select) -->
    <div class="row mt-3" v-if="agentLists.length > 0">
        <div class="col-md-12">
            <table class="table table-light">
            <thead>
              <tr>
                <th class="text-center mb-1" scope="col">
                  AGENTS
                  <Select2 class="text-center text-nowrap" v-model="form.agent_id" :options="agents" :settings="{ width: '100%', textTransform: 'uppercase', margin: 0,  padding: 0, fontSize: '14px'}" />
                </th>
              </tr>
            </thead>
            <tbody>
                <tr>
                <td>
                    <div class="row">
                        <div class="col-md-4" v-for="(agent, index) in agentLists" :key="agent.id">
                        <!-- <div v-if="index == 0" class="mb-1 fw-bold text-muted w-100">
                          <span>Selected envoy :</span> 
                          <span>{{ agent.envoy.first_name }}</span>
                        </div> -->
                        <div class="mb-3 d-flex flex-row flex-nowrap">
                            <span class="mr-1">
                            <input type="checkbox" :value="agent.id" :checked="isChecked(agent.envoy_id)" @change="updateAgentStatus(agent.id, form.envoy_id, $event.target.checked)">
                            </span>
                                <span class="fw-bold">{{ agent.id }}</span>
                                <span> - ({{ agent.first_name }} {{ agent.last_name }} {{ agent.code }})</span>
                        </div>
                        </div>
                    </div>
                </td>
                </tr>
            </tbody> 
            </table>
        </div>
    </div>
    <!-- End all agent (To select) -->
    </div>
  </div>
</template>
  
<script>
import { onMounted, reactive, ref, watch } from 'vue';
import useUsers from "../../services/usersservices";
import useUtils from "../../services/utilsservices";
import { useAbility } from '@casl/vue';
import Swal from "sweetalert2";

export default {
  props: ['user', 'userrole'],
  setup(props) {
    const { getEnvoyAgentInfos, agentInfos, getEnvoys, envoys, getAgents, agents, getSelectedEnvoyAgentInfos, getAgentList, agentLists, getSelectedAgentList, updateEnvoyAgent } = useUsers();
    const { formatDecimalNumber, isLoading } = useUtils();
    const { can, cannot } = useAbility();

    const form = reactive({
      q: '',
      envoy_id: '',
      agent_id: '',
    });

    const agentVerif = reactive({
      agentFirstName: null,
      agentLastName: null,
      agentCode: null,
      commission: null,
      commission_ref: null,
      recharge: null,
      cash_in_hand: null,
      capital: null,
      debt: null,
      totalDebit: null,
      totalCredit: null,
    });

    const errors = ref([]);

    const openVerificationBalanceModal = (commission, commission_ref, recharge, cash_in_hand, capital, debt, agentFirstName, agentLastName, agentCode) => {
      agentVerif.commission = commission;
      agentVerif.commission_ref = commission_ref;
      agentVerif.recharge = recharge;
      agentVerif.cash_in_hand = cash_in_hand;
      agentVerif.capital = capital;
      agentVerif.debt = debt;
      agentVerif.totalDebit = commission + commission_ref + recharge + cash_in_hand;
      agentVerif.totalCredit = capital + debt;

      agentVerif.agentFirstName = agentFirstName;
      agentVerif.agentLastName = agentLastName;
      agentVerif.agentCode = agentCode;
      const myModal = new bootstrap.Modal(document.getElementById('agentVerification'));
      myModal.show();
    };

    onMounted(async() => {
      await getEnvoyAgentInfos();
      await getEnvoys();
      await getAgents();
    });

    const isChecked = (envoyId) => {
        // Check if the authenticated user's ID matches the agent's envoy_id
        if(form.envoy_id)
        {
            return form.envoy_id == envoyId;
        }
    };

    const updateAgentStatus = async (agentId, envoyId, isChecked) => {
        try {
            if (isChecked) {
                await updateEnvoyAgent({"agent_id": agentId, "envoy_id": envoyId});
                await getSelectedEnvoyAgentInfos({'envoy_id': envoyId});
            } else {
                await updateEnvoyAgent({"agent_id": agentId, "envoy_id": '1'});
            }
            console.log(`Agent ${agentId} status updated to ${isChecked ? 'checked' : 'unchecked'}`);
            await getSelectedEnvoyAgentInfos({'envoy_id': envoyId});
        } catch (error) {
            console.error('Error updating agent status:', error);
        }
        };

    watch([() => form.envoy_id], async (values, oldValue) => {
        const newValue = values[0];
        if (newValue !== null) {
            await getSelectedEnvoyAgentInfos({'envoy_id': newValue});
            await getAgentList({'agent_id': 0});
        }
    });

    watch([() => form.agent_id], async (values, oldValue) => {
        const newValue = values[0];
        if (newValue !== null) {
          await getAgentList({'agent_id': newValue});
        }
    });

    return {
        props,
        getEnvoyAgentInfos,
        agentInfos,
        form,
        isLoading,
        errors,
        formatDecimalNumber,
        getEnvoys,
        envoys,
        getAgentList,
        agentLists,
        agents,
        isChecked,
        updateAgentStatus,
        openVerificationBalanceModal,
        agentVerif
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