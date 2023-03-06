<template>
  <v-table>
    <thead>
      <tr>
        <th class="text-left">Brand</th>
        <th class="text-left">Model</th>
        <th class="text-left">OS</th>
        <th class="text-left">Release Date</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="device in devices">
        <td>{{ device.brand }}</td>
        <td>{{ device.model }}</td>
        <td>{{ device.os }}</td>
        <td>{{ device.release_date }}</td>
      </tr>
    </tbody>
  </v-table>
</template>

<script lang="ts">
type Device = {
  brand: string;
  model: string;
  os: string;
  release_date: string;
};

export default {
  data() {
    return {
      devices: [] as Device[],
    };
  },
  async created() {
    await this.loadDevices();
  },
  methods: {
    async loadDevices() {
      try {
        const response = await fetch("http://localhost:8080/devices");
        const { data } = await response.json();

        this.devices = data;
      } catch (error) {
        alert("Unable to load devices at the moment. Try again later");
      }
    },
  },
};
</script>
