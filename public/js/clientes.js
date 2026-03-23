(() => {
  const tableEl = document.getElementById('clientesTable');
  const reloadBtn = document.getElementById('reloadClientesBtn');
  const alertBox = document.getElementById('clientesAlert');
  const modalEl = document.getElementById('modalPedidos');
  const titleEl = document.getElementById('modalTitulo');
  const pedidosBody = document.getElementById('tablaPedidos');

  if (!tableEl || !reloadBtn || !alertBox || !modalEl || !titleEl || !pedidosBody) return;

  const modal = new bootstrap.Modal(modalEl);

  const showAlert = (message, type = 'danger') => {
    alertBox.textContent = message;
    alertBox.className = `alert alert-${type}`;
  };

  const clearAlert = () => {
    alertBox.className = 'alert d-none';
    alertBox.textContent = '';
  };

  const setButtonLoading = (button, isLoading, idleLabel, loadingLabel = 'Cargando...') => {
    if (isLoading) {
      button.disabled = true;
      button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>${loadingLabel}`;
      return;
    }

    button.disabled = false;
    button.textContent = idleLabel;
  };

  const escapeHtml = (value) => {
    const div = document.createElement('div');
    div.textContent = String(value ?? '');
    return div.innerHTML;
  };

  const formatDate = (value) => {
    if (!value) return '-';
    const date = new Date(`${value}T00:00:00`);
    if (Number.isNaN(date.getTime())) return value;

    return new Intl.DateTimeFormat('es-MX', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    }).format(date);
  };

  const formatPrice = (value) => {
    const amount = Number(value || 0);

    return amount.toLocaleString('es-MX', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  };

  const table = $('#clientesTable').DataTable({
    processing: true,
    responsive: true,
    pageLength: 100,
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json',
    },
    ajax: async (_data, callback) => {
      clearAlert();
      try {
        const response = await fetch('/clientes/datatable', {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const payload = await response.json();

        if (!response.ok || !payload.ok) {
          showAlert(payload.message || 'No se pudo cargar clientes.');
          callback({ data: [] });
          return;
        }

        callback({ data: payload.data || [] });
      } catch (_error) {
        showAlert('Error de red al consultar clientes.');
        callback({ data: [] });
      }
    },
    columns: [
      { data: 'nombre' },
      {
        data: 'telefono',
        render: (value, _type, row) => {
          const tel = value || 'Sin teléfono';
          return `<button type="button" class="btn btn-link p-0 align-baseline js-pedidos" data-id="${row.id}" data-telefono="${escapeHtml(tel)}">${escapeHtml(tel)}</button>`;
        },
      },
      { data: 'pedidos_pagados' },
      { data: 'pedidos_no_pagados' },
    ],
  });

  const loadPedidos = async (clientId, telefono, triggerBtn) => {
    titleEl.textContent = `Pedidos de ${telefono || 'cliente'}`;
    pedidosBody.innerHTML = '<tr><td colspan="4" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Cargando pedidos...</td></tr>';
    modal.show();
    setButtonLoading(triggerBtn, true, telefono || 'Ver', 'Cargando...');

    try {
      const response = await fetch(`/clientes/pedidos/${clientId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });
      const payload = await response.json();

      if (!response.ok || !payload.ok) {
        pedidosBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">No se pudieron cargar los pedidos.</td></tr>';
        return;
      }

      const rows = payload.data || [];
      if (rows.length === 0) {
        pedidosBody.innerHTML = '<tr><td colspan="4" class="text-center">Sin pedidos</td></tr>';
        return;
      }

      pedidosBody.innerHTML = '';
      for (const order of rows) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>#${escapeHtml(order.id)}</td>
          <td>${escapeHtml(formatDate(order.fecha))}</td>
          <td>$${escapeHtml(formatPrice(order.total))}</td>
          <td class="text-center"><span class="badge ${Number(order.pagado) === 1 ? 'text-bg-success' : 'text-bg-danger'}">${Number(order.pagado) === 1 ? 'Sí' : 'No'}</span></td>
        `;
        pedidosBody.appendChild(tr);
      }
    } catch (_error) {
      pedidosBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error de red al cargar pedidos.</td></tr>';
    } finally {
      setButtonLoading(triggerBtn, false, telefono || 'Ver');
    }
  };

  reloadBtn.addEventListener('click', () => {
    setButtonLoading(reloadBtn, true, 'Actualizar');
    table.ajax.reload(() => setButtonLoading(reloadBtn, false, 'Actualizar'));
  });

  tableEl.addEventListener('click', (event) => {
    const target = event.target;
    if (!(target instanceof HTMLButtonElement)) return;

    if (target.classList.contains('js-pedidos') && target.dataset.id) {
      loadPedidos(target.dataset.id, target.dataset.telefono || 'cliente', target);
    }
  });
})();
