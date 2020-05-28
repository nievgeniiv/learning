Vue.component('add-search',{
  props: ['searchFunction'],
  data() {
    return {
      dataSearch:'',
    }
  },
  template: `<div class="row justify-content-center padding">
                <input type="text" placeholder="Поиск" v-model="dataSearch">
              </div>`,
  watch: {
    dataSearch: function () {
      this.searchFunction(this.dataSearch);
    }
  },
});