<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileCare - Inscription Patient</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e0f7fa 0%, #bbdefb 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 540px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 2.8rem;
            font-weight: 700;
            color: #0288d1;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #546e7a;
            font-size: 1.2rem;
        }

        .card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px 30px;
        }

        .form-title {
            font-size: 1.8rem;
            color: #01579b;
            margin-bottom: 25px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #37474f;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #b0bec5;
            border-radius: 8px;
            font-size: 1rem;
        }

        input:focus,
        textarea:focus {
            border-color: #0288d1;
            outline: none;
            box-shadow: 0 0 0 2px rgba(2, 136, 209, 0.2);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background-color: #0288d1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
        }

        .switch-form {
            text-align: center;
            margin-top: 20px;
            color: #546e7a;
        }

        .switch-form a {
            color: #0288d1;
            text-decoration: none;
            font-weight: 500;
        }

        .error-message {
            color: #d32f2f;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        @media (max-width: 500px) {
            .card {
                padding: 30px 20px;
            }

            .logo {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="logo">SmileCare</div>
            <div class="subtitle">Cabinet Dentaire</div>
        </div>

        <div class="card">
            <h2 class="form-title">Creer un compte patient</h2>

            @if ($errors->any())
                <div style="color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px;">
                    <ul style="list-style-type: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.register.submit') }}">
                @csrf

                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required autofocus>
                    @error('nom')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="prenom">Prenom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                    @error('prenom')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="telephone">Telephone</label>
                    <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                    @error('telephone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                    @error('date_naissance')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="adresse">Adresse</label>
                    <textarea id="adresse" name="adresse" rows="3" required>{{ old('adresse') }}</textarea>
                    @error('adresse')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn">Creer un compte</button>
            </form>

            <div class="switch-form">
                Deja un compte? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
