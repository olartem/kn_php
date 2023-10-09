<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileType = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if ($fileType === "kml" || $fileType === "gpx") {
        $uploadedFile = $_FILES["file"]["tmp_name"];

        // Use DOMDocument to parse the uploaded file
        $dom = new DOMDocument();
        $dom->load($uploadedFile);

        if ($fileType === "kml") {
            // Parse KML and extract data
            $coordinates = [];
            $placemarks = $dom->getElementsByTagName('Placemark');
            foreach ($placemarks as $placemark) {
                $coordinatesNode = $placemark->getElementsByTagName('coordinates')->item(0);
                if ($coordinatesNode) {
                    $coordinates[] = $coordinatesNode->nodeValue;
                }
            }

            // Convert to GPX format
            $gpxContent = '<?xml version="1.0" encoding="UTF-8"?>
<gpx version="1.1" xmlns="http://www.topografix.com/GPX/1/1">
  <trk>
    <name>KML to GPX Conversion</name>
    <trkseg>';
            foreach ($coordinates as $coord) {
                list($longitude, $latitude, $altitude) = explode(',', $coord);
                $gpxContent .= "<trkpt lat=\"$latitude\" lon=\"$longitude\"><ele>$altitude</ele></trkpt>";
            }
            $gpxContent .= '</trkseg>
  </trk>
</gpx>';

            // Save the converted GPX content to a new file or send as response
            header('Content-Type: application/gpx+xml');
            header('Content-Disposition: attachment; filename="converted.gpx"');
            echo $gpxContent;
        } elseif ($fileType === "gpx") {
            // Parse GPX and extract data
            $coordinates = [];
            $trackPoints = $dom->getElementsByTagName('trkpt');
            foreach ($trackPoints as $point) {
                $latitude = $point->getAttribute('lat');
                $longitude = $point->getAttribute('lon');
                $elevationNode = $point->getElementsByTagName('ele')->item(0);
                $elevation = $elevationNode ? $elevationNode->nodeValue : '';
                $coordinates[] = "$longitude,$latitude,$elevation";
            }

            // Convert to KML format
            $kmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
  <Document>
    <name>GPX to KML Conversion</name>';
            foreach ($coordinates as $coord) {
                list($longitude, $latitude, $elevation) = explode(',', $coord);
                $kmlContent .= "<Placemark>
                                <Point><coordinates>$longitude,$latitude,$elevation</coordinates></Point>
                                </Placemark>";
            }
            $kmlContent .=
 '</Document>
</kml>';

            // Save the converted KML content to a new file or send as response
            header('Content-Type: application/vnd.google-earth.kml+xml');
            header('Content-Disposition: attachment; filename="converted.kml"');
            echo $kmlContent;
        }
    } else {
        echo "Invalid file format. Please upload a KML or GPX file.";
    }
}

