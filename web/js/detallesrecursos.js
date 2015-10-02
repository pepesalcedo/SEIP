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
 
        $(document).ready(function(){


          $('form').submit( function( e ){
            e.preventDefault();

            postForm( $(this), function( response ){
                if (response.success)
                {
                    $('#form-content').modal('toggle');
                    reloadGridRecursos();
                    
                    //location.reload();            
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

        });

        

