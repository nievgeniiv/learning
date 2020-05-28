Vue.component ('add-buttons', {
  props: {
    href: String,
    request: Function,
    text: String,
    disable: Boolean,
  },
  template: `<div class="row padding">
               <a :href="href"><button class="btn btn-primary">{{text}}</button></a>
               <div class="col">
                 <button class="btn btn-primary" :disabled="!disable" @click="request">Удалить</button>
               </div>
             </div>`,
});