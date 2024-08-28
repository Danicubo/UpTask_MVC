<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if($mostrar) { ?>
        <form method="POST" class="formulario">

            <div class="campo">
                <label for="password">Password:</label>
                <input 
                type="password"
                id="password"
                placeholder="Tu password"
                name="password"
                />
            </div>
            <input type="submit" value="Restablecer Password" class="boton">
        </form>
        <?php } ?>
    </div><!-- .contenedor-sm -->
</div>