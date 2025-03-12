function createPagination(containerId, totalPages, currentPage, onPageChange) {
    let paginationContainer = document.getElementById(containerId);
    if (!paginationContainer) return;

    paginationContainer.innerHTML = ""; 

    let paginationHTML = `
        <ul class="pagination d-flex justify-content-center">
            <li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" onclick="${onPageChange}(${currentPage - 1})">&laquo;</a>
            </li>`;

    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="javascript:void(0);" onclick="${onPageChange}(${i})">${i}</a>
            </li>`;
    }

    paginationHTML += `
            <li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" onclick="${onPageChange}(${currentPage + 1})">&raquo;</a>
            </li>
        </ul>`;

    paginationContainer.innerHTML = paginationHTML;
}
