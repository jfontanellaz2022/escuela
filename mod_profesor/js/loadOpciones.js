function cargarOpciones() {
    $.get( "html/cargarOpciones.html", function( data ) {
       let titulo = '<h1><i>Seleccione que desea hacer</i></h1><hr>';
       let nav = `<nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item">Opciones</li>
                   </ol>
                 </nav>`;
       $("#breadcrumb").html(nav);
       $("#titulo").html(titulo);
       $("#resultado").html(data);
    });
}