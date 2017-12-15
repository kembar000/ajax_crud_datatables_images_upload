  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Version 2.0</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Paket</h3>
            </div>
            <div class="box-body">
        <button class="btn btn-success" onclick="add_paket()"><i class="glyphicon glyphicon-plus"></i>Tambah Paket</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
              <div class="form-group">
                <label>Tanggal Spesifik</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" class="form-control datepicker" name="tgl_input" id="tgl_input">
                </div>
                </div>
                     
        <button class="btn btn-success" id="get_chart_spesifik"><i class="fa fa-circle-o"></i> Spesifik Tanggal</button>            
        <br />
        <br />
        <div id="qr">
            
        </div>
        <div class="chart">
            <canvas id="lineChart" height="35" width="100"  /></canvas>
        </div>          
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Pengirim</th>
                    <th>CP Pengirim</th>
                    <th>Penerima</th>            
                    <th>CP Penerima</th>
                    <th>Jenis Barang</th>
                    <th>Status</th>
                    <th>Tanggal Input</th>
                    <th>Tanggal Approve</th>
                    <th>Foto</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                    <th>Pengirim</th>
                    <th>CP Pengirim</th>
                    <th>Penerima</th>            
                    <th>CP Penerima</th>
                    <th>Jenis Barang</th>
                    <th>Status</th>
                    <th>Tanggal Input</th>
                    <th>Tanggal Approve</th>
                    <th>Foto</th>
            </tr>
            </tfoot>
        </table>
    </div>
          </div>
        </div>
      </div>       
  </div>
<script src="<?php echo base_url();?>assets/dashboard/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/dashboard/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/dashboard/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/dashboard/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/dashboard/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="https://datatables.yajrabox.com/js/handlebars.js"></script>
<script src="<?php echo base_url();?>assets/dashboard/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';
    var graphChart = new Chart($("#lineChart"), {
        type: 'bar',
        data: {
            labels: ['Semua Paket'],
            datasets: [{
                label: 'Pakets',
                data: [0],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ]
            }]
        }
    });
    $.ajax({
        url: "<?php echo site_url('paket/init_chart');?>",
        method: "GET",
        success: function(data) {
            data = data.data;
            const tanggal = data.map(function(o) {
                if(o.tgl_input === null){
                    return now;
                }
                return o.tgl_input;
            });
            const id_paket = data.map(function(o) {
                return o.Total;
            });
            graphChart.data.datasets.map(function(o) {
                o.data = id_paket;
                return o;
            });
            graphChart.data.labels = ['Semua Paket'];
            graphChart.update();
        },
        error: function(data) {
            console.log(data);
        }
    });

 $('#get_chart_spesifik').on('click',function()
{
    var tgl_input = $('#tgl_input').val();

    $.ajax({
        url: "<?php echo site_url('paket/get_chart_spesifik');?>/"+tgl_input,
        method : "POST",
        dataType: "JSON",
        success: function(data) {
            data = data.data;
            const tanggal = data.map(function(o) {
                if(o.tgl_input === null){
                    return tgl_input;
                }
                return o.tgl_input;
            });
            const id_paket = data.map(function(o) {
                return o.Total;
            });
            graphChart.data.datasets.map(function(o) {
                o.data = id_paket;
                return o;
            });
            graphChart.data.labels = tanggal;
            graphChart.update();
        },
        error: function(data) {
            console.log(data);
        }
    });
});    
$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('paket/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -2 ], //2 last column (photo)
                "orderable": false, //set not orderable
            },
        ],

    });
    $('[data-mask]').inputmask();
    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "bottom auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_paket()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title

    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Upload Photo'); // label photo upload
}

function edit_paket(id_paket)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('paket/ajax_edit')?>/" + id_paket,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id_paket"]').val(data.id_paket);
            $('[name="awb"]').val(data.awb);
            $('[name="id_user"]').val(data.id_user);
            $('[name="pengirim"]').val(data.pengirim);
            $('[name="telp_pengirim"]').val(data.telp_pengirim);
            $('[name="penerima_tertera"]').val(data.penerima_tertera);
            $('[name="alamat_penerima"]').val(data.alamat_penerima);
            $('[name="telp_penerima"]').val(data.telp_penerima);
            $('[name="jenis_barang"]').val(data.jenis_barang);
            $('[name="qty"]').val(data.qty);
            $('[name="width"]').val(data.width);
            $('[name="length"]').val(data.length);
            $('[name="height"]').val(data.height);
            $('[name="kendaraan"]').val(data.kendaraan);
            $('[name="deskripsi_barang"]').val(data.deskripsi_barang);
            $('[name="status_pengiriman"]').val(data.status_pengiriman);
            $('[name="tgl_approve"]').val(data.tgl_approve);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

            $('#photo-preview').show(); // show photo preview modal

            if(data.photo)
            {
                $('#label-photo').text('Change Photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'uploadpaket/'+data.photo+'" class="img-responsive">'); // show photo
                $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.photo+'"/> Remove photo when saving'); // remove photo

            }
            else
            {
                $('#label-photo').text('Upload Photo'); // label photo upload
                $('#photo-preview div').text('(No photo)');
            }


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
function get_qr(awb)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('paket/get_qr')?>/"+awb,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                                $('#modal_qr').modal('show'); // show bootstrap modal
                $('#qr-preview div').html('<img src="'+base_url+'tes.png">'); // 
                $('#qr-preview div').html('<p>'+data.awb+'</p>'); // 

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $('#modal_qr').modal('show'); // show bootstrap modal
                $('#qr-preview div').html('<img src="'+base_url+'tes.png">'); //                 
            }
        });

    }    
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('paket/ajax_add')?>";
    } else {
        url = "<?php echo site_url('paket/ajax_update')?>";
    }

    // ajax adding data to database

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_user(id_paket)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('paket/ajax_delete')?>/"+id_paket,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                location.reload();                
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

