const formData = document.querySelector("#formData");
function fetchAndDisplay(file, type) {
  fetch(`/storage/${file}`)
    .then((response) => response.text())
    .then((text) => {
      let html = "";
      if (type === "photo") {
        const urls = text.trim().split(/\r?\n/).filter(Boolean);
        html = urls
          .map(
            (url) =>
              `<img src="${url}" alt="Image" class="inline-block border border-white m-[20px]  justify-center cursor-pointer" />`
          )
          .join("");
      } else {
        const items = text.trim().split(/\r?\n/).filter(Boolean);
        html = items.length
          ? `<table class="table  w-[70%] " >${items
              .map(
                (i, index) => `
              <tr class='border-b-2 h-[70px] border-white'>
              <td class='text-[20px] font-bold  text-center w-[200px]' >${
                index + 1
              }</td>
              <td class='text-[20px] font-bold ' >${i}</td>
              </tr>
              `
              )
              .join("")}</table>`
          : "<h1 class='text-[50px] font-bold text-center' > No data found. </h1>";
      }
      document.getElementById("displayData").innerHTML =
        html ||
        "<h1 class='text-[50px] font-bold text-center p-[100px]' > No data found. </h1>";
    })
    .catch(() => {
      document.getElementById("displayData").innerText = "Failed to load data.";
    });
}
formData.addEventListener("submit", (e) => {
  e.preventDefault();
  fetchAndDisplay("imagedata.txt", "photo");
  const myFormData = new FormData(e.target);
  fetch("../handlers/extract.php", {
    method: "POST",
    body: myFormData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
   
        const oldAlert = document.getElementById("success-alert");
        if (oldAlert) oldAlert.remove();
        // Create alert div
        const alertDiv = document.createElement("div");
        alertDiv.id = "success-alert";
        alertDiv.setAttribute("role", "alert");
        alertDiv.className = "alert alert-success absolute top-[20px] right-[20px] flex items-center gap-2 my-4";
        alertDiv.innerHTML = `
          <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-6 w-6 shrink-0 stroke-current\" fill=\"none\" viewBox=\"0 0 24 24\">
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\" />
          </svg>
          <span>Your extract has been completed successfully!</span>
        `;
        // Insert alert at the top of the form
        formData.parentNode.insertBefore(alertDiv, formData);
        setTimeout(() => alertDiv.remove(), 3500);
      } else {
         const oldAlert = document.getElementById("error-alert");
         if (oldAlert) oldAlert.remove();
         // Create alert div
         const alertDiv = document.createElement("div");
         alertDiv.id = "error-alert";
         alertDiv.setAttribute("role", "alert");
         alertDiv.className =
           "alert alert-error absolute top-[20px] right-[20px] flex items-center gap-2 my-4";
         alertDiv.innerHTML = `
          <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-6 w-6 shrink-0 stroke-current\" fill=\"none\" viewBox=\"0 0 24 24\">
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\" />
          </svg>
          <span>Invalid your link URL, Please try againt</span>
        `;
         // Insert alert at the top of the form
         formData.parentNode.insertBefore(alertDiv, formData);
         setTimeout(() => alertDiv.remove(), 3500);
      }
    });
});


fetchAndDisplay("imagedata.txt", "photo");
document.getElementById("showPhoto").onclick = () =>
  fetchAndDisplay("imagedata.txt", "photo");
document.getElementById("showEmail").onclick = () =>
  fetchAndDisplay("emaildata.txt", "email");
document.getElementById("showPhone").onclick = () =>
  fetchAndDisplay("phonedata.txt", "phone");
