<!doctype html5>
<html>
	<head>
		<script src="https://www.google.com/jsapi"></script>
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBLXG-6dcb4Wj12DApXhNdQJls9pfuUFog&amp;sensor=false"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;v=3.7"></script>
		<script type="text/javascript" src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>
		<script type="text/javascript" src="http://geoxml3.googlecode.com/svn/trunk/ProjectedOverlay.js"></script>
		<script type="text/javascript">
			var elevator;
			var map;
			var chart;
			var mousemarker = null;
			var route, routeLatLng, path;
			var infowindow = new google.maps.InfoWindow();
			var polyline;
			var myGeoXml3Zoom = true;
			var geoXMLParser = null;
			var elevationReqActive = false;
			var SAMPLES = 200;
			// Load the Visualization API and the columnchart package.
			google.load('visualization', '1', {
				packages : ['columnchart']
			});

			function initialize() {
				/*var mapOptions = {
				 zoom : 8,
				 center : lonepine,
				 mapTypeId : 'terrain'
				 }*/

				var mapOptions = {
					zoom : 8,
					center : new google.maps.LatLng(50.4219, 4.43428),
					mapTypeId : 'terrain'
				}
				map = new google.maps.Map(document.getElementById('map'), mapOptions);
				// Create an ElevationService.
				chart = new google.visualization.ColumnChart(document.getElementById('profile'));
				elevationService = new google.maps.ElevationService();
                infowindow = new google.maps.InfoWindow({});
				loadKML();
				 google.visualization.events.addListener(chart, 'onmouseover', function(e) {
			      if (mousemarker == null) {
			        mousemarker = new google.maps.Marker({
			          position: elevations[e.row].location,
			          map: map,
			          icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
			        });
			        var contentStr = "elevation="+elevations[e.row].elevation+"<br>location="+elevations[e.row].location.toUrlValue(6);
				mousemarker.contentStr = contentStr;
				google.maps.event.addListener(mousemarker, 'click', function(evt) {
				  mm_infowindow_open = true;
			          infowindow.setContent(this.contentStr);
				  infowindow.open(map,mousemarker);
			        });
			      } else {
			        var contentStr = "elevation="+elevations[e.row].elevation+"<br>location="+elevations[e.row].location.toUrlValue(6);
				mousemarker.contentStr = contentStr;
			        infowindow.setContent(contentStr);
			        mousemarker.setPosition(elevations[e.row].location);
			        // if (mm_infowindow_open) infowindow.open(map,mousemarker);
			      }
			    });

				//elevator = new google.maps.ElevationService();

				// Draw the path, using the Visualization API and the Elevation service.
			}

			function loadKML() {
				geoXMLParser = new geoXML3.parser({
					map: map, 
					singleInfoWindow: true,
					zoom: myGeoXml3Zoom,
					afterParse: useTheData});
				
				geoXMLParser.parse("CaminoSaintJeanRoncevalles.kml");
				/*var routeCenter = new google.maps.LatLng();
				route = new google.maps.KmlLayer("http://www.visionsandviews.net/kml/CaminoSaintJeanRoncevalles.kml");
				route.setMap(map);*/
			}
			
			function drawPath(path) {
        if (elevationReqActive || !path) return;


        // Create a PathElevationRequest object using this array.
        // Ask for 100 samples along that path.
        var pathRequest = {
          path: path,
          samples: SAMPLES
        }
        elevationReqActive = true;

        // Initiate the path request.
        elevationService.getElevationAlongPath(pathRequest, plotElevation);
      }

      // Takes an array of ElevationResult objects, draws the path on the map
      // and plots the elevation profile on a Visualization API ColumnChart.
      function plotElevation(results, status) {
        elevationReqActive = false;
        if (status == google.maps.ElevationStatus.OK) {
          elevations = results;

          // Extract the elevation samples from the returned results
          // and store them in an array of LatLngs.

          // Extract the data from which to populate the chart.
          // Because the samples are equidistant, the 'Sample'
          // column here does double duty as distance along the
          // X axis.
          var data = new google.visualization.DataTable();
          data.addColumn('string', 'Sample');
          data.addColumn('number', 'Elevation');
          for (var i = 0; i < results.length; i++) {
            data.addRow(['', elevations[i].elevation]);
          }

          // Draw the chart using the data within its DIV.
          document.getElementById('profile').style.display = 'block';
          chart.draw(data, {
            width: 640,
            height: 200,
            legend: 'none',
            titleY: 'Elevation (m)'
          });
        }
      }

			function useTheData(doc){
			  geoXmlDoc = doc[0];
			  for (var i = 0; i < geoXmlDoc.placemarks.length; i++) {
			    // console.log(doc[0].markers[i].title);
			    var placemark = geoXmlDoc.placemarks[i];
			    if (placemark.polyline) {
			      if (!path) {
			        path = [];
			        var samples = placemark.polyline.getPath().getLength();
			        var incr = samples/SAMPLES;
			        if (incr < 1) incr = 1;
			        for (var i=0;i<samples; i+=incr)
			        {
			          path.push(placemark.polyline.getPath().getAt(parseInt(i)));
			        }
			      }								 
			    }
			  }
			  drawPath(path);
			};		
		</script>
		<title>Elevation demo</title>
		<style type="text/css">
			#content {
				width: 960px;
				height: 100%;
			}
			#map {
				height: 400px;
				width: 700px;
			}
		</style>
	</head>
	<body onload="initialize()">
		<div id="content">
			<div id="map"></div>
			<div id="elevation">
				<div id="profile"></div>
			</div>
		</div>
	</body>
</html>
