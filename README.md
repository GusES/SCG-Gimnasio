# SCG-Gimnasio
<p>    Aplicacion Web para la gestión de información del establecimiento y clientes de un centro de musculación o gimnasio.<br/>    </p>

# Requisitos
<ul>
   <li>XAMPP 7.3.4 (en Windows)</li>
   <li>Apache 2.4.39</li>
   <li>PHP 7.3.4</li>
   <li>MySQL/MariaDB 10.1.38</li>
   <li>phpMyAdmin 4.8.5</li>
 </ul>

# Tecnologias utilizadas
<ul>
   <li>PHP 7.3.4</li>
   <li>Apache 2.4.39</li>
   <li>MySQL/MariaDB 10.1.38</li>   
   <li>JavaScript ES5</li>
   <li>HTML5 / CSS3</li>
   <li>JQuery</li>
   <li>Bootstrap</li>
 </ul>
 
# Configuracion
1 - Importar la base de datos dbgym.sql incluida en la carpeta "base de datos". La importacion creara una base con nombre dbgym por defecto.<br/>
2 - Volcar los archivos en el directorio htdocs(raiz o subcarpeta) dentro de la carpeta XAMPP, respetando la jerarquia dada.<br/>
3 - Abrir el localhost si se volcaron en raiz, o localhost/subdirecto de caso contrario, previo inicio de XAMPP.<br/>

# Credenciales
Usuario:    <b>admin</b> <br />
Contraseña: <b>admin</b> <br/>
En esta version la contraseña no esta hasheada, en la version del cliente se utilizo la funcion Crypt().

# Guia de inicio rapido con ilustraciones [20 Paginas]
https://github.com/GusES/SCG-Gimnasio/blob/master/Guia%20de%20inicio%20rapido.pdf

# Que se Realizo?
Login de Inicio de sesion.  <br />
<hr>

Seccion de membresias con creacion, edicion, eliminacion de las mismas. 
<ul>
   <li>Asignacion de precios</li>
   <li>Asignacion de encargado de la membresia</li>   
</ul>
<hr>

Seccion de clientes con creacion, edicion, eliminacion de los mismos. <br />
<ul>
   <li>Creacion de grupos</li>
   <li>Asignacion de membresias al cliente o grupo</li>
   <li>Asignacion del cliente a un grupo</li>
   <li>Seguimiento de asistencias del cliente</li>
   <li>Seguimiento de membresias asignadas al cliente</li>
</ul>
<hr>

Seccion de comprobantes con creacion, edicion, eliminacion de los mismos. <br />
<ul>
   <li>Generacion de comprobantes imprimibles</li>
   <li>Generacion de imprimible de credito a favor del cliente</li>
   <li>Seguimiento de pagos del cliente</li>   
</ul>
<hr>

Seccion de entrenamientos con creacion, edicion, eliminacion de los mismos. <br />
<ul>
   <li>Creacion de ejercicios</li>
   <li>Creacion de rutinas</li>
   <li>Asignacion de rutinas a clientes o grupos</li>   
   <li>Historial de rutinas asignadas a clientes o grupos</li>   
   <li>Generacion de rutinas imprimibles</li>   
   <li>Generacion de codigo QR para descargar rutinas en PDF</li>   
</ul>
<hr>

Registro de ingresos de clientes al establecimiento. <br />
<ul>
   <li>Eleccion de la membresia a realizar</li>
   <li>Informacion del estado economico de las membresias del cliente</li>       
</ul>
<hr>

Registro de ingresos de clientes al establecimiento. <br />
<ul>
   <li>Eleccion de la membresia a realizar</li>
   <li>Informacion del estado economico de las membresias del cliente</li>   
   <li>Conexion al sistema mediante la red local del establecimiento</li>
</ul>
<hr>

Panel de informacion del establecimiento. <br />
<ul>
   <li>Cantidad de clientes activos</li>
    <li>Cantidad de clientes con pagos retrasados o adeudados</li>
    <li>Cantidad recaudada en un periodo de tiempo por categorias</li>
</ul>
<br/>
Y algunas funcionalidades extra
<br/ >
