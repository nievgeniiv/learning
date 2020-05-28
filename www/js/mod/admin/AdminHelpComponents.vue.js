Vue.component ('admin-help-table', {
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
              <th>Сообщение</th>
              <th>Студент</th>
              <th>Отвечено</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data in dataJSON">
                <td class="thCheckbox">
                  <input v-model="checkedNames" type="checkbox" :checked="checked" name="id[]" :value= "data.identificate">
                </td>
                <td>
                  <a :href="'/admin/help/view/?student='+data.whose+'&id='+data.identificate">{{data.message}}</a>
                </td>
                <td>{{data.student}}</td>
                <td v-if="data.answer != ''">Да</td>
                <td v-else>Нет</td>
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

Vue.component('admin-help',{
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
              <div class="row padding">
                <input id="studentAjaxHelp" type="text" list="cars" placeholder="ФИО студента">
                <select class="custom-select marginLeft">
                  <option @click="filtrationData('all')">Все</option>
                  <option @click="filtrationData('noHaveAnswer')">Не отвеченные</option>
                  <option @click="filtrationData('haveAnswer')">Отвеченные</option>
                  <option @click="filtrationData('dateChangeNew')">Свежие</option>
                  <option @click="filtrationData('dateChangeOld')">Старые</option>
                </select>
              </div>
              <div class="row padding">
                <button class="btn btn-primary" :disabled="!checkOn" @click="deleteData">Удалить</button>
              </div>
              <admin-help-table :dataJSON="dataJSON"
                                :dateNow="formatDateNow"
                                :checked="checkboxOn"></admin-help-table>
              <pagination :pages="dataPages"
                          :getDataPage="newPageReq"></pagination>
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
      getRequest(this.link + '&page=' + k, this.getData);
    },
    searchData: function(k) {
      search('/admin/help/?search=', k, this.getData);
    },
    checkboxOn: function(k) {
      this.dataCheckbox = k;
    },
    deleteData: function () {
      postReqest('/admin/help/delete/', this.link, this.dataCheckbox, this.getData);
    },
    filtrationData: function(k) {
      getRequest(this.link + '&filter=' + k, this.getData);
    }
  }
});