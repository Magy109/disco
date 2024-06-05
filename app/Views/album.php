<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albums</title>
    <link rel="stylesheet" href="semantic/css/semantic.min.css">
    <link rel="stylesheet" href="semantic/components/icon.min.css">
</head>
<body>
<div class="ui container">
        <h1></h1>
        <h2 class="ui teal header">   </h2>
        <center><h2 class="ui teal header">Tabla Albums</h2></center>
        <button class="ui button" id="openModalButton" style="background-color: white;">
            <i class="plus icon" style="color: black;"></i> Agregar
        </button>

        
        <div class="ui large icon input">
            <input type="text" placeholder="filtrar por album..." id="nombre">
            <i class="search icon"></i>
        </div>
        <div class="ui large icon input">
            <input type="text" placeholder="filtrar por autor..." id="autor">
            <i class="search icon"></i>
        </div>
        <h2></h2>
      <div class="ui container">
        <table id="miTabla" class="ui teal inverted celled table dataTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($albums)): ?>
                    <?php foreach ($albums as $album): ?>
                        <tr>
                            <td><?= esc($album->id) ?></td>
                            <td><?= esc($album->name) ?></td>
                            <td><?= esc($album->author) ?></td>
                            <td><?= esc($album->genre_name) ?></td>
                            <td>
                            <button class="ui teal button edit-button" data-id="<?=$album->id?>"><i class="edit icon"></i> Editar</button>
                            <button class="ui teal button delete-button" data-id="<?=$album->id?>"><i class="trash alternate icon"></i> Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No se encontraron álbumes.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="semantic/js/semantic.min.js"></script>

     <!-- modal de confirmacion-->
     <div class="ui small modal" id="deleteModal">
        <div class="header">Confirmar Eliminación</div>
        <div class="content">
            <p>¿Está seguro de que desea eliminar este género?</p>
        </div>
        <div class="actions">
            <button class="ui button" id="cancelDelete">Cancelar</button>
            <button class="ui red button" id="confirmDelete">Eliminar</button>
        </div>
    </div>

    <!--modal de agregar-->
<div class="ui small modal" id="addModal">
    <div class="header">Agregar Nuevo Álbum</div>
    <div class="content">
        <form class="ui form" id="addForm">
            <div class="field">
                <label for="newName">Nombre del Álbum:</label>
                <input type="text" id="newName" name="name" required>
            </div>
            <div class="field">
                <label for="author">Autor:</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="field">
                <label for="genreSelect">Género:</label>
                <select id="genreSelect" name="genre_id" class="ui dropdown" required>
                    <option value="">Seleccionar Género</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= esc($genre->id) ?>"><?= esc($genre->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="ui teal button">Guardar</button>
        </form>
    </div>
</div>

<!-- Modal de edición -->
<div class="ui small modal" id="editModal">
    <div class="header">Editar Álbum</div>
    <div class="content">
        <form class="ui form" id="editForm">
            <input type="hidden" id="editId" name="id">
            <div class="field">
                <label for="editName">Nombre del Álbum:</label>
                <input type="text" id="editName" name="name" required>
            </div>
            <div class="field">
                <label for="editAuthor">Autor:</label>
                <input type="text" id="editAuthor" name="author" required>
            </div>
            <div class="field">
                <label for="editGenreSelect">Género:</label>
                <select id="editGenreSelect" name="genre_id" class="ui dropdown" required>
                    <option value="">Seleccionar Género</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= esc($genre->id) ?>"><?= esc($genre->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="ui teal button">Guardar Cambios</button>
        </form>
    </div>
</div>

    <script>
    
        // abre modal de confirmacion
         $('.delete-button').click(function() {
          albumIdToDelete = $(this).data('id');
          $('#deleteModal').modal('show');
        });

         // Cancela la eliminación
         $('#cancelDelete').click(function() {
         $('#deleteModal').modal('hide');
         albumIdToDelete = null;
        });
        // Confirma la eliminación
        $('#confirmDelete').click(function() {
        if (albumIdToDelete) {
        $.post('<?= base_url('albums/delete') ?>/' + albumIdToDelete, function(response) {
            if (response.status === 'success') {
                location.reload();
            } else {
                alert('Error al eliminar el álbum: ' + response.message);
            }
        }).fail(function(xhr, status, error) {
            console.log('Error:', xhr.responseText);
            alert('Error al eliminar el álbum');
        });
    }
});
      //buscar
        $(document).ready(function() {
        $('#nombre').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#miTabla tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
        });
    });

    $('#autor').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#miTabla tbody tr').filter(function() {
            $(this).toggle($(this).find('td:nth-child(3)').text().toLowerCase().indexOf(value) > -1);
        });
    });
});
    //modal agregar
      $(document).ready(function() {
       $('#openModalButton').click(function() {
        $('#addModal').modal('show');
    });
    $('#addForm').submit(function(event) {
        event.preventDefault(); 
        var formData = $(this).serialize(); 
        $.post('<?= base_url('albums/save') ?>', formData, function(response) {
            location.reload(); 
        }).fail(function() {
            alert('Error al guardar el álbum');
        });
    });
});

     // Editar álbum
$('.edit-button').click(function() {
        var albumId = $(this).data('id');
        $.get('<?= base_url('albums/get') ?>/' + albumId, function(response) {
            if (response.status === 'success') {
                var album = response.album;
                var genres = response.genres;

                $('#editId').val(album.id);
                $('#editName').val(album.name);
                $('#editAuthor').val(album.author);

                // Limpiar y cargar géneros
                $('#editGenreSelect').empty();
                $.each(genres, function(index, genre) {
                    $('#editGenreSelect').append($('<option>', {
                        value: genre.id,
                        text: genre.name,
                        selected: genre.id == album.genre_id
                    }));
                });

                $('#editModal').modal('show');
            } else {
                alert('Error al cargar los datos del álbum');
            }
        }).fail(function() {
            alert('Error al cargar los datos del álbum');
        });
    });

    // Actualizar álbum
    $('#editForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.post('<?= base_url('albums/update') ?>', formData, function(response) {
            location.reload();
        }).fail(function() {
            alert('Error al actualizar el álbum');
        });
    });
    </script>
</body>
</html>
