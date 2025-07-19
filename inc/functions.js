function toggleContent(id) {
    const preview = document.getElementById("preview-" + id);
    const full = document.getElementById("full-" + id);
    //const toggle = document.getElementById("toggle-" + id);

    if (preview.style.display === "none") {
        preview.style.display = "block";
        full.style.display = "none";
        //toggle.innerText = "...See more";
    }
    else {
        preview.style.display = "none";
        full.style.display = "block";
        //toggle.innerText = "See less";
    }
}