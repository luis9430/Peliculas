@extends('layouts.app')

@section('title', $movie->title)

@section('content')
<div class="container mt-4">
    <h1>{{ $movie->title }}</h1>
    <div class="row">
        <div class="col-md-8">
            <img src="{{ $movie->poster }}" class="img-fluid" alt="{{ $movie->title }}">
        </div>
        <div class="col-md-4">
            <h3>Sinopsis</h3>
            <p>{{ $movie->synopsis }}</p>
            <h3>Reseña</h3>
            <p>{{ $movie->review }}</p>
            <h3>Fecha de Lanzamiento</h3>
            <p>{{ $movie->release_date }}</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <h3>Comentarios</h3>
            <div id="commentsList">
                <!-- Aquí se insertarán los comentarios -->
            </div>
            @auth
            <form id="commentForm">
                @csrf
                <div class="mb-3">
                    <label for="comment_text" class="form-label">Agregar un comentario</label>
                    <textarea class="form-control" id="comment_text" rows="3" required></textarea>
                    <div class="invalid-feedback" id="commentError"></div>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
            @endauth
            @guest
            <p>Por favor, <a href="{{ route('login') }}">inicia sesión</a> para dejar un comentario.</p>
            @endguest
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const commentsList = document.querySelector('#commentsList');
    const commentForm = document.querySelector('#commentForm');
    const commentText = document.querySelector('#comment_text');
    const movieId = {{ $movie->id }};
    const userId = @json(Auth::id());

    async function fetchComments() {
        const response = await fetch(`/api/comments?filter[movie_id]=${movieId}&with=user`, {
            headers: {
                'Accept': 'application/json',
            },
            credentials: 'include'
        });

        if (!response.ok) {
            console.error('Error al obtener los comentarios:', response.status, response.statusText);
            return;
        }

        const comments = await response.json();
        console.log('Comentarios obtenidos:', comments);

        commentsList.innerHTML = '';
        comments.forEach(comment => {
            const commentDiv = document.createElement('div');
            commentDiv.classList.add('comment');
            commentDiv.innerHTML = `
                <p><strong>${comment.user.name}</strong>: ${comment.comment_text}</p>
                <p><small>${comment.created_at}</small></p>
            `;
            commentsList.appendChild(commentDiv);
        });
    }

    async function submitComment(event) {
        event.preventDefault();

        const commentData = {
            movie_id: movieId,
            user_id: userId,
            comment_text: commentText.value
        };

        try {
            const response = await fetch('/api/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(commentData)
            });

            const data = await response.json();

            if (response.ok) {
                commentText.value = '';
                fetchComments();
            } else {
                if (data.errors) {
                    for (const key in data.errors) {
                        document.getElementById(`${key}Error`).innerText = data.errors[key][0];
                    }
                } else {
                    console.error('Error al enviar el comentario:', data.error);
                }
            }
        } catch (error) {
            console.error('Error al procesar la solicitud:', error.message);
        }
    }

    if (commentForm) {
        commentForm.addEventListener('submit', submitComment);
    }

    fetchComments();
});
</script>
@endsection
