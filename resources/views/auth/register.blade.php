@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form id="registerForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter name" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
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
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" required>
                            <div class="invalid-feedback" id="passwordConfirmationError"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            // Clear previous errors
            document.getElementById('name').classList.remove('is-invalid');
            document.getElementById('email').classList.remove('is-invalid');
            document.getElementById('password').classList.remove('is-invalid');
            document.getElementById('password_confirmation').classList.remove('is-invalid');
            document.getElementById('nameError').innerText = '';
            document.getElementById('emailError').innerText = '';
            document.getElementById('passwordError').innerText = '';
            document.getElementById('passwordConfirmationError').innerText = '';

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, email, password, password_confirmation })
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado',
                        text: 'Usuario registrado exitosamente',
                    }).then(() => {
                        window.location.href = '/login';
                    });
                } else {
                    if (data.messages) {
                        if (data.messages.name) {
                            document.getElementById('name').classList.add('is-invalid');
                            document.getElementById('nameError').innerText = data.messages.name[0];
                        }
                        if (data.messages.email) {
                            document.getElementById('email').classList.add('is-invalid');
                            document.getElementById('emailError').innerText = data.messages.email[0];
                        }
                        if (data.messages.password) {
                            document.getElementById('password').classList.add('is-invalid');
                            document.getElementById('passwordError').innerText = data.messages.password[0];
                        }
                        if (data.messages.password_confirmation) {
                            document.getElementById('password_confirmation').classList.add('is-invalid');
                            document.getElementById('passwordConfirmationError').innerText = data.messages.password_confirmation[0];
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
