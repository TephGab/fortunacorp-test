<template>
  <nav class="w-full rounded-md bg-sky-100 px-2 py-2 dark:bg-sky-100 mt-1 mb-2">
    <ol class="list-reset flex">
        <li>
            <router-link :to="{ name: 'dashboard' }" class="text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600">
                <i class="fa fa-house"></i>
            </router-link>
        </li>
        <li>
        <span class="mx-2 text-neutral-500 dark:text-neutral-300">/</span>
        </li>

        <li v-for="(crumb, index) in breadcrumbs" :key="index">
            <!-- Display parent routes if they exist -->
            <template v-for="(parent, index) in crumb.parents" :key="index">
            <router-link v-if="parent.name" :to="{ name: parent.name }" class="text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600">{{ parent.label }}</router-link>
            <!-- Display the slash separator if there is a parent route and it's not the last parent -->
            <span class="mx-2 text-neutral-500 dark:text-neutral-300">/</span>
            <!-- <span v-if="parent.name && parentIndex < crumb.parents.length - 1" class="mx-2 text-neutral-500 dark:text-neutral-300">/</span> -->
            </template>
            
            <!-- Display the current route label -->
            <span class="text-neutral-400 dark:text-neutral-400">{{ crumb.label }}</span>
            <!-- <router-link class="text-neutral-400 dark:text-neutral-400" :to="{ name: crumb.name }">{{ crumb.label }}</router-link> -->
        </li>
    </ol>
    </nav>
</template>
  
  <script>
  import { computed, watch, ref } from 'vue';
  import { useRouter } from 'vue-router';
  
  export default {
    setup() {
      const router = useRouter();
      const breadcrumbs = ref([]);
  
      watch(
        () => router.currentRoute.value.matched,
        (matchedRoutes) => {
          breadcrumbs.value = matchedRoutes.map(route => {
            return {
              label: route.meta.breadcrumb,
              name: route.name,
              parents: route.meta?.parents || [] // Accessing multiple parent routes
            };
          });
        },
        { immediate: true }
      );
  
      return {
        breadcrumbs
      };
    }
  }
  </script>
  
  <style scoped>
  /* .breadcrumb-container {
  } */
  </style>
  