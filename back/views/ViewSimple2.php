<?php
include_once __DIR__ . '/header.php';
?>

<form action="/simple2/save/" method="post">
  <div id="app123">
    <h2>Список пользователей</h2>
    <userform :user="user"></userform>
    <div>
      <useritem v-for="(user, index) in users"
                :key="index"
                :user="user"
                :index="index"
                v-on:userchange="change">
      </useritem>
    </div>
  </div>
  <input type="submit">
</form>




  <div id="app111">
    <form @submit.prevent="submitForm">
      <div>
        <label for="name">Name:</label><br>
        <span v-show="!name" v-on:click="name='<?=$T->name?>'"><?=$T->name?></span>
        <input v-show="name" type="text" v-model="name" value="{{name}}"required/>
      </div>
      <div>
        <label for="email">Email:</label><br>
        <input id="email" type="email" v-model="email"  required/>
      </div>
      <div>
        <label for="caps">HOW DO I TURN OFF CAPS LOCK:</label><br>
        <textarea id="caps" v-model="caps" required></textarea>
      </div>
      <button :class="[name ? activeClass : '']" type="submit">Submit</button>
      <div>
        <h3>Response from server:</h3>
        <pre>{{ response }}</pre>
      </div>
    </form>
  </div>
<hr>


<?php include_once __DIR__ . '/footer.php';
