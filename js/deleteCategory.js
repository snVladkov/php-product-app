const deleteCategory = (id) => {
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
  //xhr request
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

      xhr.open("GET", `delete_category.php?cid=${id}`);
      xhr.send();
    });
  };

  let errorBox = document.getElementById("errorBox");
  let errorMsg = document.getElementById("errorMsg");
  //send and handle request
  ajaxCall().then(
    (success) => {
      if (success.includes("Error")) {
        errorBox.classList.add("alert-danger", "show");
        errorMsg.innerHTML = success;
      } else {
        errorBox.classList.add("alert-success", "show");
        errorMsg.innerHTML = success;
        document.getElementById(`row${id}`).remove();
      }
    },
    (error) => {
      errorBox.classList.add("alert-error", "show");
      errorMsg.innerHTML = error;
    }
  );
};
