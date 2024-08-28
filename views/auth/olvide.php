<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Restablece tu password ingresando tu correo</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/olvide" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email:</label>
                <input 
                type="email"
                id="email"
                placeholder="Tu email"
                name="email"
                />
            </div>
            <input type="submit" value="Enviar Instrucciones" class="boton">
            <div class="acciones">
                <a href="/crear">¿Aún no tienes una cuenta? Crea Una</a>
                <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
            </div>
        </form>
    </div><!-- .contenedor-sm -->
</div>