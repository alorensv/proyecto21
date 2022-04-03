$("#btnColumnas").click(function(){
    alert("columnas")
});

$("#btnLineas").click(function(){
    $("#modal-lineas").css("background-color", "#000");
    $("#modal-lineas").modal("show");
    GraficarLineas();
});

$("#btnArea").click(function(){
    alert("btnArea")
});

function GraficarLineas(){

    Highcharts.chart('container-modal', {
        chart:{
            type: 'line'
        },
        title: {
            text: 'Solar Employment Growth by Sector, 2010-2016'
        },
    
        subtitle: {
            text: 'Source: thesolarfoundation.com'
        },
    
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },    
        xAxis: {
            accessibility: {
                rangeDescription: 'Range: 2010 to 2017'
            }
        },    
        series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        }],
    
    
    });

}
