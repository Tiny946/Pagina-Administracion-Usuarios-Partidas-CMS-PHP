<h3>
    <a href="<?php echo $_SESSION['home'] ?>admin" title="Inicio">Inicio</a> <span>| Partidas</span>
</h3>
<div class="row">
    <!--Nuevo-->
<!--    <article class="col s12 l6">-->
<!--        <div class="card horizontal admin">-->
<!--            <div class="card-stacked">-->
<!--                <div class="card-content">-->
<!--                    <i class="grey-text material-icons medium">image</i>-->
<!--                    <h4 class="grey-text">-->
<!--                        Crear partida prueba-->
<!--                    </h4><br><br>-->
<!--                </div>-->
<!--                <div class="card-action">-->
<!--                    <a href="--><?php //echo $_SESSION['home']."admin/noticias/crear" ?><!--" title="Añadir nueva noticia">-->
<!--                        <i class="material-icons">add_circle</i>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </article>-->
    <?php foreach ($datos as $row){ ?>
        <article class="col s12 l6">
            <div class="card horizontal  sticky-action admin">
                <div class="card-stacked">
                    <?php if ($row->imagen){ ?>
                        <div class="card-image">
                            <img src="<?php echo $_SESSION['public']."img/".$row->imagen ?>" alt="<?php echo $row->titulo ?>">
                        </div>
                    <?php } ?>
                    <div class="card-content">
                        <?php if (!$row->imagen){ ?>
                            <i class="grey-text material-icons medium">image</i>
                        <?php } ?>
                        <h4>
                            <?php echo $row->titulo ?>
                        </h4>
                        <strong>Puntuación:</strong> <?php echo $row->puntuacion ?><br>
                        <strong>Duración:</strong> <?php echo $row->duracion ?><br>
                        <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($row->fecha)) ?>
                    </div>
                    <div class="card-action">
                        <a href="<?php echo $_SESSION['home']."admin/noticias/editar/".$row->id ?>" title="Editar">
                            <i class="material-icons">edit</i>
                        </a>
                        <?php $title = ($row->activa == 1) ? "Desactivar" : "Activar" ?>
                        <?php $color = ($row->activa == 1) ? "green-text" : "red-text" ?>
                        <?php $icono = ($row->activa == 1) ? "mood" : "mood_bad" ?>
                        <a href="<?php echo $_SESSION['home']."admin/noticias/activar/".$row->id ?>" title="<?php echo $title ?>">
                            <i class="<?php echo $color ?> material-icons"><?php echo $icono ?></i>
                        </a>
                        <?php $title = ($row->home == 1) ? "Quitar de la home" : "Mostrar en la home" ?>
                        <?php $color = ($row->home == 1) ? "green-text" : "red-text" ?>
                        <a href="<?php echo $_SESSION['home']."admin/noticias/home/".$row->id ?>" title="<?php echo $title ?>">
                            <i class="<?php echo $color ?> material-icons">home</i>
                        </a>
                        <a href="#" class="activator" title="Borrar">
                            <i class="material-icons">delete</i>
                        </a>
                    </div>
                </div>
                <!--Confirmación de borrar-->
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Borrar noticia<i class="material-icons right">close</i></span>
                    <p>
                        ¿Está seguro de que quiere borrar la noticia<strong><?php echo $row->titulo ?></strong>?<br>
                        Esta acción no se puede deshacer.
                    </p>
                    <a href="<?php echo $_SESSION['home']."admin/noticias/borrar/".$row->id ?>" title="Borrar">
                        <button class="btn waves-effect waves-light" type="button">Borrar
                            <i class="material-icons right">delete</i>
                        </button>
                    </a>
                </div>
            </div>
        </article>
    <?php } ?>
</div>