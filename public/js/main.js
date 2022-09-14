const toggleSearch = document.querySelector('.toggle-search');
const search = document.querySelector('.search');
const closeSearch = document.querySelector('.close-search');

const toggleUploadResults = document.querySelector('#toggleUploadResults');
const toggleUserResults = document.querySelector('#toggleUserResults');
const uploadResults = document.querySelector('#uploadResults');
const userResults = document.querySelector('#userResults');

const toggleMenu = document.querySelector('.toggle-menu');
const menu = document.querySelector('.menu');

window.onload = () => {
  search.style.display = "none";
  menu.style.display = "none";

  if(userResults) {
    userResults.style.display = "none";
  }
}

toggleSearch.addEventListener("click", () => {
  if(search.style.display == "none") {
    search.style.display = "block";
  } else {
    search.style.display = "none";
  }
})

closeSearch.addEventListener("click", () => {
  search.style.display = "none";
})

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

if(toggleMenu) {
  toggleMenu.addEventListener("click", () => {
    if(menu.style.display == "none") {
      menu.style.display = "block";
    } else {
      menu.style.display = "none";
    }
  })
}