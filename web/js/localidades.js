/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    function preparaComboCities(strPathCities)
    {
        $(function(){
            $("#centroatencion_provincia").change(function(){
            var data = {
                province_id: $(this).val()
            };

            $.ajax({
                type: 'post',
                url: strPathCities,
                data: data,
                success: function(data) {
                    var $city_selector = $('#centroatencion_localidad');

                    $city_selector.html('<option value>Ciudad</option>');

                    var myArray = jQuery.parseJSON(data);
                    for (var i=0, total = myArray.length; i < total; i++) {
                        $city_selector.append('<option value="' + myArray[i].id + '">' + myArray[i].name + '</option>');
                    }
                }
            });
        });  
        
        
        $("#servicio_provincia").change(function(){
            var data = {
                province_id: $(this).val()
            };

            $.ajax({
                type: 'post',
                url: strPathCities,
                data: data,
                success: function(data) {
                    var $city_selector = $('#servicio_localidad');

                    $city_selector.html('<option>Ciudad</option>');

                    var myArray = jQuery.parseJSON(data);
                    for (var i=0, total = myArray.length; i < total; i++) {
                        $city_selector.append('<option value="' + myArray[i].id + '">' + myArray[i].name + '</option>');
                    }
                }
            });
        }); 
        
        });
    }
    
    
    
    function preparaComboCentros(strPathCentro)
    {
        $(function(){
        $("#servicio_ubicacion").change(function(){
            var data = {
                tipo_id: $(this).val()
            };

            $.ajax({
                type: 'post',
                url: strPathCentro,
                data: data,
                success: function(data) {
                    var $centro_selector = $('#servicio_centroAtencion');

                    $centro_selector.html('<option>Centro Atención</option>');

                    var myArray = jQuery.parseJSON(data);
                    for (var i=0, total = myArray.length; i < total; i++) {
                        $centro_selector.append('<option value="' + myArray[i].id + '">' + myArray[i].descripcion + '</option>');
                    }
                }
            });
        }); 
        
        });
    }    