function showData(url) {
  document.addEventListener("DOMContentLoaded", function () {
    const btnGallery = document.getElementById("btn-gallery");
    const gallery = document.getElementById("gallery");
    const btnVeterinaryReports = document.getElementById(
      "btn-veterinaryReports"
    );
    const veterinaryReports = document.getElementById("veterinaryReports");

    showInfo(btnGallery, btnVeterinaryReports, gallery, veterinaryReports);

    const newRapport = document.getElementById("new-rapport");

    newRapport.addEventListener("click", function () {
      const rapport = document.getElementById("add-rapport");
      rapport.classList.remove("d-none");
    });

    document
      .querySelector(".submit-rapport")
      .addEventListener("click", function (e) {
        e.preventDefault();

        let formData = {
          health: document.getElementById("veterinary_report_health").value,
          veterinaryReport: document.getElementById(
            "veterinary_report_veterinaryReports"
          ).value,
        };

        fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Erreur lors de la requête.");
            }
            return response.json();
          })
          .then((data) => {
            console.log("Réponse reçue du serveur :", data);

            if (data.status == "success") {
              let newElement = document.createElement("div");
              newElement.classList.add("ms-1");
              newElement.innerHTML = `
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">le : ${data.date}</p>
                    </div>
                        <p class="text-dark" > ${data.veterinaryReport} </p>
                    <br>    
                    `;
              document.getElementById("rapport-create").appendChild(newElement);
              document.getElementById("add-rapport").classList.add("d-none");
            } else {
              console.error("Erreur lors de la validation : ", data.message);
            }
          })
          .catch((error) => {
            console.error("Erreur lors de la validation : ", error);
          });
      });
  });

  function showInfo(
    btnGallery,
    btnVeterinaryReports,
    gallery,
    veterinaryReports
  ) {
    if (btnGallery != null) {
      btnGallery.addEventListener("click", function () {
        gallery.classList.remove("d-none");
        veterinaryReports.classList.add("d-none");
      });
    }

    btnVeterinaryReports.addEventListener("click", function () {
      veterinaryReports.classList.remove("d-none");
      if (btnGallery != null) {
        gallery.classList.add("d-none");
      }
    });
  }
}

function manageEditeReport() {
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-report").forEach(function (button) {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        let reportDataElement = document.getElementById(
          "veterinaryreport-data"
        );
        let reportId = reportDataElement.dataset.veterinaryreportId;

        editVeterinaryReport(reportId, reportDataElement);
      });
    });

    function editVeterinaryReport(reportId, reportDataElement) {
        
      const animalDataElement = document.getElementById("animal-data");
      const animalId = animalDataElement.dataset.animalId;
      let editUrl = reportDataElement.dataset.editUrl;
      editUrl = editUrl.replace("ANIMAL_ID", animalId, "REPORT_ID", reportId);

      let detailElement = document.getElementById(
        `veterinaryreport-${reportId}`
      );

      if (detailElement) {
        let currentDetail = detailElement.textContent;

        let textarea = document.createElement("textarea");
        textarea.value = currentDetail;
        textarea.className = "form-control";

        detailElement.replaceWith(textarea);

        textarea.focus();

        let saveButton = document.createElement("button");
        saveButton.textContent = "Valider";
        saveButton.className = "btn btn-primary mt-2";
        textarea.parentNode.insertBefore(saveButton, textarea.nextSibling);

        saveButton.addEventListener("click", function () {
          let newDetail = textarea.value;
          if (newDetail !== currentDetail) {
            updateVeterinaryReport(reportId, animalId, editUrl, newDetail);
          }

          let newP = document.createElement("p");
          newP.id = `veterinaryreport-${reportId}`;
          newP.className = "text-dark";
          newP.textContent = newDetail;
          textarea.replaceWith(newP);
          saveButton.remove();
        });
      }
    }

    function updateVeterinaryReport(editUrl, newDetail) {
      const health = document.getElementById("veterinary_report_health").value;  
      fetch(editUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          health: health,
          veterinaryReport: newDetail,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.error("Le rapport a été mis à jour");
          } else {
            console.error("Erreur lors de la mise à jour");
          }
        });
    }
  });
}
