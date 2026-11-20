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

        /* =========================================
                                                                           ARCGIS POPUP - MAIN CONTAINER
                                                                        ========================================= */

        .esri-popup__main-container {
            min-width: 300px !important;
            max-width: 400px !important;
            max-height: 400px !important;
            background: #ffffff !important;
        }

        /* =========================================
                                                                           POPUP CONTENT VISIBILITY
                                                                        ========================================= */

        .esri-popup .esri-features__content-feature,
        .esri-popup .esri-features__heading,
        .esri-popup .esri-feature-content,
        .esri-popup .esri-feature__content-node,
        .esri-popup .esri-features__container {
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* =========================================
                                                                           POPUP TEXT
                                                                        ========================================= */

        .esri-popup .esri-features__heading {
            color: #323232 !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .esri-popup .esri-feature-content,
        .esri-popup .esri-feature-content * {
            color: #323232 !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
        }

        /* =========================================
                                                                           POPUP BACKGROUND
                                                                        ========================================= */

        .esri-popup .esri-features__content-feature,
        .esri-popup .esri-features__container {
            background: #ffffff !important;
        }


        .survey-popup {
            padding: 12px;
            color: #323232;
            font-size: 13px;
        }

        .survey-popup-section {
            margin-bottom: 18px;
        }

        .survey-popup-section h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1769aa !important;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .survey-popup-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 7px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .survey-popup-row span {
            color: #777777 !important;
            flex: 0 0 42%;
        }

        .survey-popup-row strong {
            color: #323232 !important;
            font-weight: 600;
            text-align: right;
            overflow-wrap: anywhere;
        }


        /* GALERI FOTO SURVEY123 */

        .survey-photo-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .survey-photo-item {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #eeeeee;
            border-radius: 8px;
            background: #ffffff;
        }

        .survey-photo-item img {
            display: block;
            width: 100%;
            height: 125px;
            object-fit: cover;
            border-radius: 7px;
            cursor: pointer;
        }

        .survey-photo-name {
            padding: 7px;
            font-size: 11px;
            color: #555555 !important;
            overflow-wrap: anywhere;
        }

        .survey-no-photo {
            padding: 15px;
            text-align: center;
            color: #777777 !important;
            background: #f5f5f5;
            border-radius: 8px;
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

        view.popupEnabled = true;

        // view.on("click", async function(event) {

        //     console.log("MAP DIKLIK:", event.mapPoint);

        //     const hit = await view.hitTest(event);

        //     console.log("HASIL HIT TEST:", hit.results);

        //     const graphicResult = hit.results.find(result =>
        //         result.graphic &&
        //         result.graphic.attributes &&
        //         result.graphic.attributes.objectid
        //     );

        //     if (!graphicResult) {
        //         console.warn("Graphic Survey123 tidak ditemukan pada titik ini.");
        //         return;
        //     }

        //     const graphic = graphicResult.graphic;

        //     console.log("GRAPHIC TERPILIH:", graphic);
        //     console.log("ATRIBUT:", graphic.attributes);
        //     console.log("POPUP TEMPLATE:", graphic.popupTemplate);
        //     view.popup.title = "POPUP TEST";

        //     view.popup.content =
        //         `
    //         <div style="padding: 15px; color: #222;">
    //             <h3>Data Survey Berhasil!</h3>
    //             <p>Object ID: ${graphic.attributes.objectid}</p>
    //             <p>Jenis Bangunan: ${graphic.attributes.jenis_bangunan}</p>
    //             <p>Fungsi Bangunan: ${graphic.attributes.fungsi_bangunan}</p>
    //             <p>Kondisi Bangunan: ${graphic.attributes.kondisi_bangunan}</p>
    //         </div>
    //     `;

        //     view.popup.open({
        //         location: graphic.geometry
        //     });
        //     console.log("POPUP DIBUKA:", view.popup.visible);

        // });
        view.on("click", async function(event) {

            const hit = await view.hitTest(event);

            const graphicResult = hit.results.find(result =>
                result.graphic &&
                result.graphic.attributes &&
                result.graphic.attributes.objectid
            );

            if (!graphicResult) {
                console.warn("Graphic tidak ditemukan.");
                return;
            }


            const graphic = graphicResult.graphic;
            const attr = graphic.attributes;

            console.log("OBJECTID:", attr.objectid);

            // Ambil foto berdasarkan OBJECTID
            const photoHTML = await getSurveyPhotos(attr.objectid);

            // Buat konten popup
            const popupHTML =
                `
                    <div class="survey-popup">

                        <h4>Informasi Bangunan</h4>

                        <div class="survey-popup-row">
                            <span>Jenis Bangunan</span>
                            <strong>${attr.jenis_bangunan ?? "-"}</strong>
                        </div>

                        <div class="survey-popup-row">
                            <span>Tipe Bangunan</span>
                            <strong>${attr.tipe_bangunan ?? "-"}</strong>
                        </div>

                        <div class="survey-popup-row">
                            <span>Fungsi Bangunan</span>
                            <strong>${attr.fungsi_bangunan ?? "-"}</strong>
                        </div>

                        <div class="survey-popup-row">
                            <span>Kondisi Bangunan</span>
                            <strong>${attr.kondisi_bangunan ?? "-"}</strong>
                        </div>

                        <div class="survey-popup-row">
                            <span>Tanggal Survei</span>
                            <strong>
                                ${attr.tanggal_survei
                                    ? new Date(attr.tanggal_survei)
                                        .toLocaleDateString("id-ID")
                                    : "-"}
                            </strong>
                        </div>

                        <hr>

                        <h4>Dokumentasi Foto (${attr.objectid})</h4>

                        ${photoHTML}

                    </div>
                `;

            // Perbarui konten Graphic
            graphic.popupTemplate = {
                title: "Detail Survei Bangunan",
                content: popupHTML
            };

            // Buka popup
            view.popup.open({
                features: [graphic],
                location: graphic.geometry
            });

            console.log("POPUP DIBUKA:", view.popup.visible);

            // Periksa DOM setelah popup dibuka
            setTimeout(() => {

                const popup = document.querySelector(".esri-popup");

                console.log("POPUP OUTER HTML:", popup?.outerHTML);

                console.log("POPUP INNER HTML:", popup?.innerHTML);

                console.log("SHADOW ROOT:", popup?.shadowRoot);

                console.log(
                    "SHADOW HTML:",
                    popup?.shadowRoot?.innerHTML
                );

                console.log(
                    "SEMUA ELEMEN DI POPUP:",
                    popup?.querySelectorAll("*")
                );
                console.log("POPUP VISIBLE:", view.popup.visible);

                console.log("POPUP TITLE:", view.popup.title);

                console.log("POPUP CONTENT:", view.popup.content);

                console.log("POPUP SELECTED FEATURE:", view.popup.selectedFeature);

                console.log("POPUP FEATURES:", view.popup.features);
                // console.log("CONTENT STYLE:", content ? {
                //     display: getComputedStyle(content).display,
                //     visibility: getComputedStyle(content).visibility,
                //     opacity: getComputedStyle(content).opacity,
                //     color: getComputedStyle(content).color,
                //     fontSize: getComputedStyle(content).fontSize,
                //     height: content.getBoundingClientRect().height
                // } : "ELEMENT TIDAK DITEMUKAN");

            }, 300);

        });
        const surveyLayer = new GraphicsLayer({
            title: "Survey123 - Titik Survey",
            visible: true,
            popupEnabled: true
        });

        map.add(surveyLayer);

        async function getSurveyPhotos(objectId) {
            try {
                const response = await fetch(
                    `/arcgis/features/survey-points/${objectId}/attachments`
                );

                if (!response.ok) {
                    throw new Error("Gagal mengambil foto Survey123");
                }

                const result = await response.json();

                if (!result.status || !result.attachments?.length) {
                    return `<div class="survey-no-photo">
                Belum ada foto dokumentasi.
            </div>`;
                }

                return `
            <div class="survey-photo-grid">
                ${result.attachments.map(photo => `
                                <div class="survey-photo-item">
                                    <a href="${photo.url}" target="_blank" rel="noopener">
                                        <img
                                            src="${photo.url}"
                                            alt="${photo.name}"
                                            loading="lazy"
                                        >
                                    </a>
                                    <div class="survey-photo-name">
                                        ${photo.name}
                                    </div>
                                </div>
                            `).join("")}
            </div>
        `;

            } catch (error) {
                console.error("SURVEY PHOTO ERROR:", error);

                return `<div class="survey-no-photo">
            Foto dokumentasi gagal dimuat.
        </div>`;
            }
        }

        // ==========================================
        // FORMAT DATA
        // ==========================================

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


                    // ==========================================
                    // FORMAT DATA SURVEY123
                    // ==========================================

                    const attr = feature.attributes;

                    function formatTanggal(timestamp) {
                        if (!timestamp) return "-";

                        return new Date(timestamp).toLocaleDateString("id-ID", {
                            day: "2-digit",
                            month: "long",
                            year: "numeric"
                        });
                    }

                    function formatWaktu(timestamp) {
                        if (!timestamp) return "-";

                        return new Date(timestamp).toLocaleString("id-ID", {
                            day: "2-digit",
                            month: "long",
                            year: "numeric",
                            hour: "2-digit",
                            minute: "2-digit"
                        });
                    }

                    function escapeHTML(value) {
                        return String(value ?? "-").replace(/[&<>"']/g, char => ({
                            "&": "&amp;",
                            "<": "&lt;",
                            ">": "&gt;",
                            '"': "&quot;",
                            "'": "&#039;"
                        })[char]);
                    }

                    // ==========================================
                    // POPUP SURVEY123
                    // ==========================================

                    const popupContent = `
                        <div class="survey-popup">

                            <div class="survey-popup-section">
                                <h4>Informasi Bangunan</h4>

                                <div class="survey-popup-row">
                                    <span>ID Survey</span>
                                    <strong>${escapeHTML(attr.objectid)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Jenis Bangunan</span>
                                    <strong>${escapeHTML(attr.jenis_bangunan)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Tipe Bangunan</span>
                                    <strong>${escapeHTML(attr.tipe_bangunan)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Fungsi Bangunan</span>
                                    <strong>${escapeHTML(attr.fungsi_bangunan)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Kondisi Bangunan</span>
                                    <strong>${escapeHTML(attr.kondisi_bangunan)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Tanggal Survei</span>
                                    <strong>${formatTanggal(attr.tanggal_survei)}</strong>
                                </div>
                            </div>

                            <div class="survey-popup-section">
                                <h4>Informasi Administrasi</h4>

                                <div class="survey-popup-row">
                                    <span>Global ID</span>
                                    <strong>${escapeHTML(attr.globalid)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Dibuat Oleh</span>
                                    <strong>${escapeHTML(attr.Creator)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Waktu Pembuatan</span>
                                    <strong>${formatWaktu(attr.CreationDate)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Diedit Oleh</span>
                                    <strong>${escapeHTML(attr.Editor)}</strong>
                                </div>

                                <div class="survey-popup-row">
                                    <span>Waktu Perubahan</span>
                                    <strong>${formatWaktu(attr.EditDate)}</strong>
                                </div>
                            </div>

                        </div>
                    `;



                    // ==========================================
                    // CREATE GRAPHIC
                    // ==========================================

                    const graphic = new Graphic({
                        geometry: {
                            type: "point",
                            longitude: feature.geometry.x,
                            latitude: feature.geometry.y
                        },

                        attributes: attr,

                        symbol: {
                            type: "simple-marker",
                            color: "#00bcd4",
                            size: 12,
                            outline: {
                                color: "#ffffff",
                                width: 2
                            }
                        },

                        popupTemplate: {
                            title: "Detail Survei Bangunan",
                            content: popupContent
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
                zoom: 14
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
