@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" required>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Clear previous errors
            document.getElementById('email').classList.remove('is-invalid');
            document.getElementById('password').classList.remove('is-invalid');
            document.getElementById('emailError').innerText = '';
            document.getElementById('passwordError').innerText = '';

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Guardar el token en el almacenamiento local
                    localStorage.setItem('token', data.token);
                    // Redirigir al usuario a la página de inicio
                    window.location.href = '/home';
                } else {
                    if (data.messages) {
                        if (data.messages.email) {
                            document.getElementById('email').classList.add('is-invalid');
                            document.getElementById('emailError').innerText = data.messages.email[0];
                        }
                        if (data.messages.password) {
                            document.getElementById('password').classList.add('is-invalid');
                            document.getElementById('passwordError').innerText = data.messages.password[0];
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
    </script>
@endsection
