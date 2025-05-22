<template>
  <div>
    <h1>Video List</h1>
    <ul>
      <li v-for="video in videos" :key="video.id">
        {{ video.title }} 
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      videos: []
    }
  },
  mounted() {
    this.fetchVideos();
  },
  methods: {
    fetchVideos() {
      axios.get('/videos')
        .then(response => {
          this.videos = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
}
</script>