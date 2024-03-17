import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useDepartments() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const departments = ref([]);
    const departmentsNoPaginate = ref([]);
    const department = ref([]);
    const departmentforuser = ref([]);

    const getDepartments = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/departments?page=" + page);
        departments.value = await response.data;
        isLoading.value = false;
    };

    const getDepartmentsForUser = async () => {
        isLoading.value = true;
        let response = await axios.get("/api/departmentforuser");
        departmentforuser.value = await response.data;
        isLoading.value = false;

        console.log(departmentforuser.value)
    };

    const getDepartment = async (id) => {
        let response = await axios.get("/api/departments/" + id);
        department.value = await response.data;
    };

    const getDepartmentNoPaginate = async () => {
        let response = await axios.get("/api/departmentsnopaginate");
        departmentsNoPaginate.value = await response.data;
    };

    const storeDepartment = async (data) => {
        await axios.post("/api/departments", data);
        Swal.fire({
            text: 'New department registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'departments.index' });
    };

    const updateDepartment = async (id) => {
        let response = await axios.put("/api/departments/" + id, department.value);
        departments.value = response.data;
        Swal.fire({
            text: 'Department updated!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        router.push({ name: 'departments.index' });
    };

    const destroyDepartment = async (id) => {
        await Swal.fire({
            title: 'Are you sure you want to this department?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.isConfirmed) {
                axios.delete("/api/departments/" + id);
                getDepartments();
                router.push({ name: 'departments.index' });
              Swal.fire({
                toast: true,
                position: 'top-right',
                icon: 'info',
                title: 'Department deleted!',
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
        getDepartments,
        departments,
        getDepartment,
        department,
        storeDepartment,
        updateDepartment,
        getDepartmentsForUser,
        departmentforuser,
        getDepartmentNoPaginate,
        departmentsNoPaginate
    };
}