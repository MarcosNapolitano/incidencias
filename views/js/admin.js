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
          chk.checked = aplica[id].estados.includes(parseInt(chk.value));
        });

      document.querySelector('#modalTipo input[name="id_tipo"]').value = id;
    });
  });

  document.querySelectorAll('.btn-new-tipo').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelector('#modalTipo .modal-title').textContent = 'Nuevo Tipo';

      document.querySelector('#modalTipo input[name="nombre"]').value = '';
      
      document.querySelector('#modalTipo textarea[name="mensaje"]').value = '';

      document.querySelectorAll('#modalTipo input[type="checkbox"]')
        .forEach(function(chk) {
          chk.checked = false;
        });

      document.querySelector('#modalTipo input[name="id_tipo"]').value = '';
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

  document.querySelectorAll('.btn-new-categoria').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelector('#modalCategoria .modal-title').textContent = 'Nueva Categoría';

      document.querySelector('#modalCategoria input[name="nombre"]').value = '';

      document.querySelector('#modalCategoria input[name="id_categoria"]').value = '';
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

  document.querySelectorAll('.btn-nuevo-encargado').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelector('#modalEncargado .modal-title').textContent = 'Nuevo Encargado';

      document.querySelector('#modalEncargado input[name="nombre"]').value = '';

      document.querySelector('#modalEncargado input[name="id_encargado"]').value = '';
    });
  });
});
