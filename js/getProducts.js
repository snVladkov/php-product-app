document.addEventListener("DOMContentLoaded", () => {
  let btn = document.getElementById("btnSearch");
  btn.addEventListener("click", (e) => {
    let cat = document.getElementById("pcat").value;
    let name = document.getElementById("pname").value;
    let priceMin = document.getElementById("pmin").value;
    let priceMax = document.getElementById("pmax").value;

    const ajaxCall = () => {
      return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              resolve(xhr.responseText);
            } else {
              reject(`Server could not respond. Error ${xhr.status}.`);
            }
          }
        };

        xhr.open(
          "GET",
          `get_products.php?cat=${cat}&name=${name}&pmin=${priceMin}&pmax=${priceMax}`
        );
        xhr.send();
      });
    };
    ajaxCall().then(
      (list) => {
        let productList = document.getElementById("productList");
        productList.innerHTML = list;
      },
      (error) => {
        let errorBox = document.getElementById("errorBox");
        let errorMsg = document.getElementById("errorMsg");
        errorBox.classList.add("alert-error", "show");
        errorMsg.innerHTML = error;
      }
    );
  });
});
