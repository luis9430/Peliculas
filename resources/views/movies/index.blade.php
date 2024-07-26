@extends('layouts.app')

@section('title', 'Movies')

@section('content')
    <div class="container mt-5">
        <h1>Movies</h1>
        <button class="btn btn-primary mb-3" id="createNewMovie">Create New Movie</button>
        <table class="table table-bordered" id="moviesTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Synopsis</th>
                <th>Poster</th>
                <th>Review</th>
                <th>Release Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="movieModal" tabindex="-1" aria-labelledby="movieModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="movieModalLabel">Create New Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="movieForm">
                        <input type="hidden" id="movieId">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" required>
                            <div class="invalid-feedback" id="titleError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea class="form-control" id="synopsis" rows="3" required></textarea>
                            <div class="invalid-feedback" id="synopsisError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="poster" class="form-label">Poster URL</label>
                            <input type="text" class="form-control" id="poster" required>
                            <div class="invalid-feedback" id="posterError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea class="form-control" id="review" rows="3" required></textarea>
                            <div class="invalid-feedback" id="reviewError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="release_date" class="form-label">Release Date</label>
                            <input type="date" class="form-control" id="release_date" required>
                            <div class="invalid-feedback" id="release_dateError"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const moviesTableBody = document.querySelector('#moviesTable tbody');

            // Fetch movies data and populate table
            async function fetchMovies() {
                const response = await fetch("{{ route('movies.index') }}");
                const movies = await response.json();

                moviesTableBody.innerHTML = '';
                movies.forEach(movie => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${movie.id}</td>
                    <td>${movie.title}</td>
                    <td>${movie.synopsis}</td>
                    <td>${movie.poster}</td>
                    <td>${movie.review}</td>
                    <td>${movie.release_date}</td>
                    <td>
                        <button class="btn btn-info editMovie" data-id="${movie.id}">Edit</button>
                        <button class="btn btn-danger deleteMovie" data-id="${movie.id}">Delete</button>
                    </td>
                `;
                    moviesTableBody.appendChild(row);
                });

                document.querySelectorAll('.editMovie').forEach(button => {
                    button.addEventListener('click', editMovie);
                });

                document.querySelectorAll('.deleteMovie').forEach(button => {
                    button.addEventListener('click', deleteMovie);
                });
            }

            // Handle create new movie
            document.getElementById('createNewMovie').addEventListener('click', () => {
                document.getElementById('movieForm').reset();
                document.getElementById('movieId').value = '';
                document.getElementById('movieModalLabel').innerText = 'Create New Movie';
                new bootstrap.Modal(document.getElementById('movieModal')).show();
            });

            // Handle save movie
            document.getElementById('saveBtn').addEventListener('click', async (e) => {
                e.preventDefault();
                const movieId = document.getElementById('movieId').value;
                const title = document.getElementById('title').value;
                const synopsis = document.getElementById('synopsis').value;
                const poster = document.getElementById('poster').value;
                const review = document.getElementById('review').value;
                const release_date = document.getElementById('release_date').value;

                // Clear previous errors
                document.querySelectorAll('.form-control').forEach(input => {
                    input.classList.remove('is-invalid');
                });
                document.querySelectorAll('.invalid-feedback').forEach(feedback => {
                    feedback.innerText = '';
                });

                const movieData = { title, synopsis, poster, review, release_date };
                const url = movieId ? `{{ route('movies.store') }}/${movieId}` : "{{ route('movies.store') }}";
                const method = movieId ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(movieData),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        fetchMovies();
                        new bootstrap.Modal(document.getElementById('movieModal')).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Movie saved successfully',
                        });
                    } else {
                        if (data.errors) {
                            for (const key in data.errors) {
                                document.getElementById(key).classList.add('is-invalid');
                                document.getElementById(`${key}Error`).innerText = data.errors[key][0];
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error,
                            });
                        }
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la solicitud: ' + error.message,
                    });
                }
            });

            // Handle edit movie
            async function editMovie(event) {
                const movieId = event.target.getAttribute('data-id');
                const response = await fetch(`{{ route('movies.index') }}/${movieId}/edit`);
                const movie = await response.json();

                document.getElementById('movieId').value = movie.id;
                document.getElementById('title').value = movie.title;
                document.getElementById('synopsis').value = movie.synopsis;
                document.getElementById('poster').value = movie.poster;
                document.getElementById('review').value = movie.review;
                document.getElementById('release_date').value = movie.release_date;

                document.getElementById('movieModalLabel').innerText = 'Edit Movie';
                new bootstrap.Modal(document.getElementById('movieModal')).show();
            }

            // Handle delete movie
            async function deleteMovie(event) {
                const movieId = event.target.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`{{ route('movies.store') }}/${movieId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                            });

                            if (response.ok) {
                                fetchMovies();
                                Swal.fire(
                                    'Deleted!',
                                    'Your movie has been deleted.',
                                    'success'
                                );
                            } else {
                                const data = await response.json();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.error,
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al procesar la solicitud: ' + error.message,
                            });
                        }
                    }
                });
            }

            fetchMovies();
        });
    </script>
@endsection
