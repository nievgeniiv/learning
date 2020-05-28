Vue.component ('admin-table', {
  props: {
    dataJSON: Array,
    checked: Function,
  },
  data: function(){
    return {
      checkedNames: [],
      selectAll: false
    }
  },
  template: `
      <div class="row justify-content-center padding">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th class="thCheckbox">
                <input type="checkbox" @click="select">
              </th> 
              <th>ФИО студента</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in dataJSON">
                <td class="thCheckbox">
                  <input v-model="checkedNames" type="checkbox" :checked="checked" class="checkboxTable" name="id[]" v-bind:value= "data.identificate">
                </td> 
                <td>
                  <a v-bind:href="'/admin/?edit=' + data.identificate">{{data.name}}</a>
                </td>
            </tr>
          </tbody>
        </table>
      </div>`,
  watch: {
    checkedNames: function () {
      this.checked(this.checkedNames);
    }
  },
  methods: {
    select: function () {
      let f = CheckboxSelect(this.selectAll, this.checkedNames, this.dataJSON);
      this.checkedNames = f[0];
      this.selectAll = f[1];
    }
  }
});

Vue.component('admin',{
  props: ['link'],
  data() {
    return {
      checkOn: false,
      timer: null,
      dataCheckbox: [],
      dataJSON: [],
      dataPages: [],
    }
  },
  template: `
            <div class="col">
              <add-search :searchFunction="searchData"></add-search>
              <add-buttons  :href="'/admin/?new'" 
                            :text="'Добавить студента'" 
                            :request="deleteData" 
                            :disable="checkOn"></add-buttons>
              <admin-table :dataJSON="dataJSON" :checked="checkboxOn"></admin-table>
              <pagination :pages="dataPages" :getDataPage="newPageReq"></pagination>
            </div>`,
  mounted: function() {
    getRequest(this.link, this.getData);
  },
  watch: {
    dataCheckbox: function () {
      this.checkOn = hasDataCheckbox(this.dataCheckbox);
    }
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
      search('/admin/?search=', k, this.getData);
    },
    checkboxOn: function(k) {
      this.dataCheckbox = k;
    },
    deleteData: function () {
      postReqest('/admin/delete/', this.link, this.dataCheckbox, this.getData);
    }
  }
});