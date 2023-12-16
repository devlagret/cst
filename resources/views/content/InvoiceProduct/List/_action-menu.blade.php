
<td class="text-center">
    <a type="button" href="{{route('invoice.product',$model->product_id)}}" class="btn btn-sm btn-info btn-active-light-info">
        Nota Produk
    </a>
    <a type="button" href="{{route('invoice.product',$model->product_id)}}" class="btn btn-sm btn-success btn-active-light-success">
        Nota Addon
    </a>
    <a type="button" href="{{route('invoice.product',$model->product_id)}}" class="btn btn-sm btn-primary btn-active-light-primary">
        Nota Maintenance
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