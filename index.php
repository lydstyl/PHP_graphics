<?php
    // connexion BDD à include ?
    $link = mysqli_connect("localhost","root", "", "graphic") or die("Impossible de se connecter : ".mysqli_error());
    mysqli_query($link, 'SET NAMES UTF8');
    // création d'un tableau à partir de la base de données
    $resultat = mysqli_query($link, 'SELECT * FROM `composition`');
    $assignTable = [];
    while($data = mysqli_fetch_assoc($resultat)){
        array_push($assignTable,  array(
            'ingredient'=> $data['ingredient'],
            'poids'=> $data['poids']
        ) );
    }
    $t = [['Ingredient', 'Poids']];
    foreach ($assignTable as $key => $value) {
        array_push($t, array(
            $value['ingredient'],
            $value['poids']
        ));
    }
    // création d'un tableau string pour js
    $jsTab = '[';
    for ($i=0; $i < 4; $i++ ) { 
      $jsTab .= '["';
      $jsTab .= $t[$i][0];
      $jsTab .= '",';
      $i==0?$jsTab .= '"'.$t[$i][1].'"' : $jsTab.= $t[$i][1];
      $jsTab .= '],';
    }
    $jsTab .= ']';
    // déconnexion BDD à include ?
    mysqli_close($link); 
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
/*        var jsTab = [
          ['Ingredient', 'Poids'],
          ['tomate',     30],
          ['courgette',      10],
          ['carotte',  50],
        ]*/
        var data = google.visualization.arrayToDataTable(<?= $jsTab ?>);
        //var data = google.visualization.arrayToDataTable($t);
        var options = {
          title: 'Composition de ma recette'
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>