/*!
 * Ajax CRUD (Bootstrap 4/5 compatible)
 * -------------------------------------
 * Support for johnitvn/yii2-ajaxcrud extension
 * Author: John Martin (gốc)
 * Chỉnh sửa & cập nhật: GPT-5 (2025)
 */

$(function () {

    /** -------------------------
     *  TẠO CÁC INSTANCE CỦA MODAL
     * ------------------------- */
    const modal = new ModalRemote($('#ajaxCrudModal').length ? '#ajaxCrudModal' : '#ajaxCrubModal');
    const modal2 = new ModalRemote($('#ajaxCrudModal2').length ? '#ajaxCrudModal2' : '#ajaxCrubModal2');
    const modal3 = new ModalRemote($('#ajaxCrudModal3').length ? '#ajaxCrudModal3' : '#ajaxCrubModal3');

    /** -------------------------
     *  HÀNH ĐỘNG: MỞ MODAL 1
     * ------------------------- */
    $(document).on('click', '[role="modal-remote"]', function (e) {
        e.preventDefault();
        modal.open(this, null);
    });

    /** -------------------------
     *  HÀNH ĐỘNG: MỞ MODAL 2
     * ------------------------- */
    $(document).on('click', '[role="modal-remote-2"]', function (e) {
        e.preventDefault();
        modal2.open(this, null);
    });

    /** -------------------------
     *  HÀNH ĐỘNG: MỞ MODAL 3
     * ------------------------- */
    $(document).on('click', '[role="modal-remote-3"]', function (e) {
        e.preventDefault();
        modal3.open(this, null);
    });

    /** -------------------------
     *  HÀNH ĐỘNG BULK (chọn nhiều)
     * ------------------------- */
    $(document).on('click', '[role="modal-remote-bulk"]', function (e) {
        e.preventDefault();

        const selector = $(this).data("selector") || 'selection';
        const selectedIds = $(`input:checkbox[name="${selector}[]"]:checked`)
            .map(function () { return $(this).val(); })
            .get();

        if (selectedIds.length === 0) {
            modal.show();
            modal.setTitle('Chưa chọn dữ liệu');
            modal.setContent('Vui lòng chọn dữ liệu để thực hiện hành động này!');
            modal.addFooterButton('Đóng lại', '', 'btn btn-secondary', function () {
                this.hide();
            });
            return;
        }

        modal.open(this, selectedIds);
    });

});
