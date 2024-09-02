<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form action="/cambiar-password" method="POST" class="formulario">
        <div class="campo">
            <label for="password_actual">Tu Password Actual:</label>
            <input 
            type="password" 
            name="password_actual" 
            id="password_actual"
            placeholder="Tu password"
            />
        </div>
        <div class="campo">
            <label for="password_nuevo">Tu Nuevo Password:</label>
            <input 
            type="password" 
            name="password_nuevo" 
            id="password_nuevo"
            placeholder="Tu password nuevo"
            />
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>