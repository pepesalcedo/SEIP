/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


   function limpiarCaracteres() {
    
    // función ñapa para que cambie el aspecto de la columna de comnandos !!! CUIDADO
    // consigue el texto y lo convierte a html, solo para los que todavia no se hayan codificado, es decir que el primer caracter sea raro
        $('.actionsGrid').each(function(){
        var $this = $(this);
        var html = $this.html();
        if (html != "" && html[0] != "<")
        {
            var t = $this.text();
            $this.html(t);
        }
        //$this.html(t.replace('&lt;a','<a').replace('a&gt;', 'a>'));
}); 

       // Si trabajamos con un selector le ponemos la primera opción y lo ocultamos
       $select = $('.grid_massactions').find('select');
       $select.val('0');
       $('.grid_massactions').hide();

       //ligarEventosGrid();     
   }
 
 
 function abrirLinkEnPopup(event, link)
 {
       event.preventDefault(); //cancela el comportamiento por defecto
       //var link = $(this);
       var target = link.attr("href");
        
        $('body').css({'cursor' : 'wait'});

        $('#detalles_float').html('Cargando...');
        $('#form-content').modal({show:true});

        $.ajax({
        type: 'get',
        url: target,
        success: function(data) {
            $('#detalles_float').html(data);
            // muestro la ventana
            //$('#form-content').modal({show:true});
            //var scopeAngular = angular.element('[ng-controller=formController]').scope();
            //scopeAngular.jqxWindowSettings.apply('open');
            $('body').css({'cursor' : 'default'});
            // tengo que ligarlos, sino al cerrar se pierden los eventos
            //ligarEventosGrid();


    }});

 }
 
 function deleteElement(event, link)
 {
        event.preventDefault(); //cancela el comportamiento por defecto
        
         if (!confirm('Esta seguro de eliminar este registro?'))
             return;
        //var link = $(this);
        var target = link.attr("href");
        
        $('body').css({'cursor' : 'wait'});

            $.ajax({
        type: 'post',
        url: target,
        success: function(data) {
            $('body').css({'cursor' : 'default'});
            if (data.success == true)
            {
                var mensaje = $('#mensajes');
                 mensaje.html('Elemento eliminado correctamente'); 
                 if (typeof reloadGridRecursosPersonas == 'function') {
                         reloadGridRecursosPersonas();
                         reloadGridRecursosVehiculos();                         
                 }
                 else if (typeof reloadCurrentGridPacientes == 'function')
                 {
                     reloadCurrentGridPacientes();
                     loadFirstPaciente();
                 } 
                 else
                 {
                    location.reload();
                 }
             }
             else
             {
                 $('#mensajes').html('Error eliminando registro, causa: ' + data.cause);
             }
           },
        fail: function(data){
            var mensaje = $('#mensajes');
             mensaje.html('Error de comunicación con el servidor, compruebe conexión e intentelo de nuevo'); 
             $('body').css({'cursor' : 'default'});
            
        }
        });

 }
 
 
 
 function ligarEventosGrid()
 {
     limpiarCaracteres(); 
  
    
        
        // Cuando selecionan en un grid y dan a enviar, se simula como si se envie un massAction del grid
        $('#actionSelect').one('click', function () {

            $forms = $('#gridSelect').find('form');

            $form = $($forms[0]);
            // submit form 
            var values = {};
            $.each( $form.serializeArray(), function(i, field) {
              values[field.name] = field.value;
            });

            $.ajax({
              type        : $form.attr( 'method' ),
              url         : $form.attr( 'action' ),
              data        : values,
              success     : function(data) {
                 var scopeAngular = angular.element('[ng-controller=formController]').scope();    
                 scopeAngular.jqxWindowSettings2.apply('close');
                 if (typeof reloadGridRecursosPersonas == 'function') {
                       reloadGridRecursosPersonas();
                       reloadGridRecursosVehiculos();
                 }
                 else {
                       location.reload();
                  }


              }
            });
    })

 }


