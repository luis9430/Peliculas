<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



# <p align="center" style="color:#861521"> Sistema de peliculas </p>

## <p align="center" > Instalacion

##### - Clonar el repositorio url: 
##### - Entrar a la carpeta y en la linea de comandos ejecutar: <span style="color:#578574"> Composer update </span>
##### - Ejecutar el comando : <span style="color:#578574"> php artisan migrate </span> 
##### - Ejecutar el comando:  <span style="color:#578574"> php artisan key:generate </span> 
##### - Ejecutar el comando:  <span style="color:#578574">npm install </span> 
##### - Ejecutar el comando:  <span style="color:#578574"> npm run build </span> 
##### - Ejecutar el comando:  <span style="color:#578574"> npm run dev </span> 


#
#####  <p align="center" > existen 3 maneras de entrar  </p>

     localhost/peliculas 
     peliculas.test ( con laragon)
     con el comando php artisan serve  http://127.0.0.1:8000


## <p align="center" > API REST

##### -  se ubican en app/http/Controllers/RestApi
#
####  <p align="center" > BaseCrudController.php
#

#####  su ubicacion es app/http/Controllers/BaseCrudController.php
##### con ese metodo se generan las funciones principales

##### - consultar por condicion (where) y seleccionar campos en la consulta (select)
##### - admite relaciones (with)
##### - metodo para create , update, delete 
##### - se reutiliza para el crud de comentarios y peliculas
##### - dentro de BaseCrudController.php vienen comentarios que explican el funcionamiento 





## <p align="center" > endpoints

##### - Todos los endpoints deben tener una url de inicio seguido del endpoint ejemplo :

http://127.0.0.1:8000/api/Details
peliculas.test/api/Details
localhost/peliculas/api/Details


 ##### <span style="color:#578574"> <strong style="color:#578574"> api/Details </strong> </span> - se obtienen los detalles de las peliculas 
 
 ##### <span style="color:#578574"> <strong style="color:#578574"> api/List </strong> </span> - se obtienen la lista de las peliculas  (titulo, sinopsis, poster)

 ##### <span style="color:#578574"> <strong style="color:#578574"> api/register </strong> </span> - se puede registrar un usuario nuevo

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/login </strong> </span> - se puede realizar un login al sistena

  ###### Comentarios

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/comments [GET] </strong> </span> - se pueden visualizar los comentarios

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/comments [POST] </strong> </span> - se puede agregar un nuevo comentario

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/comments/{id} [PUT] </strong> </span> - se puede editar un comentario
  
  ##### <span style="color:#578574"> <strong style="color:#578574"> api/comments/{id} [DELETE] </strong> </span> - se puede eliminar un comentario

###### Peliculas

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/movies [GET] </strong> </span> - se pueden visualizar las peliculas

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/movies [POST] </strong> </span> - se puede agregar una nuevoa pelicula

  ##### <span style="color:#578574"> <strong style="color:#578574"> api/movies/{id} [PUT] </strong> </span> - se puede editar una pelicula
  
  ##### <span style="color:#578574"> <strong style="color:#578574"> api/movies/{id} [DELETE] </strong> </span> - se puede eliminar una pelicula