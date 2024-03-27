@extends('layouts.app3')

@section('content')
    <main id="main">
        <div class="container">
            <form action="{{ route('devis.ajoutObjet') }}" method="POST">
                @csrf
                <h1>Veuillez ajouter vos biens.</h1>
                <input type="hidden" name="id_devis" value="{{ $id_devis }}">
                <div id="form-container">
                    <!-- Premier formulaire -->
                    <div class="form-group formulaire">
                        <h5>Catégorie :</h5>
                        <select name="type[]" class="form-control" required>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group formulaire">
                        <h5>Objet :</h5>
                        <input type="text" name="objet[]" class="form-control" placeholder="Ex:Chaise en bois,..."
                            required>
                    </div>

                    <div class="form-group formulaire">
                        <h5>Quantité :</h5>
                        <input type="number" name="quantite[]" min="1" value="1" class="form-control" required>
                    </div>

                    <div class="form-group formulaire">
                        <h5>Taille :</h5>
                        <select name="taille[]" class="form-control" required>
                            @foreach ($tailles as $taille)
                                <option value="{{ $taille->id }}">{{ $taille->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group formulaire">
                        <h5>Poids(en Kg) :</h5>
                        <input type="number" name="poids[]" min="1" value="0" class="form-control" required>
                    </div>

                    <hr> <!-- Ajouter une ligne horizontale -->

                    <!-- Bouton Ajouter -->

                </div>
                <button type="button" class="btn btn-primary" onclick="ajouterFormulaire()">Ajouter un autre
                    formulaire</button>

                <!-- Bouton Valider -->
                <button type="submit" class="btn btn-success">Valider</button>
            </form>
        </div>

        <script>
            function ajouterFormulaire() {
                const formContainer = document.getElementById('form-container');

                // Créer les champs du formulaire
                const nouveauFormulaire = document.createElement('div');
                nouveauFormulaire.className = 'form-group formulaire';

                const labelObjet = document.createElement('h5');
                //labelNom.setAttribute('for', 'nom');
                labelObjet.textContent = 'Objet :';

                const inputObjet = document.createElement('input');
                inputObjet.setAttribute('type', 'text');
                inputObjet.setAttribute('name', 'objet[]');
                inputObjet.className = 'form-control';
                inputObjet.setAttribute('placeholder', 'Ex:Chaise en bois,...');
                inputObjet.setAttribute('required', true);

                const labelQuantite = document.createElement('h5');
                labelQuantite.textContent = 'Quantité :';

                const inputQuantite = document.createElement('input');
                inputQuantite.setAttribute('type', 'number');
                inputQuantite.setAttribute('min', '1');
                inputQuantite.setAttribute('value', '1');
                inputQuantite.setAttribute('name', 'quantite[]');
                inputQuantite.className = 'form-control';
                inputQuantite.setAttribute('required', true);

                const labelPoids = document.createElement('h5');
                labelPoids.textContent = 'Poids(en Kg) :';

                const inputPoids = document.createElement('input');
                inputPoids.setAttribute('type', 'number');
                inputPoids.setAttribute('min', '1');
                inputPoids.setAttribute('value', '0');
                inputPoids.setAttribute('name', 'poids[]');
                inputPoids.className = 'form-control';
                inputPoids.setAttribute('required', true);

                const labelCategorie = document.createElement('h5');
                labelCategorie.textContent = 'Catégorie :';

                const selectCategorie = document.createElement('select');
                selectCategorie.setAttribute('name', 'type[]');
                selectCategorie.className = 'form-control';
                selectCategorie.setAttribute('required', true);

                @foreach ($types as $type)
                    const optionCategorie{{ $type->id }} = document.createElement('option');
                    optionCategorie{{ $type->id }}.value = "{{ $type->id }}";
                    optionCategorie{{ $type->id }}.textContent = "{{ $type->nom }}";
                    selectCategorie.appendChild(optionCategorie{{ $type->id }});
                @endforeach

                const labelTaille = document.createElement('h5');
                labelTaille.textContent = 'Taille :';

                const selectTaille = document.createElement('select');
                selectTaille.setAttribute('name', 'taille[]');
                selectTaille.className = 'form-control';
                selectTaille.setAttribute('required', true);

                @foreach ($tailles as $taille)
                    const optionTaille{{ $taille->id }} = document.createElement('option');
                    optionTaille{{ $taille->id }}.value = "{{ $taille->id }}";
                    optionTaille{{ $taille->id }}.textContent = "{{ $taille->nom }}";
                    selectTaille.appendChild(optionTaille{{ $taille->id }});
                @endforeach

                /*const inputTaille = document.createElement('input');
                inputTaille.setAttribute('type', 'number');
                inputTaille.setAttribute('name', 'taille[]');
                inputTaille.className = 'form-control';
                inputTaille.setAttribute('required', true);*/

                const hr = document.createElement('hr');
                const br = document.createElement('br');

                // Bouton Supprimer
                const boutonSupprimer = document.createElement('button');
                boutonSupprimer.type = 'button';
                boutonSupprimer.className = 'btn btn-danger';
                boutonSupprimer.textContent = 'X';
                boutonSupprimer.onclick = function() {
                    formContainer.removeChild(nouveauFormulaire);
                    formContainer.removeChild(boutonSupprimer);
                };

                // Ajouter les champs et le bouton au formulaire
                nouveauFormulaire.appendChild(hr);
                nouveauFormulaire.appendChild(labelCategorie);
                nouveauFormulaire.appendChild(selectCategorie);
                nouveauFormulaire.appendChild(labelObjet);
                nouveauFormulaire.appendChild(inputObjet);
                nouveauFormulaire.appendChild(labelQuantite);
                nouveauFormulaire.appendChild(inputQuantite);
                nouveauFormulaire.appendChild(labelTaille);
                nouveauFormulaire.appendChild(selectTaille);
                nouveauFormulaire.appendChild(labelPoids);
                nouveauFormulaire.appendChild(inputPoids);
                nouveauFormulaire.appendChild(br);
                nouveauFormulaire.appendChild(boutonSupprimer);

                // Ajouter le formulaire cloné avant le bouton Ajouter
                formContainer.insertBefore(nouveauFormulaire, formContainer.lastElementChild);
            }
        </script>
    </main>
@endsection
