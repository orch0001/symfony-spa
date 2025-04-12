<template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">État des services</h3>
    </div>

    <div v-if="loading">Chargement...</div>

    <div v-else>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(entry, index) in lastSixServiceLogs" :key="index">
              <td>{{ entry.service.name }}</td>
              <td>
                <button :class="['btn', statusClass(entry.status)]">
                  <p v-if="entry.status === 'OK'">Fonctionnel</p>
                  <p v-else-if="entry.status === 'KO'">Non fonctionnel</p>
                  <p v-else-if="entry.status === 'problem'">
                    Problème existant
                  </p>
                  <p v-else class="text-muted">Statut inconnu</p>
                </button>
              </td>
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
      logs: [],
      loading: true,
      refreshInterval: null,
    };
  },
  computed: {
    lastSixServiceLogs() {
      // Limit the service logs to the last 6
      return this.logs.slice(-6);
    },
  },
  methods: {
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("/controller/service-status");
        this.logs = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des logs:", error);
      } finally {
        this.loading = false;
      }
    },
    statusClass(status) {
      return (
        {
          OK: "btn-success",
          KO: "btn-danger",
          problem: "btn-warning",
        }[status] || ""
      );
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
