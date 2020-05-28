Vue.component('pagination',{
  props: ['pages', 'getDataPage'],
  template: `<div class="row padding">
    <nav aria-label="...">
      <ul class="pagination">
          <li v-if="pages.page === 1" class="page-item disabled">
            <a class="page-link" @click="getDataPage(pages.page-1)" tabindex="-1" aria-disabled="true">Назад</a>
          </li>
          <li v-else class="page-item">
            <a class="page-link" @click="getDataPage(pages.page-1)" tabindex="-1" aria-disabled="true">Назад</a>
          </li>
          <div v-for="index in pages.nofPage">
            <li v-if="index === pages.page" class="page-item active" aria-current="page">
              <a class="page-link" @click="getDataPage(index)">{{index}}<span class="sr-only">(current)</span></a>
            </li>
            <li v-else class="page-item"><a class="page-link" @click="getDataPage(index)">{{index}}</a></li>
        </div>
          <li v-if="pages.page === pages.nofPage" class="page-item disabled">
            <a class="page-link" @click="getDataPage(pages.page+1)" tabindex="-1" aria-disabled="true">Вперед</a>
          </li>
          <li v-else class="page-item">
          <a class="page-link" @click="getDataPage(pages.page+1)" tabindex="-1" aria-disabled="true">Вперед</a>
        </li>
      </ul>
    </nav></div>`,
});