document.addEventListener("DOMContentLoaded", () => {
  let btn = document.getElementById("export");
  btn.addEventListener("click", () => {
    //create class for row data
    class Row {
      constructor(name, category, price) {
        this.name = name;
        this.category = category;
        this.price = price;
      }
    }

    //get product data from results
    let rowsArr = [];
    let rows = document.querySelectorAll("tr");
    rows.forEach((row) => {
      let pname = row.children.item(1).innerText;
      let pcat = row.children.item(2).innerText;
      let pprice = row.children.item(3).innerText;
      let newRow = new Row(pname, pcat, pprice);
      rowsArr.push(newRow);
    });
    let jsonArr = JSON.stringify(rowsArr);

    //create new message box if deleted
    if (!document.contains(document.getElementById("errorBox"))) {
      let errorBox = document.createElement("div");
      errorBox.classList.add("alert", "alert-dismissible", "fade");
      errorBox.id = "errorBox";
      errorBox.setAttribute("role", "alert");
      errorBox.innerHTML =
        "<strong id='errorMsg'></strong><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
      document.body.insertBefore(errorBox, document.getElementById("main"));
    }

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

        xhr.open("POST", "export_products.php");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(jsonArr);
      });
    };

    let errorBox = document.getElementById("errorBox");
    let errorMsg = document.getElementById("errorMsg");

    ajaxCall().then(
      (success) => {
        let tempLink = document.createElement('a');
        tempLink.setAttribute('href', `${success}.txt`);
        tempLink.setAttribute('download', `${success}.txt`);
        document.body.appendChild(tempLink);
        tempLink.click();
        document.body.removeChild(tempLink);
      },
      (error) => {
        errorBox.classList.add("alert-error", "show");
        errorMsg.innerHTML = error;
      }
    );
  });
});
