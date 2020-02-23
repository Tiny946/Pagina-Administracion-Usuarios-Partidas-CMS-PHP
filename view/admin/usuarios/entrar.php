<h3>Acceso</h3>
<div class="row logincito">
    <form class="col m12 l14" method="POST">
        <div class="row dentroLogin">
            <div class="input-field col s12">
<!--                con esto nos borra los campos si nos equivocamos-->
                <input id="usuario" type="text" name="usuario" value="">
                <label for="usuario">Usuario</label>
            </div>
            <div class="input-field col s12">
                <!--                con esto nos borra los campos si nos equivocamos-->
                <input id="clave" type="password" name="clave" value="">
                <label for="clave">Contraseña</label>
            </div>
            <div class="input-field col s12" id="btnLogin">
                <button style="width: 100%" class="btn waves-effect waves-light btn-large" type="submit" name="acceder">Acceder
                    <i class="material-icons right">person</i>
                </button>
            </div>
            <div class="input-field col s12">
                <button style="width: 100%" class="btn waves-effect waves-light btn-small" type="submit" name="registrar" id="btnRegistro">Registrarse
                    <i class="material-icons left">add_circle_outline</i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    var btnRegistrarse= $('#btnRegistro');
    btnRegistrarse.hide();
    var btnLogin= $('#btnLogin');

    function opcionRegistrar() {
        btnRegistrarse.show();
        btnLogin.hide();
    }


</script>
