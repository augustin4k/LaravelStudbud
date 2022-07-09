<template>
  <div>
    <div class="row justify-content-center">
      <div
        v-for="card in cardInfo"
        :key="card.text"
        class="col-6 col-md-4 col-lg-2 mb-3"
      >
        <div class="card border-hover-primary hover-scale h-100">
          <div
            class="text-center card-body vstack gap-3 justify-content-center"
          >
            <div class="text-primary">
              <i class="fa-2x" :class="card.icon" aria-hidden="true"></i>
            </div>
            <h6>{{ card.text }}</h6>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-4 border-top border-primary">
      <div class="counter">
        <div class="row">
          <div
            v-for="statistic in Statistics"
            :key="statistic.title"
            class="count-data col-md-3 col-6 text-center"
          >
            <h6
              :class="'count h2 text-' + statistic.color"
              :data-to="statistic.count"
              :data-speed="statistic.count"
            >
              {{ statistic.count }}
            </h6>
            <p class="m-0px font-w-600">{{ statistic.title }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      Statistics: [],
      cardInfo: [
        { text: "Chat", icon: "fa fa-comments-o" },
        { text: "Notificari", icon: "bi bi-bell-fill" },
        { text: "Postari", icon: "fa-solid fa-square-plus" },
        { text: "Recenzii", icon: "fa-solid fa-star" },
        { text: "Like-uri & Dislike-uri", icon: "fa-solid fa-thumbs-up" },
        { text: "Comentarii", icon: "fa-solid fa-message" },
        { text: "Prieteni", icon: "fa-solid fa-user-plus" },
        { text: "Cautare persoane", icon: "fa-solid fa-magnifying-glass" },
      ],
    };
  },
  mounted() {
    let dictionar = [
      { title: "Clienti totali", count: 0, color: "dark" },
      { title: "Clienti online", count: 0, color: "success" },
      { title: "Postari totale", count: 0, color: "primary" },
      { title: "Recenzii totale", count: 0, color: "success" },
      { title: "Like-uri totale", count: 0, color: "danger" },
      { title: "Dislike-uri totale", count: 0, color: "danger" },
    ];
    axios.post("api/GetStatistics", { dictionar }).then((response) => {
      this.Statistics = response.data;
    });
  },
};
</script>