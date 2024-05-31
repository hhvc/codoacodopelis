// Espera a que el DOM se cargue completamente
document.addEventListener('DOMContentLoaded', () => {
    // Selecciona el formulario en el DOM
    const form = document.querySelector('form');

    // Agrega un evento de escucha para cuando se envía el formulario
    form.addEventListener('submit', (event) => {
        // Si la validación del formulario no es exitosa
        if (!validateForm()) {
            // Muestra un mensaje en la consola indicando que el formulario no es válido
            console.log('El formulario no es válido. Por favor, corrige los errores.');
            // Evita que el formulario se envíe
            event.preventDefault();
        } else {
            // Si la validación del formulario es exitosa, muestra un mensaje en la consola
            console.log('El formulario es válido. Enviar datos...');
            // Aquí puedes enviar los datos del formulario o realizar otras acciones
        }
    });

    // Función para validar todo el formulario
    const validateForm = () => {
        let isValid = true;

        // Validar campos de email
        isValid = validateEmailField('email1', 'email2', 'El correo electrónico no es válido o no coincide') && isValid;

        // Validar campos de contraseña
        isValid = validatePasswordField('password1', 'password2', 'Las contraseñas no coinciden', 'La contraseña debe tener al menos 8 caracteres y contener letras y números') && isValid;

        return isValid;
    };

    // Función para validar los campos de email
    const validateEmailField = (fieldId1, fieldId2, errorMessage) => {
        // Obtiene los elementos de los campos de correo electrónico mediante sus IDs
        const field1 = document.getElementById(fieldId1);
        const field2 = document.getElementById(fieldId2);
        // Obtiene los valores de los campos y elimina los espacios en blanco al principio y al final
        const email1 = field1.value.trim();
        const email2 = field2.value.trim();
        // Verifica si ambos correos son válidos y si son iguales
        if (email1 === '' || email2 === '' || !isEmail(email1) || email1 !== email2) {
            // Establece un mensaje de error para los campos de correo electrónico
            setErrorFor(field1, errorMessage);
            setErrorFor(field2, errorMessage);
            // Devuelve false indicando que la validación ha fallado
            return false;
        } else {
            // Si los campos de correo electrónico son válidos y coinciden, elimina cualquier mensaje de error anterior
            setSuccessFor(field1);
            setSuccessFor(field2);
            // Devuelve true indicando que la validación ha tenido éxito
            return true;
        }
    };

    // Función para validar los campos de contraseña
    const validatePasswordField = (fieldId1, fieldId2, mismatchMessage, strengthMessage) => {
        // Obtiene los elementos de los campos mediante sus IDs
        const field1 = document.getElementById(fieldId1);
        const field2 = document.getElementById(fieldId2);
        // Obtiene los valores de los campos y elimina los espacios en blanco al principio y al final
        const value1 = field1.value.trim();
        const value2 = field2.value.trim();
        // Verifica si ambos valores son iguales y no están vacíos
        if (value1 === '' || value2 === '' || value1 !== value2) {
            // Establece un mensaje de error para los campos
            setErrorFor(field1, mismatchMessage);
            setErrorFor(field2, mismatchMessage);
            // Devuelve false indicando que la validación ha fallado
            return false;
        } else if (!validatePasswordStrength(value1)) {
            // Establece un mensaje de error para los campos si la contraseña no cumple con los requisitos de fortaleza
            setErrorFor(field1, strengthMessage);
            setErrorFor(field2, strengthMessage);
            // Devuelve false indicando que la validación ha fallado
            return false;
        } else {
            // Si los valores de los campos coinciden y no están vacíos, elimina cualquier mensaje de error anterior
            setSuccessFor(field1);
            setSuccessFor(field2);
            // Devuelve true indicando que la validación ha tenido éxito
            return true;
        }
    };

    // Función para validar si una contraseña tiene al menos 8 caracteres y contiene letras y números
    const validatePasswordStrength = (password) => {
        // Expresión regular para verificar la fortaleza de la contraseña
        const re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        // Verifica si la contraseña cumple con el patrón
        return re.test(password);
    };

    // Función para establecer un mensaje de error en un campo
    const setErrorFor = (input, message) => {
        // Encuentra el elemento padre del campo de entrada
        const formControl = input.closest('div');
        // Encuentra el elemento de texto de error dentro del elemento padre
        const errorText = formControl.querySelector('.error-text');
        // Agrega la clase de error al elemento padre para resaltar el campo
        formControl.classList.add('error');
        // Establece el texto del mensaje de error
        errorText.innerText = message;
        // Añade la clase para el tooltip de error
        errorText.classList.add('error-tooltip');
        // Establece el foco en el campo de entrada para una corrección rápida
        input.focus();
    };

    // Función para eliminar un mensaje de error de un campo
    const setSuccessFor = (input) => {
        // Encuentra el elemento padre del campo de entrada
        const formControl = input.closest('div');
        // Elimina la clase de error del elemento padre
        formControl.classList.remove('error');
        // Encuentra el elemento de texto de error dentro del elemento padre
        const errorText = formControl.querySelector('.error-text');
        // Establece el texto de error como vacío
        errorText.innerText = '';
        // Elimina la clase del tooltip de error
        errorText.classList.remove('error-tooltip');
    };

    // Función para validar si una cadena es una dirección de correo electrónico válida
    const isEmail = (email) => {
        // Expresión regular para validar el formato de correo electrónico
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // Verifica si el correo electrónico cumple con el formato
        return re.test(email);
    };

    // Agrega eventos para borrar las clases de error cuando se completa el input o se presiona Tab
    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', () => {
            // Obtiene el valor del campo y elimina los espacios en blanco al principio y al final
            const value = input.value.trim();
            // Si el campo no está vacío, elimina cualquier mensaje de error
            if (value !== '') {
                setSuccessFor(input);
            }
        });
    });

    // Agrega eventos para borrar las clases de error cuando se selecciona una opción del select
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => {
            // Obtiene el valor seleccionado del campo de selección
            const value = select.value;
            // Si se selecciona una opción, elimina cualquier mensaje de error
            if (value !== '') {
                setSuccessFor(select);
            }
        });
    });
});
