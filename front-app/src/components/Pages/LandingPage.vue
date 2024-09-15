<script>
export default {
  data() {
    return {
      items: [
        { name: "Elemento 1", checked: false },
        { name: "Elemento 2", checked: false },
        { name: "Elemento 3", checked: false },
      ]
    };
  },
  computed: {
    selectedItems() {
      return this.items.filter(item => item.checked);
    }
  }
};
</script>
<template>
  <div class="ms_p-2 ms_color-w ms_txt-cnt ms_mt-1">
    <h2>Benvenuto in Todo's App &#128640;</h2>
    <small>Da oggi tracciare gli impegni sarà molto più facile!</small>
  </div>

  <section class="demo-section">
    <div>
      <h2>Seleziona elementi dalla lista:</h2>
      <ul>
        <li v-for="(item, index) in items" :key="index">
          <label>
            <input type="checkbox" v-model="item.checked" />
            {{ item.name }}
          </label>
        </li>
      </ul>

      <h3>Elementi selezionati:</h3>
      <ul>
        <li v-for="(item, index) in selectedItems" :key="index">
          {{ item.name }}
        </li>
      </ul>
    </div>
  </section>
</template>
<style></style>
