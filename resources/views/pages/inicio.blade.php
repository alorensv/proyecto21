@extends('plantilla')

@section('content')
<div class="container">
    <div class="row featurette">
        <div class="col-md-12">
            <div class="texto-cotiza">
              <h2 class="featurette-heading">Búsqueda de <span class="text-muted">Patente</span><i class="bi bi-chevron-right"></i></h2>
            </div>
        </div>  

        <div class="col-md-12" style="padding-top: 15px;padding-bottom: 15px;color: white;">

                <section class="shadow-lg" id="contacto"> 
                <form action="{{route('informe')}}" method="POST" >
                    @csrf 
                  <div class="row"> 
                    <div class="col-md-12">

                      @if (session('info'))
                        <div class="status alert alert-success">{{session('info')}}</div>
                      @endif

                        <div class="form-group">
                          <input type="text" id="ppu"  name="ppu" class="form-control" required="required" placeholder="EJEMPLO: JDKS99">
                        </div>                          
                        @error('ppu')
                          <p class="">{{$message}}</p>
                        @enderror                
                        <div class="form-group" style="text-align: center">
                          <button type="submit" class="btn btn-primary btn-lg">Buscar</button>
                        </div> 

                    </div><!--/.col-md-12--> 
        
                  </div><!--/.row--> 
                  </form>
                </section>

        </div><!--/#fin div contacto-page-->
    </div> 
</div>

@endsection