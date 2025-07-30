let profilePictureForm = document.querySelector("#profile-picture-form");
if(profilePictureForm) {
    profilePictureForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const fileInput = document.querySelector('input[name="profile_picture"]');
        const formData = new FormData();
        formData.append("action", "upload_profile_picture");
        formData.append("profile_picture", fileInput.files[0]);
        fetch("/wp-admin/admin-ajax.php", {
          method: "POST",
          body: formData,
          credentials: "same-origin",
        })
          .then(res => res.json())
          .then(data => {
            console.log(data.data?.message)
            document.getElementById("upload-status").textContent = data.data?.message;
          });
      });
}

let removeProfilePicture = document.getElementById("remove-profile-picture");
if(removeProfilePicture) {
  removeProfilePicture.addEventListener("click", function () {
    if (!confirm("Tem certeza que quer remover a imagem de perfil?")) return;
  
    const formData = new FormData();
    formData.append("action", "remove_profile_picture");
  
    fetch("/wp-admin/admin-ajax.php", {
      method: "POST",
      body: formData,
      credentials: "same-origin",
    })
      .then(res => res.json())
      .then(data => {
        console.log(data);
        document.getElementById("upload-status").textContent = data.data?.message || "Erro ao remover imagem.";
        document.getElementById("profile-picture-preview").src = ""; // ou uma imagem padrão
      });
  });
}




  