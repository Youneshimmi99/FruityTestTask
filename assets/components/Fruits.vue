<template>
  <div class="container">
    <div class="mb-3">
        <v-alert v-model="alertSuccess" v-if="successMessage" type="success" closable @input="successMessage = ''">
            {{ successMessage }}
        </v-alert>
        <v-alert v-model="alertError" v-if="errorMessage" type="error" closable @input="errorMessage = ''">
            {{ errorMessage }}
        </v-alert>
    </div>

    <form class="mb-4">
      <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Filter by name:</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="name" v-model="filterName">
        </div>

        <label for="family" class="col-sm-2 col-form-label">Filter by family:</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="family" v-model="filterFamily">
        </div>
      </div>
    </form>

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
        <tr v-for="fruit in displayedFruits" :key="fruit.id">
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
            <i v-if="fruit.favorite" class="bi bi-heart-fill text-danger cursor-pointer" @click="toggleFavorite(fruit)"></i>
            <i v-else class="bi bi-heart cursor-pointer" @click="toggleFavorite(fruit)"></i>
          </td>
        </tr>
      </tbody>
    </table>

    <nav v-if="!isLoading">
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: page === 1 }">
                <a class="page-link" href="#" @click.prevent="page--">Previous</a>
            </li>
            <li class="page-item" v-for="p in pageCount" :key="p" :class="{ active: page === p }">
                <a class="page-link" href="#" @click.prevent="page = p">{{ p }}</a>
            </li>
            <li class="page-item" :class="{ disabled: page === pageCount }">
                <a class="page-link" href="#" @click.prevent="page++">Next</a>
            </li>
        </ul>
    </nav>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'FruitList',

  data() {
    return {
      fruits: [],
      page: 1,
      itemsPerPage: 10,
      successMessage: '',
      errorMessage: '',
      isLoading: false,
      filterName: '',
      filterFamily: '',
      alertSuccess: false,
      alertError: false,
    };
  },

  methods: {
    async fetchFruits() {
      this.isLoading = true;
      try {
        const response = await axios.get('/api/fruits');
        this.fruits = response.data;
      } catch (error) {
        console.error(error);
      } finally {
        this.isLoading = false;
      }
    },

    async toggleFavorite(fruit) {
      try {
        await axios
          .post(`/api/fruits/${fruit.id}/toggle-favorite`, { id: fruit.id })
          .then((response) => {
            this.successMessage = response.data.message;
            this.errorMessage = null;
            fruit.favorite = !fruit.favorite; // toggle favorite property
            this.alertSuccess = true;
          })
          .catch((error) => {
            this.successMessage = null;
            this.errorMessage = error.response.data.message;
            this.alertError = true;
          });
      } catch (error) {
        console.error(error);
      }
    },

    filterFruits() {
      let filteredFruits = this.fruits.filter((fruit) => {
        return (
          fruit.name.toLowerCase().includes(this.filterName.toLowerCase()) &&
          fruit.family.toLowerCase().includes(this.filterFamily.toLowerCase())
        );
      });
      return filteredFruits;
    }
  },

  computed: {
    pageCount() {
      return Math.ceil(this.filterFruits().length / this.itemsPerPage);
    },

    displayedFruits() {
      const startIndex = (this.page - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return this.filterFruits().slice(startIndex, endIndex);
    },
  },

  created() {
    this.fetchFruits();
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

.cursor-pointer {
  cursor: pointer;
}
</style>