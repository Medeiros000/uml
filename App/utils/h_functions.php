<?php

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

function h_head_t($title, $meta = Null)
{
  return "<head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <script src='js/jquery/jquery-3.7.1.min.js'></script>
              <link rel='stylesheet' href='css/bootstrap/css/bootstrap.min.css'>
              <script src='css/bootstrap/js/bootstrap.min.js'></script>
              <link rel='icon' href='img/favicon.png' type='image/png'>
              <link rel='stylesheet' href='css/style.css'>
              <script src='js/script.js'></script>
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

function h_card_t($key, $value, $l_part = Null, $l_indexes = Null)
{
  $particoes = '';
  $lines     = 3;
  $more      = "<tr class='more'><td colspan='2' class='text-center text-bg-secondary' style='padding: 0;'>click to expand</td></tr><tr style='display:none;'></tr>";
  if (count($value) > $lines) {
    $value = array_merge(array_slice($value, 0, $lines), [$more], array_slice($value, $lines));
  }
  if (array_key_exists($key, $l_part)) {
    $particoes = " ($l_part[$key] partitions)";
  }
  $html  = "<div class='card m-1 tab' style='cursor: pointer;' title='Click to expand'>
      <div class='card-header text-center rounded-top fw-bolder text-bg-dark' style='bg-opacity: 0; font-size: 0.9rem; padding: 0.2rem;'>
        $key $particoes
      </div>
      <table class='table table-striped m-0 ps-2 pe-2'>
        <tbody>";
  $index = 0;
  foreach ($value as $v) {
    if ($v == $more) {
      $html .= $more;
      continue;
    }
    $class = $index >= $lines ? 'class="expandable" style="display: none;"' : '';
    $index++;
    $v     = explode(':', $v);
    $html .= "<tr $class>
        <td class='pt-1 pb-1'>$v[0]</td>
        <td class='pt-1 pb-1'>$v[1]</td>
      </tr>";
  }
  if (!empty($l_indexes[0])) {
    foreach ($l_indexes as $v) {
      if ($v == $more) {
        $html .= $more;
        continue;
      }
      $class = $index >= $lines ? 'class="expandable" style="display: none;"' : '';
      $index++;
      $v     = explode(':', $v);
      $html .= "<tr $class>
        <td class='pt-1 pb-1'>IDX $v[0]</td>
        <td class='pt-1 pb-1'>$v[1]</td>
      </tr>";
    }
  }
  if ($index > $lines) {
    $html .= '<tr class="expandable radius-down" style="display: none;">';
  } else {
    $html .= '<tr class="radius-down">';
  }
  $html .= '
        <td colspan="2" class="text-center text-dark bg-dark radius-down" style="padding: 0;">_</td>
      </tr>';
  $html .= '</tbody>
      </table>      
    </div>';
  return $html;
}

function h_table_t($value, $l_indexes = Null)
{
  $html = "
            <table class='table table-striped m-0 ps-2 pe-2'>
              <tbody>";
  foreach ($value as $v) {
    $v     = explode(':', $v);
    $html .= "<tr>
                  <td class='pt-1 pb-1'>$v[0]</td>
                  <td class='pt-1 pb-1'>$v[1]</td>
                </tr>";
  }
  if ($l_indexes != Null) {
    foreach ($l_indexes as $v) {
      $v     = explode(':', $v);
      $html .= "<tr>
                    <td class='pt-1 pb-1'>IDX $v[0]</td>
                    <td class='pt-1 pb-1'>$v[1]</td>
                  </tr>";
    }
  }
  $html .= '</tbody>
      </table>';

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

function debug_f($var)
{
  echo "<div class='fixed-top float-start'>";
  echo '<pre>';
  print_r($var);
  echo '</pre>';
  echo '</div>';
}

?>