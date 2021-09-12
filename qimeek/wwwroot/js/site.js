function deleteDirectory(id) {
    url = "/Directories?id=" + id + "&handler=delete";
    deleteObject(url);
}

function deleteBookmark(id) {
    url = "/EditBookmark?id=" + id + "&handler=delete";
    deleteObject(url);
}

function deleteObject(url) {
    var deleteFormEl = document.getElementById("confirmDelete");
    deleteFormEl.setAttribute("action", url);
    $("#deleteObjectModal").modal("show");
}

function closeModal() {
    $("#deleteObjectModal").modal("hide");
}