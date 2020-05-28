Vue.component('practic-table',{
  props: {
    dataJSON: Array,
    dateNow: String,
  },
  template: `
      <div class="row justify-content-center padding">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th>Тема</th>
              <th>Оценка</th>
              <th>Дата последнего изменения</th>
              <th>Дата сдачи задания</th>
              <th>Задание просрочено</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in dataJSON">
                <td>
                  <a :href="'/practic/?id='+data.identificate">{{data.theme}}</a>
                </td>
                <td>{{data.rating}}</td>
                <td>{{data.dataChange}}</td>
                <td>{{data.date}}</td>
                <td v-if="Date.parse(data.date) < dateNow"><div class="textRed">Задание просрочено!</div></td>
                <td v-else></td>
            </tr>
          </tbody>
        </table>
      </div>`
});

Vue.component('practic',{
  props: {
    link: String,
  },
  data() {
    return {
      dataJSON: [],
      dataPages: [],
      formatDateNow: String,
    }
  },
  template: `<div class="col">
              <add-search :searchFunction="searchData"></add-search>
              <div class="row justify-content-center padding">
                <select class="custom-select marginLeft">
                  <option @click="filtrationData('all')">Все</option>
                  <option @click="filtrationData('noHaveRating')">Не оцененные</option>
                  <option @click="filtrationData('haveRating')">Оцененные</option>
                  <option @click="filtrationData('noHaveAnswer')">Не отвеченные</option>
                  <option @click="filtrationData('haveAnswer')">Отвеченные</option>
                  <option @click="filtrationData('dateChangeNew')">Свежие</option>
                  <option @click="filtrationData('dateChangeOld')">Старые</option>
                </select>
              </div>
              <practic-table  :dataJSON="dataJSON"
                              :dateNow="formatDateNow"></practic-table>
              <pagination :pages="dataPages"
                          :getDataPage="newPageReq"></pagination>
            </div>`,
  mounted: function() {
    getRequest(this.link, this.getData);
    getDateNow(this.formatDateNow);
  },
  methods: {
    getData: function(data) {
      this.dataJSON = data.dataTable;
      this.dataPages = data.pages;
    },
    newPageReq: function(k) {
      getRequest(this.link + '?page=' + k, this.getData);
    },
    searchData: function(k) {
      search('/practic/?search=', k, this.getData);
    },
    filtrationData: function(k) {
      getRequest(this.link + '?filter=' + k, this.getData);
    }
  }
});