// ===============================
// 1️⃣ FORMULÁRIO DE CONTACTO (o teu código original)
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('contactForm');

  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const name = document.getElementById('name').value.trim();
      const birthDateValue = document.getElementById('birthDate') ? document.getElementById('birthDate').value : null;
      const phone = document.getElementById('phone') ? document.getElementById('phone').value.trim() : "";
      const email = document.getElementById('email').value.trim();
      const message = document.getElementById('message').value.trim();

      if (!name || (!birthDateValue && document.getElementById('birthDate')) || !email || !message) {
        alert('Todos os campos são obrigatórios.');
        return;
      }

      if (birthDateValue) {
        const birthDate = new Date(birthDateValue);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }
        if (age < 18) {
          alert('Você deve ter mais de 18 anos para preencher este formulário.');
          return;
        }
      }

      const phoneRegex = /^\d{9}$/;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (document.getElementById('phone') && !phoneRegex.test(phone)) {
        alert('Por favor, insira um número de telefone válido (9 dígitos).');
        return;
      }
      if (!emailRegex.test(email)) {
        alert('Por favor, insira um endereço de e-mail válido.');
        return;
      }

      alert('Dados enviados com sucesso!');
      form.reset();
    });
  }
});

// ===============================
// 2️⃣ SIMULADOR DE ORÇAMENTO (versão final corrigida)
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  const orcamentoForm = document.getElementById('orcamentoForm');
  const resultado = document.getElementById('resultado');

  if (orcamentoForm && resultado) {
    orcamentoForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const tipoSite = parseFloat(document.getElementById('tipoSite').value) || 0;
      const prazo = parseInt(document.getElementById('prazo').value) || 0;

      const extrasSelecionados = document.querySelectorAll('input[type="checkbox"]:checked');
      let extrasTotal = 0;
      extrasSelecionados.forEach(extra => {
        extrasTotal += parseFloat(extra.value);
      });

      let total = tipoSite + extrasTotal;
      if (prazo > 0 && prazo < 4) total *= 1.2;

      if (tipoSite === 0) {
        resultado.textContent = 'Por favor, selecione o tipo de site.';
        resultado.style.color = 'red';
        return;
      }

      resultado.textContent = `€${total.toFixed(2)}`;
      resultado.style.color = 'green';
    });
  }
});


// ===============================
// 3️⃣ FAQ DINÂMICO (para contactos.html)
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  const faqItems = document.querySelectorAll('#faq .accordion-button');

  if (faqItems.length > 0) {
    faqItems.forEach(button => {
      button.addEventListener('click', function () {
        // Fecha outras perguntas abertas
        faqItems.forEach(btn => {
          if (btn !== this) {
            btn.classList.add('collapsed');
            const collapse = document.querySelector(btn.getAttribute('data-bs-target'));
            collapse.classList.remove('show');
          }
        });
      });
    });
  }
});

// ===============================
// 4️⃣ CARREGAMENTO DINÂMICO DE SERVIÇOS
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  const servicosContainer = document.getElementById('servicos-container');
  const servicoDetalhes = document.getElementById('servico-detalhes');

  if (servicosContainer && servicoDetalhes) {
    function loadServicos() {
      fetch('servicos.json')
        .then(response => {
          if (!response.ok) throw new Error('Erro ao carregar os serviços.');
          return response.json();
        })
        .then(data => {
          const servicos = data.servicos;
          let html = '';

          servicos.forEach((servico, index) => {
            html += `
              <button class="list-group-item list-group-item-action" data-index="${index}">
                ${servico.titulo}
              </button>
            `;
          });

          servicosContainer.innerHTML = html;

          document.querySelectorAll('.list-group-item').forEach(button => {
            button.addEventListener('click', function () {
              const index = this.getAttribute('data-index');
              showServicoDetalhes(servicos[index]);
            });
          });
        })
        .catch(error => {
          servicosContainer.innerHTML = `<p class="text-danger">Erro: ${error.message}</p>`;
        });
    }

    function showServicoDetalhes(servico) {
      servicoDetalhes.innerHTML = `
        <h3 class="text-success">${servico.titulo}</h3>
        <p>${servico.descricao}</p>
        <img src="${servico.imagem}" alt="${servico.titulo}" class="img-fluid rounded shadow">
      `;
    }

    loadServicos();
  }
});

// ===============================
// TESTEMUNHOS DINÂMICOS VIA JSON
// ===============================
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('testemunhos');

  if (container) {
    fetch('testemunhos.json')
      .then(res => {
        if (!res.ok) throw new Error('Erro ao carregar os testemunhos.');
        return res.json();
      })
      .then(data => {
        container.innerHTML = data.clientes.map(cliente => `
          <div class="col-md-4">
            <div class="card bg-dark text-light h-100 shadow text-center p-3">
              <img src="${cliente.imagem}" class="rounded-circle mx-auto mb-3" style="width:120px;height:120px;object-fit:cover;" alt="${cliente.nome}">
              <h5 class="text-success">${cliente.nome}</h5>
              <p class="fst-italic">"${cliente.opiniao}"</p>
            </div>
          </div>
        `).join('');
      })
      .catch(err => {
        container.innerHTML = `<p class="text-danger">${err.message}</p>`;
      });
  }
});
