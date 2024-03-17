import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useProviders() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const providers = ref([]);
    const provider = ref([]);

    const getProviders = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/providers?page=" + page);
        providers.value = await response.data;
        isLoading.value = false;
    };

    const getProvider = async (id) => {
        let response = await axios.get("/api/providers/" + id);
        provider.value = await response.data;
    };

    const storeProvider = async (data) => {
        await axios.post("/api/providers", data);
        Swal.fire({
            text: 'New provider registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        router.push({ name: 'providers.index' });
    };

    const updateProvider = async (id) => {
        let response = await axios.put("/api/providers/" + id, provider.value);
        providers.value = response.data;
        Swal.fire({
            text: 'Provider infos updated!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        router.push({ name: 'providers.index' });
    };

    const destroyProvider = async (id) => {
        await Swal.fire({
            title: 'Are you sure you want to this provider?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.isConfirmed) {
                axios.delete("/api/providers/" + id);
                getProviders();
                router.push({ name: 'providers.index' });
              Swal.fire({
                toast: true,
                position: 'top-right',
                icon: 'info',
                title: 'Provider deleted!',
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

    
    return { 
        getProviders,
        providers,
        getProvider,
        provider,
        storeProvider,
        updateProvider,
        destroyProvider,
        isLoading,
        buttonIsLoading
    };
}