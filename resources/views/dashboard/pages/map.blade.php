@extends('dashboard.layouts.index')


@section('konten')
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .header {
            height: 60px;
            background: #1f2937;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        #viewDiv {
            width: 100%;
            height: 100vh;
        }

        #layer-panel {
            position: absolute;
            top: 75px;
            right: 20px;
            z-index: 100;
            width: 260px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .layer-header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
            font-weight: bold;
        }

        .layer-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .layer-item:hover {
            background: #f5f5f5;
        }

        .layer-eye {
            width: 30px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 18px;
        }

        .layer-name {
            flex: 1;
            font-size: 13px;
        }
    </style>
    <main class="main users chart-page" id="skip-target">
        <div class="container">
            <div class="row">
                <h1>Peta Irigasi</h1>
            </div>

            <div id="layer-panel">
                <div class="layer-header">
                    <strong>Daftar Layer</strong>
                </div>

                <div id="layer-list"></div>
            </div>
            <div id="viewDiv" style="height: 100vh; width: 100%;"></div>

        </div>
    </main>
    <script type="module">
        import Map from "https://js.arcgis.com/4.31/@arcgis/core/Map.js";
        import MapView from "https://js.arcgis.com/4.31/@arcgis/core/views/MapView.js";
        import GraphicsLayer from "https://js.arcgis.com/4.31/@arcgis/core/layers/GraphicsLayer.js";
        import Graphic from "https://js.arcgis.com/4.31/@arcgis/core/Graphic.js";
        import Point from "https://js.arcgis.com/4.31/@arcgis/core/geometry/Point.js";

        const map = new Map({
            basemap: "streets-vector"
        });

        const view = new MapView({
            container: "viewDiv",
            map: map,
            center: [109.35, -0.08],
            zoom: 11
        });

        const surveyLayer = new GraphicsLayer({
            title: "Survey123 - Titik Survey"
        });

        map.add(surveyLayer);


        // Ambil data dari Laravel
        fetch("/arcgis/features/survey-points")
            .then(response => response.json())
            .then(data => {

                console.log("Data Survey:", data);

                data.features.forEach(feature => {

                    const geometry = feature.geometry;
                    const attributes = feature.attributes;

                    if (
                        !geometry ||
                        geometry.x === null ||
                        geometry.y === null ||
                        (geometry.x === 0 && geometry.y === 0)
                    ) {
                        return;
                    }

                    const point = {
                        type: "point",
                        longitude: geometry.x,
                        latitude: geometry.y
                    };

                    const graphic = new Graphic({
                        geometry: point,

                        // WAJIB: masukkan attributes
                        attributes: {
                            objectid: attributes.objectid,
                            globalid: attributes.globalid,
                            tanggal: attributes.tanggal,
                            luas_baku: attributes.luas_baku,
                            luas_potensial: attributes.luas_potensial,
                        },

                        symbol: {
                            type: "simple-marker",
                            color: [0, 180, 200],
                            outline: {
                                color: [255, 255, 255],
                                width: 1
                            }
                        },

                        popupTemplate: {
                            title: "Data Survey",

                            content: [{
                                type: "fields",

                                fieldInfos: [{
                                        fieldName: "luas_baku",
                                        label: "LUAS BAKU"
                                    },
                                    {
                                        fieldName: "luas_potensial",
                                        label: "LUAS POTENSIAL"
                                    },
                                    {
                                        fieldName: "tanggal",
                                        label: "TANGGAL",
                                        format: {
                                            dateFormat: "short-date"
                                        }
                                    }
                                ]
                            }]
                        }
                    });

                    surveyLayer.add(graphic);

                });


                // Zoom otomatis ke seluruh titik
                if (surveyLayer.graphics.length > 0) {

                    view.goTo(
                        surveyLayer.graphics.toArray()
                    );

                }

            })
            .catch(error => {
                console.error(
                    "Gagal mengambil data Survey123:",
                    error
                );
            });
    </script>
    <script>
        require([
            "esri/Map",
            "esri/views/MapView",
            "esri/layers/GraphicsLayer",
            "esri/Graphic",
            "esri/geometry/Polygon"
        ], function(Map, MapView, GraphicsLayer, Graphic, Polygon) {

            const map = new Map({
                basemap: "topo-vector"
            });

            const view = new MapView({
                container: "viewDiv",
                map: map,
                center: [112.3121, 0.6183],
                zoom: 12
            });

            const layerObjects = [];

            fetch('/arcgis/features')
                .then(response => response.json())
                .then(data => {

                    if (!data.features || data.features.length === 0) {
                        console.warn("Data shape tidak ditemukan.");
                        return;
                    }

                    let shapeNumber = 0;

                    data.features.forEach((feature, featureIndex) => {

                        const rings = feature.geometry?.rings ?? [];

                        // Setiap ring dibuat sebagai shape terpisah.
                        rings.forEach((ring, ringIndex) => {

                            shapeNumber++;

                            const graphicsLayer = new GraphicsLayer({
                                title: `Shape ${shapeNumber}`,
                                visible: true
                            });

                            const polygon = new Polygon({
                                rings: [ring],
                                spatialReference: {
                                    wkid: 4326
                                }
                            });

                            const graphic = new Graphic({
                                geometry: polygon,
                                attributes: feature.attributes,
                                symbol: {
                                    type: "simple-fill",
                                    color: [0, 150, 100, 0.35],
                                    outline: {
                                        color: [0, 100, 70, 1],
                                        width: 2
                                    }
                                },
                                popupTemplate: {
                                    title: `Shape ${shapeNumber}`,
                                    content: `Luas: ${feature.attributes.luas ?? '-'}`
                                }
                            });

                            graphicsLayer.add(graphic);
                            map.add(graphicsLayer);

                            layerObjects.push({
                                id: shapeNumber,
                                name: `Shape ${shapeNumber}`,
                                layer: graphicsLayer,
                                visible: true
                            });

                        });
                    });

                    renderLayerPanel();

                    // Zoom ke seluruh shape
                    const allGraphics = layerObjects.flatMap(item =>
                        item.layer.graphics.toArray()
                    );

                    if (allGraphics.length > 0) {
                        view.goTo(allGraphics);
                    }

                })
                .catch(error => {
                    console.error("Gagal memuat shape:", error);
                });


            // Membuat daftar layer
            function renderLayerPanel() {

                const layerList = document.getElementById('layer-list');

                layerList.innerHTML = '';

                layerObjects.forEach(item => {

                    const row = document.createElement('div');
                    row.className = 'layer-item';

                    const button = document.createElement('button');
                    button.className = 'layer-eye';

                    button.innerHTML = item.visible ? '👁️' : '🚫';
                    button.title = item.visible ?
                        'Sembunyikan shape' :
                        'Tampilkan shape';

                    const name = document.createElement('span');
                    name.className = 'layer-name';
                    name.textContent = item.name;

                    button.addEventListener('click', function() {

                        item.visible = !item.visible;

                        item.layer.visible = item.visible;

                        button.innerHTML = item.visible ? '👁️' : '🚫';

                        button.title = item.visible ?
                            'Sembunyikan shape' :
                            'Tampilkan shape';

                    });

                    row.appendChild(button);
                    row.appendChild(name);

                    layerList.appendChild(row);

                });
            }

        });
    </script>
@endsection
