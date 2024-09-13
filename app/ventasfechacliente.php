<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: vistas/login.html");
} else {
  require $_SERVER['DOCUMENT_ROOT'] . '/vistas/header.php';

  if ($_SESSION['consultav'] == 1) {
    ?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Consulta de Ventas por fecha y cliente</h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                    value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                    value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Cliente</label>
                  <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true"
                    required>
                  </select>
                  <button class="btn btn-success" onclick="listar()">Mostrar</button>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Cliente</th>
                    <th>Comprobante</th>
                    <th>Número</th>
                    <th>Total Venta</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Cliente</th>
                    <th>Comprobante</th>
                    <th>Número</th>
                    <th>Total Venta</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>

              <!--Fin centro -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->
    <?php
  } else {
    require $_SERVER['DOCUMENT_ROOT'] . '/vistas/noacceso.php';
  }

  require $_SERVER['DOCUMENT_ROOT'] . '/vistas/footer.php';
  ?>
  <script>
    var tabla;

    //Función que se ejecuta al inicio
    function init() {
      listar();
      //Cargamos los items al select cliente
      $.post("/ajax/venta.php?op=selectCliente", function (r) {
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');
      });
      $('#mConsultas').addClass("treeview active");
      $('#lConsulasV').addClass("active");
    }


    //Función Listar
    function listar() {
      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();
      var idcliente = $("#idcliente").val();

      tabla = $('#tbllistado').dataTable(
        {
          "lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
          "aProcessing": true,//Activamos el procesamiento del datatables
          "aServerSide": true,//Paginación y filtrado realizados por el servidor
          dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
          buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
          ],
          "ajax":
          {
            url: '/ajax/consultas.php?op=ventasfechacliente',
            data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idcliente: idcliente },
            type: "get",
            dataType: "json",
            error: function (e) {
              console.log(e.responseText);
            }
          },
          "language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
              "copyTitle": "Tabla Copiada",
              "copySuccess": {
                _: '%d líneas copiadas',
                1: '1 línea copiada'
              }
            }
          },
          "bDestroy": true,
          "iDisplayLength": 5,//Paginación
          "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
    }


    init();
  </script>
  <?php
}
ob_end_flush();
?>