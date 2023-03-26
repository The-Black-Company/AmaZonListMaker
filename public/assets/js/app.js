document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll("a[data-ajax]");

  links.forEach((link) => {
    link.addEventListener("click", (event) => {
      event.preventDefault();
      const url = event.target.getAttribute("href");

      fetch(url)
        .then((response) => response.text())
        .then((html) => {
          document.querySelector("#content").innerHTML = html;
        })
        .catch((error) => {
          console.warn(error);
        });
    });
  });
});
