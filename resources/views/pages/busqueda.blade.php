@extends('plantilla')



@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<style>
  .info-1{
    background-color: white;
  }
  .titles-1 h4{
    color: white;
  }
</style>
<div id="contentInfo" class="container">

  <div class="title" style="background-color: white; padding: 15px;">
    <div class="row">
      <div class="col-md-5">
          <h4>AutoCheck</h4>
          <span id="DownloadPDF">Descargar PDF</span>
      </div>
      <div class="col-md-7">
          <h4>Patente: {{$datos->ppu}}</h4>
      </div>
    </div>
  </div>

  <div id="titles-1" class="titles-1" style="background-color:#424267; padding:7px;">
    <div class="row">
      <div class="col-md-5">
          <h4>Datos del vehículo</h4>
      </div>
      <div class="col-md-7">
          <h4>Resumen Destacado</h4>
      </div>
    </div>
  </div>


  <div class="info-1" style="padding: 7px;">
    <div class="row">
      <div class="col-md-5">        
        <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%">
          <thead>
              <tr>
                <th>Patente</th>
                <td>{{$datos->ppu}}</td>
              </tr>
              <tr>
                <th>Tipo de Vehículo</th>
                <td>Pendiente</td>
              </tr>
              <tr>
                <th>Año</th>
                <td>{{$datos->anio_fabricacion}}</td>
              </tr>
              <tr>
                <th>Marca</th>
                <td>{{$datos->marca}}</td>
              </tr>
              <tr>
                <th>Modelo</th>
                <td>{{$datos->modelo}}</td>
              </tr>
              <tr>
                <th>Número Motor</th>
                <td>{{$datos->num_motor}}</td>
              </tr>
              <tr>
                <th>Número Chasis</th>
                <td>{{$datos->num_chasis}}</td>
              </tr>
          </thead>
        </table>
      </div>
      <div class="col-md-7">

        <table  class='table table-striped table-bordered display nowrap' style="width:100%">
          <tr>
            <th>Cantidad de propietarios</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Encargo por robo</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Limitaciones al dominio</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Permiso de circulación</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Registro de transportes</th>
            <td>?</td>
          </tr>
        </table>
  
      </div>
    </div>
  </div>

  <div class="titles-1" style="background-color:#424267; padding:7px;">
    <div class="row">
      <div class="col-md-5">
          <h4>Información técnica y mecánica</h4>
      </div>
      <div class="col-md-7">
          <h4>Información de multas e infracciones</h4>
      </div>
    </div>
  </div>

  <div class="info-1" style="padding: 7px;">
    <div class="row">
      <div class="col-md-5">        
        <table  class='table table-striped table-bordered display nowrap' style="width:100%">
          <tr>
            <th>Registros de revisión técnica</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Historial de ventas por remates</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Servicios informados</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Recalls o llamados a revisión</th>
            <td>?</td>
          </tr>
        </table>
      </div>
      <div class="col-md-7">

        <table  class='table table-striped table-bordered display nowrap' style="width:100%">
          <tr>
            <th>Pasadas pendientes en autopistas</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Multas Anotadas en certificado de multas</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Historial de infracciones municipalidades</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Infracciones en vías exclusivas</th>
            <td>?</td>
          </tr>
          <tr>
            <th>Infracciones por restricción vehicular</th>
            <td>?</td>
          </tr>
        </table>
  
      </div>
    </div>
  </div>

  <div class="info" style="background-color:#fff; padding:7px;">
    <div class="row">
      <div class="col-md-12">
        <p>Verifica que el Nº de motor y el Nº de chasis sean iguales a los impresos físicamente en el vehículo, los lugares más comunes donde puedes
          encontrar estos códigos son en la parte inferior del parabrisas, marco de puerta, bajo el capó en el área de compartimiento de motor, entre
          otros.
          </p>
        
          <p>i) El informe AUTOFACT está basado en información provista a AUTOFACT que estuvo disponible el 06/10/2021 a las 17:38 hrs. Sin embargo, puede existir información que
            NO haya sido reportada a AUTOFACT o que haya sido recientemente entregada pero no incluida aún en las bases de datos. Por ende, podrían existir datos que NO están
            presentes en este informe, incluyendo accidentes, multas, registros de kilometrajes, remates, pertenencia a flotas u otros.</p>
          <p>ii) Utiliza los antecedentes que te entrega el informe AUTOFACT como una ayuda para conocer mejor el vehículo que quieres comprar, te ayudará a reducir el riesgo y poder
            pagar un precio más justo. Como complemento, realiza una inspección visual, mecánica, prueba del vehículo y otras actividades para reducir el riesgo aún más.
            </p>
          <p>iii) El informe AUTOFACT contiene información en línea e información que proviene de bases de datos históricas y otras fuentes externas. Por lo que no puede garantizar ni
            certificar la información presente en este informe. Lee los mensajes en cada sección para que puedas comprender la actualización de cada información</p>
      </div>
    </div>
  </div>
  
  <div class="titles-1" style="background-color:#424267; padding:7px;">
    <div class="row">
      <div class="col-md-12">
          <h4>Historial de servicios de transporte </h4>
      </div>
    </div>
  </div>


  <div class="info" style="background-color:#fff; padding:7px;">
    <div class="row">
      <div class="col-md-12">
        @if (!empty($model))
        <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%">
          <thead>
              <tr>
                  <th>PATENTE</th>
                  <th>MODELO</th>
                  <th>MARCA</th>
                  <th>AÑO</th>
                  <th>CATEGORIA</th>
                  <th>TIPO SERVICIO</th>
                  <th>ESTADO</th>
                  <th>FECHA INGRESO</th>
              </tr>
          </thead>
          <tbody>
            @foreach($model as $model)
              <tr>
                  <th>{{$model->ppu}}</th>
                  <th>{{$model->marca}}</th>
                  <th>{{$model->modelo}}</th>
                  <th>{{$model->anio_fabricacion}}</th>
                  <th>{{$model->categoria}}</th>
                  <th>{{$model->tipo_servicio}}</th>
                  <th>{{$model->estado}}</th>
                  <th>{{$model->fecha_ingreso}}</th>
              </tr>
              @endforeach
          </tbody>
        </table>
        @endif  
      </div>
    </div>
  </div>

  <div class="titles-1" style="background-color:#424267; padding:7px;">
    <div class="row">
      <div class="col-md-12">
          <h4>Historial de revisiones técnicas</h4>
      </div>
    </div>
  </div>

  <div class="info" style="background-color:#fff; padding:7px;">
    <div class="row">
      <div class="col-md-12">
        <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%">
          <thead>
              <tr>
                  <th>PATENTE</th>
                  <th>FECHA REVISION</th>
                  <th>FECHA DE VENCIMIENTO</th>
                  <th>KILOMETRAJE</th>
                  <th>N# CERTIFICADO</th>
                  <th>RESULTADO</th>
                  <th>FECHA VENCIMIENTO GASES</th>
              </tr>
          </thead>
          <tbody>
            @foreach($car as $car)
              <tr>
                  <th>{{$car->ppu}}</th>
                  <th>{{$car->fec_revision}}</th>
                  <th>{{$car->fec_vencimiento}}</th>
                  <th>{{number_format($car->kilometraje)}}</th>
                  <th>{{$car->num_certificado}}</th>
                  <th>{{$car->resultado_crt}}</th>
                  <th>{{$car->fec_vencimiento_gases}}</th>
              </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>



  
</div>

<script>
  $("#DownloadPDF").click(async function() {
      
     var element = document.getElementById('contentInfo');
      html2pdf(element);
  });
</script>
@endsection