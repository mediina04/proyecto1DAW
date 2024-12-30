<?php
require_once 'model/usuario.php';
require_once 'model/usuariosDAO.php';
require_once 'model/producto.php';
require_once 'model/productosDAO.php';

class AdminController {

    // Ver todos los usuarios
    public function verUsuarios() {
        $usuarioDAO = new UsuariosDAO(DataBase::connect());
        require_once 'views/admin/usuarios.php'; // Mostrar usuarios en vista
    }

    // Ver todos los productos
    public function verProductos() {
        $productosDAO = new ProductosDAO(DataBase::connect());
        $productos = $productosDAO->obtenerTodos();
        require_once 'views/admin/productos.php'; // Mostrar productos en vista
    }

    // Crear un nuevo producto
    public function crearProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $imagenPrincipal = $_POST['imagen_principal'];
            $imagenSecundaria = $_POST['imagen_secundaria'];

            $producto = new Producto(null, $nombre, $descripcion, $precio, $imagenPrincipal, $imagenSecundaria);
            $productoDAO = new ProductosDAO(DataBase::connect());

            if ($productoDAO->agregar($producto)) {
                echo "Producto creado con éxito.";
            } else {
                echo "Error al crear el producto.";
            }
        }
    }
}
