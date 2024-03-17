import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useRoleAndPermission() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const authUserRole = ref('');
    const roles = ref([]);
     const role = ref([]);
    const availablePermissions = ref([]);
    const availablePermissionsOnRoleEdit = ref([]);
    const rolePermissions = ref([]);

    const getRoles = async () => {
        let response = await axios.get("/api/getroles");
        roles.value = response.data;
    };

    const getRole = async (data) => {
        let response = await axios.post("/api/editrole", data);
        role.value = response.data;
    };

    const getPermissions = async () => {
        let response = await axios.get("/api/getpermissions");
        availablePermissions.value = response.data;
    };

    const getAvailablePermissionsOnRoleEdit = async (data) => {
        let response = await axios.post("/api/availablepermissionsonroleedit", data);
        availablePermissionsOnRoleEdit.value = response.data;
    };

    const getAuthUserRole = async () => {
        let response = await axios.get("/api/authuserrole");
        authUserRole.value = response.data;
    };

    const storeRole = async (data) => {
        let response = await axios.post("/api/roleandpermission", data);
        // users.value = response.data;
        Swal.fire({
            text: 'Mew role Added!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

     const addPermissionToRole = async (data) => {
        let response = await axios.post("/api/addpermissiontorole", data);
        Swal.fire({
            text: 'Permission added!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

    const removePermissionFromRole = async (data) => {
        let response = await axios.post("/api/removepermissionfromrole", data);
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

    const addNewPermisssions = async (data) => {
        let response = await axios.post("/api/newpermissions", data);
        Swal.fire({
            text: 'Mew permisssion Added!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

    const addNewSuperAdminPermission = async (data) => {
        let response = await axios.post("/api/newsuperadminpermission", data);
        Swal.fire({
            text: 'New permisssion added for super admin!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
    };

     const getPermissionsForRole = async (role_name) => {
        let response = await axios.post("/api/rolepermissions", {role_name: role_name});
        rolePermissions.value = response.data;
    }

    const storePermission = async (data) => {
        let response = await axios.post("/api/createpermission", data);
        Swal.fire({
            text: 'New permisssion added!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'roleandpermission.index' });
    };

    return {
        getRoles,
        roles,
        getRole,
        role,
        getPermissions,
        availablePermissions,
        getAvailablePermissionsOnRoleEdit,
        availablePermissionsOnRoleEdit,
        getAuthUserRole,
        storeRole,
        getPermissionsForRole,
        rolePermissions,
        addNewPermisssions,
        addNewSuperAdminPermission,
        addPermissionToRole,
        removePermissionFromRole,
        storePermission
    };
}
