// Espera a que el DOM se cargue completamente
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona el formulario en el DOM
  const form = document.querySelector("form");

  // Agrega un evento de escucha para cuando se envía el formulario
  form.addEventListener("submit", (event) => {
    // Si la validación del formulario no es exitosa
    if (!validateForm()) {
      // Muestra un mensaje en la consola indicando que el formulario no es válido
      console.log(
        "El formulario no es válido. Por favor, corrige los errores."
      );
      // Evita que el formulario se envíe
      event.preventDefault();
    } else {
      // Si la validación del formulario es exitosa, muestra un mensaje en la consola
      console.log("El formulario es válido. Enviar datos...");
      // Aquí puedes enviar los datos del formulario o realizar otras acciones
    }
  });

  // Función para validar todo el formulario
  const validateForm = () => {
    let isValid = true;

    // Validar campo de email en la página de inicio de sesión
    if (document.getElementById("exampleInputEmail1")) {
      isValid =
        validateEmail(
          "exampleInputEmail1",
          "El correo electrónico no es válido"
        ) && isValid;
    }

    // Validar campos de email en la página de registro
    if (
      document.getElementById("email1") &&
      document.getElementById("email2")
    ) {
      isValid =
        validateEmailField(
          "email1",
          "email2",
          "El correo electrónico no es válido o no coincide"
        ) && isValid;
    }

    // Validar campo de contraseña en la página de inicio de sesión
    if (document.getElementById("exampleInputPassword1")) {
      isValid =
        validatePassword(
          "exampleInputPassword1",
          "La contraseña debe tener al menos 8 caracteres y contener letras y números"
        ) && isValid;
    }

    // Validar campos de contraseña en la página de registro
    if (
      document.getElementById("password1") &&
      document.getElementById("password2")
    ) {
      isValid =
        validatePasswordField(
          "password1",
          "password2",
          "Las contraseñas no coinciden",
          "La contraseña debe tener al menos 8 caracteres y contener letras y números"
        ) && isValid;
    }

    return isValid;
  };

  // Función para validar un campo de email
  const validateEmail = (fieldId, errorMessage) => {
    const field = document.getElementById(fieldId);
    const email = field.value.trim();
    if (email === "" || !isEmail(email)) {
      setErrorFor(field, errorMessage);
      return false;
    } else {
      setSuccessFor(field);
      return true;
    }
  };

  // Función para validar los campos de email
  const validateEmailField = (fieldId1, fieldId2, errorMessage) => {
    const field1 = document.getElementById(fieldId1);
    const field2 = document.getElementById(fieldId2);
    const email1 = field1.value.trim();
    const email2 = field2.value.trim();
    if (
      email1 === "" ||
      email2 === "" ||
      !isEmail(email1) ||
      email1 !== email2
    ) {
      setErrorFor(field1, errorMessage);
      setErrorFor(field2, errorMessage);
      return false;
    } else {
      setSuccessFor(field1);
      setSuccessFor(field2);
      return true;
    }
  };

  // Función para validar un campo de contraseña
  const validatePassword = (fieldId, strengthMessage) => {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    if (value === "" || !validatePasswordStrength(value)) {
      setErrorFor(field, strengthMessage);
      return false;
    } else {
      setSuccessFor(field);
      return true;
    }
  };

  // Función para validar los campos de contraseña
  const validatePasswordField = (
    fieldId1,
    fieldId2,
    mismatchMessage,
    strengthMessage
  ) => {
    const field1 = document.getElementById(fieldId1);
    const field2 = document.getElementById(fieldId2);
    const value1 = field1.value.trim();
    const value2 = field2.value.trim();
    if (value1 === "" || value2 === "" || value1 !== value2) {
      setErrorFor(field1, mismatchMessage);
      setErrorFor(field2, mismatchMessage);
      return false;
    } else if (!validatePasswordStrength(value1)) {
      setErrorFor(field1, strengthMessage);
      setErrorFor(field2, strengthMessage);
      return false;
    } else {
      setSuccessFor(field1);
      setSuccessFor(field2);
      return true;
    }
  };

  // Función para validar si una contraseña tiene al menos 8 caracteres y contiene letras y números
  const validatePasswordStrength = (password) => {
    const re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    return re.test(password);
  };

  // Función para establecer un mensaje de error en un campo
  const setErrorFor = (input, message) => {
    const formControl = input.closest("div");
    const errorText = formControl.querySelector(".error-text");
    formControl.classList.add("error");
    errorText.innerText = message;
    errorText.classList.add("error-tooltip");
    input.focus();
  };

  // Función para eliminar un mensaje de error de un campo
  const setSuccessFor = (input) => {
    const formControl = input.closest("div");
    formControl.classList.remove("error");
    const errorText = formControl.querySelector(".error-text");
    errorText.innerText = "";
    errorText.classList.remove("error-tooltip");
  };

  // Función para validar si una cadena es una dirección de correo electrónico válida
  const isEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  };

  // Agrega eventos para borrar las clases de error cuando se completa el input o se presiona Tab
  form.querySelectorAll("input").forEach((input) => {
    input.addEventListener("input", () => {
      const value = input.value.trim();
      if (value !== "") {
        setSuccessFor(input);
      }
    });
  });

  // Agrega eventos para borrar las clases de error cuando se selecciona una opción del select
  form.querySelectorAll("select").forEach((select) => {
    select.addEventListener("change", () => {
      const value = select.value;
      if (value !== "") {
        setSuccessFor(select);
      }
    });
  });
});
