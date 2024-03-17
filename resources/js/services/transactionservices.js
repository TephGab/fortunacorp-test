import { ref } from "vue";
import axios from "axios";
import moment from "moment";
import Swal from "sweetalert2";
import router from "../router";
import useUtils from "./utilsservices";
import useTransactionUtils from './transactionutils/transactionutils';
import jsPDF from 'jspdf';
import 'jspdf-autotable';


export default function useTransactions() {
    const { getDailyRate, dailyrateSale, dailyratePurchase, senderAmount, receiverAmount, receiverAmountMinusP2pFee,
         moncashTransfertSiP2PFee, moncashTransfertSiCashOutFee, moncashReceptionSi, calculateMoncashFee,
        natcashTransfertSiP2PFee, natcashTransfertSiCashOutFee, calculateNatcashFee } = useTransactionUtils();
    const { isLoading, isButtonLoading, sendButtonDisabled } = useUtils();
    const transactions = ref([]);
    const completedTransactions = ref([]);
    const transaction = ref([]);
    const transactionsPdf = ref([]);
    const notifications = ref([]);
    const transactioninfo = ref([]);
    const amountHtgNet = ref(0);
    const fortunaFee = ref(0);
    const isClientSenderExist = ref(true);
    const isClientReceiverExist = ref(true);
    const client = ref([]);
    const senderMinusP2pfeeReceptionSi = ref(0);
    const accountstransac = ref([]);

    getDailyRate();
    
    const getTransactions = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/transactions?page=" + page);
        transactions.value = await response.data;
        isLoading.value = false;
    };

    const getCompletedTransactions = async () => {
        isLoading.value = true;
        let response = await axios.get("/api/completedtransactions");
        completedTransactions.value = await response.data;
        isLoading.value = false;
    };

    const setMonthAndYear = async (data) => {
        isLoading.value = true;
        let response = await axios.post("/api/transactionsmonth", data);
        transactions.value = await response.data;
        isLoading.value = false;
    };

    const getTransaction = async (id) => {
        let response = await axios.get("/api/transactions/" + id);
        transaction.value = await response.data;
    };

    const dailyRateUpdate = async () => {
        await axios.post("/api/dailyrateupdate", {dailyrateSale: dailyrateSale.value, dailyratePurchase: dailyratePurchase.value});
    };

    const getTransactionInfo = async (data) => {
        let response = await axios.post("/api/gettransactioninfo", data);
        transactioninfo.value = await response.data;
    };

    const setViewTransaction = async (data) => {
        let response = await axios.post("/api/settransactionview", data);
    };

    const getTransactionDetails = async (data) => {
        let response = await axios.post("/api/transactiondetails", data);
        transaction.value = await response.data;
    };

    const agentCancelTransaction = async (data) => {
        isButtonLoading.value = true;
        sendButtonDisabled.value = true;
        await axios.post("/api/canceltransaction", data);
        Swal.fire({
            text: "Transaction canceled!",
            toast: true,
            position: "top-right",
            icon: "success",
            color: "#000",
            padding: "0",
            showConfirmButton: false,
            timer: 4500,
          });
        isButtonLoading.value = false;
        sendButtonDisabled.value = false;
        router.push({ name: "transaction.index" });
    };

    const storeTransaction = async (data) => {
        isButtonLoading.value = true;
        sendButtonDisabled.value = true;
        await axios.post("/api/transactions", data);
        Swal.fire({
            text: "New transaction done!",
            toast: true,
            position: "top-right",
            icon: "success",
            color: "#000",
            padding: "0",
            showConfirmButton: false,
            timer: 2500,
        });
        router.push({ name: "transaction.index" });
        // isButtonLoading.value = false;
        // sendButtonDisabled.value = false;
    };

    const reviewDisapprovedTransaction = async (data) => {
        isButtonLoading.value = true;
        await axios.post("/api/reviewdisapprovedtransaction", data);
        Swal.fire({
            text: "Transaction updated!",
            toast: true,
            position: "top-right",
            icon: "success",
            color: "#000",
            padding: "0",
            showConfirmButton: false,
            timer: 4500,
        });
        isButtonLoading.value = false;
        router.push({ name: "transaction.index" });
    };

    const updateTransaction = async (id, data) => {
        isButtonLoading.value = true;
        let response = await axios.put("/api/transactions/" + id, data);
        transactions.value = response.data;
        Swal.fire({
            text: "Transactions updated!",
            toast: true,
            position: "top-right",
            icon: "success",
            color: "#000",
            padding: "0",
            showConfirmButton: false,
            timer: 2500,
        });
        isButtonLoading.value = false;
        router.push({ name: "transaction.index" });
    };

    const updateTransactionStatus = async (data) => {
        isButtonLoading.value = true;
        let response = await axios.post("/api/updatetransactionstatus", data);
        transactions.value = response.data;
        isButtonLoading.value = false;
        // router.push({ name: "transaction.index" });
    };

    const updateTransactionStatusCompleted = async (data) => {
        isButtonLoading.value = true;
        let response = await axios.post("/api/updatetransactionstatuscompleted", data);
        transactions.value = response.data;
        Swal.fire({
            text: "Transaction completed!",
            toast: true,
            position: "top-right",
            icon: "success",
            color: "#000",
            padding: "0",
            showConfirmButton: false,
            timer: 2500,
        });
        isButtonLoading.value = false;
    };

    const formatDate = (data) => {
        moment.locale("en");
        return moment(data).format("D MMMM, YYYY HH:mm");
    };

      const getAccountsTransac = async (data) => {
        let response = await axios.post("/api/accountstransaction", data);
        accountstransac.value = await response.data.data;
    };

    const currencyConverterPesosToHTG = async (senderAmount,operation,type) => {
        if (senderAmount >= 100) {
            switch (operation) {
                case "transfert_si":
                    switch (type) {
                        case "moncash":
                            if (senderAmount <= 500) {
                                fortunaFee.value = 50;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 500 && senderAmount <= 1500) {
                                fortunaFee.value = 100;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 1500 && senderAmount <= 2000) {
                                fortunaFee.value = 150;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 2000 && senderAmount <= 3000) {
                                fortunaFee.value = 190;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 3000) {
                                fortunaFee.value = (6.5 * senderAmount) / 100;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            break;
                        case "natcash":
                            if (senderAmount <= 500) {
                                fortunaFee.value = 50;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 500 && senderAmount <= 1500) {
                                fortunaFee.value = 100;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 1500 && senderAmount <= 2000) {
                                fortunaFee.value = 150;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 2000 && senderAmount <= 3000) {
                                fortunaFee.value = 190;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 3000) {
                                fortunaFee.value = (6.5 * senderAmount) / 100;
                                let receiverAmountInPesos = senderAmount - fortunaFee.value;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            break;
                        default:
                        // code block
                    }
                    break;
                case "transfert":
                    switch (type) {
                        case "moncash":
                            if (senderAmount <= 500) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG, operation);
                            }
                            if (senderAmount > 500 && senderAmount <= 1500) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 1500 && senderAmount <= 2000) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 2000 && senderAmount <= 3000) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 3000) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateMoncashFee(receiverAmountConvertedToHTG,operation);
                            }
                            break;
                        case "natcash":
                            if (senderAmount <= 500) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG = receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG, operation);
                            }
                            if (senderAmount > 500 && senderAmount <= 1500) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 1500 && senderAmount <= 2000) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 2000 && senderAmount <= 3000) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            if (senderAmount > 3000) {
                                fortunaFee.value = 0;
                                let receiverAmountInPesos = senderAmount;
                                let receiverAmountConvertedToHTG =
                                receiverAmountInPesos * dailyrateSale.value;
                                calculateNatcashFee(receiverAmountConvertedToHTG,operation);
                            }
                            break;
                        default:
                        // code block
                    }
                    break;
                case "reception_si":
                    switch (type) {
                        case "moncash":
                             // Check moncash fee for reception si
                             if (senderAmount >= 20 && senderAmount <= 99) {
                                moncashTransfertSiP2PFee.value = 2
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 100 && senderAmount <= 249) {
                                moncashTransfertSiP2PFee.value = 4;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 250 && senderAmount <= 499) {
                                moncashTransfertSiP2PFee.value = 7;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 500 && senderAmount <= 999) {
                                moncashTransfertSiP2PFee.value = 10;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 1000 && senderAmount <= 1999) {
                                moncashTransfertSiP2PFee.value = 30;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 2000 && senderAmount <= 3999) {
                                moncashTransfertSiP2PFee.value = 40;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 4000 && senderAmount <= 7999) {
                                moncashTransfertSiP2PFee.value = 55;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 8000 && senderAmount <= 11999) {
                               moncashTransfertSiP2PFee.value = 70;
                               senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 12000 && senderAmount <= 19999) {
                                moncashTransfertSiP2PFee.value = 80;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 20000 && senderAmount <= 39999) {
                                moncashTransfertSiP2PFee.value = 90;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 40000 && senderAmount <= 59999) {
                                moncashTransfertSiP2PFee.value = 110;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 60000 && senderAmount <= 75125) {
                                moncashTransfertSiP2PFee.value = 125;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - moncashTransfertSiP2PFee.value);
                            }
                            // End check moncash fee for reception si
                            let receiverAmountConvertedToPesos = senderMinusP2pfeeReceptionSi.value * dailyratePurchase.value;
                            if (receiverAmountConvertedToPesos <= 500) {
                                fortunaFee.value = 50;
                                calculateMoncashFee(senderMinusP2pfeeReceptionSi.value,operation, fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 500 && receiverAmountConvertedToPesos <= 1500) {
                                fortunaFee.value = 100;
                                calculateMoncashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 1500 && receiverAmountConvertedToPesos <= 2000) {
                                fortunaFee.value = 150;
                                calculateMoncashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 2000 && receiverAmountConvertedToPesos <= 3000) {
                                fortunaFee.value = 190;
                                calculateMoncashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 3000) {
                                fortunaFee.value = (6.5 * receiverAmountConvertedToPesos) / 100;
                                calculateMoncashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            break;
                        case "natcash":
                            // Check natcash fee for reception si
                             if (senderAmount >= 20 && senderAmount <= 99) {
                                natcashTransfertSiP2PFee.value = 5.50
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 100 && senderAmount <= 249) {
                                natcashTransfertSiP2PFee.value = 11.50;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 250 && senderAmount <= 499) {
                                natcashTransfertSiP2PFee.value = 13.50;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 500 && senderAmount <= 999) {
                                natcashTransfertSiP2PFee.value = 21;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 1000 && senderAmount <= 1999) {
                                natcashTransfertSiP2PFee.value = 41;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 2000 && senderAmount <= 3999) {
                                natcashTransfertSiP2PFee.value = 68;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 4000 && senderAmount <= 7999) {
                                natcashTransfertSiP2PFee.value = 97;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 8000 && senderAmount <= 11999) {
                               natcashTransfertSiP2PFee.value = 125;
                               senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 12000 && senderAmount <= 19999) {
                                natcashTransfertSiP2PFee.value = 165;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            if (senderAmount >= 20000 && senderAmount <= 40000) {
                                natcashTransfertSiP2PFee.value = 274;
                                senderMinusP2pfeeReceptionSi.value = (senderAmount - natcashTransfertSiP2PFee.value);
                            }
                            // End check natcash fee for reception si
                            receiverAmountConvertedToPesos = senderMinusP2pfeeReceptionSi.value * dailyratePurchase.value;
                            if (receiverAmountConvertedToPesos <= 500) {
                                fortunaFee.value = 50;
                                calculateNatcashFee(senderMinusP2pfeeReceptionSi.value,operation, fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 500 && receiverAmountConvertedToPesos <= 1500) {
                                fortunaFee.value = 100;
                                calculateNatcashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 1500 && receiverAmountConvertedToPesos <= 2000) {
                                fortunaFee.value = 150;
                                calculateNatcashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 2000 && receiverAmountConvertedToPesos <= 3000) {
                                fortunaFee.value = 190;
                                calculateNatcashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 3000) {
                                fortunaFee.value = (6.5 * receiverAmountConvertedToPesos) / 100;
                                calculateNatcashFee(senderMinusP2pfeeReceptionSi.value,operation,fortunaFee.value);
                            }
                            break;
                        default:
                        // code block
                    }
                    break;
                case "reception":
                    switch (type) {
                        case "moncash":
                            let receiverAmountConvertedToPesos = senderAmount * dailyratePurchase.value;
                            if (receiverAmountConvertedToPesos <= 500) {
                                fortunaFee.value = 50;
                                calculateMoncashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 500 && receiverAmountConvertedToPesos <= 1500) {
                                fortunaFee.value = 100;
                                calculateMoncashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 1500 && receiverAmountConvertedToPesos <= 2000) {
                                fortunaFee.value = 150;
                                calculateMoncashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 2000 && receiverAmountConvertedToPesos <= 3000) {
                                fortunaFee.value = 190;
                                calculateMoncashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 3000) {
                                fortunaFee.value = (6.5 * receiverAmountConvertedToPesos) / 100;
                                calculateMoncashFee(senderAmount,operation,fortunaFee.value);
                            }
                            break;
                        case "natcash":
                            receiverAmountConvertedToPesos = senderAmount * dailyratePurchase.value;
                            if (receiverAmountConvertedToPesos <= 500) {
                                fortunaFee.value = 50;
                                calculateNatcashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 500 && receiverAmountConvertedToPesos <= 1500) {
                                fortunaFee.value = 100;
                                calculateNatcashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 1500 && receiverAmountConvertedToPesos <= 2000) {
                                fortunaFee.value = 150;
                                calculateNatcashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 2000 && receiverAmountConvertedToPesos <= 3000) {
                                fortunaFee.value = 190;
                                calculateNatcashFee(senderAmount,operation,fortunaFee.value);
                            }
                            if (receiverAmountConvertedToPesos > 3000) {
                                fortunaFee.value = (6.5 * receiverAmountConvertedToPesos) / 100;
                                calculateNatcashFee(senderAmount,operation,fortunaFee.value);
                            }
                            break;
                        default:
                        // code block
                    }
                    break;
                default:
                // code block
            }
        }
    };

    const getNotifications = async () => {
        let response = await axios.get("/api/notifications");
        notifications.value = await response.data;
    };

    const checkIfClientSenderExist = async (phone) => {
        let response = await axios.post("/api/checkclient", { phone: phone });
        if (response.data.length != 0) {
            client.value = response.data;
            isClientSenderExist.value = true;
        } else {
            client.value = response.data;
            isClientSenderExist.value = false;
        }
    };

    const checkIfClientReceiverExist = async (phone) => {
        let response = await axios.post("/api/checkclient", { phone: phone });
        if (response.data.length != 0) {
            client.value = response.data;
            isClientReceiverExist.value = true;
        } else {
            client.value = response.data;
            isClientReceiverExist.value = false;
        }
    };

    const transactionSort = async (type) => {
        let response = await axios.post("/api/transactionsort", type);
        transactions.value = await response.data;
    };

      const cancelTransaction = async () => {
      Swal.fire({
          title: 'Cancel!',
          text: "Go back to transactions list",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "transaction.index" });
          }
        })
    }

    const generatePdf = async (data) => {
        isButtonLoading.value = true;
        sendButtonDisabled.value = true;

        axios.post("/api/generatepdf", data)
        .then((response) => {
            transactionsPdf.value = response.data;

            if(transactionsPdf.value.length != 0){
                            
                const customWidth = 8.5; // inches
                const customHeight = 14; // inches (note the swap in dimensions for landscape)

                const doc = new jsPDF({
                    orientation: 'landscape', // or 'landscape' depending on your content
                    unit: 'in', // or 'pt', 'mm', 'cm', 'in'
                    format: [customWidth, customHeight],
                });

                doc.setFontSize(13);
                //doc.text('Last transactions', 10, 10);
                switch (data.pdf_export_option) {
                    case 'today':
                        doc.text('Today transactions', 10, 10);
                        break;
                    case 'seven_days':
                        doc.text('Last 7 days transactions', 10, 10);
                        break;
                    case 'this_month':
                        doc.text('This month transactions', 10, 10);
                        break;
                    case 'last_month':
                        doc.text('Last transactions', 10, 10);
                        break;
                    case 'all':
                            doc.text('Transactions', 20, 10);
                            break;
                    default:
                        break;
                }
        
                const itemsPerPage = 40; // Number of items per page
                const totalPages = Math.ceil(transactionsPdf.value.length / itemsPerPage);
                const pageHeight = doc.internal.pageSize.getHeight(); // Get page height
                let currentPage = 0; // Current page number

                for (let page = 1; page <= totalPages; page++) {
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                const columns = ['ID', 'Client name', 'Client phone', 'Type', 'Sender amount', 'Receiver amount', 'Status', 'Date'];
                const pageData = transactionsPdf.value.slice(startIndex, endIndex);

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
                    body: pageData.map((transaction) => [
                        transaction.id, transaction.client.name, transaction.client.phone, transaction.type,
                        transaction.operation == 'transfert' || transaction.operation == 'transfert_si' ? `${transaction.sender_amount} PESOS (Rate ${transaction.rate})` : `${transaction.sender_amount} HTG (Rate ${transaction.rate})`, 
                        transaction.operation == 'reception' || transaction.operation == 'reception_si' ? `${transaction.receiver_amount} PESOS` : `${transaction.receiver_amount} HTG`,
                        transaction.status, formatDate(transaction.created_at)
                    ]),
                    startY: 1, // Adjust the starting Y position for the table
                    margin: { top: 1 }, // Adjust top margin
                });
                }

                // Save the PDF
                doc.save('transactions.pdf');

                Swal.fire({
                text: "Pdf file generated!",
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
                    text: "No transaction found!",
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
          console.log(error.response.data.errors);
        })
    };

    return {
        getDailyRate,
        dailyrateSale,
        dailyratePurchase,
        transactions,
        getTransactions,
        transaction,
        getTransaction,
        storeTransaction,
        getNotifications,
        notifications,
        updateTransaction,
        updateTransactionStatus,
        updateTransactionStatusCompleted,
        formatDate,
        transactioninfo,
        getTransactionInfo,
        currencyConverterPesosToHTG,
        receiverAmount,
        receiverAmountMinusP2pFee,
        senderAmount,
        amountHtgNet,
        fortunaFee,
        isLoading,
        isButtonLoading,
        isClientSenderExist,
        isClientReceiverExist,
        client,
        checkIfClientSenderExist,
        checkIfClientReceiverExist,
        dailyRateUpdate,
        moncashTransfertSiP2PFee,
        moncashTransfertSiCashOutFee,
        moncashReceptionSi,
        natcashTransfertSiP2PFee,
        natcashTransfertSiCashOutFee,
        getAccountsTransac,
        accountstransac,
        transactionSort,
        sendButtonDisabled,
        reviewDisapprovedTransaction,
        cancelTransaction,
        generatePdf,
        transactionsPdf,
        setMonthAndYear,
        agentCancelTransaction,
        setViewTransaction,
        getTransactionDetails,
        getCompletedTransactions,
        completedTransactions
    };
}