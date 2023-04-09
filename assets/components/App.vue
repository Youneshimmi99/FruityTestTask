<template>
  <v-app>
    <v-app-bar app color="primary" class="mb-5">
      <v-tabs v-model="activeTab" align-with-title show-arrows grow>
        <v-tab to="/" exact>All Fruits</v-tab>
        <v-tab to="/favorites">Favorite Fruits</v-tab>
      </v-tabs>
    </v-app-bar>
    <v-content id="content">
      <router-view></router-view>
    </v-content>
  </v-app>
</template>
<script>
export default {
  data() {
    return {
      activeTab: '',
    }
  },
  watch: {
    $route(to) {
      this.activeTab = to.path
    },
  },
}
</script>
<style>
.v-tabs__item--active {
  color: white !important;
}

#content {
  padding-top: 80px;
}
</style>