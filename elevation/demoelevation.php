<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <title>geoxml3 Markers Example</title>
<!-- 
modified from original page at:
http://www.geocontext.org/dir/2010/test-geoxml3/test2/state_capitals.kml
L. Ross 9/13/2010
-->
  <style type="text/css">
    html, body, #map_canvas {width: 100%; height: 98%; margin: 0; padding: 0;}
    .infowindow * {font-size: 90%; margin: 0}
  </style>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <script type="text/javascript" src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>

  <script type="text/javascript">
    var geoxml = null;

    function initialize() {
      var myLatlng = new google.maps.LatLng(39.397, -100.644);
      var myOptions = {
        zoom: 5,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

    };




   function load_markers_kml(){

   geoXml = new geoXML3.parser({map: map, singleInfoWindow: true});

            geoXml.parse('state_capitals.xml');

   }



   function hide_markers_kml(){

            geoXml.hideDocument();  // see geoxml3-modify: http://geocontext.org/pliki/2010/test-geoxml3/test2/geoxml3-modify.js

   }

   function unhide_markers_kml(){

            geoXml.showDocument();  // see geoxml3-modify: http://geocontext.org/pliki/2010/test-geoxml3/test2/geoxml3-modify.js

   }


  </script>
</head>
<body onload="initialize()">

  <button onclick="load_markers_kml();">load markers</button> ← load!!! 
  <button onclick="hide_markers_kml();">hide markers</button>
  <button onclick="unhide_markers_kml();">unhide markers</button>



  <div id="map_canvas"></div>




</body>
</html>
