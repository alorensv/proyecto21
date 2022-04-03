@extends('plantilla')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@if (isset($inscripcion->ppu))

<div id="contentInfo" class="container">

    <div class="title" style="background-color: white; padding: 15px;">
      <div class="row">
        <div class="col-md-5">
            <h4>AutoCheck</h4>
            <span id="DownloadPDF">Descargar PDF</span>
        </div>
        <div class="col-md-7">
            <h4>Patente: {{$inscripcion->ppu}}</h4>
        </div>
      </div>
    </div>

    <div id="titles-1" class="titles-1" style="background-color:white; padding:7px;border-top: 1px solid rgba(72, 94, 144, 0.16);">
      <div class="row">
        <div class="col-md-6">
            <h4>Datos del vehículo</h4>
        </div>
        <div class="col-md-6">
            <h4>Resumen Destacado</h4>
        </div>
      </div>
    </div>

    <div class="info-1" style="padding: 7px; background-color: white">
      <div class="row">
        <div class="col-md-6">        
          <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%;">
            <thead>
                <tr>
                  <th>Patente</th>
                  <td>{{$inscripcion->ppu}}</td>
                </tr>
                <tr>
                  <th>Tipo de Vehículo</th>
                  <td>{{$inscripcion->tipo_vehiculo}}</td>
                </tr>
                <tr>
                  <th>Año</th>
                  <td>{{$inscripcion->anio}}</td>
                </tr>
                <tr>
                  <th>Marca</th>
                  <td>{{$inscripcion->marca}}</td>
                </tr>
                <tr>
                  <th>Modelo</th>
                  <td>{{$inscripcion->modelo}}</td>
                </tr>
            </thead>
          </table>
        </div>
        <div class="col-md-6">
  
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
              <td><?= (isset($per->anio_pc))? $per->anio_pc : '' ?> <?= (isset($per->tipo_pago))? $per->tipo_pago : ''  ?></td>
            </tr>
            <tr>
              <th>Registro de transportes</th>
              <td><?= (isset($transportes))? 'SI':'NO' ?></td>
            </tr>
          </table>
    
        </div>
      </div>
    </div>

    
    <!--revisiones técnicas-->
    <div id="titles-1" class="titles-1" style="background-color:white; padding:7px;border-top: 1px solid rgba(72, 94, 144, 0.16);">
      <div class="row">
        <div class="col-md-12">
            <h4>Revisiones técnicas</h4>
        </div>
      </div>
    </div>

    <div class="info-1" style="padding: 7px; background-color: white">
      <div class="row">
        <div class="col-md-12">        
          <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%;">
            <thead>

              <tr>
                <th>Kilometraje</th>
                <th>Fecha Revisión</th>
                <th>Fecha Vencimiento</th>
                <th>Resultado CRT</th>
                <th>Identificación</th>  
                <th>Visual</th>  
                <th>Luces</th>  
                <th>Alineación</th>  
                <th>Frenos</th>
                <th>Holgura</th>                  
              </tr>

              @foreach ($revisiones as $rev)
                <tr>
                  <td>{{$rev->kilometraje}}</td>
                  <td>{{$rev->fec_revision}}</td>
                  <td>{{$rev->fec_vencimiento}}</td>
                  <td>{{$rev->resultado_crt}}</td>
                  <td>{{$rev->identificacion}}</td>
                  <td>{{$rev->visual}}</td>
                  <td>{{$rev->luces}}</td>
                  <td>{{$rev->alineacion}}</td>
                  <td>{{$rev->frenos}}</td>
                  <td>{{$rev->holguras}}</td>
                </tr>                  
              @endforeach
                
            </thead>
          </table>
        </div>
      </div>
    </div>

    <!--permisos -->
    <div id="titles-1" class="titles-1" style="background-color:white; padding:7px;border-top: 1px solid rgba(72, 94, 144, 0.16);">
      <div class="row">
        <div class="col-md-12">
            <h4>Permisos de circulación</h4>
        </div>
      </div>
    </div>

    <div class="info-1" style="padding: 7px; background-color: white">
      <div class="row">
        <div class="col-md-12">        
          <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%;">
            <thead>

              <tr>
                <th>Región</th>
                <th>Comuna</th>
                <th>Año</th>
                <th>Tipo de pago</th>
                <th>Valor</th>                    
              </tr>

              @foreach ($permisos as $permiso)
                <tr>
                  <td>{{$permiso->region}}</td>
                  <td>{{$permiso->comuna}}</td>
                  <td>{{$permiso->anio_pc}}</td>
                  <td>{{$permiso->tipo_pago}}</td>
                  <td>$<?= number_format($permiso->valor_total, 0, '', '.');  ?></td>
                </tr>                  
              @endforeach
                
            </thead>
          </table>
        </div>
      </div>
    </div>

    <!--remates -->
    <div id="titles-1" class="titles-1" style="background-color:white; padding:7px;border-top: 1px solid rgba(72, 94, 144, 0.16);">
      <div class="row">
        <div class="col-md-12">
            <h4>Información de siniestros</h4>
        </div>
      </div>
    </div>

    <div class="info-1" style="padding: 7px; background-color: white">
      <div class="row">
        <div class="col-md-12">        
          <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%;">
            <thead>

              <tr>
                <th>Compañia de seguros</th>
                <th>Tipo de siniestro</th>
                <th>Estado</th>
                <th>Tipo de operación</th>
                <th>Fecha de operación</th>  
                <th>Monto</th>                  
              </tr>

              <?php $i=0; ?>
              @foreach ($remates as $remate)
                <tr>
                  <td>{{$remate->compania}}</td>
                  <td>{{$remate->tipo_siniestro}}</td>
                  <td>{{$remate->estado}}</td>
                  <td>{{$remate->tipo_operacion}}</td>
                  <td>{{$remate->fecha_operacion}}</td>
                  <td>$<?= number_format($remate->monto, 0, '', '.');  ?></td>
                </tr>      
                <?= $i++; ?>            
              @endforeach
              @if ($i==0)
                <tr>
                  <td colspan="6" >No existen registros de remate para el vehículo consultado</td>
                </tr>
              @endif
                
            </thead>
          </table>
        </div>
      </div>
    </div>    
    
    <!--transporte -->
    <div id="titles-1" class="titles-1" style="background-color:white; padding:7px;border-top: 1px solid rgba(72, 94, 144, 0.16);">
      <div class="row">
        <div class="col-md-12">
            <h4>Registro de transporte</h4>
        </div>
      </div>
    </div>

    <div class="info-1" style="padding: 7px; background-color: white">
      <div class="row">
        <div class="col-md-12">        
          <table id="listado"  class='table table-striped table-bordered display nowrap' style="width:100%;">
            <thead>

              <tr>
                <th>Categoria</th>
                <th>Tipo de servicio</th>
                <th>Fecha de ingreso</th>
                <th>Estado</th>
                <th>Fecha de cancelación</th>  
                <th>Año de fabriación</th>                  
              </tr>

              <?php $i=0; ?>
              @foreach ($transportes as $registro)
                <tr>
                  <td>{{$registro->categoria}}</td>
                  <td>{{$registro->tipo_servicio}}</td>
                  <td>{{$registro->fecha_ingreso}}</td>
                  <td>{{$registro->estado_ppu}}</td>
                  <td>{{$registro->fecha_cancelacion}}</td>
                  <td>{{$registro->anio_fab}}</td>
                </tr>      
                <?php $i++; ?>            
              @endforeach
              @if ($i==0)
                <tr>
                  <td colspan="6" >No existen registros de remate para el vehículo consultado</td>
                </tr>
              @endif
                
            </thead>
          </table>
        </div>
      </div>
    </div>    

</div>

@else           
            
<div  id="contentInfo" class="container">
  <div class="title" style="background-color: white; padding: 15px;">
    <div class="row">
      <div class="col-md-5">
          <h4>AutoCheck</h4>
          <span id="DownloadPDF">Descargar PDF</span>
      </div>
      <div class="col-md-7">
          <h4>Patente: No se encuentran registros</h4>
      </div>
    </div>
  </div>
</div>

@endif

<script>
  $("#DownloadPDF").click(async function() {
      
     var element = document.getElementById('contentInfo');
      html2pdf(element);
  });
</script>
@endsection