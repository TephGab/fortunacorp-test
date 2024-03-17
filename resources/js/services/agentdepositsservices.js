import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';

export default function useAgentDeposits() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const agentDeposits = ref([]);
    const agentDeposit = ref([]);
    const agentDepositUser = ref([]);
    const envoyDeposit = ref([]);
    const pendingDeposit = ref([]);
    const totalAgentDepositCount = ref([]);


    const getAgentDeposits = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/agentdeposits?page=" + page);
        agentDeposits.value = await response.data;
        isLoading.value = false;
    };

    const getTotalAgentDepositsCount = async () => {
        let response = await axios.get("/api/totalagentdepositscount");
        totalAgentDepositCount.value = await response.data;
    };

    const getAgentDeposit = async (id) => {
        let response = await axios.get("/api/agentdeposits/" + id);
        agentDeposit.value = await response.data;
    };

    const getAgentDepositDetails = async (data) => {
        let response = await axios.post("/api/getagentdepositdetails", data);
        agentDeposit.value = await response.data;
        agentDepositUser.value = await response.data.user;
        console.log(agentDeposit)
        console.log(data)
    };

    const confirmAgentDeposit = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/confirmagentdeposit", data);
        agentDeposit.value = await response.data;
        Swal.fire({
            text: 'Deposit confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 3500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'agentsdeposits.index' });
    };

    const getEnvoyDeposit = async (id) => {
        let response = await axios.post("/api/getenvoydeposit", {'id': id});
        envoyDeposit.value = response.data;
    };

    const storeAgentDeposit = async (data) => {
        buttonIsDisabled.value = true;
        await axios.post("/api/agentdeposits", data);
        Swal.fire({
            text: 'Deposit registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 3500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'agentsdeposits.index' });
    };

    const checkPendingDeposit = async (id) => {
        let response = await axios.post("/api/checkpendingdeposit", {'id': id});
        pendingDeposit.value = response.data;
    };

    const cancelAgentDeposit = async (data) => {
        let response = await axios.post("/api/cancelagentdeposit", data);
        agentDeposits.value = response.data;
        Swal.fire({
            text: 'Canceled!',
            icon: 'success'
          })
        router.push({ name: 'agentsdeposits.index' });
    };

    // const updateAgentDeposit = async (id, data) => {
    //     let response = await axios.put("/api/agentdeposits/" + id, data);
    //     agentDeposits.value = response.data;
    //     Swal.fire({
    //         text: 'Updated!',
    //         toast: true,
    //         position: 'top-right',
    //         icon: 'success',
    //         color: '#000',
    //         padding: '0',
    //         showConfirmButton: false,
    //         timer: 5500
    //         });
    // };

    const updateAgentDeposit = async (data) => {
        let response = await axios.post("/api/updateagentdeposit", data);
        agentDeposits.value = response.data;
        Swal.fire({
            text: 'Updated!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 5500
            });
        router.push({ name: 'agentsdeposits.index' });
    };
    
    return {
        getAgentDeposits,
        agentDeposits,
        getAgentDeposit,
        getAgentDepositDetails,
        agentDeposit,
        storeAgentDeposit,
        getEnvoyDeposit,
        envoyDeposit,
        agentDepositUser,
        confirmAgentDeposit,
        checkPendingDeposit,
        pendingDeposit,
        getTotalAgentDepositsCount,
        totalAgentDepositCount,
        buttonIsDisabled,
        cancelAgentDeposit,
        updateAgentDeposit
    };
}