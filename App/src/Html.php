<?php

namespace App;

class Html
{
  function h_doctype()
  {
    return '<!DOCTYPE html>
            <html lang="en">';
  }

  function h_c_html()
  {
    return '</html>';
  }

  function h_head($title, $meta = Null)
  {
    return "<head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <script src='public/js/jquery/jquery-3.7.1.min.js'></script>
              <link rel='stylesheet' href='public/css/bootstrap/css/bootstrap.min.css'>
              <script src='public/css/bootstrap/js/bootstrap.min.js'></script>
              <link rel='stylesheet' href='public/css/style.css'>
              <script src='public/js/script.js'></script>
              $meta
              <title>$title</title>
            </head>";
  }

  function h_o_body($class = Null)
  {
    return "<body class='$class'>";
  }

  function h_c_body()
  {
    return '</body>';
  }

  function h_h1($title, $class = Null)
  {
    return "<h1 class='$class'>$title</h1>";
  }

  function h_span($title, $class = Null)
  {
    return "<span class='$class'>$title</span>";
  }

  function h_o_container($class = Null)
  {
    return "<div class='container $class'>";
  }

  function h_c_container()
  {
    return '</div>';
  }

  function h_card($head, $link)
  {
    return "<div class='card'>
              <div class='card-header'>
                $head
              </div>
              <div class='card-body'>
                <a onclick=send_form('$link') class='btn btn-primary'>Ir</a>
              </div>
            </div>";
  }

  function h_card_t($key, $value, $l_part, $l_indexes = Null)
  {
    $particoes = '';
    if (array_key_exists($key, $l_part)) {
      $particoes = " ($l_part[$key] partitions)";
    }
    $html = "<div class='card m-1 border-bottom-0'>
      <div class='card-header text-center rounded-top bg-light fw-bolder' style='bg-opacity: 0;'>
        $key $particoes
      </div>
      <table class='table table-striped m-0 ps-2 pe-2'>
        <tbody>";
    foreach ($value as $v) {
      $v     = explode(':', $v);
      $html .= "<tr>
        <td class='pt-1 pb-1'>$v[0]</td>
        <td class='pt-1 pb-1'>$v[1]</td>
      </tr>";
    }
    if (!empty($l_indexes[0])) {
      foreach ($l_indexes as $v) {
        $v     = explode(':', $v);
        $html .= "<tr>
        <td class='pt-1 pb-1'>IDX $v[0]</td>
        <td class='pt-1 pb-1'>$v[1]</td>
      </tr>";
      }
    }
    $html .= '</tbody>
      </table>
    </div>';
    return $html;
  }

  function post()
  {
    return '<div id="post"></div>';
  }

  function button($link, $name)
  {
    return "<a onclick=send_form('$link') style='width:100px; height: 38px' class='btn btn-dark m-1'>$name</a>";
  }

  function h_a_link($link, $name)
  {
    return "<a onclick=send_form('$link') style='width:100px; height: 38px' class='text-decoration-none text-danger m-1'>$name</a>";
  }

  function debug($var)
  {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
  }
}

?>