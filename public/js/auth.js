(() => {
  const form = document.getElementById('loginForm');
  if (!form) return;

  const alertBox = document.getElementById('loginAlert');
  const submitBtn = form.querySelector('button[type="submit"]');

  const showAlert = (message, type = 'danger') => {
    alertBox.textContent = message;
    alertBox.className = `alert alert-${type}`;
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

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    setButtonLoading(submitBtn, true, 'Entrar', 'Entrando...');

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
      });

      const payload = await response.json();

      if (!response.ok || !payload.ok) {
        showAlert(payload.message || 'No fue posible iniciar sesión.');
        return;
      }

      window.location.href = payload.redirect || '/';
    } catch (error) {
      showAlert('Error de red. Inténtalo nuevamente.');
    } finally {
      setButtonLoading(submitBtn, false, 'Entrar');
    }
  });
})();
