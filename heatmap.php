<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Heatmap</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/papaparse@5.4.1/papaparse.min.js"></script>
  <script src="https://unpkg.com/chroma-js@2.4.2/chroma.min.js"></script>
  <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'includes/nav.php'; ?>

  <div style="text-align: center; padding: 10px;">
    <button id="viewToggle" type="button" class="button" aria-pressed="false">Toggle view</button>
  </div>

  <div class="map-card">
    <div id="map" role="application" aria-label="Heatmap"></div>
  </div>

  <!-- Grid Information Container -->
  <div class="container mt-4">
    <div class="card" id="gridInfoCard" style="display: none;">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Selected Location Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Grid ID:</strong> <span id="displayGridId">-</span></p>
            <p><strong>Coordinates:</strong> <span id="displayCoords">-</span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Average Windspeed:</strong> <span id="displayWindspeed">-</span></p>
            <p><strong>Average Luminosity:</strong> <span id="displayLuminosity">-</span></p>
          </div>
        </div>
        <?php if (isset($_SESSION['id'])): ?>
        <div class="mt-3">
          <button id="addListingBtn" class="btn btn-success" onclick="addListingAtLocation()">
            Add Listing at This Location
          </button>
        </div>
        <?php else: ?>
        <div class="mt-3">
          <p class="text-muted">Please <a href="login.php">login</a> to add a listing at this location.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

<script>
// Global variable to store selected grid data
let selectedGridData = null;

function addListingAtLocation() {
  if (!selectedGridData) {
    alert('Please select a location on the map first.');
    return;
  }

  // Redirect to add listing page with grid data
  const params = new URLSearchParams({
    grid_id: selectedGridData.grid_id,
    lat: selectedGridData.lat,
    lon: selectedGridData.lon
  });
  window.location.href = 'add-listing.php?' + params.toString();
}

function updateGridInfo(gridId, coords, windspeed, luminosity) {
  document.getElementById('displayGridId').textContent = gridId;
  document.getElementById('displayCoords').textContent = coords;
  document.getElementById('displayWindspeed').textContent = windspeed ? windspeed.toFixed(2) + ' m/s' : 'N/A';
  document.getElementById('displayLuminosity').textContent = luminosity ? luminosity.toFixed(2) + ' lux' : 'N/A';
  document.getElementById('gridInfoCard').style.display = 'block';

  // Store the selected grid data
  selectedGridData = {
    grid_id: gridId,
    lat: coords.split(',')[0].trim(),
    lon: coords.split(',')[1].trim(),
    windspeed: windspeed,
    luminosity: luminosity
  };
}

