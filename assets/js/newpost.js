const inputBox = document.querySelector("#description");
const imagePreview = document.querySelector("#imgPreview");
const fileButton = document.querySelector("input[type='file']");
const video = document.getElementById("video");
const source = document.querySelector("source");
      inputBox.style.width = `250px`;

imagePreview.addEventListener("click", () => {
  fileButton.click();
});
      inputBox.style.width = `${imagePreview.offsetWidth}px`;
fileButton.addEventListener("change", (event) => {
  const selectedFile = event.target.files[0];
  if (selectedFile.type == "image/jpg") {
       imagePreview.style.display = "block";
    imagePreview.src = URL.createObjectURL(selectedFile);
    video.style.display = "none";

    imagePreview.onload = () => {
      inputBox.style.width = `${imagePreview.offsetWidth}px`;
    };
  } else {
    imagePreview.style.display = "none";
    video.style.display = "block";
    source.src = URL.createObjectURL(selectedFile);
    video.onload = () => {
          inputBox.style.width = `${video.offsetWidth}px`;

    }
        video.load();

  }})