$(document).ready(function(){

    //Sidenav en móviles
    $('.sidenav').sidenav();

    //Mensajes
    var input_tipo = $("input[name=tipo-mensaje]");
    var input_texto = $("input[name=texto-mensaje]");
    if (input_tipo.length && input_texto.length){
        //var contenido = $('<span class="'+ input_tipo.val() +'">'+ input_texto.val() +'</span>');
        M.toast({html: input_texto.val(), classes: input_tipo.val() + " lighten-5"});
    }

    //Ocultar toast
    $(".toast").click(function () {
        $(this).hide();
    });

    //Cambiar clave
    $("input[type=checkbox][name=cambiar_clave]").click(function () {
        $("#password").toggleClass( "hide" );
    });


    //Fecha
    $( ".datepicker" ).datepicker({
        firstDay: true,
        format: 'dd-mm-yyyy',
        i18n: {
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic"],
            weekdays: ["Domingo","Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
            weekdaysShort: ["Dom","Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            weekdaysAbbrev: ["D","L", "M", "M", "J", "V", "S"]
        }
    });
});