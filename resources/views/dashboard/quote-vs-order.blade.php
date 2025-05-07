@extends('layout.master')

@push('plugin-scripts')
    <!-- Chart.js + datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
@endpush

@push('style')
<style>
 body{font-family:sans-serif;background:#f7f9fb;}
 .card{background:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.05);
       padding:24px;text-align:center}
 .wrap{display:flex;flex-wrap:wrap;gap:32px;justify-content:center;margin:40px}
</style>
@endpush

@section('content')
<div class="wrap">
  {{-- กราฟโดนัท --}}
  <div class="card" style="width:440px">
      <h2 class="mb-3">ใบเสนอราคาที่แปลงเป็นใบสั่งขาย</h2>
      <select id="sale-select" class="form-control mb-3"></select>
      <canvas id="doughnutChart" style="max-width:100%;height:380px"></canvas>
      <div id="summary" class="mt-3 small"></div>
  </div>

  {{-- กราฟแท่ง --}}
  <div class="card" style="width:640px">
      <h2 class="mb-3">TOP 10 ลูกค้าที่สั่งซื้อมากที่สุด</h2>
      <canvas id="barChart" style="max-width:100%;height:380px"></canvas>
  </div>
</div>


<script>
Promise.all([
  fetch('{{ route("dashboard.convert-stats") }}').then(res=>res.json()),
  fetch('{{ route("dashboard.top-customers") }}').then(res=>res.json())
])
.then(([saleStats, topCustomers]) => {
    initDoughnut(saleStats);
    drawBar(topCustomers);
})
.catch(() => alert('โหลดข้อมูลไม่สำเร็จ'));

const fmt = n => (+n).toLocaleString('th-TH');

/* ---------- กราฟโดนัท ---------- */
const dColors = ['#10B981', '#6B7280'], dHover = ['#059669', '#4B5563'];
let doughnutChart, saleData;

function initDoughnut(stats){
  saleData = stats;
  const sel = document.getElementById('sale-select');
  stats.forEach(s => sel.add(new Option(s.name, s.id)));
  sel.onchange = () => drawDoughnut(sel.value);
  drawDoughnut(sel.value);          // ค่าเริ่มต้น (คนแรก)
}

function drawDoughnut(id){
  const s = saleData.find(x => x.id == id);
  const remain = s.quotes - s.converted;

  if (doughnutChart) {
      doughnutChart.data.datasets[0].data = [s.converted, remain];
      doughnutChart.update();
  } else {
      doughnutChart = new Chart('doughnutChart', {
        plugins: [ChartDataLabels],
        type: 'doughnut',
        data: {
          labels: ['คำสั่งซื้อ', 'ยังไม่แปลง'],
          datasets: [{
            data: [s.converted, remain],
            backgroundColor: dColors,
            hoverBackgroundColor: dHover
          }]
        },
        options: {
          responsive: true,
          cutoutPercentage: 60,
          plugins: {
            datalabels: {
              color: '#fff', font: {weight:'bold'},
              formatter: v => v
            },
            legend: { display: false }
          }
        }
      });
  }

  const rate = s.quotes ? (s.converted * 100 / s.quotes).toFixed(1) : '0.0';
  document.getElementById('summary').innerHTML =
     `ทั้งหมด <b>${fmt(s.quotes)}</b> ใบ<br>` +
     `แปลงสำเร็จ <b>${fmt(s.converted)}</b> ใบ<br>` +
     `Conversion Rate <b>${rate}%</b>`;
}

/* ---------- กราฟแท่ง TOP ลูกค้า ---------- */
function drawBar(rows){
  if (!rows.length) return;

  new Chart('barChart', {
    plugins: [ChartDataLabels],
    type: 'bar',
    data: {
      labels: rows.map(r => r.name),
      datasets: [{
        label: 'จำนวนใบสั่งซื้อ',
        data: rows.map(r => r.count),
        backgroundColor: '#3B82F6',
        hoverBackgroundColor: '#2563EB'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        datalabels: {
          anchor: 'end', align: 'top', color: '#111',
          formatter: v => fmt(v)
        },
        legend: { display: false }
      },
      scales: {
        yAxes: [{ ticks: { beginAtZero: true, callback: v => fmt(v) } }],
        xAxes: [{ gridLines: { display:false } }]
      }
    }
  });
}
</script>


@endsection
