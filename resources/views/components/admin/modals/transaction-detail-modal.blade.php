<div class="modal fade" id="transactionDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionDetailModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>ID Transaksi: <span id="id-transaksi-detail"></span></h5>

                <table id="tbl-detail-transaksi" class="table dt-responsive nowrap" style="width: 100%">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Servis</th>
                            <th>Kategori</th>
                            <th>Banyak</th>
                            <th>Harga</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-ajax">
                    </tbody>
                </table>

                <br><br>
                <h5>Tipe Service: <span id="service-type"></span></h5>
                <h5>Dibayar: <span id="payment-amount"></span></h5>
                <h5>Metode Pembayaran: <span id="payment-method"></span></h5>
                <h5>Status: <span id="status-transaction"></span></h5>
                <br>
                <h5>Alamat: <span id="user-address"></span></h5>

                <div id="bukti-bayar-wrapper" style="margin-top: 15px;">
                    <h5>Bukti Pembayaran:</h5>
                    <div id="bukti-bayar-content">
                        <!-- Gambar akan dimasukkan di sini via JavaScript -->
                    </div>
                </div>

                <div id="foto-pengembalian-wrapper" style="margin-top: 20px;">
                    <h5>Foto Bukti Pengaduan:</h5>
                    <div id="foto-pengembalian-content" class="d-flex flex-wrap gap-2">
                        <!-- Foto akan dimasukkan via JS -->
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
