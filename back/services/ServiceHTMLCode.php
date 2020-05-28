<?php


class ServiceHTMLCode
{
  protected static $html;

  public static function addStyle() : string
  {
    return '
      <link rel="stylesheet" type="text/css" href="'.CSS.'bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="'.CSS.'flatpickr.css">
      <link rel="stylesheet" type="text/css" href="'.CSS.'main.css">
      <link rel="stylesheet" type="text/css" href="'.CSS.'drag-and-drop.css">
      <link rel="stylesheet" type="text/css" href="'.CSS.'jquery-ui.min.css">
      <link rel="stylesheet" type="text/css" href="'.CSS.'jquery-ui.structure.css">
      <link rel="stylesheet" type="text/css" href="'.CSS.'jquery-ui.theme.css">';
  }

  public static function addRichEdit(string $language): string
  {
    return '
                  <script src="'.JS.'ext/tinymce/tinymce.min.js"></script>
                  <script>tinyMCE.init({
                    selector:"textarea",
                    language: "' . $language . '",
                    skin_url: "/css/tinymce/skins/ui/oxide",
                    content_css: "/css/tinymce/skins/ui/oxide/content.css",
                    //height: 300,
                  });</script>';
  }

  public static function addNavbar() : string
  {
    $htmlNavbar = '
      <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
        <div class="container-fluid">
          <a href="/theory/" class="navbar-brand"><img src="'.IMAGE.'logo.png" alt="Лого"></a>
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
          <div class="dropdown">';
    if (isset($_SESSION['user'])) {
      $htmlNavbar .= '<a href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                        <img src="'.IMAGE.'icon_login.jpg" alt="Пользователь" width="50px" height="50px">
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                        <p class="dropdown-header">' . $_SESSION['user'] . '</p>
                        <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="/login/out/">Выйти</a>
                        </div>
                      </div>';
    }
    $htmlNavbar .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>
      </nav>';
    return $htmlNavbar;
  }

  public static function openDiv(string $atr, string $text = '')
  {
    return '<div '.$atr.'>'.$text;
  }

  public static function closeDiv()
  {
    return '</div>';
  }

  public static function addBreadcrum(array $data) :string
  {
    $i = 0;
    $htmlBreadcrum = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    while ($i <= count($data)-1) {
      if ($i === count($data) -1) {
        $htmlBreadcrum .= '<li class="breadcrumb-item active" aria-current="page">'.$data[$i]['head'].'</li>';
      } else {
        $htmlBreadcrum .= '<li class="breadcrumb-item"><a href="'.$data[$i]['href'].'">
                              '.$data[$i]['head'].'</a></li>';
      }
      $i++;
    }
    return $htmlBreadcrum .= '</ol></nav>';
  }

  public static function addParagraph(string $atr, string $text)
  {
    return '<p '.$atr.'>'.$text.'</p>';
  }

  public static function addLable(string $atr, string $text)
  {
    return '<lable '.$atr.'>'.$text.'</lable>';
  }

  public static function addError(string $nameInput)
  {
    return errorView($nameInput);
  }

  public static function openForm(string $atrForm)
  {
    return '<form '.$atrForm.'>';
  }

  public static function addFormInput(string $atrDiv, string $atrLable, string $textLable, string $atrInput,
                                      string $nameInput)
  {
    return '<div '.$atrDiv.'>'.
                    errorView($nameInput).
                    '<lable '.$atrLable.'>'.$textLable.'</lable>'.
                    '<input '.$atrInput.'/> </div>';
  }

  public static function addFormButton(string $atr)
  {
    return '<input '.$atr.'>';
  }

  public static function addFormTextArea(string $atrTextArea, string $textTextArea)
  {
    return '<textarea '.$atrTextArea.'>'.$textTextArea.'</textarea>';
  }

  public static function closeForm()
  {
    return '</form>';
  }

  public static function openA(string $atr, string $text) : string
  {
    return '<a '.$atr.'>'.$text.'</a>';
  }

  public static function addButton(string $atrA, string $atrButton, string $text) : string
  {
    return '<a '.$atrA.'><button '.$atrButton.'>'.$text.'</button></a>';
  }

