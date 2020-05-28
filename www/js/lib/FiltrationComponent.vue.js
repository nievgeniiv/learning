Vue.component('add-filter',{
  props: {
    filtration: Function
  },
  template: `
      <div class="row padding">
        <input id="studentAjaxPractic" type="text" list="cars" placeholder="ФИО студента">
        <select class="custom-select marginLeft">
          <option @click="filtration('all')">Все</option>
          <option @click="filtration('noHaveRating')">Не оцененные</option>
          <option @click="filtration('haveRating')">Оцененные</option>
          <option @click="filtration('noHaveAnswer')">Не отвеченные</option>
          <option @click="filtration('haveAnswer')">Отвеченные</option>
          <option @click="filtration('dateChangeNew')">Свежие</option>
          <option @click="filtration('dateChangeOld')">Старые</option>
        </select>
      </div>
  `
});