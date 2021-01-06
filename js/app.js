
var fec1 = "";
var fec2 = "";
var tipo  = "";

$(function () {
    
    MuestraChart();

    const boton = document.querySelector("#BtnUpdate");
    boton.addEventListener("click", function(evento){
          evento.preventDefault();
          MuestraChart();

    });

});


function MuestraChart() {
  fec1 = $('#PickerFecIni').val().split("-").reverse().join("-");
  fec2 = $('#PickerFecFin').val().split("-").reverse().join("-");

  switch ($('#tipo').val())  {
    case "Sucursal" :
         tipo = "S";
         break;
    case "Division":
         tipo = "D";
        break;
  };
  
  document.querySelector("#chartReport").innerHTML = '<canvas id="chartCanvas"></canvas>';
  var ctx = document.getElementById("chartCanvas");

  TraeDatosChart(ctx, fec1, fec2, tipo);
  
};

function TraeDatosChart (ctx, fec1, fec2, div) {
	$.ajax({
            url   : 'chart/vtasnetas.php',
	          type  : 'POST',
            async : true,
            data  : {
               "fecini":  fec1,
               "fecfin":  fec2,
               "div"   :  div,
            },
            success: function(response){ 
              CreaChart(ctx, response) ;
              CreaTable(response);
            },
            error: function(error) {
              console.log(error);
            }
           })

};

function CreaTable (data) { 

  $('#vtasnetas').dataTable( {
    "aaData"  : data,
    "paging"  : false,
    "info"    : false,
    "bFilter" : false,
    "bDestroy": true,
    "columns" : [
        { "data": "suc" },
        { "data": "vta" },
        { "data": "uni" }
    ]
  })
 }

function CreaChart(ctx, Data) {
  
  var yAxisLabels  = [];
  var dataSeries1  = [];
  var dataSeries2  = [];

  
  for (var i in Data) {
    yAxisLabels.push(Data[i].suc);
    dataSeries1.push(Data[i].vta);
    dataSeries2.push(Data[i].uni);
  }
  
  
  var chartdata = {
    labels: yAxisLabels,
    datasets: [
      {
        type : 'bar',
        label: 'PESOS',
        borderColor: '#042f66',
        backgroundColor:  '#042f66',
        data: dataSeries1
      }, //dataset1
      {
        type : 'line',
        label: 'UNIDADES',
        fill : false,
        borderWidth:2,
        tension: 0,
        borderColor: '#44bcd8',
        backgroundColor:  '#44bcd8',
        data: dataSeries2,
      } //dataset2
    ] //datasets
  }; //var chartdata

 
  var myChart = new Chart(ctx, {
    type : 'bar',
    data: chartdata,
    options: {
      title: {
        display: true,
        fontSize:18,
        fontColor: '#111B54'
      },
      legend:{
        position: 'bottom',
        label:{
            padding:5,
            boxwidth:15,
            fontFamily:'sans-serif',
            fontColor: 'black',
            fontSize : 2
        }
      },
      tooltips: {
        backgroundColor: '#F8AC23',
        titleFontSize :14,
        titleFontColor : '#2C3179',
        bodyFontColor : 'black', 
        xPadding: 20,
        yPadding: 10,
        bodyFontSize:14,
        bodySpacing: 5,
        mode: 'point',
        callbacks: {
          label: function(tooltipItem, data) {

              let label = data.labels[tooltipItem.index];
              let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
              return ' ' + formatoMX(value) ;

          }
      }
    },
     scales: {
      yAxes: [{
          ticks: {
              beginAtZero: true,
              fontSize : 10,
              callback: function(value, index, values) {
                return '$' + value;
            }
          },
          scaleLabel: {
            display: true,
            labelString: 'Pesos $'
          }
       }],
      xAxes: [{
        ticks: {
            fontSize : 8
        }
     }]
      }
    },
  }) 

  myChart.options.scales.yAxes[0].scaleLabel.labelString = "UNITS";
  myChart.options.scales.yAxes[1].scaleLabel.labelString = "PESOS";
  // myChart.options.legend.onClick = function(ev, legendItem){
  //   if (legendItem.text == "PESOS") {
             
  //   }
 //}
    
  MuestraTotales(Data);

} // Function CreaChart


function MuestraTotales(Data){
  var TotPesos     = 0;
  var TotUnidades  = 0;
  for (var i in Data) {
     TotPesos      += parseFloat(Data[i].vta);
     TotUnidades   += parseInt(Data[i].uni);
  }

  pesos = TotPesos.toLocaleString("es-MX", { style: 'currency', currency: 'MXN' });
  //$("#vtastot")[0].innerText = pesos;
  
  unidades = formatoMX(TotUnidades);
  //$("#unistot")[0].innerText = unidades;
  $("#ut")[0].innerText = unidades;

  var fields = pesos.split(".");
  var pes = fields[0];
  var cen = fields[1];
  $("#num")[0].innerText = pes;
  $("sup")[0].innerText = cen;
}

const formatoMX = (number) => {
  const exp = /(\d)(?=(\d{3})+(?!\d))/g;
  const rep = '$1,';
  let arr = number.toString().split('.');
  arr[0] = arr[0].replace(exp,rep);
  return arr[1] ? arr.join('.'): arr[0];
}









 

