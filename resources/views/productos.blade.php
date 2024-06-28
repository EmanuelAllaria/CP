<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scrollbar-width: none;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Gestión de Productos</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/15fbf0e0d4.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'inter', sans-serif;
            background-color: #11121E;
            padding: 20px;
            margin-left: 200px;
        }

        .producto-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: 1fr;
            grid-column-gap: 5px;
            grid-row-gap: 5px;
        }

        .producto-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .producto-item .producto-sku {
            font-size: 1rem;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .producto-item .producto-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .producto-item .producto-description {
            margin-bottom: 10px;
        }

        .producto-item .producto-dates {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .producto-item .producto-budget {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .producto-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .producto-buttons button {
            margin-left: 5px;
        }

        @media screen and (max-width: 999px) {
            .producto-list {
                grid-template-columns: 1fr;
            }

            .producto-buttons {
                position: relative;
                top: 0;
                right: 0;
            }

            .producto-buttons button {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4 text-white">Gestión de Productos</h1>

        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createProductoModal">
            Crear Nuevo Producto
        </button>
        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
            Subir Productos con Excel
        </button>
        <div class="producto-list">
            @foreach ($productos as $producto)
            <div class="producto-item">
                <div class="producto-buttons">
                    <button class="btn btn-sm btn-primary edit-producto" data-producto-id="{{ $producto['id'] }}">Editar</button>
                    <button class="btn btn-sm btn-danger delete-producto" data-producto-id="{{ $producto['id'] }}">Eliminar</button>
                </div>
                <div class="producto-sku">SKU: {{ $producto['id'] }}</div>
                <div class="producto-name">{{ $producto['name'] }}</div>
                <div class="producto-description">{{ $producto['description'] }}</div>
                <div class="producto-dates">Stock: {{ $producto['stock'] }}</div>
                <div class="producto-budget">Precio: ${{ number_format($producto['price'], 2, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para la creación de nuevo producto -->
    <div class="modal fade" id="createProductoModal" tabindex="-1" role="dialog" aria-labelledby="createProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductoModalLabel">Crear Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createProductoForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="productoName">Nombre del Producto</label>
                            <input type="text" class="form-control" id="productoName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="productoDescription">Descripción</label>
                            <textarea class="form-control" id="productoDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="productoPrice">Precio</label>
                            <input type="number" class="form-control" id="productoPrice" name="price" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="productoStock">Stock</label>
                            <input type="number" class="form-control" id="productoStock" name="stock" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para la carga de productos en masa -->
    <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadExcelModalLabel">Subir Productos con Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="uploadExcelForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="excelFile">Archivo Excel</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls, .csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Subir Productos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript para manejar la creación de productos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createProductoForm = document.getElementById('createProductoForm');

            createProductoForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(createProductoForm);
                formData.append('user_id', <?php echo $user_id ?>);

                fetch('/producto', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#createProductoModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });

            const uploadExcelForm = document.getElementById('uploadExcelForm');

            uploadExcelForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(uploadExcelForm);
                formData.append('user_id', <?php echo $user_id ?>);

                fetch('/productos/uploadExcel', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#uploadExcelModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <!-- JavaScript para editar y eliminar productos -->
    <script>
        // Escuchar eventos de clic en botones de editar y eliminar
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-producto');
            const deleteButtons = document.querySelectorAll('.delete-producto');

            // Manejar clic en botón de editar
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productoId = button.getAttribute('data-producto-id');
                    // Redirigir a la página de edición o ejecutar la acción necesaria
                    alert(`Editar producto ${productoId}`);
                });
            });

            // Manejar clic en botón de eliminar
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productoId = button.getAttribute('data-producto-id');
                    // Confirmar eliminación
                    if (confirm(`¿Estás seguro de eliminar el producto ${productoId}?`)) {
                        // Aquí puedes realizar una petición AJAX para eliminar el producto
                        alert(`Eliminar producto ${productoId}`);
                    }
                });
            });
        });
    </script>
</body>

</html>