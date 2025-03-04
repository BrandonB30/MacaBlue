# MacaBlue

MacaBlue es una plataforma de comercio electrónico enfocada en la venta de ropa, diseñada para ofrecer a los usuarios una experiencia de compra en línea ágil y sin complicaciones. Este documento describe los aspectos clave de su diseño y desarrollo.

## 📌 Tecnologías utilizadas

- **HTML**
- **CSS**
- **JavaScript**
- **PHP**
- **XAMPP** (para el servidor local y base de datos)

## 📋 Requisitos previos

Antes de ejecutar el proyecto, asegúrate de tener instalado lo siguiente:

- [XAMPP](https://www.apachefriends.org/es/index.html) (para la base de datos y el servidor local)
- Un navegador web (Google Chrome, Firefox, Edge, etc.)

## 🚀 Instalación y ejecución

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/BrandonB30/MacaBlue.git
   ```
2. **Mover el proyecto a la carpeta htdocs de XAMPP:**
   ```bash
   mv MacaBlue /c/xampp/htdocs/
   ```
3. **Iniciar Apache y MySQL en XAMPP.**
4. **Importar la base de datos:**
   - Accede a `http://localhost/phpmyadmin/`
   - Crea una base de datos llamada `MacaBlue`
   - Importa el archivo `database.sql` que se encuentra en `admin/model`
5. **Acceder a la plataforma:**
   - **Login Admin:** `http://localhost/MacaBlue/admin/login.php`
   - **Vista Cliente:** `http://localhost/MacaBlue/`

## 📸 Capturas de pantalla

### Panel de Administración
![Admin Panel](https://github.com/BrandonB30/MacaBlue/blob/main/assets/images/Panel%20Admin.jpg)

### Página de Productos
![Productos](https://github.com/BrandonB30/MacaBlue/blob/main/assets/images/Panel%20User.jpg)

## 👥 Autores

- **Juan Sebastián Quinto**
- **Brandon Bernal**

## 📄 Licencia

Este proyecto se distribuye bajo la licencia MIT. Para más información, consulta el archivo `LICENSE`.
