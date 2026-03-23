(() => {
  const tableEl = document.getElementById('usersTable');
  const alertBox = document.getElementById('usersAlert');
  const newUserBtn = document.getElementById('newUserBtn');
  const saveBtn = document.getElementById('saveUserBtn');
  const userForm = document.getElementById('userForm');
  const userModalEl = document.getElementById('userModal');

  if (!tableEl || !alertBox || !newUserBtn || !saveBtn || !userForm || !userModalEl) return;

  const modal = new bootstrap.Modal(userModalEl);
  const title = document.getElementById('userModalTitle');
  const idInput = document.getElementById('userId');
  const nameInput = document.getElementById('nombre');
  const userInput = document.getElementById('user');
  const passInput = document.getElementById('pass');
  const checkboxes = Array.from(document.querySelectorAll('.permission-checkbox'));
  const rowStore = new Map();

  const showAlert = (message, type = 'danger') => {
    alertBox.textContent = message;
    alertBox.className = `alert alert-${type}`;
  };

  const clearAlert = () => {
    alertBox.className = 'alert d-none';
    alertBox.textContent = '';
  };

  const setButtonLoading = (button, isLoading, idleLabel, loadingLabel = 'Procesando...') => {
    if (!button) return;

    if (isLoading) {
      button.disabled = true;
      button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>${loadingLabel}`;
      return;
    }

    button.disabled = false;
    button.textContent = idleLabel;
  };

  const getSelectedPermissions = () => checkboxes.filter((cb) => cb.checked).map((cb) => cb.value);

  const setSelectedPermissions = (permissions = []) => {
    checkboxes.forEach((cb) => {
      cb.checked = permissions.includes(cb.value);
    });
  };

  const setFormDisabled = (disabled) => {
    const fields = userForm.querySelectorAll('input, button, select, textarea');
    fields.forEach((field) => {
      if (field !== saveBtn) {
        field.disabled = disabled;
      }
    });
  };

  const resetForm = () => {
    userForm.reset();
    idInput.value = '';
    setSelectedPermissions([]);
  };

  const openCreateModal = () => {
    resetForm();
    title.textContent = 'Nuevo usuario';
    passInput.required = true;
    modal.show();
  };

  const openEditModal = (row) => {
    resetForm();
    title.textContent = `Editar usuario #${row.id}`;
    idInput.value = row.id;
    nameInput.value = row.nombre;
    userInput.value = row.user;
    passInput.required = false;
    setSelectedPermissions(row.permissions || []);
    modal.show();
  };

  const table = $('#usersTable').DataTable({
    processing: true,
    responsive: true,
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json',
    },
    ajax: async (_data, callback) => {
      clearAlert();
      try {
        const response = await fetch('/admin/usuarios/data', {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.ok) {
          showAlert(payload.message || 'No se pudo cargar la lista de usuarios.');
          callback({ data: [] });
          return;
        }

        const rows = payload.data || [];
        rowStore.clear();
        rows.forEach((row) => rowStore.set(String(row.id), row));
        callback({ data: rows });
      } catch (_error) {
        showAlert('Error al cargar usuarios.');
        callback({ data: [] });
      }
    },
    columns: [
      { data: 'id' },
      { data: 'nombre' },
      { data: 'user' },
      {
        data: 'permissions',
        render: (value) => Array.isArray(value) ? value.join(', ') : '',
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        className: 'text-end',
        render: (_value, _type, row) =>
          `<button class="btn btn-sm btn-outline-dark me-2" data-action="edit" data-id="${row.id}" type="button">Editar</button>` +
          `<button class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${row.id}" type="button">Eliminar</button>`,
      },
    ],
  });

  const reloadTable = () => table.ajax.reload(null, false);

  const submitUser = async () => {
    const id = idInput.value.trim();
    const endpoint = id ? `/admin/usuarios/${id}` : '/admin/usuarios';
    const formData = new FormData(userForm);
    getSelectedPermissions().forEach((perm) => formData.append('permissions[]', perm));

    setButtonLoading(saveBtn, true, 'Guardar', 'Guardando...');
    setFormDisabled(true);

    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
      });

      const payload = await response.json();

      if (!response.ok || !payload.ok) {
        const details = payload.errors ? Object.values(payload.errors).join(' ') : payload.message;
        showAlert(details || 'No se pudo guardar el usuario.');
        return;
      }

      modal.hide();
      showAlert(payload.message || 'Usuario guardado.', 'success');
      reloadTable();
    } finally {
      setButtonLoading(saveBtn, false, 'Guardar');
      setFormDisabled(false);
    }
  };

  const deleteUser = async (id, button) => {
    const ok = window.confirm('¿Eliminar este usuario?');
    if (!ok) return;

    setButtonLoading(button, true, 'Eliminar', 'Eliminando...');

    try {
      const response = await fetch(`/admin/usuarios/${id}/delete`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(),
      });

      const payload = await response.json();

      if (!response.ok || !payload.ok) {
        showAlert(payload.message || 'No se pudo eliminar el usuario.');
        return;
      }

      showAlert(payload.message || 'Usuario eliminado.', 'success');
      reloadTable();
    } finally {
      setButtonLoading(button, false, 'Eliminar');
    }
  };

  newUserBtn.addEventListener('click', openCreateModal);
  saveBtn.addEventListener('click', submitUser);

  tableEl.addEventListener('click', (event) => {
    const target = event.target;
    if (!(target instanceof HTMLButtonElement)) return;

    const action = target.dataset.action;
    const id = target.dataset.id;
    if (!action || !id) return;

    if (action === 'edit') {
      const row = rowStore.get(String(id));
      if (row) openEditModal(row);
    }

    if (action === 'delete') {
      deleteUser(id, target);
    }
  });
})();
