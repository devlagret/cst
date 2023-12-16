
<td class="text-center">
    <a type="button" href="{{route('invoice.add',$model->product_id)}}" class="btn p-2 btn-sm btn-success btn-active-light-success">
        <i class="mx-0 mr-0 bi bi-plus-square fs-2 m-0"></i>
    </a>
</td>

<div class="modal fade" tabindex="-1" id="kt_modal_delete_{{$model->product_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Produk</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus Produk?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                <a href="{{route('product.delete',$model->product_id)}}" class="btn btn-danger">Iya</a>
            </div>
        </div>
    </div>
</div>