</script>
<div class="modal fade" id="modal_qr" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form Paket</h3>
            </div>
            <div class="modal-body form">
                <div class="form-group" id="qr-preview">
                    <label class="control-label col-md-3">Photo</label>
                        <div class="col-md-9">
                            (No photo)
                            <span class="help-block"></span>
                        </div>
                </div>                  
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form Paket</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_paket"/> 
            <div class="box-body">
                <div class="form-group">
                  <label>AWB</label>
                  <input type="number" class="form-control" placeholder="Enter ..." name="awb" id="awb">
                </div>
                <div class="form-group">
                  <label>Penginput</label>
                  <input type="text" class="form-control" placeholder="Enter ..." name="id_user" id="id_user" value=<?php echo $this->session->userdata('nama');?> disabled>
                </div>
                <div class="form-group">
                  <label>Pengirim</label>
                  <input type="text" class="form-control" placeholder="Enter ..." name="pengirim" id="pengirim">
                </div>
              <div class="form-group">
                <label>CP Pengirim</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>

                  <input type="text" class="form-control" name="telp_pengirim" id="telp_pengirim" data-inputmask='"mask": "(9999) 9999-9999"' data-mask>
                </div>
                </div>
                <div class="form-group">
                  <label>Penerima</label>
                  <input type="text" class="form-control" placeholder="Enter ..." name="penerima_tertera" id="penerima_tertera">
                </div>
                <div class="form-group">
                  <label>Alamat Penerima</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="alamat_penerima" id="alamat_penerima"></textarea>
                </div>                    
              <div class="form-group">
                <label>CP Penerima</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" class="form-control" name="telp_penerima" id="telp_penerima" data-inputmask='"mask": "(9999) 9999-9999"' data-mask>
                </div>
                </div>                             
                <div class="form-group">
                  <label>Jenis Barang</label>
                  <select class="form-control" name="jenis_barang" id="jenis_barang">
                    <option value="Dokumen">Dokumen</option>
                    <option value="Paket">Paket</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Qty</label>
                  <input type="number" class="form-control" placeholder="Enter ..." name="qty" id="qty">
                </div>
                <div class="form-group">
                  <label>Width</label>
                  <input type="number" class="form-control" placeholder="Enter ..." name="width" id="width">
                </div>
                <div class="form-group">
                  <label>Length</label>
                  <input type="number" class="form-control" placeholder="Enter ..." name="length" id="length">
                </div>
                <div class="form-group">
                  <label>Height</label>
                  <input type="number" class="form-control" placeholder="Enter ..." name="height" id="height">
                </div>
                <div class="form-group">
                  <label>Kendaraan</label>
                  <select class="form-control" name="kendaraan" id="kendaraan">
                    <option value="Mobil">Mobil</option>
                    <option value="Motor">Motor</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Deskripsi Barang</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="deskripsi_barang" id="deskripsi_barang"></textarea>
                </div>
                <div class="form-group">
                  <label>Status Pengiriman</label>
                  <select class="form-control" name="status_pengiriman" id="status_pengiriman">
                    <option value="Delivered">Delivered</option>
                    <option value="Manifested">Manifested</option>
                    <option value="On-Process">On-Process</option>
                  </select>
                </div>
              <div class="form-group">
                <label>Tanggal Approve</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" class="form-control datepicker" name="tgl_approve" id="tgl_approve">
                </div>
                </div>         
                <div class="form-group" id="photo-preview">
                    <label class="control-label col-md-3">Photo</label>
                        <div class="col-md-9">
                            (No photo)
                            <span class="help-block"></span>
                        </div>
                </div>                                
                <div class="form-group">
                    <label class="control-label col-md-3" id="label-photo">Upload Photo </label>
                        <div class="col-md-9">
                            <input name="photo" type="file">
                            <span class="help-block"></span>
                        </div>
                </div>                
            </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>