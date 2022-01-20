document.addEventListener("DOMContentLoaded", (e) => {
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

      xhr.open("GET", "get_categories.php");
      xhr.send();
    });
  };
  ajaxCall().then(
    (list) => {
      let catList = document.getElementById("catList");
      catList.innerHTML = list;
    },
    (error) => {
        let errorBox = document.getElementById("errorBox");
        let errorMsg = document.getElementById("errorMsg");
        errorBox.classList.remove('d-none');
        errorMsg.innerHTML = error;      
    }
  );
});
