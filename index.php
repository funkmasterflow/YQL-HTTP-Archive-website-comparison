<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
    <title>HTTP Archive - Website comparison</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css" />
    <link rel="stylesheet" href="layout.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

    <style>
        .bigChart   { position:fixed; top:70px; right:5px; border: 1px solid #000;      }
        .done       { width: 50%; }

    </style>
</head>
<body>

<div id="doc3" class="yui-t7">
   <div id="hd" role="banner"><h1>HTTP Archive - Website comparison</h1></div>
   <div id="bd" role="main">
    <div class="yui-g">       

    </div>
    
    <div class="done">
        <?php
            function getHttpArchiveData($websiteId){
                $query = 'select * from html where url="http://httparchive.org/viewsite.php?pageid='.$websiteId.'"';
                $api = 'http://query.yahooapis.com/v1/public/yql?q='.
                        urlencode($query).'&format=json';

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($output);

                $protocol = $data->query->results->body->h1->a->span->content;
                $domain = $data->query->results->body->h1->a->content;
                $website = trim($protocol).trim($domain);

                $loadtime = $data->query->results->body->p[1]->content;

                for($i=3;$i<11;$i++){
                    $charts[$i-3] = $data->query->results->body->div[$i]->img->src;
                }

                $result = "<p>".$website." ".$loadtime."</p>";

                foreach($charts as $chart){
                    $result .= "<img class=\"chart\" src=\"".$chart."\" height=\"100\" width=\"200\" /> ";
                }

                echo $result;
            }

            getHttpArchiveData(229514);
            getHttpArchiveData(228423);
            getHttpArchiveData(218493);
         ?>
    

    </div>

    </div>
   <div id="ft" role="contentinfo"><p>&copy; 2011 - Funkmaster Flow</p></div>
</div>

<div class="bigChart" style="display:none;"></div>

<script>
$("img.chart").hover(
  function () {
      $('.bigChart').append('<img src='+this.src+' width="600" height="300" />').show();
  },
  function () {
    $('.bigChart img').remove();
    $('.bigChart').hide();
  }
);
</script>





</body>
</html>