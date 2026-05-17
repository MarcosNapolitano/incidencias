document.addEventListener('DOMContentLoaded', function() {

  document.querySelectorAll('.btn-editar-tipo').forEach(function(btn) {
    btn.addEventListener('click', function() {
      let id = this.dataset.id;
      let nombre = this.dataset.nombre;
      let mensaje = this.dataset.mensaje;
      let aplica = JSON.parse(this.dataset.aplica);

      document.querySelector('#modalTipo .modal-title').textContent = 'Editar Tipo';

      document.querySelector('#modalTipo input[name="nombre"]').value = nombre;
      
      document.querySelector('#modalTipo textarea[name="mensaje"]').value = mensaje;

      document.querySelectorAll('#modalTipo input[type="checkbox"]')
        .forEach(function(chk) {
          chk.checked = aplica.includes(chk.value);
        });

      console.log("hi")
      document.querySelector('#modalTipo input[name="id_tipo"]').value = id;
    });
  });

  document.querySelectorAll('.btn-editar-categoria').forEach(function(btn) {
    btn.addEventListener('click', function() {
      let id = this.dataset.id;
      let nombre = this.dataset.nombre;

      document.querySelector('#modalCategoria .modal-title').textContent = 'Editar Categoría';

      document.querySelector('#modalCategoria input[name="nombre"]').value = nombre;

      document.querySelector('#modalCategoria input[name="id_categoria"]').value = id;
    });
  });

  document.querySelectorAll('.btn-editar-encargado').forEach(function(btn) {
    btn.addEventListener('click', function() {
      let id = this.dataset.id;
      let nombre = this.dataset.nombre;

      document.querySelector('#modalEncargado .modal-title').textContent = 'Editar Encargado';

      document.querySelector('#modalEncargado input[name="nombre"]').value = nombre;

      document.querySelector('#modalEncargado input[name="id_encargado"]').value = id;
    });
  });
});
