/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function postForm( $form, callback ){
 
  var values = {};
  $.each( $form.serializeArray(), function(i, field) {
    values[field.name] = field.value;
  });
 
  $.ajax({
    type        : $form.attr( 'method' ),
    url         : $form.attr( 'action' ),
    data        : values,
    success     : function(data) {
      callback( data );
    }
  });
 
}
 
function EnviarFormularioServicioCompleto()
{
    // Se envia primero el formulario del paciente para asegurar que se guardan todos los cambios de paciente primero
    postForm ($("form[name='pacienteForm']"), function( response ){
        if (response.success)
        {
            // Si todo va bien, se envia ahora el formulario del servicio 
            postForm( $("form[name='servicioForm']"), function( response ){
             if (response.success)
             {
                 location.reload();  
             }
             else
            {
                alert(response.cause);
                    //$('#mensajeError').html('Error' + response.cause);
            }             
             });
        }
        else
        {
            alert("Error: " +response.cause);

            //$('#mensajeError').html('Error' + response.cause);
        }             
    });    
}

/**
 * Esta función hace la llamada del submit de un form por ajax, si hay algún error se muestra en el div de mensajeError
 * @returns {undefined}
 */
function fireFormEvents ()
{
 
 
  
   $(document).off('submit');
    //$('form').submit( function( e ){
   $('form').on('submit',function(e){

    e.preventDefault();
 
    // Compruebo si existe el form de paciente y si es así lo grabo primero
    if ($(this).attr("name") === 'servicioForm')
    {
           EnviarFormularioServicioCompleto();

           return;
    }
       
    postForm( $(this), function( response ){
        if (response.success)
        {
            if (response.cause === 'paciente')
            {
                // si vengo de pacientes, no lo tenogo que recargar todo, solo debo recargar el grid de pacientes
                if (typeof reloadGridPacientes === 'function') {
                        reloadGridPacientes(response.id);
                }
                loadPaciente(response.id);
            }
            else
            {
                location.reload();   
            }
        }
        else
        {
            //var datos = response.htmlReturned.toString();
            //$('#detalles_float').html(datos);
            alert(response.cause);

            //$('#mensajeError').html('Error' + response.cause);
        }
    });
 
    return false;
  });
 
};