document.addEventListener('DOMContentLoaded', () => {

  const viewSwitch = localStorage.getItem("viewSwitch") === "true";


  // Toggles the bool viewSwitch variable to show either windspeed or luminosity
  const toggleBtn = document.getElementById('viewToggle');
  if (toggleBtn) {
    toggleBtn.textContent = viewSwitch ? 'Show Windspeed' : 'Show Luminosity';
    toggleBtn.setAttribute('aria-pressed', String(viewSwitch));
    toggleBtn.addEventListener('click', () => {
      localStorage.setItem('viewSwitch', String(!viewSwitch));
      location.reload();
    });
  }



  // ************************ //
  if (viewSwitch) {

  // gets the csv dataset
  const csvUrl = 'grid_coords_with_weather.csv';

  // create the map inside the #map element
  const map = L.map('map', { zoomControl: false, attributionControl: false, preferCanvas: true }).setView([55.79, -4.55], 12);

  // add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '© OpenStreetMap contributors'}).addTo(map);

  // turn CSV rows into GeoJSON polygons
  function buildGeoJSON(groups) {
    const features = [];
    for (const gridId in groups) {
      const rows = groups[gridId].sort((a, b) => Number(a.point_order) - Number(b.point_order));
      const coords = rows.map(r => [Number(r.lon), Number(r.lat)]);

      // close the polygon if needed
      if (!(coords[0][0] === coords[coords.length - 1][0] && coords[0][1] === coords[coords.length - 1][1])) {
        coords.push(coords[0]);
      }

      // average the values for the cell
        const lum = rows.reduce((s, r) => s + Number(r.average_luminosity), 0) / rows.length;
        const wind = rows.reduce((s, r) => s + Number(r.average_windspeed), 0) / rows.length;
        const centerLat = rows.reduce((s, r) => s + Number(r.lat), 0) / rows.length;
        const centerLon = rows.reduce((s, r) => s + Number(r.lon), 0) / rows.length;

        features.push({type: 'Feature', properties: { grid_id: gridId, average_luminosity: lum, average_windspeed: wind, center_lat: centerLat, center_lon: centerLon }, geometry: { type: 'Polygon', coordinates: [coords] }});
      }
    return { type: 'FeatureCollection', features };
  }

  // draw the grid, style it, and lock view to the grid area
  function renderChoropleth(geojson) {
    const vals = geojson.features.map(f => f.properties.average_luminosity);
    const min = Math.min(...vals), max = Math.max(...vals);
    const scale = chroma.scale(['#EFFE26','#FEC726','#FE9526','#FE5F26','#FE2626']).domain([min, max]);

    if (!map.getPane('gridPane')) {
      map.createPane('gridPane');
      map.getPane('gridPane').style.zIndex = 400;
    }

    // create the geojson layer and keep a reference named 'geojsonLayer'
    const geojsonLayer = L.geoJSON(geojson, {
      pane: 'gridPane',
      style: feature => {
        const v = feature.properties.average_luminosity;
        return {fillColor: scale(v).hex(), weight: 0.8, color: '#000', opacity: 0.35, fillOpacity: 0.5};
      },

      // creates popup on click, and grid outline on mouseover
      onEachFeature: (feature, layer) => {
        const p = feature.properties;
        layer.bindPopup(`<b>Grid</b>: ${p.grid_id}<br><b>Luminosity</b>: ${p.average_luminosity}`);
        layer.on({
          mouseover(e) { e.target.setStyle({ weight: 1.8, color: '#111' }); },
          mouseout(e) { geojsonLayer.resetStyle(e.target); },
          click(e) {
            const coords = `${p.center_lat.toFixed(6)}, ${p.center_lon.toFixed(6)}`;
            updateGridInfo(p.grid_id, coords, p.average_windspeed, p.average_luminosity);
          }
        });
      }
    }).addTo(map);

    // Fit to bounds and lock view
    const bounds = geojsonLayer.getBounds();
    map.fitBounds(bounds, { padding: [10, 10] });
    map.setMaxBounds(bounds.pad(0));
    const currentZoom = map.getZoom();
    map.setMinZoom(currentZoom);
    map.setMaxZoom(currentZoom);
    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();
    map.off();
  }

  // load the CSV, group rows by grid_id, then render
  Papa.parse(csvUrl, {
    download: true,
    header: true,
    complete: results => {
      const data = results.data.filter(r => r && r.grid_id);
      const groups = {};
      data.forEach(row => { const id = String(row.grid_id); (groups[id] ??= []).push(row); });
      const geojson = buildGeoJSON(groups);
      renderChoropleth(geojson);
    },
    error: err => { console.error('CSV load error', err); alert('Failed to load CSV: ' + err.message); }
  });


  // ******************** //
  } else {

  // gets the csv dataset
  const csvUrl = 'grid_coords_with_weather.csv';

  // create the map inside the #map element
  const map = L.map('map', { zoomControl: false, attributionControl: false, preferCanvas: true }).setView([55.79, -4.55], 12);

  // add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '© OpenStreetMap contributors'}).addTo(map);

  // turn CSV rows into GeoJSON polygons
  function buildGeoJSON(groups) {
    const features = [];
    for (const gridId in groups) {
      const rows = groups[gridId].sort((a, b) => Number(a.point_order) - Number(b.point_order));
      const coords = rows.map(r => [Number(r.lon), Number(r.lat)]);

      // close the polygon if needed
      if (!(coords[0][0] === coords[coords.length - 1][0] && coords[0][1] === coords[coords.length - 1][1])) {
        coords.push(coords[0]);
      }

      // average the values for the cell
        const wind = rows.reduce((s, r) => s + Number(r.average_windspeed), 0) / rows.length;
        const lum = rows.reduce((s, r) => s + Number(r.average_luminosity), 0) / rows.length;
        const centerLat = rows.reduce((s, r) => s + Number(r.lat), 0) / rows.length;
        const centerLon = rows.reduce((s, r) => s + Number(r.lon), 0) / rows.length;

        features.push({type: 'Feature', properties: { grid_id: gridId, average_windspeed: wind, average_luminosity: lum, center_lat: centerLat, center_lon: centerLon }, geometry: { type: 'Polygon', coordinates: [coords] }});
      }
    return { type: 'FeatureCollection', features };
  }

  // draw the grid, style it, and lock view to the grid area
  function renderChoropleth(geojson) {
    const vals = geojson.features.map(f => f.properties.average_windspeed);
    const min = Math.min(...vals), max = Math.max(...vals);
    const scale = chroma.scale(['#440154','#3b528b','#21918c','#5ec962','#fde725']).domain([min, max]);

    if (!map.getPane('gridPane')) {
      map.createPane('gridPane');
      map.getPane('gridPane').style.zIndex = 400;
    }

    // create the geojson layer and keep a reference named 'geojsonLayer'
    const geojsonLayer = L.geoJSON(geojson, {
      pane: 'gridPane',
      style: feature => {
        const v = feature.properties.average_windspeed;
        return {fillColor: scale(v).hex(), weight: 0.8, color: '#000', opacity: 0.35, fillOpacity: 0.5};
      },

      // creates popup on click, and grid outline on mouseover
      onEachFeature: (feature, layer) => {
        const p = feature.properties;
        layer.bindPopup(`<b>Grid</b>: ${p.grid_id}<br><b>Windspeed</b>: ${p.average_windspeed}`);
        layer.on({
          mouseover(e) { e.target.setStyle({ weight: 1.8, color: '#111' }); },
          mouseout(e) { geojsonLayer.resetStyle(e.target); },
          click(e) {
            const coords = `${p.center_lat.toFixed(6)}, ${p.center_lon.toFixed(6)}`;
            updateGridInfo(p.grid_id, coords, p.average_windspeed, p.average_luminosity);
          }
        });
      }
    }).addTo(map);

    // Fit to bounds and lock view
    const bounds = geojsonLayer.getBounds();
    map.fitBounds(bounds, { padding: [10, 10] });
    map.setMaxBounds(bounds.pad(0));
    const currentZoom = map.getZoom();
    map.setMinZoom(currentZoom);
    map.setMaxZoom(currentZoom);
    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();
    map.off();
  }

  // load the CSV, group rows by grid_id, then render
  Papa.parse(csvUrl, {
    download: true,
    header: true,
    complete: results => {
      const data = results.data.filter(r => r && r.grid_id);
      const groups = {};
      data.forEach(row => { const id = String(row.grid_id); (groups[id] ??= []).push(row); });
      const geojson = buildGeoJSON(groups);
      renderChoropleth(geojson);
    },
    error: err => { console.error('CSV load error', err); alert('Failed to load CSV: ' + err.message); }
  });
  }
});
</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php include 'includes/footer.php'; ?>
