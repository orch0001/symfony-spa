<template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Disponibilité sur les 7 derniers jours</h3>
    </div>

    <div v-if="loading">Chargement...</div>

    <div v-else>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Service</th>
              <th>Rate (%)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="entry in availability7Days" :key="entry.id">
              <td>{{ entry.service }}</td>
              <td>{{ entry.rate.toFixed(2) }}%</td>
              <!-- Format the rate to 2 decimal places -->
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      availability7Days: [],
      loading: true,
      refreshInterval: null,
    };
  },
  methods: {
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("/controller/availability-rate-days");
        this.availability7Days = response.data;
      } catch (error) {
        console.error(
          "Erreur lors de la récupération des availability7Days:",
          error
        );
      } finally {
        this.loading = false;
      }
    },
  },
  mounted() {
    this.fetchData(); // First call

    // update 24 hours
    this.refreshInterval = setInterval(() => {
      this.fetchData();
    }, 24 * 60 * 60 * 1000); // 24h
  },
  beforeDestroy() {
    clearInterval(this.refreshInterval);
  },
};
</script>
