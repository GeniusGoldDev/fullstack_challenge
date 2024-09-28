<template>
  <div>
    <h6>Current Temperature: <span>{{ apiResponse?.main?.temp}}</span> &deg;F</h6>
        <p><span>{{ apiResponse?.weather[0]?.description.toUpperCase() }}</span></p>
        <h6>Feels Like: <span>{{ apiResponse?.main?.feels_like}}</span> &deg;F</h6>
        <h6>Humidity: <span>{{ apiResponse?.main?.humidity}}</span> %</h6>
        <h6>Wind Speed: <span>{{ apiResponse?.wind?.speed}}</span>  mph</h6>
  </div>
</template>

<script>
import { watch, ref } from 'vue';

export default {
  name: 'WeatherDetails',
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const apiResponse = ref(null);

    watch(() => props.user, async (newValue, oldValue) => {
      await getWeatherDetails();
    });

    async function getWeatherDetails() {
      const url = 'http://localhost/weather/'+ props.user.id
      console.log(props.user.name)

      apiResponse.value = await (await fetch(url)).json();
    }

    return {
      apiResponse
    };
  }
};
</script>
