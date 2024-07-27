                                            
                         <main class="mt-6">
                        
                        @extends('layouts.app')

                        @section('title', 'Peliculas')
                        
                        @section('content')
                        <div class="container mt-4">
                            <h1>Peliculas</h1>
                            <div class="row" id="moviesList">
                                <!-- Aquí se insertarán las tarjetas de las películas -->
                            </div>
                        </div>
                        @endsection
                        
                        @section('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const moviesList = document.querySelector('#moviesList');
                        
                            async function fetchMovies() {
                                const response = await fetch("{{ route('movies.index') }}", {
                                    headers: {
                                        'Accept': 'application/json',
                                    },
                                    credentials: 'include'
                                });
                        
                                if (!response.ok) {
                                    console.error('Error al obtener las películas:', response.status, response.statusText);
                                    return;
                                }
                        
                                const movies = await response.json();
                                console.log('Películas obtenidas:', movies); // Verificar que se obtienen los datos
                        
                                moviesList.innerHTML = '';
                                movies.forEach(movie => {
                                    const card = document.createElement('div');
                                    card.classList.add('col-md-4');
                                    card.innerHTML = `
                                        <div class="card mb-4 shadow-sm">
                                            <img class="card-img-top" src="${movie.poster}" alt="${movie.title}">
                                            <div class="card-body">
                                                <h5 class="card-title">${movie.title}</h5>
                                                <p class="card-text">${movie.synopsis}</p>
                                                <a href="{{ url('pelicula') }}/${movie.id}" class="btn btn-primary">Ver más</a>
                                            </div>
                                        </div>
                                    `;
                                    console.log('Card generada:', card.innerHTML); // Verificar que se generan las tarjetas
                                    moviesList.appendChild(card);
                                });
                            }
                        
                            fetchMovies();
                        });
                        </script>
                        @endsection
                        
                    </main>

                
                </div>
            </div>
        </div>
</html>
