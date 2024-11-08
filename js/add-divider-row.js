// Obtém a parte da URL que contém os parâmetros de consulta
var queryString = window.location.search;

// Verifica se a string contém o parâmetro "child_of="
if (queryString.includes("child_of=")) {
  // Cria uma nova linha HTML como uma string
  var newTableRow = "<tr><td>Chapters:</td></tr>";

  // Obtém a referência para o primeiro elemento <tr> dentro do <tbody>
  var firstTableRow = document.querySelector("tbody tr:first-child");

  // Insere a nova linha após a primeira linha existente
  firstTableRow.insertAdjacentHTML("afterend", newTableRow);

  // Obtém a referência para a nova linha recém-adicionada
  var newAddedRow = firstTableRow.nextElementSibling;

  // Adiciona a classe "table-divider" à nova linha
  newAddedRow.classList.add("table-divider");
}
