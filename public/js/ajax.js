const base_url = $('meta[name="base_url"]').attr("content");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).on("click", ".btn-detail", function () {
    let id_transaksi = $(this).data("id");
    $("#id-transaksi-detail").html(id_transaksi);

    $.ajax({
        url: route("admin.transactions.show", { transaction: id_transaksi }),
        method: "GET",
        dataType: "json",
        success: function (data) {
            let table = "";
            let j = 1;
            $.each(data.transaction_details, function (i, val) {
                table +=
                    "<tr>" +
                    "<td>" +
                    j++ +
                    "</td>" +
                    "<td>" +
                    val.price_list.item.name +
                    "</td>" +
                    "<td>" +
                    val.price_list.service.name +
                    "</td>" +
                    "<td>" +
                    val.price_list.category.name +
                    "</td>" +
                    "<td>" +
                    val.quantity +
                    "</td>" +
                    "<td>" +
                    val.price +
                    "</td>" +
                    "<td>" +
                    val.sub_total +
                    "</td>" +
                    "</tr>";
            });
            $("#tbl-ajax").html(table);
            $("#service-type").html(data.service_type.name);
            $("#payment-amount").html(data.payment_amount);
            $('#payment-method').text(data.metodepembayaran);
            $('#status-transaction').text(data.status.name);
            console.log(data.status.name);
            if (data.user) {
                let alamat = data.user.address; // langsung ambil string
                $("#user-address").html(alamat ?? "<i>Tidak ada alamat</i>");
            }

            // Tampilkan bukti bayar jika ada (pastikan path-nya valid)
            if (data.bukti_pembayaran) {
                const img = `<img src="/${data.bukti_pembayaran}" alt="Bukti Bayar" class="img-fluid" style="max-height: 300px;">`;
                $('#bukti-bayar-content').html(img);
                $('#bukti-bayar-wrapper').show();

                console.log(img);
            } else {
                $('#bukti-bayar-content').html('<p><i>Tidak ada bukti bayar</i></p>');
                $('#bukti-bayar-wrapper').show();
            }

            // Foto pengembalian (bukti pengaduan)
            if (data.fotopengembalian && data.fotopengembalian.length > 0) {
                let fotoHtml = "";
                data.fotopengembalian.forEach((foto) => {
                    fotoHtml += `
                        <div class="m-2 text-center">
                            <img src="/${foto.foto}" alt="Bukti Pengaduan"
                                class="img-thumbnail" style="max-height: 150px;">
                        </div>`;
                });
                $("#foto-pengembalian-content").html(fotoHtml);
                $("#foto-pengembalian-wrapper").show();
            } else {
                $("#foto-pengembalian-content").empty();
                $("#foto-pengembalian-wrapper").hide();
            }


        },
    });
});

$(document).on("change", ".select-status", function () {
    let id_transaksi = $(this).data("id");
    let csrfToken = document.getElementById("csrf_token").value;

    if (confirm("Apakah anda yakin mengubah status transaksi ini?")) {
        let val = $(this).val();
        $.ajax({
            url: route("admin.transactions.update", {
                transaction: id_transaksi,
            }),
            data: {
                val: val,
                _token: csrfToken
            },
            method: "PATCH",
            success: function (data) {
                location.reload();
            },
            error: function (xhr) {
                alert("Gagal update status: " + xhr.responseText);
            }
        });
    } else {
        $(this).val($(this).data("val"));
        return;
    }
});


$(document).on("change", "#tahun", function () {
    let year = $(this).val();
    let option = "";
    $.ajax({
        url: route("admin.reports.get-month"),
        data: {
            year: year,
        },
        method: "POST",
        dataType: "json",
        success: function (data) {
            $.each(data, function (i, val) {
                option +=
                    '<option value="' +
                    val.Bulan +
                    '">' +
                    val.Bulan +
                    "</option>";
            });
            $("#bulan").html(option);
            $("#btn-cetak").removeClass("d-none");
        },
    });
});
