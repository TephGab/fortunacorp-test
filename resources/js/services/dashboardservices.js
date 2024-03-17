import { ref } from "vue";
import axios from "axios";
// import Swal from "sweetalert2";
// import router from '../router';

export default function useDashboard() {
    const totalTransaction = ref([]);
    const totalUserTransaction = ref([]);

    const getTotalTransaction = async (data) => {
        let response = await axios.post("/api/gettotaltransaction", data);
        totalTransaction.value = await response.data;
    };

    const getTotalUserTransaction = async (data) => {
        let response = await axios.post("/api/gettotalusertransaction", data);
        totalUserTransaction.value = await response.data;
    };

    const monthlyTransactionRecapChart = async (d) => {
        let response = await axios.post("/api/getmonthlytransactionchart", d);
        let data = await response.data;
        let ctx = await document.getElementById("monthlyTransactionChart");
        let salesChartData = await {
            datasets: [
                {
                    label: "Local",
                    backgroundColor: "rgba(240, 173, 78, 0.9)",
                    borderColor: "rgba(240, 173, 78, 0.9)",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(240, 173, 78, 1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(240, 173, 78, 1)",
                    fill: true,
                    data: data.allLocalTransaction
                },
                {
                    label: "Moncash",
                    backgroundColor: "rgba(217, 83, 79, 0.9)",
                    borderColor: "rgba(217, 83, 79, 0.9)",
                    pointRadius: true,
                    pointColor: "rgba(217, 83, 79, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(217, 83, 77, 1)",
                    fill: true,
                    data: data.allMoncashTransaction
                },
                {
                    label: "Natcash",
                    backgroundColor: "rgba(2, 117, 216, 0.9)",
                    borderColor: "rgba(2, 117, 216, 0.9)",
                    pointRadius: true,
                    pointColor: "rgba(2, 117, 216, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(2, 117, 216, 1)",
                    fill: true,
                    data: data.allNatcashTransaction
                },
            ],
            labels: [
                "Junuary",
                "Feburary",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
        };
        let transactionsChartOptions = await {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true,
            },
            scales: {
                xAxes: [
                    {
                        gridLines: {
                            display: false,
                        },
                    },
                ],
                yAxes: [
                    {
                        gridLines: {
                            display: true,
                        },
                    },
                ],
            },
        };
        let mchart = await new Chart(ctx, {
            type: d.chart_type,
            data: salesChartData,
            options: transactionsChartOptions,
        });

        mchart.update({
            duration: 0,
            lazy: false,
            animate: false
        });
    };
    
    const monthlyUserTransactionRecapChart = async (da) => {
        let response = await axios.post("/api/getmonthlyusertransactionchart", da);
        let data = await response.data;
        let ctx = await document.getElementById("monthlyUserTransactionChart");
        let salesChartData = await {
            datasets: [
                {
                    label: "Local",
                    backgroundColor: "rgba(240, 173, 78, 0.9)",
                    borderColor: "rgba(240, 173, 78, 0.9)",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(240, 173, 78, 1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(240, 173, 78, 1)",
                    fill: true,
                    data: data.allLocalTransaction
                },
                {
                    label: "Moncash",
                    backgroundColor: "rgba(217, 83, 79, 0.9)",
                    borderColor: "rgba(217, 83, 79, 0.9)",
                    pointRadius: true,
                    pointColor: "rgba(217, 83, 79, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(217, 83, 77, 1)",
                    fill: true,
                    data: data.allMoncashTransaction
                },
                {
                    label: "Natcash",
                    backgroundColor: "rgba(2, 117, 216, 0.9)",
                    borderColor: "rgba(2, 117, 216, 0.9)",
                    pointRadius: true,
                    pointColor: "rgba(2, 117, 216, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(2, 117, 216, 1)",
                    fill: true,
                    data: data.allNatcashTransaction
                },
            ],
            labels: [
                "Junuary",
                "Feburary",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
        };
        let transactionsChartOptions = await {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true,
            },
            scales: {
                xAxes: [
                    {
                        gridLines: {
                            display: false,
                        },
                    },
                ],
                yAxes: [
                    {
                        gridLines: {
                            display: true,
                        },
                    },
                ],
            },
        };
        let mchart = await new Chart(ctx, {
            type: da.chart_type,
            data: salesChartData,
            options: transactionsChartOptions,
        });

        mchart.update({
            duration: 0,
            lazy: false,
            animate: false
        });
    };

    return {
     totalTransaction,
     totalUserTransaction,
     getTotalTransaction,
     getTotalUserTransaction,
     monthlyTransactionRecapChart,
     monthlyUserTransactionRecapChart
    };
}