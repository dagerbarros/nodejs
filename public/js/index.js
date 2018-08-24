var $bandera=0;
$(document).ready(function(){
//    $('#form2').hide();
$('#olvido').click(function(){/*Visualiza el catalogo de Grupos.*/
        $('#Limpiar').click();
         mCargaModal('views/olvido.php', 'Olvido de Contraseña',60);
    });
    function mValidaRegistro(){
        var retornar=false;
        var element = $('#txt_correo').val().split('@');
        console.log(element);
        if(!$('#txt_nombre').val()){
          alert("Ingrese el nombre del usuario","advertencia");  
        }else if(!$('#txt_apellido').val()){
          alert("Ingrese el apellido del usuario","advertencia");   
        }else if(!$('#txt_cedula').val()){
          alert("Ingrese la cedula del usuario","advertencia"); 
        }else if(!$('#txt_correo').val()){
            alert("Ingrese un correo del usuario","advertencia");
        }else if(element[1]!='inces.gob.ve'){
            alert("Disculpe debe ingresar solo correo institucional","advertencia");
        }else {
            retornar=true;
        }
        return retornar;
     }
    
    function mValidaIngreso(){
        var retornar=false;
        if(!$('#txt_usuario').val()){
          alert("Ingrese el nombre del usuario","advertencia");  
        }else if(!$('#txt_passwd').val()){
          alert("Ingrese su contraseña","advertencia");
        }else {
            retornar=true;
        }
        return retornar;
     }
    $('#bton_ingresar').click(function (){
        console.log('aqui')
        if(mValidaIngreso()){
            $('#form1').submit();
        };
    });
    
    $('#bton_registrar').click(function (){
        console.log('aqui2')
        if(mValidaRegistro()){
            $('#form2').submit();
        };
    });
    $('#Limpiar').click(function (){
        $('#txt_usuario').val('');
        $('#txt_passwd').val('');
    });
});

// Toggle Function
$('.toggle').click(function(){
  // Switches the Icon
  $(this).children('i').toggleClass('fa-pencil');
  // Switches the forms  
  $('.form').animate({
     height: "toggle",
    'padding-top': 'toggle',
    'padding-bottom': 'toggle',
     opacity: "toggle"
  }, "slow");
});