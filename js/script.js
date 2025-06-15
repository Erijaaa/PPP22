document.addEventListener("DOMContentLoaded", function () {
  // Gestion de la sidebar
  const menuItems = document.querySelectorAll(".menu-item");
  const contents = document.querySelectorAll(".main-content");

  menuItems.forEach((item) => {
    item.addEventListener("click", function () {
      menuItems.forEach((i) => i.classList.remove("active"));
      contents.forEach((c) => c.classList.remove("active"));
      this.classList.add("active");
      const contentId = this.id + "-content";
      document.getElementById(contentId).classList.add("active");
    });
  });

  // Stocker les modèles de lignes pour chaque tableau
  const tableTemplates = {};
  // Sauvegarder les modèles de lignes au chargement
  document.querySelectorAll("table").forEach((table) => {
    if (table.id) {
      const firstRow = table.querySelector("tbody tr");
      if (firstRow) {
        const template = firstRow.cloneNode(true);

        // Vider les champs du modèle
        template
          .querySelectorAll("input")
          .forEach((input) => (input.value = ""));
        template
          .querySelectorAll("select")
          .forEach((select) => (select.selectedIndex = 0));

        // Retirer les éventuelles valeurs spécifiques
        const deleteBtn = template.querySelector(".btn-delete");
        if (deleteBtn) {
          deleteBtn.addEventListener("click", function (e) {
            e.preventDefault();
            template.remove();
            updateRowNumbers(table.id);
          });
        }

        tableTemplates[table.id] = template;
      }
    }
  });

  // Fonction pour mettre à jour les numéros des lignes
  function updateRowNumbers(tableId) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    if (!tbody) return;

    const rows = tbody.querySelectorAll("tr");
    rows.forEach((row, index) => {
      const firstCell = row.querySelector("td:first-child");
      if (firstCell && /^\d+$/.test(firstCell.textContent.trim())) {
        firstCell.textContent = index + 1;
      }
    });
  }

  // Fonction pour créer une nouvelle ligne
  function createNewRow(tableId) {
    const template = tableTemplates[tableId];
    if (!template) return null;

    const newRow = template.cloneNode(true);
    newRow.classList.add("new-row");

    // Vider tous les champs de saisie
    newRow.querySelectorAll("input").forEach((input) => {
      input.value = "";
    });

    // Réinitialiser les selects
    newRow.querySelectorAll("select").forEach((select) => {
      select.selectedIndex = 0;
    });

    // Configurer le bouton de suppression
    const deleteBtn = newRow.querySelector(".btn-delete");
    if (deleteBtn) {
      deleteBtn.onclick = function (e) {
        e.preventDefault();
        newRow.remove();
        updateRowNumbers(tableId);
      };
    }

    return newRow;
  }

  // Fonction pour ajouter une ligne dans un tableau
  function addTableRow(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const template = tableTemplates[tableId];
    if (!template) {
      alert("❌ Aucun modèle de ligne trouvé pour le tableau : " + tableId);
      return;
    }

    const newRow = template.cloneNode(true);

    // Réinitialiser tous les champs
    newRow.querySelectorAll("input").forEach((input) => (input.value = ""));
    newRow
      .querySelectorAll("select")
      .forEach((select) => (select.selectedIndex = 0));

    // Ajouter écouteur sur le bouton supprimer
    const deleteBtn = newRow.querySelector(".btn-delete");
    if (deleteBtn) {
      deleteBtn.addEventListener("click", function (e) {
        e.preventDefault();
        newRow.remove();
        updateRowNumbers(tableId);
      });
    }
    table.querySelector("tbody").appendChild(newRow);
    updateRowNumbers(tableId);
  }
  console.log("data-table cliqué :", tableId);

  // Gestionnaire pour les boutons d'ajout
  document.querySelectorAll(".btn-add").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const tableId = this.getAttribute("data-table");
      if (tableId) {
        addTableRow(tableId);
      }
    });
  });

  // --- Boutons "حذف" initiaux ---
  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const row = this.closest("tr");
      const table = this.closest("table");
      if (row && table) {
        row.remove();
        updateRowNumbers(table.id);
      }
    });
  });

  // Initialiser les numéros pour tous les tableaux
  document.querySelectorAll("table").forEach((table) => {
    if (table.id) {
      updateRowNumbers(table.id);
    }
  });

  // --- Gestion modale ---
  const modal = document.getElementById("myModal");
  const openModalBtn = document.getElementById("openModalBtn");
  const closeModalBtn = document.querySelector(".close");

  if (modal && openModalBtn && closeModalBtn) {
    openModalBtn.onclick = function () {
      modal.style.display = "block";
    };

    closeModalBtn.onclick = function () {
      modal.style.display = "none";
    };

    window.onclick = function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    };
  }
});

/*document.querySelectorAll(".btn-add").forEach((button) => {
  button.addEventListener("click", function (e) {
    console.log(
      "Bouton إضافة سطر cliqué pour table :",
      this.getAttribute("data-table")
    );
  });
});
// Sauvegarder les modèles de lignes au chargement
document.querySelectorAll("table").forEach((table) => {
  if (table.id) {
    const firstRow = table.querySelector("tbody tr");
    if (firstRow) {
      tableTemplates[table.id] = firstRow.cloneNode(true);
    }
  }
});
if (!template) {
  alert("Aucun modèle de ligne trouvé pour ce tableau.");
  return;
}
document.addEventListener("DOMContentLoaded", function () {
  // --- Navigation sidebar ---
  const menuItems = document.querySelectorAll(".menu-item");
  const contents = document.querySelectorAll(".main-content");

  menuItems.forEach((item) => {
    item.addEventListener("click", function () {
      menuItems.forEach((i) => i.classList.remove("active"));
      contents.forEach((c) => c.classList.remove("active"));
      this.classList.add("active");
      const contentId = this.id + "-content";
      const content = document.getElementById(contentId);
      if (content) {
        content.classList.add("active");
      }
    });
  });

  // Fonction pour supprimer une ligne existante
  document.querySelectorAll(".btn-delete").forEach((button) => {
    button.addEventListener("click", function () {
      this.closest("tr").remove();
    });
  });
});*/
