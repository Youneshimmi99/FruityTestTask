<template>
  <div class="container">

    <div class="alert alert-info mb-3">
      Total nutritional facts of favorite fruits: 
      <ul >
        <li>Carbohydrates: {{totalNutritionalFacts.carbohydrates}}</li>
        <li>Protein: {{totalNutritionalFacts.protein}}</li>
        <li>Fat: {{totalNutritionalFacts.fat}}</li>
        <li>Calories: {{totalNutritionalFacts.calories}}</li>
        <li>Sugar: {{totalNutritionalFacts.sugar}}</li>
      </ul>
    </div>

    <div v-if="isLoading" class="text-center">
      <i class="mdi mdi-loading mdi-spin mdi-48px"></i>
    </div>

    <table v-else class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Genus</th>
          <th>Family</th>
          <th>Order</th>
          <th>Carbohydrates</th>
          <th>Protein</th>
          <th>Fat</th>
          <th>Calories</th>
          <th>Sugar</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="fruit in fruits" :key="fruit.id">
          <td>{{ fruit.name }}</td>
          <td>{{ fruit.genus }}</td>
          <td>{{ fruit.family }}</td>
          <td>{{ fruit.order }}</td>
          <td>{{ fruit.carbohydrates }}</td>
          <td>{{ fruit.protein }}</td>
          <td>{{ fruit.fat }}</td>
          <td>{{ fruit.calories }}</td>
          <td>{{ fruit.sugar }}</td>
          <td>
            <i v-if="fruit.favorite" class="bi bi-heart-fill text-danger"></i>
            <i v-else class="bi bi-heart"></i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'FruitList',

  data() {
    return {
      fruits: [],
      totalNutritionalFacts: [],
      isLoading: false,
    };
  },

  methods: {
    async fetchFavoriteFruits() {
      this.isLoading = true;
      try {
        const response = await axios.get('/api/fruits/favorite');

        this.fruits = response.data.fruits;
        this.totalNutritionalFacts = response.data.sumNutrition;
      } catch (error) {
        console.error(error);
      } finally {
        this.isLoading = false;
      }
    },
  },
  created() {
    this.fetchFavoriteFruits();
  },
};
</script>
<style scoped>
.bi-heart {
  font-size: 1.5rem;
}

.bi-heart-fill {
  font-size: 1.5rem;
}
</style>