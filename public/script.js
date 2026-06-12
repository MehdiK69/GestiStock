
    async function loadCategories() {
    const url = "http://127.0.0.1:8000/api/categories";
    const response = await fetch(url);
    const result = await response.json();

    const tbody = document.getElementById('tbody-categories');
    tbody.innerHTML = '';

    result.forEach(categorie => {
    const tr = document.createElement('tr');

    const tdId = document.createElement('td');
    tdId.textContent = categorie.id;

    const tdNom = document.createElement('td');
    tdNom.textContent = categorie.nom;

    const tdAction = document.createElement('td');

    const btnDelete = document.createElement('button');
    btnDelete.textContent = 'Supprimer';
    btnDelete.onclick = () => deleteCategorie(categorie.id);
    tdAction.appendChild(btnDelete);

    tr.appendChild(tdId);
    tr.appendChild(tdNom);
    tr.appendChild(tdAction);
    tbody.appendChild(tr);
});
}

    async function loadProduits(){
    const url = "http://127.0.0.1:8000/api/produits";
    const response = await fetch(url);
    const result = await response.json();

    const tbody = document.getElementById('tbody-produits');
    tbody.innerHTML = '';

    result.forEach(produit => {
        const tr = document.createElement('tr');

        const tdId = document.createElement('td');
        tdId.textContent = produit.id;

        const tdNom = document.createElement('td');
        tdNom.textContent = produit.nom;

        const tdPrix = document.createElement('td');
        tdPrix.textContent = produit.prix;

        const tdQtite = document.createElement('td');
        tdQtite.textContent = produit.quantite;

        const tdSeuil = document.createElement('td');
        tdSeuil.textContent = produit.seuil_alerte;

        const tdCatId = document.createElement('td');
        tdCatId.textContent = produit.id_categorie;

        const tdAction = document.createElement('td');
        const btnDelete = document.createElement('button');
        btnDelete.textContent = 'Supprimer';
        btnDelete.onclick = () => deleteProduit(produit.id);
        tdAction.appendChild(btnDelete);

        tr.appendChild(tdId);
        tr.appendChild(tdNom);
        tr.appendChild(tdPrix);
        tr.appendChild(tdQtite);
        tr.appendChild(tdSeuil);
        tr.appendChild(tdCatId);
        tr.appendChild(btnDelete);
        tbody.appendChild(tr);
});
}

    async function loadMouvements(){
    const url = "http://127.0.0.1:8000/api/mouvements";
    const response = await fetch(url);
    const result = await response.json();

    const tbody = document.getElementById('tbody-mouvements');
    tbody.innerHTML = '';

    result.forEach(mouvement => {
    const tr = document.createElement('tr');

    const tdId = document.createElement('td');
    tdId.textContent = mouvement.id;

    const tdQtite = document.createElement('td');
    tdQtite.textContent = mouvement.quantite;

    const tdType = document.createElement('td');
    tdType.textContent = mouvement.type;

    const tdDate = document.createElement('td');
    tdDate.textContent = mouvement.date;

    const tdProdId = document.createElement('td');
    tdProdId.textContent = mouvement.produit_id;


    tr.appendChild(tdId);
    tr.appendChild(tdQtite);
    tr.appendChild(tdType);
    tr.appendChild(tdDate);
    tr.appendChild(tdProdId);
    tbody.appendChild(tr);
});
}


    async function deleteCategorie(id) {
        const url = `http://127.0.0.1:8000/api/categories/${id}`;
        try {
            const response = await fetch(url, {
                method: 'DELETE'
            });
            if (!response.ok) {
                throw new Error('Erreur suppression');
            }
            loadCategories();
        } catch (error) {
            console.error(error);
        }
    }

    function afficherFormCategorie(){

        const form = document.getElementById('form-categorie');
        form.innerHTML = '';
        const label = document.createElement("label");
        const inputNom = document.createElement("input")
        const btnValider = document.createElement("button");
        btnValider.textContent = 'Valider';
        label.textContent = 'Nom: ';
        form.appendChild(label);
        form.appendChild(inputNom);
        form.appendChild(btnValider1);
        btnValider1.onclick = () => {
            console.log(inputNom.value);
            addCategorie(inputNom.value);
        }
    }
    async function addCategorie(nom){
    const data = {nom:nom};
        const url = "http://127.0.0.1:8000/api/categories";
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            if (!response.ok) {
                throw new Error('Erreur ajout');
            }
            loadCategories();
        } catch (error) {
            console.error(error);
        }
    }

    function afficherFormProduit(){

        const form = document.getElementById('form-produit');
        form.innerHTML = '';

        const labelNom = document.createElement("label");
        labelNom.textContent = 'Nom: '
        const inputNom = document.createElement("input")

        const labelPrix = document.createElement("label");
        labelPrix.textContent = 'Prix: '
        const inputPrix = document.createElement("input")

        const labelQtite = document.createElement("label");
        labelQtite.textContent = 'Quantite: '
        const inputQtite = document.createElement("input")

        const labelSeuil = document.createElement("label");
        labelSeuil.textContent = 'Seuil alerte: '
        const inputSeuil = document.createElement("input")

        const labelIdCat = document.createElement("label");
        labelIdCat.textContent = 'Id Categorie: '
        const inputIdCat = document.createElement("input")

        const btnValider2 = document.createElement("button");
        btnValider2.textContent = 'Valider';

        form.appendChild(labelNom);
        form.appendChild(inputNom);
        form.appendChild(labelPrix);
        form.appendChild(inputPrix);
        form.appendChild(labelQtite);
        form.appendChild(inputQtite);
        form.appendChild(labelSeuil);
        form.appendChild(inputSeuil);
        form.appendChild(labelIdCat);
        form.appendChild(inputIdCat);
        form.appendChild(btnValider2);
        btnValider2.onclick = () =>{
            addProduit(inputNom.value,parseFloat(inputPrix.value),parseInt(inputQtite.value),parseInt(inputSeuil.value),parseInt(inputIdCat.value));
        }
    }

    async function addProduit(nom,prix,quantite,seuil,idcat){
        const data = {nom:nom,prix:prix,quantite:quantite,seuil_alerte:seuil,id_categorie:idcat};
        const url = "http://127.0.0.1:8000/api/produits";
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            if (!response.ok) {
                throw new Error('Erreur ajout');
            }
            loadProduits();
        } catch (error) {
            console.error(error);
        }
    }

    async function deleteProduit(id) {
        const url = `http://127.0.0.1:8000/api/produits/${id}`;
        try {
            const response = await fetch(url, {
                method: 'DELETE'
            });
            if (!response.ok) {
                throw new Error('Erreur suppression');
            }
            loadProduits();
        } catch (error) {
            console.error(error);
        }
    }


loadCategories();
loadProduits();
loadMouvements();
