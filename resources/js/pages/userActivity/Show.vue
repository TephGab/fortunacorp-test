<template>
  <!-- <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-3"> -->
  <!-- Edit mode -->
  <!-- TABLE: LATEST TRANSFERTS -->
  <div class="card card-primary card-outline direct-chat direct-chat-primary">
    <div class="card-header border-transparent bg-transparent">
      <!-- Account Info -->
      <div class="text-center">
        <!-- component -->
        <div class="mbg-white w-full rounded-lg" v-if="userSort">
          <div>
            <h2 class="text-sm fw-bold">
              <span class="mr-1">{{ userSort.first_name }} </span>
              <span>{{ userSort.last_name }} </span>
            </h2>
            <p class="text-sm text-gray-500">
              <span> {{ userSort.code }}</span>
            </p>
          </div>
          <div class="text-uppercase" v-if="userSort && userSort.roles">
            <span>
              {{ userSort.roles[0].name }}
            </span>
          </div>
        </div>
        <!-- </div> -->
        <div>
        </div>
      </div>
      <!-- Account info End -->
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0 m-0">
      <div class="row text-gray-300">
        <div class="col-md-12 text-center">
          Activities
        </div>
      </div>
      <div class="row text-right">
        <div>
          <label><i class="fa-regular fa-calendar-days"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs mr-2" v-model="form.selected_month" @change="sortByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionMonth" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
          <label><i class="fa-regular fa-calendar"></i></label>
          <select class="border-0 outline-0 text-gray-500 text-xs" v-model="form.selected_year" @change="sortByYear(form.selected_year, form.selected_month)">
            <option v-for="option in optionYear" :value="option.value" :key="option.text">
              {{ option.text }}
            </option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table m-0">
          <nav>
            <div class="nav nav-tabs fw-bold" id="nav-tab" role="tablist">

              <button v-if="props.userrole.name == 'agent' || props.userrole.name == 'operator' || props.userrole.name == 'system-engineer' || props.userrole.name == 'super-admin' || props.userrole.name == 'admin'" :class="[props.userrole.name == 'agent' || props.userrole.name == 'operator' ? 'active' : '', 'nav-link text-uppercase text-xs']" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab" aria-controls="commission" aria-selected="true" @click="tabToDisplay('commission')">
               Commisions
              </button>

              <button :class="[props.userrole.name == 'system-engineer' || props.userrole.name == 'super-admin' || props.userrole.name == 'admin' || props.userrole.name == 'envoy' ? 'active' : '', 'nav-link text-uppercase text-xs']" id="referral_commission-tab" data-bs-toggle="tab" data-bs-target="#referral_commission" type="button" role="tab" aria-controls="referral_commission" aria-selected="false"  @click="tabToDisplay('referral_commission')">
                Refs Commisions 
              </button>

              <button v-if="props.userrole.name == 'agent' || props.userrole.name == 'system-engineer' || props.userrole.name == 'super-admin' || props.userrole.name == 'admin'" class="nav-link text-uppercase text-xs" id="recharge-tab" data-bs-toggle="tab" data-bs-target="#recharge" type="button" role="tab" aria-controls="recharge" aria-selected="false" @click="tabToDisplay('recharge')">
                Recharge
              </button>

              <button v-if="props.userrole.name == 'envoy' || props.userrole.name == 'agent' || props.userrole.name == 'system-engineer' || props.userrole.name == 'super-admin' || props.userrole.name == 'admin'" class="nav-link text-uppercase text-xs" id="debt-tab" data-bs-toggle="tab" data-bs-target="#debt" type="button" role="tab" aria-controls="debt" aria-selected="false" @click="tabToDisplay('debt')">
                Debt
              </button>

              <button v-if="props.userrole.name == 'agent' || props.userrole.name == 'system-engineer' || props.userrole.name == 'super-admin' || props.userrole.name == 'admin'" class="nav-link text-uppercase text-xs" id="cash_in_hand-tab" data-bs-toggle="tab" data-bs-target="#cash_in_hand" type="button" role="tab" aria-controls="cash_in_hand" aria-selected="false" @click="tabToDisplay('cash_in_hand')">
                Cash in hand
              </button>

              <button v-if="props.userrole.name == 'agent' || props.userrole.name == 'system-engineer' || props.userrole.name == 'super-admin' || props.userrole.name == 'admin'" class="nav-link text-uppercase text-xs" id="capital-tab" data-bs-toggle="tab" data-bs-target="#capital" type="button" role="tab" aria-controls="capital" aria-selected="false" @click="tabToDisplay('capital')">
                Capital
              </button>
             
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">

            <!-- Commission  -->
              <Commission :sortedActivities="sortedActivities" 
                          :getLastEntryFromHistory="getLastEntryFromHistory"
                          :formatDecimalNumber="formatDecimalNumber"
                          :formatDateCalendar="formatDateCalendar"
                          :formatDate="formatDate"
                          :userrole="props.userrole"
                          v-if="form.tab == 'commission'"/>
            <!-- End Commision -->

            <!-- Referral Commission  -->
             <ReferralCommission :sortedActivities="sortedActivities" 
                          :getLastEntryFromHistory="getLastEntryFromHistory"
                          :formatDecimalNumber="formatDecimalNumber"
                          :formatDateCalendar="formatDateCalendar"
                          :formatDate="formatDate"
                          :userrole="props.userrole"
                          v-if="form.tab == 'referral_commission'"/>
            <!-- End Referral Commision -->


            <!-- Recharge -->
              <Recharge :sortedActivities="sortedActivities" 
                        :getLastEntryFromHistory="getLastEntryFromHistory"
                        :formatDecimalNumber="formatDecimalNumber"
                        :formatDateCalendar="formatDateCalendar"
                        :formatDate="formatDate"
                        :userrole="props.userrole"
                        v-if="form.tab == 'recharge'"/>
            <!-- End Recharge -->

            <!-- Commission  -->
            <Debt :sortedActivities="sortedActivities" 
                          :getLastEntryFromHistory="getLastEntryFromHistory"
                          :formatDecimalNumber="formatDecimalNumber"
                          :formatDateCalendar="formatDateCalendar"
                          :formatDate="formatDate"
                          :userrole="props.userrole"
                          v-if="form.tab == 'debt'"/>
            <!-- End Commision -->

            <!-- Cashin in hand  -->
            <CashInHand :sortedActivities="sortedActivities" 
                          :getLastEntryFromHistory="getLastEntryFromHistory"
                          :formatDecimalNumber="formatDecimalNumber"
                          :formatDateCalendar="formatDateCalendar"
                          :formatDate="formatDate"
                          :userrole="props.userrole"
                          v-if="form.tab == 'cash_in_hand'"/>
            <!-- End Cashin in hand -->

            <!-- Capital  -->
            <Capital :sortedActivities="sortedActivities" 
                          :getLastEntryFromHistory="getLastEntryFromHistory"
                          :formatDecimalNumber="formatDecimalNumber"
                          :formatDateCalendar="formatDateCalendar"
                          :formatDate="formatDate"
                          :userrole="props.userrole"
                          v-if="form.tab == 'capital'"/>
            <!-- End Capital -->

            
          </div>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
  </div>
  <!-- <div class="row">
        </div> -->
  <!-- </div>
    </div>
  </div> -->
