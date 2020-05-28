<?php
/**
 * Created by PhpStorm.
 * User: zhenya
 * Date: 14.10.18
 * Time: 1:08
 */

include __DIR__ . '/../header.php';
?>
<!--Шапка сайта-->

<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
  <div class="container-fluid">
    <a href="/theory/" class="navbar-brand"><img src="/img/logo.png"></a>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="/admin/" class="nav-link">Главная</a>
        </li>
        <li class="nav-item">
          <a href="/admin/theory/?page=1" class="nav-link">Теория</a>
        </li>
        <li class="nav-item">
          <a href="/admin/practic/list/?page=1" class="nav-link">Практика</a>
        </li>
        <li class="nav-item">
          <a href="/admin/help/list/?page=1" class="nav-link">Помощь</a>
        </li>
      </ul>
    </div>
    <div class="dropdown">
      <a href="#" role="button" data-toggle="dropdown"
         aria-haspopup="true" aria-expanded="false">
        <img src="/img/icon_login.jpg" width="50px" height="50px">
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
        <p class="dropdown-header"><?=$_SESSION['user']?></p>
        <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/login/out/">Выйти</a>
        </div>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

