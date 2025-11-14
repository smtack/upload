const container = document.querySelector('.container');

const toggleSideMenu = document.querySelector('.toggle-side-menu');
const sideMenu = document.querySelector('.side-menu');

const toggleUploadResults = document.querySelector('#toggleUploadResults');
const toggleUserResults = document.querySelector('#toggleUserResults');
const uploadResults = document.querySelector('#uploadResults');
const userResults = document.querySelector('#userResults');

const toggleMenu = document.querySelector('.toggle-menu');
const menu = document.querySelector('.menu');

window.onload = () => {
  menu.style.display = "none";

  if(userResults) {
    userResults.style.display = "none";
  }
}

// Toggle user menu

if(toggleMenu) {
  toggleMenu.addEventListener("click", () => {
    if(menu.style.display == "none") {
      menu.style.display = "block";
    } else {
      menu.style.display = "none";
    }
  })
}

// Toggle side menu

toggleSideMenu.addEventListener("click", () => {
  if(sideMenu.style.width == "250px") {
    sideMenu.style.width = "0";
    container.style.marginLeft = "0";
  } else {
    sideMenu.style.width = "250px";
    container.style.marginLeft = "250px";
  }
})

// Toggle search results

if(uploadResults) {
  toggleUploadResults.style.borderBottom = "5px solid #000000";

  toggleUserResults.addEventListener('mouseover', () => {
    toggleUserResults.style.borderBottom = "5px solid #000000";
  })

  toggleUploadResults.addEventListener('mouseover', () => {
    toggleUploadResults.style.borderBottom = "5px solid #000000";
  })

  toggleUserResults.addEventListener('click', () => {
    uploadResults.style.display = "none";
    userResults.style.display = "block";
    toggleUploadResults.style.borderBottom = "none";
    toggleUserResults.style.borderBottom = "5px solid #000000";
  })

  toggleUploadResults.addEventListener('click', () => {
    uploadResults.style.display = "block";
    userResults.style.display = "none";
    toggleUploadResults.style.borderBottom = "5px solid #000000";
    toggleUserResults.style.borderBottom = "none";
  })
}