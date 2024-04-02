import Dropzone from "dropzone";
import Swal from "sweetalert2";

Dropzone.autoDiscover = false;

if (document.querySelector("#dropzone")) {
    const dropzone = new Dropzone("#dropzone", {
        dictDefaultMessage: "Sube aquí tu imagen",
        acceptedFiles: ".png,.jpg,.jpeg,.gif",
        addRemoveLinks: true,
        dictRemoveFile: "Borrar Archivo",
        maxFiles: 1,
        uploadMultiple: false,

        init: function () {
            if (document.querySelector("input[name='imagen']").value.trim()) {
                const imagenPublicada = {};
                imagenPublicada.size = Number(
                    document.querySelector("input[name='size']").value
                );
                imagenPublicada.name = document.querySelector(
                    "input[name='imagen']"
                ).value;
                this.options.addedfile.call(this, imagenPublicada);
                this.options.thumbnail.call(
                    this,
                    imagenPublicada,
                    `/uploads/${imagenPublicada.name}`
                );

                imagenPublicada.previewElement.classList.add(
                    "dz-success",
                    "dz-complete"
                );
            }
        },
    });

    dropzone.on("success", (file, response) => {
        document.querySelector("input[name='imagen']").value = response.imagen;
        document.querySelector("input[name='size']").value = file.size;
    });

    dropzone.on("removedfile", async (file) => {
        const imagen = document.querySelector("input[name='imagen']");
        const response = await fetch(`/api/imagenes/${imagen.value}`, {
            method: "POST",
        });
        const data = await response.json();

        Swal.fire({
            icon: data.error ? "error" : "success",
            title: data.error ? "¡Error!" : "¡Imagen eliminada!",
            text: data.mensaje
        })

        imagen.value = "";
        document.querySelector("input[name='size']").value = "";
    });
}
