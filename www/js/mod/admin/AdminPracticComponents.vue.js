Vue.component ('admin-practic-table', {
  props: {
    dataJSON: Array,
    dateNow: String,
    checked: Function
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
              <th>Тема</th>
              <th>Студент</th>
              <th>Оценка</th>
              <th>Задание просрочено</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in dataJSON">
                <td class="thCheckbox">
                  <input v-model="checkedNames" type="checkbox" :checked="checked" name="id[]" :value= "data.identificate">
                </td> 
                <td>
                  <a :href="'/admin/practic/view/?student='+data.whose+'&id='+data.identificate">{{data.theme}}</a>
                </td>
                <td>{{data.student}}</td>
                <td>{{data.rating}}</td>
                <td v-if="Date.parse(data.date) < dateNow"><div class="textRed">Задание просрочено!</div></td>
                <td v-else></td>
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

Vue.component('admin-practic',{
  props: {
    link: String,
    student: String,
  },
  data() {
    return {
      checkOn: false,
      dataCheckbox: [],
      dataJSON: [],
      dataPages: [],
      formatDateNow: String,
    }
  },
  template: `<div class="col">
              <add-search :searchFunction="searchData"></add-search>
              <add-filter :filtration="filtrationData"></add-filter>
              <add-buttons  :href="'/admin/practic/edit/?student='+student+'&id=0'" 
                            :text="'Добавить новое задание'" 
                            :request="deleteData" 
                            :disable="checkOn"></add-buttons>
              <admin-practic-table  :dataJSON="dataJSON"
                                    :dateNow="formatDateNow"
                                    :checked="checkboxOn"></admin-practic-table>
              <pagination :pages="dataPages"
                          :getDataPage="newPageReq"></pagination>
            </div>`,
  mounted: function() {
    getRequest(this.link, this.getData);
    getDateNow(this.formatDateNow);
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
      search('/admin/practic/?search=', k, this.getData);
    },
    checkboxOn: function(k) {
      this.dataCheckbox = k;
    },
    deleteData: function () {
      postReqest('/admin/practic/delete/', this.link, this.dataCheckbox, this.getData);
    },
    filtrationData: function(k) {
      getRequest(this.link + '&filter=' + k, this.getData);
    }
  }
});