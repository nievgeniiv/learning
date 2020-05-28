Vue.component('help-table', {
  props: {
    dataJSON: Array
  },
  template: `
      <div class="row justify-content-center padding">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th>Сообщение</th>
              <th>Отвечено</th>
              <th>Дата последнего изменения</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in dataJSON">
                <td>
                  <a :href="'/help/view/?id='+data.identificate">{{data.message}}</a>
                </td>
                <td v-if="data.answer != ''">Да</td>
                <td v-else>Нет</td>
                <td>{{data.dataChange}}</td>
            </tr>
          </tbody>
        </table>
      </div>
  `
});

Vue.component('help', {
  props:{
    link: String
  },
  data() {
    return {
      dataJSON: [],
      dataPages: [],
      formatDateNow: String,
    }
  },
  template: `
        <div class="col">
          <add-search :searchFunction="searchData"></add-search>
          <div class="row justify-content-center padding">
            <select class="custom-select marginLeft">
              <option @click="filtrationData('all')">Все</option>
              <option @click="filtrationData('noHaveAnswer')">Не отвеченные</option>
              <option @click="filtrationData('haveAnswer')">Отвеченные</option>
              <option @click="filtrationData('dateChangeNew')">Свежие</option>
              <option @click="filtrationData('dateChangeOld')">Старые</option>
            </select>
          </div>
          <div class="row padding">
            <a href="/help/view/?id=0"><button class="btn btn-primary">Задать новый вопрос</button></a>
          </div>
          <help-table :dataJSON="dataJSON"></help-table>
          <pagination :pages="dataPages"
                      :getDataPage="newPageReq"></pagination>
        </div>
  `,
  mounted: function() {
    getRequest(this.link, this.getData);
    getDateNow(this.formatDateNow);
  },
  methods: {
    getData: function(data) {
      this.dataJSON = data.dataTable;
      this.dataPages = data.pages;
    },
    newPageReq: function (k) {
      getRequest(this.link + '?page=' + k, this.getData);
    },
    searchData: function(k) {
      search('/help/?search=', k, this.getData);
    },
    filtrationData: function(k) {
      getRequest(this.link + '?filter=' + k, this.getData);
    }
  }
});