var tdElements = document.querySelectorAll('td[data-colname="Children of"]');
    
    // Itera sobre as td selecionadas
    tdElements.forEach(function(td) {
      // Seleciona o link dentro de cada td
      var link = td.querySelector('a');
      
      // Verifica se existe um link dentro da td
      if (link) {
        // Altera o texto do link para "Ver mais"
        link.textContent = "See chapters";
      }
    });
