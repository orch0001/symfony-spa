<template>
  <div>
    <div class="row row-cols-2">
      <ServiceStatus />
      <LastInterruptions />
      <Availability24Hours />
      <Availability7Days />
    </div>
  </div>
</template>

<script>
import LastInterruptions from "./LastInterruptions.vue";
import ServiceStatus from "./ServiceStatus.vue";
import Availability24Hours from "./Availability24Hours.vue";
import Availability7Days from "./Availability7Days.vue";

export default {
  components: {
    ServiceStatus,
    LastInterruptions,
    Availability24Hours,
    Availability7Days,
  },
};
</script>
