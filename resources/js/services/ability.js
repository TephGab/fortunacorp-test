import { defineAbility } from '@casl/ability';

export function defineAbilityFor(user) {
    return defineAbility((can) => {
        // console.log(user.roles)
        // Define abilities based on user roles and permissions
        if (user && user.roles && user.permissions) {
            //user.roles.forEach(role => {
                user.permissions.forEach(permission => {
                    // console.log('ok')
                    const [action, subject] = permission.name.split(' '); // Assuming your permission name is in the format 'action_subject'
                    //console.log("Action:", action, "Subject:", subject);
                    can(action, subject); 
                });
            //});
        }
    });
}