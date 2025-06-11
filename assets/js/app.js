const formData = document.querySelector("#formData");

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
        alertDiv.className =
          "alert alert-success absolute top-[20px] right-[20px] flex items-center gap-2 my-4";
        alertDiv.innerHTML = `
          <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-6 w-6 shrink-0 stroke-current\" fill=\"none\" viewBox=\"0 0 24 24\">
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\" />
          </svg>
          <span>Your extract has been completed successfully!</span>
        `;
        // Insert alert at the top of the form
        formData.parentNode.insertBefore(alertDiv, formData);
        setTimeout(() => alertDiv.remove(), 3500);
        // Refresh the displayed data immediately after success
        fetchAndDisplay.cache["imagedata.txt"] = undefined;
        fetchAndDisplay("imagedata.txt", "photo-refresh");
      } else if (data.status === "blocked") {
        showBlockedAlert(); // Show blocked message
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

function fetchAndDisplay(file, type) {
  // Simple in-memory cache
  if (!fetchAndDisplay.cache) fetchAndDisplay.cache = {};
  const cache = fetchAndDisplay.cache;
  // If cached and not photo refresh, use cache
  if (cache[file] && type !== "photo-refresh") {
    renderDisplay(cache[file], type);
    return;
  }
  fetch(`/storage/${file}`)
    .then((response) => response.text())
    .then((text) => {
      cache[file] = text;
      renderDisplay(text, type);
    })
    .catch(() => {
      document.getElementById("displayData").innerText = "Failed to load data.";
    });
}

function renderDisplay(text, type) {
  let html = "";
  if (type === "photo" || type === "photo-refresh") {
    const urls = text.trim().split(/\r?\n/).filter(Boolean);
    html = urls
      .map(
        (url) =>
          `
        <div class="relative group flex justify-center items-center m-[20px]">
         <div class="overlay group-hover:h-full h-0 w-0 group-hover:w-full absolute  duration-[0.7s] bg-[rgba(0,0,0,0.81)]"></div>
                <img src="${url}" loading="lazy" alt="Image" class="border border-white m-[20px] cursor-pointer" />
                <button type="button" id="displayData" class ="border opacity-0 group-hover:opacity-100 duration-[0.7s] active:border active:border-white border-black absolute cursor-pointer text-2xl font-bold  p-[10px_10px] bg-black " >Copy</button>
        </div>
        `
      )
      .join("");
  } else {
    const items = text.trim().split(/\r?\n/).filter(Boolean);
    html = items.length
      ? `
      <div class="overflow-x-auto w-full">
      <table class="table  w-[70%] m-auto" >${items
          .map(
            (i, index) => `
          <tr class='border-b-2  h-[70px] overflow-scroll border-white'>
          <td class='text-[20px] font-bold  text-center w-[200px]' >${
            index + 1
          }</td>
          <td class='text-[20px] font-bold ' >${i}</td>
          </tr>
          `
          )
          .join("")}
          </table>
          </div>
          `
      : "<h1 class='md:text-[50px] text-[25px] font-bold text-center' > No data found. </h1>";
  }
  document.getElementById("displayData").innerHTML =
    html ||
    "<h1 class='md:text-[50px] text-[25px] font-bold text-center p-[100px]' > No data found. </h1>";
}

// Delegate copy button click for image URLs
document.getElementById("displayData").addEventListener("click", function (e) {
  if (
    e.target &&
    e.target.tagName === "BUTTON" &&
    e.target.textContent.trim() === "Copy"
  ) {
    const img = e.target.parentElement.querySelector("img");
    if (img && img.src) {
      navigator.clipboard.writeText(img.src).then(() => {
        e.target.textContent = "Copied!";
        setTimeout(() => (e.target.textContent = "Copy"), 1200);
      });
    }
  }
});

// Debounce utility
function debounce(fn, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

// Debounced event handlers
const debouncedShowPhoto = debounce(
  () => fetchAndDisplay("imagedata.txt", "photo"),
  300
);
const debouncedShowEmail = debounce(
  () => fetchAndDisplay("emaildata.txt", "email"),
  300
);
const debouncedShowPhone = debounce(
  () => fetchAndDisplay("phonedata.txt", "phone"),
  300
);

document.getElementById("showPhoto").onclick = debouncedShowPhoto;
document.getElementById("showEmail").onclick = debouncedShowEmail;
document.getElementById("showPhone").onclick = debouncedShowPhone;

// Initial load
fetchAndDisplay("imagedata.txt", "photo");

// Add this function to show blocked alert
function showBlockedAlert() {
  const oldAlert = document.getElementById("blocked-alert");
  if (oldAlert) oldAlert.remove();
  const alertDiv = document.createElement("div");
  alertDiv.id = "blocked-alert";
  alertDiv.setAttribute("role", "alert");
  alertDiv.className =
    "alert alert-error absolute top-[20px] right-[20px] flex items-center gap-2 my-4";
  alertDiv.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
    <span>Link ​is Blocked !</span>
  `;
  formData.parentNode.insertBefore(alertDiv, formData);
  setTimeout(() => alertDiv.remove(), 3500);
}
  	
// Add Clear button functionality
document.querySelectorAll("#formData button").forEach((btn) => {
  if (btn.textContent.trim().toLowerCase() === "clear") {
    btn.type = "button";
    btn.addEventListener("click", function () {
      formData.querySelector('input[name="url"]').value = "";
      fetch("../handlers/clear.php", { method: "POST" })
        .then((res) => res.json())
        .then(() => {
          fetchAndDisplay.cache = {}; // Clear cache
          fetchAndDisplay("imagedata.txt", "photo");
        });
    });
  }
});