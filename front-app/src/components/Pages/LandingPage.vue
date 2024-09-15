<script>
export default {
  data() {
    return {
      items: [
        { name: "Compra quello che manca per il sushi 🍣 ", checked: false },
        { name: "Appuntamento dal parrucchiere alle 14:30! ", checked: false },
        { name: "Connettiti alle 20 su Discord 👾", checked: false },
      ],
      completedQuests: [],
    };
  },
  watch: {
    items: {
      handler() {
        // Filtra gli elementi selezionati
        this.completedQuests = this.items.filter((item) => item.checked);
      },
      deep: true, // Monitora i cambiamenti interni dell'array items
    },
  },
  computed: {
    allCompleted() {
      return this.completedQuests.length === this.items.length;
    },
  },
};
</script>
<template>
  <div class="ms_p-2 ms_color-w ms_txt-cnt ms_mt-1">
    <h2>Benvenuto in Todo's App &#128640;</h2>
    <small>Da oggi tracciare gli impegni sarà molto più facile!</small>
  </div>

  <section class="demo-section">
    <div>
      <h4>I tuoi impegni di oggi : &#127880;</h4>
      <ul>
        <li v-for="(item, index) in items" :key="index" class="ms_p-2">
          <label>
            <input type="checkbox" v-model="item.checked" />
            <span :class="item.checked ? 'ms_done' : ''" class="ms_ml-1">{{
              item.name
            }}</span>
          </label>
        </li>
      </ul>
      <div v-if="completedQuests.length">
        <h4 class="ms_mt-1">Vediamo i tuoi progessi! &#128204; ({{ completedQuests.length }}/{{ items.length }})</h4>
        <ul>
          <li
            v-for="(item, index) in completedQuests"
            :key="index"
            class="ms_p-2"
          >
            {{ item.name }}
          </li>
        </ul>
        <div class="ms_txt-cnt ms_congratulations" v-if="allCompleted">
          <strong>Complimenti hai completato tutto per oggi! &#129351; 🎉</strong>
        </div>
      </div>
    </div>
  </section>
</template>
<style></style>
