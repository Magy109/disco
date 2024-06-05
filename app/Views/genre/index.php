<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generos</title>
    <link rel="stylesheet" href="semantic/css/semantic.min.css">
    <link rel="stylesheet" href="semantic/components/icon.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="ui container">
        <h1></h1>
        <h2 class="ui olive header">Proyecto Final</h2>
        <center><h2 class="ui olive header">Tabla Genero</h2></center>
        <button class="ui button" id="openModalButton" style="background-color: white;">
            <i class="plus icon" style="color: black;"></i> Agregar
        </button>

        
        <div class="ui large icon input">
            <input type="text" placeholder="Buscar..." id="busqueda">
            <i class="search icon"></i>
        </div>

        <a href="albums" class="ui right labeled icon olive basic button">Ver Álbumes<i class="right arrow icon"></i></a>
       
        
        <table id="miTabla" class="ui olive inverted celled table dataTable">
            <thead><th>Id</th><th>Nombre</th><th>Opciones</th></thead>
            <tbody>
                <?php $i=1;?>
                <?php foreach($genres as $genre): ?>
                    <tr>
                        <td><?=$i++?></td>
                        <td><?=$genre->name?></td>
                        <td>
                            <button class="ui olive button edit-button" data-id="<?=$genre->id?>"><i class="edit icon"></i> Editar</button>
                            <button class="ui olive button delete-button" data-id="<?=$genre->id?>"><i class="trash alternate icon"></i> Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Agregar-->
    <div class="ui small modal" id="addModal">
        <div class="header">Agregar Nuevo Género</div>
        <div class="content">
            <form class="ui form" id="addForm">
                <div class="field">
                    <label for="newName">Nombre:</label>
                    <input type="text" id="newName" name="name" required>
                </div>
                <button type="submit" class="ui olive button">Guardar</button>
            </form>
        </div>
    </div>
    <!-- Modal de Edición -->
    <div class="ui small modal" id="editModal">
        <div class="header">Editar Género</div>
        <div class="content">
            <form class="ui form" id="editForm">
                <div class="field">
                    <label for="editName">Nombre:</label>
                    <input type="text" id="editName" name="name" required>
                </div>
                <input type="hidden" id="editId" name="id">
                <button type="submit" class="ui olive button">Guardar Cambios</button>
            </form>
        </div>
    </div>
    <!-- Modal Confirmacion-->
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

    <script src="semantic/js/semantic.min.js"></script>

    <script>
        $(document).ready(function() {
            var genreIdToDelete;

            $('#busqueda').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#miTabla tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            //abre modal agregar
            $('#openModalButton').click(function() {
                $('#addModal').modal('show');
            });

            //abre modal edicion
            $('.edit-button').click(function() {
                var genreId = $(this).data('id');
                var genreName = $(this).closest('tr').find('td:nth-child(2)').text(); 
                $('#editId').val(genreId); 
                $('#editName').val(genreName); 
                $('#editModal').modal('show'); 
            });
            //edicion genre/update
             $('#editForm').submit(function(event) {
                 event.preventDefault();
                   var formData = $(this).serialize();
                   $.post('<?= base_url('Genre/update') ?>', formData, function(response) {
                    location.reload(); 
              }).fail(function() {
                      alert('Error al editar el género');
                 });
              });
               //genre/save
            $('#addForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.post('<?= base_url('Genre/save') ?>', formData, function(response) {
                    location.reload(); 
                }).fail(function() {
                    alert('Error al guardar el género');
                });
            });
           //abre modal de confirmacion
            $('.delete-button').click(function() {
                genreIdToDelete = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // Cancela la eliminación
            $('#cancelDelete').click(function() {
                $('#deleteModal').modal('hide');
                genreIdToDelete = null;
            });

            // Confirma la eliminación
            $('#confirmDelete').click(function() {
                if (genreIdToDelete) {
                    $.post('<?= base_url('Genre/delete') ?>/' + genreIdToDelete, function(response) {
                        location.reload(); 
                    }).fail(function(xhr, status, error) {
                        console.log('Error:', xhr.responseText);
                        alert('Error al eliminar el género');
                    });
                }
            });
        });
    </script>
</body>
</html>
