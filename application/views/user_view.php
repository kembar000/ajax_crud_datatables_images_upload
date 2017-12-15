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
              <h3 class="box-title">Users</h3>
            </div>
            <div class="box-body">
        <button class="btn btn-success" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i>Tambah User</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
              <div class="form-group">
                <label>Tanggal Spesifik</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" class="form-control datepicker" name="tanggal_input" id="tanggal_input">
                </div>
                </div>
                     
        <button class="btn btn-success" id="get_chart_spesifik"><i class="fa fa-circle-o"></i> Spesifik Tanggal</button>            
        <br />
        <br />
        <div class="chart">
            <canvas id="lineChart" height="35" width="100"  /></canvas>
        </div>        
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Type</th>            
                    <th>Status</th>
                    <th>Foto Profile</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Type</th>
                    <th>Foto Profile</th>
                    <th>Status</th>                    
                    <th>Action</th>
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
            labels: ['Semua Users'],
            datasets: [{
                label: 'Users',
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
$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('user/ajax_list')?>",
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
    $.ajax({
        url: "<?php echo site_url('user/init_chart');?>",
        method: "GET",
        success: function(data) {
            data = data.data;
            const tanggal = data.map(function(o) {
                if(o.tanggal_input === null){
                    return now;
                }
                return o.tanggal_input;
            });
            const id_user = data.map(function(o) {
                return o.Total;
            });
            graphChart.data.datasets.map(function(o) {
                o.data = id_user;
                return o;
            });
            graphChart.data.labels = ['Semua Users'];
            graphChart.update();
        },
        error: function(data) {
            console.log(data);
        }
    });
});


 $('#get_chart_spesifik').on('click',function()
{
    var tanggal_input = $('#tanggal_input').val();

    $.ajax({
        url: "<?php echo site_url('user/get_chart_spesifik');?>/"+tanggal_input,
        method : "POST",
        dataType: "JSON",
        success: function(data) {
            data = data.data;
            const tanggal = data.map(function(o) {
                if(o.tanggal_input === null){
                    return tanggal_input;
                }
                return o.tanggal_input;
            });
            const id_user = data.map(function(o) {
                return o.Total;
            });
            graphChart.data.datasets.map(function(o) {
                o.data = id_user;
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
function add_user()
{
    save_method = 'add';
    $('#form_tambah')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_tambah').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add User'); // Set Title to Bootstrap modal title

    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Upload Photo'); // label photo upload
}

function edit_user(id_user)
{
    save_method = 'update';    
    $('#form_update')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('user/ajax_edit')?>/" + id_user,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {        
            $('[name="id_user"]').val(data.id_user);
            $('[name="email"]').val(data.email);                    
            $('[name="nama"]').val(data.nama);
            $('[name="type"]').val(data.type);
            $('[name="alamat"]').val(data.alamat);
            $('[name="telp"]').val(data.telp);
            $('[name="status"]').val(data.status);
            $('#modal_form_update').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

            $('#photo-preview').show(); // show photo preview modal

            if(data.foto_profil)
            {
                $('#label-photo').text('Change Photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'uploaduser/'+data.foto_profil+'" class="img-responsive">'); // show photo
                $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.foto_profil+'"/> Remove photo when saving'); // remove photo

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
        url = "<?php echo site_url('user/ajax_add')?>";
        var formData = new FormData($('#form_tambah')[0]);
        var modal = $('#modal_form_tambah').modal('hide');
    } else {
        url = "<?php echo site_url('user/ajax_update')?>";
        var formData = new FormData($('#form_update')[0]);
        var modal = $('#modal_form_update').modal('hide');
    }

    // ajax adding data to database

    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
               modal;
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_user(id_user)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('user/ajax_delete')?>/"+id_user,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}
</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_tambah" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form User</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_tambah" class="form-horizontal">
                    <input type="hidden" value="" name="id_user"/> 
                    <div class="form-body">
                <div class="form-group">
                  <label>Email address</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                  </div>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="Enter ..." name="pass" id="pass">
                </div>                   
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" placeholder="Enter ..." name="nama" id="nama">
                </div>                
                <div class="form-group">
                  <label>Type</label>
                  <select class="form-control" name="type" id="type">
                    <option value="admin">Admin</option>
                    <option value="kurir">Kurir</option>
                  </select>
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
                                <input name="foto_profil" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="alamat" id="alamat"></textarea>
                </div>                
              <div class="form-group">
                <label>Kontak</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" class="form-control" name="telp" id="telp" data-inputmask='"mask": "(9999) 9999-9999"' data-mask>
                </div>
              </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status" id="status">
                    <option value="aktif">Aktif</option>
                    <option value="non-aktif">Non Aktif</option>
                  </select>
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
<div class="modal fade" id="modal_form_update" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form User</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_update" class="form-horizontal">
                    <input type="hidden" value="" name="id_user" id="id_user" /> 
                    <div class="form-body">
                <div class="form-group">
                  <label>Email address</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="Email" name="email" id="emails">
                  </div>
                </div>              
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" class="form-control" placeholder="Enter ..." name="nama" id="namas">
                </div>                
                <div class="form-group">
                  <label>Type</label>
                  <select class="form-control" name="type" id="types">
                    <option value="admin">Admin</option>
                    <option value="kurir">Kurir</option>
                  </select>
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
                                <input name="foto_profil" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="alamat" id="alamats"></textarea>
                </div>                
              <div class="form-group">
                <label>Kontak</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" class="form-control" name="telp" id="telps" data-inputmask='"mask": "(9999) 9999-9999"' data-mask>
                </div>
              </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status" id="statuss">
                    <option value="aktif">Aktif</option>
                    <option value="non-aktif">Non Aktif</option>
                  </select>
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