<h2>Báo cáo doanh thu</h2>

<canvas id="chart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = [
    @foreach($revenue as $r)
        "{{ $r->date }}",
    @endforeach
];

const data = [
    @foreach($revenue as $r)
        {{ $r->total }},
    @endforeach
];

new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu',
            data: data
        }]
    }
});
</script>