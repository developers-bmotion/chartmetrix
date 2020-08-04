<template>
  <div class="container">
    <h1>Char Metrics</h1>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: []
    };
  },
  mounted() {
    //this.getAlbums();
    console.log("Component mounted.");
    this.getToken();
  },
  methods: {
    getToken() {
      axios
        .get(`http://localhost:8000/api/artist_neighboring?id=3883`)
        .then(res => {
          console.log(res);
          this.data = res.data.token;
          // this.getAlbums();
        })
        .catch(err => {
          console.log(err);
        });
    },
    getAlbums() {
      console.log(`Bearer ${this.token}`);
      let Token = `Bearer ${this.token}`;
      var miInit = {
        method: "GET", // or 'PUT'
        headers: {
          Authorization: Token,
          "Access-Control-Allow-Origin": "anonymous"
        }
      };
      fetch(
        "https://api.chartmetric.com/api/artist/anr/by/social-index?sortBy=spotify_followers",
        miInit
      )
        .then(function(response) {
          return response.json();
        })
        .then(function(myJson) {
          console.log(myJson);
        });
      /*
      axios
        .get(
          "https://api.chartmetric.com/api/artist/anr/by/social-index?sortBy=spotify_followers",
          {
            headers: {
              Authorization: `Bearer ${this.token}`,
              "Access-Control-Allow-Origin": "*"
            }
          }
        )
        .then(resp => {
          console.log(resp);
          this.albums = resp.data;
        })
        .catch(err => {
          console.log(err);
        });
  */
    }
  }
};
</script>
