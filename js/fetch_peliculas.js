// Definimos la URL base de la API de The Movie DB
const API_SERVER = 'https://api.themoviedb.org/3';

// Opciones para las peticiones fetch a la API
const options = {
    method: 'GET',
    headers: {
        accept: 'application/json',
        Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJhYTJjYTAwZDYxZWIzOTEyYjZlNzc4MDA4YWQ3ZmNjOCIsInN1YiI6IjYyODJmNmYwMTQ5NTY1MDA2NmI1NjlhYyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.4MJSPDJhhpbHHJyNYBtH_uCZh4o0e3xGhZpcBIDy-Y8'
    }
};

// Función para crear elementos HTML
const createElement = (tag, className, attributes = {}) => {
    const element = document.createElement(tag);
    if (className) {
        element.classList.add(className);
    }
    Object.entries(attributes).forEach(([key, value]) => element.setAttribute(key, value));
    return element;
};

// Función para cargar películas en la cuadrícula de tendencias
const fetchMoviesGrid = async (page = 1) => {
    const response = await fetch(`${API_SERVER}/movie/popular?page=${page}`, options);
    const data = await response.json();
    const movies = data.results;
    const tendenciasContainer = document.querySelector('.pelisTendencia .pelis');
    tendenciasContainer.innerHTML = '';

    movies.forEach(movie => {
        const pelicula = createElement('div', 'pelicula');
        const anchor = createElement('a', '', { href: './pages/detalle.html' });
        const img = createElement('img', 'imgTendencia', {
            src: `https://image.tmdb.org/t/p/w500/${movie.poster_path}`,
            alt: movie.title,
            loading: 'lazy'
        });
        img.classList.add('w-100');
        const tituloPelicula = createElement('div', 'tituloPelicula');
        const titulo = createElement('h4', '');
        titulo.textContent = movie.title;

        tituloPelicula.appendChild(titulo);
        pelicula.append(img, tituloPelicula);
        anchor.appendChild(pelicula);
        const peliculaWrapper = createElement('div', 'peliculas');
        peliculaWrapper.appendChild(anchor);
        tendenciasContainer.appendChild(peliculaWrapper);
    });

    tendenciasContainer.parentElement.setAttribute('data-page', page);
};

// Event listener para el botón "Anterior"
document.querySelector('.anterior').addEventListener('click', () => {
    let currentPage = Number(document.querySelector('.pelisTendencia').getAttribute('data-page'));
    if (currentPage > 1) {
        fetchMoviesGrid(currentPage - 1);
    }
});

// Event listener para el botón "Siguiente"
document.querySelector('.siguiente').addEventListener('click', () => {
    let currentPage = Number(document.querySelector('.pelisTendencia').getAttribute('data-page'));
    fetchMoviesGrid(currentPage + 1);
});

// Ejecutamos las funciones de carga de películas al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.pelisTendencia').setAttribute('data-page', 1); // Inicializamos el atributo data-page
    fetchMoviesGrid();
});
