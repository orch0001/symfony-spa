<template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">les 5 dernières Interruptions</h3>
    </div>

    <div v-if="loading">Chargement...</div>

    <div v-else>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Service</th>
              <th>Début</th>
              <th>Fin</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="entry in interruptions" :key="entry.id">
              <td>{{ entry.service }}</td>
              <td>{{ formatDate(entry.start) }}</td>
              <td>{{ formatDate(entry.endDate) }}</td>
              <!-- Format the rate to 2 decimal places -->
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

import axios from "axios";

export default {
  data() {
    return {
      interruptions: [],
      loading: true,
      refreshInterval: null,
    };
  },
  methods: {
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("/controller/last-five-interruptions");
        this.interruptions = response.data;
      } catch (error) {
        console.error("Error while loading interruptions:", error);
      } finally {
        this.loading = false;
      }
    },
    formatDate(datetime) {
      return dayjs(datetime).format("D MMMM YYYY à HH:mm");
    },
  },
  mounted() {
    this.fetchData(); // First call

    // update 60 secondes
    this.refreshInterval = setInterval(() => {
      this.fetchData();
    }, 60000); // 60000ms = 60s
  },
  beforeDestroy() {
    clearInterval(this.refreshInterval);
  },
};
</script>
