<script>
import WeatherDetails from '@/components/WeatherDetails.vue';

export default {
  name: "ApiTest",
  components: {
  WeatherDetails
},
  data: () => ({
    apiResponse: null,
    selectedUser: {},
  }),
  
  created() {
    this.fetchData()
  },

  methods: {
    async fetchData() {
      const url = 'http://localhost/'

      this.apiResponse = await (await fetch(url)).json()
    },
    openModal(user) {
      this.selectedUser = user
      this.$refs.myModal.classList.add('show');
      this.$refs.myModal.style.display = 'block';
    },
    closeModal() {
      this.$refs.myModal.classList.remove('show');
      this.$refs.myModal.style.display = 'none';
    }
  }
}
</script>
<style>
.sortable tr {
    cursor: pointer;
}
</style>

<template>
  <div v-if="!apiResponse">
    Pinging the api...
  </div>

  <div v-if="apiResponse">
    <div class="container">
      <div class="row">
        <table class="table table-hover sortable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Longitude</th>
              <th scope="col">Latitude</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in apiResponse.users" :key="index" @click="openModal(user)">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{user.name}}</td>
              <td>{{user.email}}</td>
              <td>{{ user.longitude }}</td>
              <td>{{ user.latitude }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" ref="myModal">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Weather for {{ selectedUser.name }}</h5>
            <button @click="closeModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <WeatherDetails :user="selectedUser" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- The api responded with: <br />
    <code>
    {{ apiResponse }}
    </code> -->
  </div>
</template>