import { ref } from "vue";
import axios from "axios";
import moment from "moment";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';
import jsPDF from 'jspdf';
import 'jspdf-autotable';


export default function useUsers() {
    const { isLoading, buttonIsLoading, buttonIsDisabled, isButtonLoading, sendButtonDisabled, formatDate } = useUtils();
    const users = ref([]);
    const user = ref([]);
    const authUserRole = ref('');
    const userAccount = ref([]);
    const roles = ref([]);
    const references = ref([]);
    const reference = ref([]);
    const userBankAccountDeposits = ref([]);
    const systemBankAccountDeposits = ref([]);
    const envoys = ref([]);
    const listedEnvoys = ref([]);
    const agents = ref([]);
    const operators = ref([]);
    const agent = ref([]);
    const availablePermissionsOnUserEdit = ref([]);
    const currentPermissionsOnUserEdit = ref([]);
    const usersPdf = ref([]);
    const profitToRecharges = ref([]);
    const userActivities = ref([]);
    const userSort = ref();
    const agentInfos = ref([]);
    const agentInfo = ref([]);
    const agentLists = ref([]);


    const getUsers = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/users?page=" + page);
        users.value = await response.data;
        isLoading.value = false;
    };

    const getEnvoys = async () => {
        let response = await axios.get("/api/envoys");
        envoys.value = await response.data.data;
    };

    const getAgents = async () => {
        let response = await axios.get("/api/agents");
        agents.value = await response.data.data;
    };

    const getAgentList = async (data) => {
        let response = await axios.post("/api/getagentlist", data);
        agentLists.value = await response.data;
    };

    const getSelectedAgentList = async (data) => {
        let response = await axios.post("/api/getselectedagentlist", data);
        agentLists.value = await response.data;
    };

    const getOperators = async () => {
        let response = await axios.get("/api/operators");
        operators.value = await response.data.data;
    };

    const getAgent = async (data) => {
        let response = await axios.post("/api/agent", data);
        agent.value = await response.data;
    };

    const getUserBankAccountDeposit = async () => {
        let response = await axios.get("/api/userbankaccounts");
        userBankAccountDeposits.value = await response.data.data;
    };

    const getSystemBankAccountDeposit = async () => {
        let response = await axios.get("/api/systembankaccounts");
        systemBankAccountDeposits.value = await response.data.data;
    };

    const getUser = async (id) => {
        let response = await axios.get("/api/users/" + id);
        user.value = response.data;
    };

    // const getUserAccount = async (id) => {
    //     let response = await axios.get("/api/useraccount/" + id);
    //     userAccount.value = response.data;
    // };

    const getUserAccount = async (id) => {
        let response = await axios.post("/api/useraccount", {'id': id});
        userAccount.value = response.data;
    };

    const storeUser = async (data, d) => {
        let response = await axios.post("/api/users", data);
        user.value = response.data;
        Swal.fire({
            text: 'New user created!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        router.push({ name: 'users.index' });
    };

    const storeUserBankAccount = async (data) => {
        await axios.post("/api/userbankaccount", data);
        Swal.fire({
            text: 'New bank account registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 3500
            });
        // router.push({ name: 'users.index' });
    };

    const updateUser = async (id, data) => {
        let response = await axios.put("/api/users/" + id, data);
        users.value = response.data;
        Swal.fire({
            text: 'User updated!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        router.push({ name: 'users.index' });
    };

    const updatePassword = async (id, data) => {
        let response = await axios.put("/api/updatepassword/" + id, data);
        // users.value = response.data;
        Swal.fire({
            text: 'Password updated!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

    const resetPassword = async (data) => {
        let response = await axios.post("/api/resetpassword", data);
        // users.value = response.data;
        Swal.fire({
            text: 'User password has been reset successfuly!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

    const destroyUser = async (id) => {
        await Swal.fire({
            text: 'Are you sure you want to delete this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.isConfirmed) {
                axios.delete("/api/users/" + id);
                getUsers();
                router.push({ name: 'users.index' });
              Swal.fire({
                toast: true,
                position: 'top-right',
                icon: 'info',
                title: 'User deleted!',
                showConfirmButton: false,
                color: '#fff',
                background: '#87adbd',
                timer: 1500,
                timerProgressBar: true
              }
              )
            }
          })
    };

    const getRoles = async () => {
        let response = await axios.get("/api/getroles");
        roles.value = response.data;
    };

    const idFormatter = (location, first_name, id) => {
       return location.charAt(0).toUpperCase() + first_name.charAt(0).toUpperCase() + '-' + id
    };

    const searchReference = async () => {
        let response = await axios.get("/api/searchref");
        references.value = response.data.data;
    }

    // Get reference info
    const getReference = async (id) => {
        if(id){
            let response = await axios.post("/api/getref", {'id':id});
            reference.value = response.data[0];
        }
    }

    const sortByRole = async (data) => {
        let response = await axios.post("/api/sortuserbyrole", data);
        users.value = response.data;
    }

    const transfertProfitsToRecharge = async (data) => {
        isLoading.value = true;
        let response = await axios.post("/api/transfertprofitstorecharge", data);
        Swal.fire({
            text: 'Transfert completed click anywhere to close modal!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 15500
            });
        isLoading.value = false;
        router.push({ name: 'profittorecharge.index' });
    }

    const sortByBRDR = async (data) => {
        let response = await axios.post("/api/sortbybrdr", data);
        users.value = response.data;
    }

    const getAuthUserRole = async () => {
        let response = await axios.get("/api/authuserrole");
        authUserRole.value = response.data;
    };

     const logoutOtherSessions = async (data) => {
            await axios.post("/api/logout-other-sessions", data);
            // await getUser(data.user_id);
    }

    const geCurrentPermissionsOnUserEdit = async (data) => {
        let response = await axios.post("/api/currentpermissionsonuseredit", data);
        currentPermissionsOnUserEdit.value = response.data;
    };

    const getAvailablePermissionsOnUserEdit = async (data) => {
        let response = await axios.post("/api/availablepermissionsonuseredit", data);
        availablePermissionsOnUserEdit.value = response.data;
    };
    
    const removePermissionFromUser = async (data) => {
        let response = await axios.post("/api/removepermissionfromuser", data);
        Swal.fire({
            text: 'Permission removed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

    const allowAndDisallowAgentDebt = async (data) => {
        let response= await axios.post("/api/allowanddisallowagentdebt", data);
        users.value = await response.data;
        Swal.fire({
            text: 'Success!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

    const getProfitToRecharges = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/profittorecharge?page=" + page);
        profitToRecharges.value = await response.data;
        isLoading.value = false;
    };

    
    const generatePdf = async (data) => {
        isButtonLoading.value = true;
        sendButtonDisabled.value = true;

        axios.post("/api/generateuserpdf", data)
        .then((response) => {
            usersPdf.value = response.data;
            if(usersPdf.value.length != 0){
                            
                const customWidth = 8.5; // inches
                const customHeight = 14; // inches (note the swap in dimensions for landscape)

                const doc = new jsPDF({
                    orientation: 'landscape', // or 'landscape' depending on your content
                    unit: 'in', // or 'pt', 'mm', 'cm', 'in'
                    format: [customWidth, customHeight],
                });

                doc.setFontSize(13);
                let title = 'Users'; // Default title

                switch (true) {
                    case data.pdf_export_option == 'admin':
                        title = 'Admins';
                        console.log('Hello admin');
                        break;
                    case data.pdf_export_option == 'agent':
                        title = 'Agents';
                        break;
                    case data.pdf_export_option == 'operator':
                        title = 'Operators';
                        break;
                    case data.pdf_export_option == 'system_engineer':
                        title = 'System Engineer';
                        break;
                    case data.pdf_export_option == 'all':
                        title = 'Users';
                        break;
                    default:
                        break;
                }

                doc.text(title, 10, 10);
        
                const itemsPerPage = 40; // Number of items per page
                const totalPages = Math.ceil(usersPdf.value.length / itemsPerPage);
                const pageHeight = doc.internal.pageSize.getHeight(); // Get page height
                let currentPage = 0; // Current page number 

                for (let page = 1; page <= totalPages; page++) {
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                const columns = ['Code', 'Role', 'Name', 'Email', 'Phone', 'Balance', 'Recharge', 'Debt', 'Registration date'];
                const pageData = usersPdf.value.slice(startIndex, endIndex);

                // Generate a new page for each chunk of data
                if (page > 1) {
                    doc.addPage(); // Add a new page
                    currentPage++; // Increment the current page number
                }

                // Add page number to the footer
                // const pageNumberString = `Page ${currentPage} of ${totalPages}`;
                // doc.text(pageNumberString, 10, pageHeight - 10); // Adjust the position as needed
                doc.setFontSize(11);
                // Create the table for the current page
                doc.autoTable({
                    head: [columns],
                    body: pageData.map((user) => [
                        user.code, user.roles[0].name, user.first_name +' '+ user.last_name, user.email, user.phone, user.user_account.profits, user.user_account.investments, user.user_account.depts, formatDate(user.created_at)
                    ]),
                    startY: 1, // Adjust the starting Y position for the table
                    margin: { top: 1 }, // Adjust top margin
                    didDrawPage: function (data) {
                        doc.text(title, 10, 10);
                    }
                });
                }

                // Save the PDF
                doc.save('users.pdf');

                Swal.fire({
                text: "Users file generated!",
                toast: true,
                position: "top-right",
                icon: "success",
                color: "#000",
                padding: "0",
                showConfirmButton: false,
                timer: 2500,
                });
            }else{
                Swal.fire({
                    text: "No user found!",
                    icon: "error",
                    color: "#000",
                    padding: "0",
                    showConfirmButton: true,
                    timer: 4500,
                    });
            }
            isButtonLoading.value = false;
            sendButtonDisabled.value = false;
        })
        .catch((error) => {
          console.log(error);
        })
    };

    const getUserSorts = async (data) => {
        let response = await axios.post("/api/usersorts", data);
        userSort.value = await response.data;
    };

    const getEnvoyAgentInfos = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/agentinfos?page=" + page);
        agentInfos.value = await response.data;
        isLoading.value = false;
    };

    const getSelectedEnvoyAgentInfos  = async (data) => {
        let response = await axios.post("/api/selectedagentinfos", data);
        agentInfos.value = await response.data;
    };

    const getEnvoyList = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/envoylist?page=" + page);
        listedEnvoys.value = await response.data;
        isLoading.value = false;
    };

    const updateEnvoyAgent = async (data) => {
        let response = await axios.post("/api/updateenvoyagent", data);
        // agentInfos.value = await response.data;
    };

    return {
       users,
       getUsers,
       user,
       getUser,
       storeUser,
       updateUser,
       destroyUser,
       getRoles,
       roles,
       searchReference,
       references,
       idFormatter,
       updatePassword,
       resetPassword,
       getReference,
       reference,
       getUserAccount,
       userAccount,
       getUserBankAccountDeposit,
       userBankAccountDeposits,
       getSystemBankAccountDeposit,
       systemBankAccountDeposits,
       storeUserBankAccount,
       getEnvoys,
       envoys,
       getAgents,
       agents,
       getOperators,
       operators,
       getAgent,
       agent,
       getAuthUserRole,
       authUserRole,
       logoutOtherSessions,
       getAvailablePermissionsOnUserEdit,
       availablePermissionsOnUserEdit,
       geCurrentPermissionsOnUserEdit,
       currentPermissionsOnUserEdit,
       removePermissionFromUser,
       allowAndDisallowAgentDebt,
       sortByRole,
       sortByBRDR,
       generatePdf,
       isButtonLoading,
       transfertProfitsToRecharge,
       isLoading,
       getProfitToRecharges,
       profitToRecharges,
       getUserSorts,
       userSort,
       agentInfos,
       agentInfo,
       getEnvoyAgentInfos,
       getEnvoyList,
       listedEnvoys,
       getSelectedEnvoyAgentInfos,
       getAgentList,
       agentLists,
       updateEnvoyAgent,
       getSelectedAgentList,
    };
}
