<template>
    <div>
      <input v-model="searchQuery.id" @input="search" placeholder="Search by ID..." />
      <input v-model="searchQuery.name" @input="search" placeholder="Search by Name..." />
      <input v-model="searchQuery.number" @input="search" placeholder="Search by Number..." />
      <ul>
        <li v-for="result in searchResults" :key="result.id">{{ result.name }}</li>
      </ul>
      <button @click="loadMore" v-if="hasMoreResults">Load More</button>
    </div>
  </template>
  
  <script>
  import { ref, reactive } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const searchQuery = reactive({
        id: '',
        name: '',
        number: ''
      });
  
      const searchResults = ref([]);
      const currentPage = ref(1);
      const hasMoreResults = ref(true);
  
      const search = async () => {
        try {
          currentPage.value = 1;
          const response = await searchTransactions(currentPage.value, searchQuery);
          searchResults.value = response.data.data;
          hasMoreResults.value = response.data.next_page_url !== null;
        } catch (error) {
          console.error(error);
        }
      };
  
      const loadMore = async () => {
        try {
          currentPage.value++;
          const response = await searchTransactions(currentPage.value, searchQuery);
          searchResults.value.push(...response.data.data);
          hasMoreResults.value = response.data.next_page_url !== null;
        } catch (error) {
          console.error(error);
        }
      };
  
      const searchTransactions = async (page, searchQuery) => {
        try {
          const response = await axios.get('/api/search_transactions', {
            params: { page, ...searchQuery }
          });
          return response;
        } catch (error) {
          console.error(error);
        }
      };
  
      return {
        searchQuery,
        searchResults,
        hasMoreResults,
        search,
        loadMore
      };
    }
  };
  </script>
  