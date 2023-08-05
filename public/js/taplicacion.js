// //Abrir el modal y descargar
//     function openPdfModal(pdfUrl, modalId) {
//         var modal = $('#' + modalId);
//         var modalLabel = modal.find('.modal-title');
//         var modalBody = modal.find('.modal-body');
//         var downloadLink = modal.find('#pdfDownloadLink');

//         modalLabel.text(getFileNameFromUrl(pdfUrl));
//         modalBody.html('<embed src="' + pdfUrl + '" type="application/pdf" width="100%" height="500px" />');
//         downloadLink.attr('href', pdfUrl);
//         modal.modal('show');
//     }

//     function getFileNameFromUrl(url) {
//         var index = url.lastIndexOf("/");
//         var filename = (index !== -1) ? url.substring(index + 1) : url;
//         return filename;
//     }

// // cerrar el modal
//     $(document).ready(function() {
//         $('.btn-close').click(function() {
//             $(this).closest('.modal').modal('hide');
//         });
//     });