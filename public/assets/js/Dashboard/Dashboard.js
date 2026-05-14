  document.addEventListener('DOMContentLoaded', function () {
    const rowsPerPage = 2;
    const table = document.querySelector('.responsive-table tbody');
    const pagination = document.createElement('ul');
    pagination.className = 'pagination center-align';

    let rows = Array.from(table.querySelectorAll('tr'))
      .filter(row => !row.querySelector('td[colspan]')); // Excluye mensaje "No hay préstamos registrados"

    const totalPages = Math.ceil(rows.length / rowsPerPage);
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'center-align';
    paginationContainer.appendChild(pagination);

    const showPage = (page) => {
      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
      });

      Array.from(pagination.children).forEach((li, i) => {
        li.classList.toggle('active', i === (page - 1));
      });
    };

    if (totalPages > 1) {
      for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = '#!';
        a.textContent = i;
        a.className = 'teal-text';
        li.appendChild(a);
        li.addEventListener('click', () => showPage(i));
        pagination.appendChild(li);
      }

      // Insertar después de la tabla
      table.parentElement.appendChild(paginationContainer);
      showPage(1);
    }
  });