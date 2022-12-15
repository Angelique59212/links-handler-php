<div id="title-addLink">
    <h1>Ajouter un lien</h1>
</div>
<div id="form-addLink">
    <form action="/?c=link&a=add-link" method="post" enctype="multipart/form-data">
        <div>
            <label for="title">Nom du lien</label>
            <input type="text" name="title" id="title">
        </div>
        <div>
            <label for="image-acces">Chemin d'accès à l'image</label>
            <input type="file" name="image" id="image-acces">
        </div>

        <input type="submit" id="btn-addLink" name="submit" value="Enregistrer">
    </form>
</div>