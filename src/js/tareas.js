(function(){
    //Boton para mostrar modal de tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario(){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML= `
            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                        type="text" 
                        name="tarea" 
                        id="tarea"
                        placeholder="Añadir Tarea al Proyecto Actual"
                    />
                </div>
                <div class="opciones">
                    <input
                        type="submit"
                        class="submit-nueva-tarea"
                        value="Nueva Tarea"
                    />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            } 

            if(e.target.classList.contains('submit-nueva-tarea')){
                submitNuevoFormularioTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitNuevoFormularioTarea() {
        const tarea = document.querySelector('#tarea').value.trim();
        if(tarea === ''){
            //Mostrar alerta cuando detecte ningun valor
            mostrarAlerta('¡El nombre de la tarea es obligatorio!', 'error', 
                document.querySelector('.formulario legend'));
            return;
        } 
        agregarTarea();
    }
    function mostrarAlerta(mensaje, tipo, referencia){
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia) {
            alertaPrevia.remove();
        }
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;


        //Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

    function agregarTarea(tarea){

    }




})();//IIEF evita que se mezclen las variables
