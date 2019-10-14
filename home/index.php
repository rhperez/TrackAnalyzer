<?php
include_once "../controllers/oAuth.php";
include_once "../controllers/requestCtrlr.php";
include_once "../controllers/db_connection.php";

$track_id = "1JSTJqkT5qHq8MDJnJbRE1";
if (isset($_GET['search'])) { // es la URI
  if (strpos($_GET['search'], "/") === false) {
    $search_arr = explode(":", $_GET['search']);
    if ($search_arr[1] == 'track') {
      $track_id = $search_arr[2];
    }
  } else { // es un link
    $search_str = str_replace("?", "/", $_GET['search']);
    $search_arr = explode("/", $search_str);
    if ($search_arr[3] == 'track') {
      $track_id = $search_arr[4];
    }
  }
}

//echo requestTrackFeatures("1JSTJqkT5qHq8MDJnJbRE1");
//executeRequest("https://api.spotify.com/v1/audio-features/", $access_token, "1JSTJqkT5qHq8MDJnJbRE1");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Track Analyzer by Robert
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="../assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
  <?php echo '<input type="hidden" id="track_id" value="'.$track_id.'"></input>';?>
  <div class="wrapper">
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)">Track Analyzer by Robert</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
              <li class="search-bar input-group">
                <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split"></i>
                  <span class="d-lg-none d-md-block">Search</span>
                </button>
              </li>
              <li class="dropdown nav-item">
                <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="notification d-none d-lg-block d-xl-block"></div>
                  <i class="tim-icons icon-sound-wave"></i>
                  <p class="d-lg-none">
                    Notifications
                  </p>
                </a>

              </li>
              <li class="dropdown nav-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="photo">
                    <img src="../assets/img/anime3.png" alt="Profile Photo">
                  </div>
                  <b class="caret d-none d-lg-block d-xl-block"></b>
                  <p class="d-lg-none">
                    Log out
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-navbar">
                  <li class="nav-link">
                    <a href="javascript:void(0)" class="nav-item dropdown-item">Profile</a>
                  </li>
                  <li class="nav-link">
                    <a href="javascript:void(0)" class="nav-item dropdown-item">Settings</a>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li class="nav-link">
                    <a href="javascript:void(0)" class="nav-item dropdown-item">Log out</a>
                  </li>
                </ul>
              </li>
              <li class="separator d-lg-none"></li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <form action="index.php" method="get">
                <input type="text" name="search" id="search" class="form-control" id="inlineFormInputGroup" placeholder="URI o link de tu canción">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="tim-icons icon-simple-remove"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Navbar -->
      <div class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-search">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <form action="index.php" method="get">
                      <div class="form-group">
                        <label>Analizar una canción</label>
                        <input type="text" name="search" class="form-control" placeholder="Copia la URI o el link de una canción de Spotify y pégalo aquí">
                        <button type="submit" class="btn btn-fill btn-primary">Analizar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card card-chart">
              <div class="card-header ">
                <div class="row">
                  <div class="col-sm-2 text-left">
                    <img id="album_pic" width=300 style="margin-left:10px;"></img>
                  </div>
                  <div class="col-sm-6 text-left">
                    <h2 id="song" class="card-title">Song name</h2>
                    <h3 id="artist" class="card-title">Artist name</h3>
                    <h4 id="album">Album name</h4>
                    <h5 class="card-category" id="duracion">Duración: 00:00</h5>
                    <h5 class="card-category" id="volumen">Volumen: 0 Db</h5>
                    <h5 class="card-category" id="secciones">Secciones: 0</h5>
                  </div>
                </div>
              </div>
              <div class="card-body">

              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title"><span class="text-success">&#119070;</span> Análisis</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table tablesorter " id="table_sections">
                    <thead class="text-success">
                      <tr>
                        <th class="text-success">
                          Sección
                        </th>
                        <th class="text-success">
                          Clave
                        </th>
                        <th class="text-success">
                          Modo
                        </th>
                        <th class="text-success">
                          Tempo
                        </th>
                        <th class="text-success">
                          Métrica
                        </th>
                        <th class="text-success">
                          Inicio
                        </th>
                        <th class="text-success">
                          Duración
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title"><i class="tim-icons icon-headphones text-info"></i> Características</h3>
              </div>
              <div class="card-body">
                <div class="radar-chart1">
                  <canvas id="radar-chart"></canvas>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">

          <div class="copyright">
            ©
            <script>
              document.write(new Date().getFullYear())
            </script> Powered by
            <a href="https://developer.spotify.com/documentation/web-api/" target="_blank">Spotify Web API</a>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
      <a href="#" data-toggle="dropdown">
        <i class="fa fa-cog fa-2x"> </i>
      </a>
      <ul class="dropdown-menu">
        <li class="header-title"> Sidebar Background</li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger background-color">
            <div class="badge-colors text-center">
              <span class="badge filter badge-primary active" data-color="primary"></span>
              <span class="badge filter badge-info" data-color="blue"></span>
              <span class="badge filter badge-success" data-color="green"></span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="adjustments-line text-center color-change">
          <span class="color-label">LIGHT MODE</span>
          <span class="badge light-badge mr-2"></span>
          <span class="badge dark-badge ml-2"></span>
          <span class="color-label">DARK MODE</span>
        </li>
        <li class="button-container">
          <a href="https://www.creative-tim.com/product/black-dashboard" target="_blank" class="btn btn-primary btn-block btn-round">Download Now</a>
          <a href="https://demos.creative-tim.com/black-dashboard/docs/1.0/getting-started/introduction.html" target="_blank" class="btn btn-default btn-block btn-round">
            Documentation
          </a>
        </li>
        <li class="header-title">Thank you for 95 shares!</li>
        <li class="button-container text-center">
          <button id="twitter" class="btn btn-round btn-info"><i class="fab fa-twitter"></i> &middot; 45</button>
          <button id="facebook" class="btn btn-round btn-info"><i class="fab fa-facebook-f"></i> &middot; 50</button>
          <br>
          <br>
          <a class="github-button" href="https://github.com/creativetimofficial/black-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star ntkme/github-buttons on GitHub">Star</a>
        </li>
      </ul>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <!-- Place this tag in your head or just before your close body tag. -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/black-dashboard.min.js?v=1.0.0"></script>
  <!-- Black Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <script>
    $(document).ready(function() {
      loadRadar($("#track_id").val());

      $().ready(function() {
        $sidebar = $('.sidebar');
        $navbar = $('.navbar');
        $main_panel = $('.main-panel');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');
        sidebar_mini_active = true;
        white_color = false;

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();



        $('.fixed-plugin a').click(function(event) {
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .background-color span').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data', new_color);
          }

          if ($main_panel.length != 0) {
            $main_panel.attr('data', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data', new_color);
          }
        });

        $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
          var $btn = $(this);

          if (sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            sidebar_mini_active = false;
            blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
          } else {
            $('body').addClass('sidebar-mini');
            sidebar_mini_active = true;
            blackDashboard.showSidebarMessage('Sidebar mini activated...');
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);
        });

        $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
          var $btn = $(this);

          if (white_color == true) {

            $('body').addClass('change-background');
            setTimeout(function() {
              $('body').removeClass('change-background');
              $('body').removeClass('white-content');
            }, 900);
            white_color = false;
          } else {

            $('body').addClass('change-background');
            setTimeout(function() {
              $('body').removeClass('change-background');
              $('body').addClass('white-content');
            }, 900);

            white_color = true;
          }


        });

        $('.light-badge').click(function() {
          $('body').addClass('white-content');
        });

        $('.dark-badge').click(function() {
          $('body').removeClass('white-content');
        });
      });
    });
  </script>
  <script>
  var marksCanvas = document.getElementById("radar-chart");





  function loadRadar(id) {
    $.ajax({
      url: "requests.php",
      contentType: "application/json; charset=utf-8",
      dataType: 'json',
      method: "GET",
      data: {'accion':'getTrack', 'id': id},
      success: function(r) {
        if (r.response == 'error') {
          alert(r.error_message);
          return;
        }
        var track = r.track;
        var data = r.features;
        var analysis = r.analysis;
        var key = getClave(data.key);
        var mode = data.mode == 1 ? "mayor" : "menor";
        var tempo = data.tempo;
        var time_signature = data.time_signature;
        var duracion = msToMin(data.duration_ms);
        var volumen = data.loudness;
        var n_secciones = analysis.sections.length;


        $('#song').text(track.name);
        $('#artist').text(track.artists[0].name);
        $('#album_pic').attr("src",track.album.images[1].url);
        $('#album').text(track.album.name);
        $('#clave').html(key);
        $('#modalidad').html(mode);
        $('#tempo').html(tempo);
        $('#compas').html(time_signature);
        $('#duracion').html("Duración: " + duracion);
        $('#volumen').html("Volumen: " + volumen+ " dB");
        $('#secciones').html("Secciones: " + n_secciones);
        jQuery.each(analysis.sections, function(i,data) {
            $("#table_sections").append("<tr><td>" + i + "</td><td>" + getClave(data.key) + "</td><td>" + getModalidad(data.mode) + "</td><td>" + data.tempo + "</td></td><td>" + data.time_signature + "</td></td><td>" + data.start + "</td><td>" + data.duration + "</td></tr>");
        });

        var ctx = document.getElementById("radar-chart");

        var marksData = {
          labels: ["Bailabilidad", "Energía", "Vocalidad", "Acusticidad", "Instrumentalidad", "Viveza", "Valencia"],
          datasets: [{
            label: "Track",
            data: [data.danceability, data.energy, data.speechiness, data.acousticness, data.instrumentalness, data.liveness, data.valence],
            backgroundColor: '#df4ecc55',
					  borderColor: '#df4ecc'
          }]
        };

        var radarChart = new Chart(marksCanvas, {
          type: 'radar',
          data: marksData,
          options: {
    				legend: {
              display: false
    				},
    				title: {
    					display: false
    				},
            scale: {
                angleLines: { color: '#333388' },
                gridLines: { color: '#333388' },
                pointLabels: {
                  fontColor: '#236ab8',
                  fontSize: 14
                },
                ticks: {
                    display: false,
                    stepSize: 0.2
                }
            },
            tooltips: {
              intersect: false,
              mode: 'nearest'
            }
    			}
        });

      },
      error: function(data) {
        alert("Error: "+data);
      }
    });
  }

  function msToMin(millis) {
    var minutes = Math.floor(millis / 60000);
    var seconds = ((millis % 60000) / 1000).toFixed(0);
    return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
  }

  function getClave(key) {
    switch (key) {
      case 0:
        key = "C";
        break;
      case 1:
        key = "C&#9839; / D&#9837;";
        break;
      case 2:
        key = "D";
        break;
      case 3:
        key = "D&#9839; / E&#9837;";
        break;
      case 4:
        key = "E";
        break;
      case 5:
        key = "F";
        break;
      case 6:
        key = "F&#9839; / G&#9837;";
        break;
      case 7:
        key = "G";
        break;
      case 8:
        key = "G&#9839; / A&#9837;";
        break;
      case 9:
        key = "A";
        break;
      case 10:
        key = "A&#9839; / B&#9837;";
        break;
      case 11:
        key = "B";
        break;
      default:
        key = "No identificada / No aplica";
        break;
    }
    return key;
  }

  function getModalidad(mode) {
    return mode == 1 ? "Mayor" : "menor";
  }
  </script>
</body>

</html>
