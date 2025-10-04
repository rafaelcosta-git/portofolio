document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('contactForm');

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    // Pegar valores e remover espaços extras
    const name = document.getElementById('name').value.trim();
    const birthDateValue = document.getElementById('birthDate').value;
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    // Verificar se todos os campos estão preenchidos
    if (!name || !birthDateValue || !phone || !email || !message) {
      alert('Todos os campos são obrigatórios.');
      return;
    }

    // Validar idade
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

    // Regex para telefone (9 dígitos) e email
    const phoneRegex = /^\d{9}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!phoneRegex.test(phone)) {
      alert('Por favor, insira um número de telefone válido (9 dígitos).');
      return;
    }
    if (!emailRegex.test(email)) {
      alert('Por favor, insira um endereço de e-mail válido.');
      return;
    }

    // Se tudo estiver OK
    alert('Dados enviados com sucesso!');
    form.reset();
  });
});
