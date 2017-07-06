(function () {
    'use strict';
    angular.module('index', []);

    angular.module('index').controller('indexController', ctrlIndex);
    ctrlIndex.$inject = ['$http','$location'];
    function ctrlIndex($http, $location) {
        var controller = this;

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

        controller.login = {};
        controller.login.enviar = enviar;
        controller.login.usuario = "";
        controller.login.contrasena = "";


        function enviar() {
            if ((controller.login.usuario !== "" && controller.login.usuario !== undefined) &&
                    (controller.login.contrasena !== "" && controller.login.contrasena !== undefined)) {
                $.notifyClose();
                var params = {};
                params.usuario = controller.login.usuario;
                params.contrasena = controller.login.contrasena;
                var loginJson = JSON.stringify(params);
                controller.webServiceCall('ws/wsLogin.php', loginJson).then(function (data) {
                    if (data.data == '99') {
                        errorMsg("Error al conectarse con la base de datos, favor de intentarlo nuevamente");
                        return;
                    }
                    var usuario = data.data;
                    if (usuario == "0") {
                        errorMsg("Usuario y/o contraseña incorrectos, favor de verificarlos");
                        return;
                    }
                    
                    window.location.replace("views/home.html");
                    controller.login.usuario = "";
                    controller.login.contrasena = "";
                    
                    

                });

            } else {
                errorMsg("Favor de llenar los campos usuario y contraseña");
            }
        }
        ;

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
    }

})();