</template>
  
<script>
import { onMounted, reactive, ref } from 'vue';
import useUserActivities from "../../services/useractivityservices.js";
import useUtils from "../../services/utilsservices";
import useUsers from "../../services/usersservices";
import Recharge from "./Recharge.vue";
import Commission from "./Commission.vue";
import ReferralCommission from "./ReferralCommission.vue";
import Debt from "./Debt.vue";
import Capital from "./Capital.vue";
import CashInHand from "./CashInHand.vue";
import { useAbility } from '@casl/vue';

export default {
  props: ['id', 'user', 'userrole'],
  components: {
    Recharge,
    Commission,
    ReferralCommission,
    Debt,
    CashInHand,
    Capital,
  },
  setup(props) {
    const { can, cannot } = useAbility();
    const { getUserActivities, userActivities } = useUserActivities();
    const { formatDecimalNumber, formatDateCalendar, formatDate } = useUtils();
    const { getUserSorts, userSort } = useUsers();

    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;

    const form = reactive({
      selected_year: '',
      selected_month: '',
      tab: 'commission',
    });
    const optionYear = ref([]);
    const optionMonth = ref([
      { text: 'January', value: '1' },
      { text: 'February', value: '2' },
      { text: 'March', value: '3' },
      { text: 'April', value: '4' },
      { text: 'May', value: '5' },
      { text: 'June', value: '6' },
      { text: 'July', value: '7' },
      { text: 'August', value: '8' },
      { text: 'September', value: '9' },
      { text: 'October', value: '10' },
      { text: 'November', value: '11' },
      { text: 'December', value: '12' }
    ]);

    // All in one
    const sortedActivities = ref([]);

    const fetchSortedActivities = async (user_id, selected_year, selected_month) => {
      try {
        const response = await axios.post("/api/userativities", { 
          'user_id': user_id,
          'selected_year': selected_year,
          'selected_month': selected_month});

        const data = response.data;

        // Convert object to array of objects
        const activitiesArray = Object.values(data).map(item => ({
          ...item, // Spread all properties from 'item'
          date: item.completed_date  || item.envoy_confirmation_date || item.receiver_confirmation_date || '',
        }));

        // Sort activities by date
        const sorted = activitiesArray.sort((a, b) => {
          const dateA = new Date(a.date);
          const dateB = new Date(b.date);
          return dateA - dateB;
        });

        sortedActivities.value = sorted;
      } catch (error) {
        console.error('Error fetching activities:', error);
      }
    };

    const getLastEntry = (obj) => {
      const entries = Object.entries(obj);
      return entries.length > 0 ? entries[entries.length - 1] : null;
    };

    const getLastEntryFromHistory = (array, fieldName) => {
      if (Array.isArray(array) && array.length > 0) {
        const lastObject = array[array.length - 1];
        if (typeof lastObject === "object" && fieldName in lastObject) {
          return lastObject[fieldName];
        }
      }
      return null; // Return null if the array is empty or fieldName is not found in the last object
    };
    // All in one end

    onMounted(async () => {
      await getUserSorts({'user_id': props.id});

      if (props.userrole.name == 'agent' || props.userrole.name == 'operator') {
        form.tab = 'commission';
      }else{
        form.tab = 'referral_commission';
      }
     
      if (userSort.value && userSort.value.user_sorts && userSort.value.user_sorts.length > 0) {
          form.selected_year = userSort.value.user_sorts[0].selected_year;
          form.selected_month = userSort.value.user_sorts[0].selected_month;
          await fetchSortedActivities(props.id, userSort.value.user_sorts[0].selected_year, userSort.value.user_sorts[0].selected_month);
      }else{
          form.selected_year = currentYear;
          form.selected_month = currentMonth;
          await fetchSortedActivities(props.id, currentYear, currentMonth);
      }

      // Add the current year
      optionYear.value.push({ text: currentYear.toString(), value: currentYear.toString() });
      // Add the past 5 years
      for (let i = 1; i <= 5; i++) {
        const pastYear = currentYear - i;
        optionYear.value.push({ text: pastYear.toString(), value: pastYear.toString() });
      }
    })

    const sortByYear = async (year, month) => {
      await fetchSortedActivities(props.id, year, month);
    }

    const tabToDisplay = async (tab) => {
      form.tab = tab;
    }

    return {
      form,
      can,
      cannot,
      formatDecimalNumber,
      formatDateCalendar,
      formatDate,
      sortByYear,
      optionYear,
      optionMonth,
      userActivities,
      getUserActivities,
      sortedActivities,
      getLastEntry,
      getLastEntryFromHistory,
      userSort,
      tabToDisplay,
      props,
    }
  },
}
</script>