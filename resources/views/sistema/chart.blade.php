@extends('plantilla')

@section('js')
    <script type="text/javascript" src="{{ asset('assets/js/chart.js') }}" defer></script>
@endsection

@section('content')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<div class="text-center" style="margin-top: 20px;">
    <div class="btn-group" role="group" aria-label="">
        <button id="btnColumnas" type="button" class="btn btn-secondary">Columnas</button>
        <button id="btnLineas" type="button" class="btn btn-primary">Lineas</button>
        <button id="btnArea" type="button" class="btn btn-info">Area</button>
    </div>
</div>

<figure class="highcharts-figure">
    <div id="container" style="min-width: 320px;height:400px;margin:0 auto;">

    </div>

    <div class="modal" id="modal-lineas" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container-modal" id="container-modal" style="min-width: 320px;height:400px;margin:0 auto;"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">Save changes</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    <p class="highcharts-description">
        Basic line chart showing trends in a dataset. This chart includes the
        <code>series-label</code> module, which adds a label to each line for
        enhanced readability.
    </p>
</figure>
@endsection