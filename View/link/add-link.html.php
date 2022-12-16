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
            <label for="link">Lien</label>
            <input type="text" name="link" id="link">
        </div>
        <div>
            <label for="imageName">Chemin d'accès à l'image</label>
            <input type="file" name="imageName" id="imageName">
        </div>

        <input type="submit" id="btn-addLink" name="submit" value="Enregistrer">
    </form>
</div>