Vue.component('theory-table', {
  props: {
    dataJSON: Array,
  },
  template: `
      <div class="row justify-content-center padding">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th>Тема</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in dataJSON">
                <td>
                  <a :href="'/theory/?id=' + data.identificate">{{data.name}}</a>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
  `
});

Vue.component('theory', {
  props: {
    link: String
  },
  data() {
    return {
      dataJSON: [],
      dataPages: [],
    }
  },
  template: `
      <div class=col>
        <add-search :searchFunction="searchData"></add-search>
        <theory-table :dataJSON="dataJSON"></theory-table>
        <pagination :pages="dataPages" :getDataPage="newPageReq"></pagination>
      </div>
  `,
  mounted: function() {
    getRequest(this.link, this.getData);
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
      search('/theory/?search=', k, this.getData);
    },
  }
});