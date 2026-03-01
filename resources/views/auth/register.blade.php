<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileCare - Inscription</title>
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
            max-width: 450px;
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
            transition: all 0.3s ease;
        }

        .form-title {
            font-size: 1.8rem;
            color: #01579b;
            margin-bottom: 25px;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
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
        textarea,
        select {
            width: 100%;
            padding: 15px;
            border: 1px solid #b0bec5;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
            background-color: white;
            appearance: none;
        }

        select {
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23b0bec5%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat, repeat;
            background-position: right .7em top 50%, 0 0;
            background-size: .65em auto, 100%;
        }

        input:focus,
        select:focus,
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
            margin-top: 25px;
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
            <h2 class="form-title">{{ !empty($patientMode) ? 'Creer un compte patient' : 'Creer un compte' }}</h2>

            @if ($errors->any())
                <div style="color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px;">
                    <ul style="list-style-type: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ !empty($patientMode) ? route('patient.register.submit') : route('register') }}">
                @csrf

                @if(empty($patientMode))
                <div class="input-group">
                    <label for="role">Vous êtes :</label>
                    <select id="role" name="role" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choisir votre rôle...</option>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="fournisseur" {{ old('role') == 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                    </select>
                    @error('role')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                <div class="input-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
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

                @if(!empty($patientMode))
                    <div class="input-group">
                        <label for="telephone">Téléphone</label>
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
                        <textarea id="adresse" name="adresse" style="width: 100%; padding: 15px; border: 1px solid #b0bec5; border-radius: 8px; font-size: 1rem;" rows="3" required>{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="cabinet_id">Cabinet Dentaire (Optionnel)</label>
                        <select id="cabinet_id" name="cabinet_id">
                            <option value="">Choisir un cabinet...</option>
                            @if(isset($cabinets))
                                @foreach($cabinets as $cabinet)
                                    <option value="{{ $cabinet->id }}" {{ old('cabinet_id') == $cabinet->id ? 'selected' : '' }}>
                                        {{ $cabinet->nom }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('cabinet_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="medecin_id">Médecin Traitant (Optionnel)</label>
                        <select id="medecin_id" name="medecin_id">
                            <option value="">Choisir un médecin...</option>
                            @if(isset($medecins))
                                @foreach($medecins as $medecin)
                                    <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                                        Dr. {{ $medecin->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('medecin_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <button type="submit" class="btn">Creer un compte</button>
            </form>

            <div class="switch-form">
                Deja un compte? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