  public static function addButtonNoA(string $atrButton, string $text) : string
  {
    return '<button '.$atrButton.'>'.$text.'</button>';
  }

  public static function addInput(string $atr) : string
  {
    return '<input '.$atr.'>';
  }

  public static function addSelect(string $atrSelect, array $data) : string
  {
    $select = '<select '.$atrSelect.'>';
    $i = 0;
    while ($i <= count($data) - 1) {
      $select .= '<option '.$data[$i]['atr'].'>'.$data[$i]['value'].'</option>';
     $i++;
    }
    return $select .= '</select>';
  }

  public static function addPagination(int $page, int $nofPage, string $link) : string
  {
    $next = $page + 1;
    $previos = $page - 1;
    $htmlPagination = '
      <nav aria-label="...">
        <ul class="pagination">';
    if ($nofPage === 1 or $page === 1) {
      $htmlPagination .= '
        <li class="page-item disabled">';
    } else {
      $htmlPagination .= '
        <li class="page-item">';
    }
    $htmlPagination .= '
          <a class="page-link" href="'.$link.'?page='.$previos.'" tabindex="-1" aria-disabled="true">Назад</a>
        </li>';
    $i = 1;
    while ($i <= $nofPage) {
      if ($i === $page) {
        $htmlPagination .= '
          <li class="page-item active" aria-current="page">
            <a class="page-link" href="'.$link.'?page='.$i.'">'.$i.'<span class="sr-only">(current)</span></a>
          </li>';
      } else {
        $htmlPagination .= '
          <li class="page-item"><a class="page-link" href="'.$link.'?page='.$i.'">'.$i.'</a></li>';
      }
      $i++;
    }
    if ($nofPage === 1 or $page === $nofPage) {
      $htmlPagination .= '
          <li class="page-item disabled">';
    } else {
      $htmlPagination .= '
          <li class="page-item">';
    }
    $htmlPagination .= '
        <a class="page-link" href="'.$link.'?page='.$next.'" tabindex="-1" aria-disabled="true">Вперед</a>
        </li>';
    return $htmlPagination;
  }


  public static function addScriptJS() : string
  {
    return '
      <script type="text/javascript" src="'.JS.'ext/vue/vue.min.js"></script>
      <script type="text/javascript" src="'.JS.'ext/jQuery/jquery-3.4.1.min.js"></script>
      <script type="text/javascript" src="'.JS.'ext/jQuery/jquery-ui.min.js"></script>
      <script type="text/javascript" src="'.JS.'ext/axios/axios.min.js"></script>
      <script type="text/javascript" src="'.JS.'ext/bootstrap/bootstrap.min.js"></script>
      <script type="text/javascript" src="'.JS.'ext/bootstrap/fontawesome.js"></script>
      <script type="text/javascript" src="'.JS.'asset/Settings.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/AdminEdit.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/Functions.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/Pagination.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/ButtonsAdminComponent.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/FiltrationComponent.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/SearchFieldComponent.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/AdminComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/AdminEdit.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/AdminHelpComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/AdminPracticComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/AdminTheoryComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/student/StudentHelpComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/student/StudentPracticComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'mod/student/StudentTheoryComponents.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/ContentPages.vue.js"></script>
      <script type="text/javascript" src="'.JS.'lib/Autocomlite.js"></script>
      <script type="text/javascript" src="'.JS.'ext/flatpickr/flatpickr.min.js"></script>
      <script type="text/javascript" src="'.JS.'ext/flatpickr/ru.js"></script>
      <script type="text/javascript" src="'.JS.'mod/student/Select.js"></script>
      <script type="text/javascript" src="'.JS.'mod/student/Select_jQuery.js"></script>
      <script type="text/javascript" src="'.JS.'ext/lodash.min.js"></script>
      <script type="text/javascript" src="'.JS.'mod/admin/calendarTask.js"></script>';
  }

  public static function setData(string $data)
  {
    self::$html .= $data;
  }

  public static function getData() :string
  {
    return self::$html;
  }

  public static function clearData()
  {
    self::$html = '';
  }
}