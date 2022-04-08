const toggleSearch = document.querySelector('.toggle-search');
const search = document.querySelector('.search');
const closeSearch = document.querySelector('.close-search');

const toggleMenu = document.querySelector('.toggle-menu');
const menu = document.querySelector('.menu');

window.onload = () => {
  search.style.display = "none";
  menu.style.display = "none";
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

if(toggleMenu) {
  toggleMenu.addEventListener("click", () => {
    if(menu.style.display == "none") {
      menu.style.display = "block";
    } else {
      menu.style.display = "none";
    }
  })
}