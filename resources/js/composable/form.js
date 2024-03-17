import { reactive } from "vue";

export default function useForm() {
    const approvalData = reactive({
        'step': 0,
        'pending': true,
        'approved': false,
        'disapproved': false,
        'completed': false,
        'partially_completed': false,
    });

    return {
        approvalData
    }
}