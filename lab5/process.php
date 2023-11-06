<?php
function latLongToStr($latitude, $longitude, $depth) {
    $min_lat = -90.0;
    $max_lat = 90.0;
    $min_lon = -180.0;
    $max_lon = 180.0;

    $result = '';
    for ($i = 0; $i < $depth; $i++) {
        $lat_mid = ($min_lat + $max_lat) / 2;
        $lon_mid = ($min_lon + $max_lon) / 2;

        if ($longitude < $lon_mid && $latitude > $lat_mid) {
            $result .= '0';
            $max_lon = $lon_mid;
            $min_lat = $lat_mid;
        }

        if ($longitude > $lon_mid && $latitude > $lat_mid) {
            $result .= '1';
            $min_lon = $lon_mid;
            $min_lat = $lat_mid;
        }

        if ($longitude < $lon_mid && $latitude < $lat_mid) {
            $result .= '2';
            $max_lon = $lon_mid;
            $max_lat = $lat_mid;
        }

        if ($longitude > $lon_mid && $latitude < $lat_mid) {
            $result .= '3';
            $min_lon = $lon_mid;
            $max_lat = $lat_mid;
        }
    }

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileType = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if ($fileType === "kml" || $fileType === "gpx") {
        $uploadedFile = $_FILES["file"]["tmp_name"];
        $dom = new DOMDocument();
        $dom->load($uploadedFile);

        $coordinates = [];

        if ($fileType === "kml") {
            // Parse KML and extract coordinates
            $placemarks = $dom->getElementsByTagName('Placemark');
            foreach ($placemarks as $placemark) {
                $coordinatesNode = $placemark->getElementsByTagName('coordinates')->item(0);
                if ($coordinatesNode) {
                    $coords = explode(',', $coordinatesNode->nodeValue);
                    $coordinates[] = ['longitude' => (float)$coords[0], 'latitude' => (float)$coords[1]];
                }
            }
        } elseif ($fileType === "gpx") {
            // Parse GPX and extract coordinates
            $trackPoints = $dom->getElementsByTagName('trkpt');
            foreach ($trackPoints as $point) {
                $longitude = (float)$point->getAttribute('lon');
                $latitude = (float)$point->getAttribute('lat');
                $coordinates[] = ['longitude' => $longitude, 'latitude' => $latitude];
            }
        }

        $xml = new SimpleXMLElement('<coordinates></coordinates>');

        foreach ($coordinates as $coord) {
            $str = latLongToStr($coord['latitude'], $coord['longitude'], 18);

            $coordinate = $xml->addChild('coordinate');
            $coordinate->addChild('longitude', $coord['longitude']);
            $coordinate->addChild('latitude', $coord['latitude']);
            $coordinate->addChild('character_string', $str);
        }

        // Save the XML file with the same name as the uploaded file
        $outputFileName = 'generated_' . strtoupper(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION)) . '.xml';
        $xml->asXML($outputFileName);

        // Return the generated file for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $outputFileName);
        readfile($outputFileName);
    } else {
        echo "Invalid file format. Please upload a KML or GPX file.";
    }
}

