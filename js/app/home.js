(function () {
    'use strict';
    angular.module('home', ['ui.grid']);

    angular.module('home').controller('indexHome', ctrlHome);
    ctrlHome.$inject = ['$http', '$scope'];

    function ctrlHome($http, $scope) {

        $scope.gridOptions = {
            enableFiltering: true,
            enableColumnMenus: false,
            columnDefs: [
                {field: 'nombre', displayName: 'Nombre', enableHiding: false},
                {field: 'rfc', displayName: 'RFC', enableHiding: false}
            ]
        };

        Chart.defaults.global.defaultFontColor = 'black';
        Chart.defaults.global.defaultFontStyle = 'bold';
        var controller = this;
        var barTecnologiaCreada = false;
        var barIdcRegionTipoCreado = false;

        controller.mostrar = false;
        controller.app = {};

        controller.app.gridClientes = false;
        controller.app.pantalla = 0;
        controller.app.tecno = false;
        controller.app.verIdcRegionTipo = false;
        controller.app.mostrarRegionStatus = false;
        controller.app.statusCreada = false;

        controller.app.datosGenerales = {
            datasets: [{
                    data: [],
                    backgroundColor: []
                }],
            labels: []
        };

        controller.app.datosGeneralesRegion = {
            datasets: [{
                    data: [],
                    backgroundColor: []
                }],
            labels: []
        };

        controller.app.datosGeneralesRegionCerrado = {
            datasets: [{
                    data: [],
                    backgroundColor: []
                }],
            labels: []
        };

        controller.app.datosGeneralesStatus = {
            datasets: [{
                    data: [],
                    backgroundColor: []
                }],
            labels: []
        };

        controller.app.datosGeneralesTecnologiaTipo = {
            datasets: [{
                    label: "Totales",
                    data: [],
                    backgroundColor: []
                }],
            labels: []
        };


        controller.app.datosGeneralesTecnologiaRegion = {
            datasets: [{
                    label: "",
                    data: [],
                    backgroundColor: []
                }],
            labels: []
        };


        var pieGeneral = document.getElementById('pieGeneral').getContext('2d');
        var pieGeneralRegion = document.getElementById('pieGeneralRegion').getContext('2d');
        var pieGeneralRegionCerrados = document.getElementById('pieGeneralRegionCerrados').getContext('2d');
        var pieGeneralStatus = document.getElementById('pieGeneralStatus').getContext('2d');
        var pieTecnologiaTipo = document.getElementById('pieTecnologiaTipo').getContext('2d');
        var pieTecnologiaRegion = document.getElementById('pieTecnologiaRegion').getContext('2d');
        var pieIdcRegionTipo = document.getElementById('pieIdcRegionTipo').getContext('2d');


        controller.webServiceCall = function (servicePath, params) {
            var head = {headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}};
            var response;
            var wsURL = servicePath;
            if (params != null)
                response = $http.post(wsURL, params, head);
            else
                response = $http.post(wsURL, head);
            return response;
        };

        function validarSesion() {
            var params = {};
            params.accion = "validarSesion";
            return controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {

                if (data.data == "99") {
                    window.location.replace("../index.html");
                }
                controller.app.datosGenerales.usuario = data.data[0];
                return data.data;
            });
        }
        validarSesion();

        controller.app.menu = function () {
            $("#wrapper").toggleClass("toggled");

            if ($("#main").hasClass("main")) {
                $("#main").removeClass("main");
                $("#main").addClass("mainFull");
            } else {
                $("#main").removeClass("mainFull");
                $("#main").addClass("main");
            }

        }

        controller.app.cerrarSesion = function () {
            var params = {};
            params.accion = "cerrarSesion";
            controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                if (data.data != "99") {
                    window.location.replace("../index.html");
                }
            });
        }

        controller.app.cambioPantalla = function (id) {
            if (controller.app.pantalla != id) {
                controller.app.pantalla = id;
                switch (id) {
                    case 0:
                        $("#menuDG").addClass("selected");
                        $("#menuST").removeClass("selected");
                        break;
                    case 1:
                        $("#menuDG").removeClass("selected");
                        $("#menuST").addClass("selected");
                        break;
                }
            }
        }

        controller.app.consultaGeneral = function () {
            validarSesion().then(function (data) {
                if (data != "99") {
                    var params = {};
                    params.accion = "consultaGeneral";
                    controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                        controller.app.totalGeneral = 0;
                        for (var x = 0; x < data.data.length; x++) {
                            controller.app.totalGeneral += parseInt(data.data[x].totalMes);
                            controller.app.datosGenerales.datasets[0].data.push(data.data[x].totalMes);
                            switch (data.data[x].mes) {
                                case "1":
                                    controller.app.datosGenerales.labels.push("Enero");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(143, 36, 22)');
                                    break;
                                case "2":
                                    controller.app.datosGenerales.labels.push("Febrero");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(255, 140, 10)');
                                    break;
                                case "3":
                                    controller.app.datosGenerales.labels.push("Marzo");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(155, 4, 52)');
                                    break;
                                case "4":
                                    controller.app.datosGenerales.labels.push("Abril");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(50, 50, 50)');
                                    break;
                                case "5":
                                    controller.app.datosGenerales.labels.push("Mayo");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(52, 255, 178)');
                                    break;
                                case "6":
                                    controller.app.datosGenerales.labels.push("Junio");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(92, 194, 232)');
                                    break;
                                case "7":
                                    controller.app.datosGenerales.labels.push("Julio");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(40, 63, 232)');
                                    break;
                                case "8":
                                    controller.app.datosGenerales.labels.push("Agosto");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(186, 6, 232)');
                                    break;
                                case "9":
                                    controller.app.datosGenerales.labels.push("Septiembre");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(143, 36, 22)');
                                    break;
                                case "10":
                                    controller.app.datosGenerales.labels.push("Octubre");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(155, 4, 52)');
                                    break;
                                case "11":
                                    controller.app.datosGenerales.labels.push("Noviembre");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(50, 50, 50)');
                                    break;
                                case "12":
                                    controller.app.datosGenerales.labels.push("Diciembre");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(255, 76, 193)');
                                    break;
                                default:
                                    controller.app.datosGenerales.labels.push("N/A");
                                    controller.app.datosGenerales.datasets[0].backgroundColor.push('rgb(250, 32, 255)');
                                    break;
                            }
                        }
                        Chart.defaults.pie.click = null;
                        new Chart(pieGeneral, {
                            type: 'pie',
                            data: controller.app.datosGenerales,
                            options: Chart.defaults.pie
                        });
                    });
                }
            });
        }
        controller.app.consultaGeneral();

        controller.app.consultaGeneralRegion = function () {
            validarSesion().then(function (data) {
                if (data != "99") {
                    var params = {};
                    params.accion = "consultaGeneralRegion";
                    controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                        controller.app.totalGeneralRegion = 0;

                        for (var x = 0; x < data.data.length; x++) {
                            controller.app.totalGeneralRegion += parseInt(data.data[x].total);
                            controller.app.datosGeneralesRegion.datasets[0].data.push(data.data[x].total);
                            controller.app.datosGeneralesRegion.labels.push(data.data[x].region);
                            var r = getRandomInt(0, 155);
                            var g = getRandomInt(0, 155);
                            var b = getRandomInt(0, 155);
                            var color = "rgb(" + r + "," + g + "," + b + ")";
                            controller.app.datosGeneralesRegion.datasets[0].backgroundColor.push(color);
                        }

                        Chart.defaults.pie.click = null;
                        new Chart(pieGeneralRegion, {
                            type: 'pie',
                            data: controller.app.datosGeneralesRegion,
                            options: Chart.defaults.pie
                        });
                    });
                }
            });
        }
        controller.app.consultaGeneralRegion();

        controller.app.consultaGeneralStatus = function () {
            validarSesion().then(function (data) {
                if (data != "99") {
                    var params = {};
                    params.accion = "consultaGeneralStatus";
                    controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                        controller.app.totalGeneralStatus = 0;
                        var aux = angular.copy(data.data);
                        var info = [];


                        for (var x = 0; x < aux.length; x++) {
                            if (aux[x].status == "EJECUCION") {
                                if (info.length == 0) {
                                    info.push(aux[x]);
                                    info[0].total = parseInt(info[0].total);
                                } else {
                                    info[0].total += parseInt(aux[x].total);
                                }
                            }
                        }

                        for (var x = 0; x < aux.length; x++) {
                            if (aux[x].status != "EJECUCION") {
                                info.push(aux[x]);
                            }
                        }

                        for (var x = 0; x < info.length; x++) {
                            controller.app.totalGeneralStatus += parseInt(info[x].total);
                            controller.app.datosGeneralesStatus.datasets[0].data.push(info[x].total);
                            controller.app.datosGeneralesStatus.labels.push(info[x].status);
                            var r = getRandomInt(0, 155);
                            var g = getRandomInt(0, 155);
                            var b = getRandomInt(0, 155);
                            var color = "rgb(" + r + "," + g + "," + b + ")";
                            controller.app.datosGeneralesStatus.datasets[0].backgroundColor.push(color);
                        }

                        var opt = angular.copy(Chart.defaults.pie);
                        opt.onClick = clickStatus;
                        new Chart(pieGeneralStatus, {
                            type: 'pie',
                            data: controller.app.datosGeneralesStatus,
                            options: opt
                        });
                    });
                }
            });
        }
        controller.app.consultaGeneralStatus();

        controller.app.consultaTecnologiaTipo = function () {
            validarSesion().then(function (data) {
                if (data != "99") {
                    var params = {};
                    params.accion = "consultaTecnologiaTipo";
                    controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                        controller.app.totalTecnologiaTipo = 0;
                        for (var x = 0; x < data.data.length; x++) {
                            controller.app.totalTecnologiaTipo += parseInt(data.data[x].total);
                            controller.app.datosGeneralesTecnologiaTipo.datasets[0].data.push(data.data[x].total);
                            controller.app.datosGeneralesTecnologiaTipo.labels.push(data.data[x].tipo);
                            var r = getRandomInt(0, 155);
                            var g = getRandomInt(0, 155);
                            var b = getRandomInt(0, 155);
                            var color = "rgb(" + r + "," + g + "," + b + ")";
                            controller.app.datosGeneralesTecnologiaTipo.datasets[0].backgroundColor.push(color);
                        }

                        var opt = angular.copy(Chart.defaults.bar);
                        opt.onClick = clickTipo;

                        new Chart(pieTecnologiaTipo, {
                            type: 'bar',
                            data: controller.app.datosGeneralesTecnologiaTipo,
                            options: opt
                        });

                    });
                }
            });
        }
        controller.app.consultaTecnologiaTipo();



        function clickStatus(p, e) {
            if (e.length > 0) {
                controller.app.mostrarRegionStatus = true;
                controller.app.regionStatus = e[0]._view.label;
                if (e.length == 1) {
                    validarSesion().then(function (data) {
                        if (data != "99") {
                            var params = {};
                            params.accion = "consultaGeneralRegionCerrados";
                            params.status = e[0]._view.label;
                            controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                                controller.app.totalGeneralRegionCerrado = 0;
                                controller.app.datosGeneralesRegionCerrado.labels = [];
                                controller.app.datosGeneralesRegionCerrado.datasets[0].data = [];
                                controller.app.datosGeneralesRegionCerrado.datasets[0].backgroundColor = [];
                                for (var x = 0; x < data.data.length; x++) {
                                    controller.app.totalGeneralRegionCerrado += parseInt(data.data[x].total);
                                    controller.app.datosGeneralesRegionCerrado.datasets[0].data.push(data.data[x].total);
                                    controller.app.datosGeneralesRegionCerrado.labels.push(data.data[x].region);
                                    var r = getRandomInt(0, 155);
                                    var g = getRandomInt(0, 155);
                                    var b = getRandomInt(0, 155);
                                    var color = "rgb(" + r + "," + g + "," + b + ")";
                                    controller.app.datosGeneralesRegionCerrado.datasets[0].backgroundColor.push(color);
                                }

                                var opt = angular.copy(Chart.defaults.pie);
                                opt.onClick = clickRegionTipo;

                                if (!controller.app.statusCreada) {
                                    controller.app.statusCreada = true;
                                    controller.app.chartStatus = new Chart(pieGeneralRegionCerrados, {
                                        type: 'pie',
                                        data: controller.app.datosGeneralesRegionCerrado,
                                        options: opt
                                    });
                                } else {
                                    controller.app.chartStatus.destroy();
                                    controller.app.chartStatus = new Chart(pieGeneralRegionCerrados, {
                                        type: 'pie',
                                        data: controller.app.datosGeneralesRegionCerrado,
                                        options: opt
                                    });

                                }

                            });
                        }
                    });
                }
            }
        }

        function clickRegionTipo(p, e) {
            if (e.length > 0) {
                
                controller.app.gridClientes = true;
                var params = {};
                params.region = e[0]._view.label;
                params.status = controller.app.regionStatus;

                validarSesion().then(function (data) {
                    if (data != "99") {
                        params.accion = "consultaClientesStatusRegion";
                        controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {

                            $scope.gridOptions.data = data.data;

                        });
                    }
                });

            }
        }

        function clickTipo(p, e) {

            if (e.length > 0) {
                if (e[0]._view.datasetLabel == "Totales") {
                    controller.app.tecno = true;
                    if (e.length == 1) {
                        var params = {};
                        params.tipo = e[0]._view.label;
                        controller.app.label = params.tipo;
                        validarSesion().then(function (data) {
                            if (data != "99") {
                                params.accion = "consultaTecnologiaRegion";
                                controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                                    controller.app.datosGeneralesTecnologiaRegion = {
                                        datasets: [{

                                                data: [],
                                                backgroundColor: []
                                            }],
                                        labels: []
                                    };

                                    controller.app.totalTecnologiaTipoRegion = 0;
                                    for (var x = 0; x < data.data.length; x++) {
                                        controller.app.totalTecnologiaTipoRegion += parseInt(data.data[x].total);
                                        controller.app.datosGeneralesTecnologiaRegion.datasets[0].data.push(data.data[x].total);
                                        controller.app.datosGeneralesTecnologiaRegion.labels.push(data.data[x].region);
                                        var r = getRandomInt(0, 155);
                                        var g = getRandomInt(0, 155);
                                        var b = getRandomInt(0, 155);
                                        var color = "rgb(" + r + "," + g + "," + b + ")";
                                        controller.app.datosGeneralesTecnologiaRegion.datasets[0].backgroundColor.push(color);

                                    }

                                    var opt2 = angular.copy(Chart.defaults.pie);
                                    opt2.onClick = clickIdcRegionTipo;

                                    if (!barTecnologiaCreada) {
                                        barTecnologiaCreada = true;
                                        controller.app.chartTecnoRegion = new Chart(pieTecnologiaRegion, {
                                            type: 'pie',
                                            data: controller.app.datosGeneralesTecnologiaRegion,
                                            options: opt2
                                        });
                                    } else {
                                        controller.app.chartTecnoRegion.destroy();
                                        controller.app.chartTecnoRegion = new Chart(pieTecnologiaRegion, {
                                            type: 'pie',
                                            data: controller.app.datosGeneralesTecnologiaRegion,
                                            options: opt2
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
            }
        }

        function clickIdcRegionTipo(p, e) {
            if (e.length == 1) {
                controller.app.verIdcRegionTipo = true;

                var params = {};
                params.region = e[0]._view.label;
                controller.app.regionIdc = params.region;
                params.tipo = controller.app.label;
                params.accion = "consultaIdcRegionTipo";
                validarSesion().then(function (data) {
                    if (data != "99") {
                        controller.webServiceCall("../ws/wsHome.php", params).then(function (data) {
                            controller.app.datosGeneralesIdcRegionTipo = {
                                datasets: [{

                                        data: [],
                                        backgroundColor: []
                                    }],
                                labels: []
                            };

                            controller.app.totalTecnologiaIdcRegionTipo = data.data.length;
                            for (var x = 0; x < data.data.length; x++) {
                                controller.app.datosGeneralesIdcRegionTipo.datasets[0].data.push(data.data[x].total);
                                controller.app.datosGeneralesIdcRegionTipo.labels.push(data.data[x].nombre);
                                var r = getRandomInt(0, 155);
                                var g = getRandomInt(0, 155);
                                var b = getRandomInt(0, 155);
                                var color = "rgb(" + r + "," + g + "," + b + ")";
                                controller.app.datosGeneralesIdcRegionTipo.datasets[0].backgroundColor.push(color);

                            }


                            if (!barIdcRegionTipoCreado) {
                                barIdcRegionTipoCreado = true;
                                controller.app.chartIdcRegionTipo = new Chart(pieIdcRegionTipo, {
                                    type: 'pie',
                                    data: controller.app.datosGeneralesIdcRegionTipo,
                                    options: Chart.defaults.pie
                                });
                            } else {
                                controller.app.chartIdcRegionTipo.destroy();
                                controller.app.chartIdcRegionTipo = new Chart(pieIdcRegionTipo, {
                                    type: 'pie',
                                    data: controller.app.datosGeneralesIdcRegionTipo,
                                    options: Chart.defaults.pie
                                });
                            }
                        });
                    }
                });
            }
        }


        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
        function errorMsg(mensaje) {
            $.notifyClose();
            $.notify({
                icon: 'glyphicon glyphicon-warning-sign',
                message: mensaje
            }, {
                type: 'danger',
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: {
                    x: 0,
                    y: 90
                },
                delay: 1000
            });
        }

        controller.mostrar = true;
    }

})();