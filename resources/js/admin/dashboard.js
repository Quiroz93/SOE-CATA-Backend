import Chart from 'chart.js/auto';

const logoutLink = document.getElementById('logout-link');
const logoutForm = document.getElementById('logout-form');

logoutLink?.addEventListener('click', function (event) {
    event.preventDefault();
    logoutForm?.submit();
});

// Store chart instances globally to destroy them on reload
window.dashboardCharts = window.dashboardCharts || {};
window.Chart = Chart;

const dashboardData = document.getElementById('dashboardData');
const meses = JSON.parse(dashboardData?.dataset.meses ?? '[]');
const ofertasPorMes = JSON.parse(dashboardData?.dataset.ofertasPorMes ?? '[]');
const ofertasActivas = Number(dashboardData?.dataset.ofertasActivas ?? 0);
const ofertasVencidas = Number(dashboardData?.dataset.ofertasVencidas ?? 0);

const chartConstructor = window.Chart;

if (chartConstructor) {
    // Destroy existing charts before creating new ones
    if (window.dashboardCharts.ofertasMes) {
        window.dashboardCharts.ofertasMes.destroy();
    }
    if (window.dashboardCharts.estadoOfertas) {
        window.dashboardCharts.estadoOfertas.destroy();
    }

    // Create and store new chart instances
    window.dashboardCharts.ofertasMes = new chartConstructor(document.getElementById('ofertasMes'), {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Ofertas publicadas',
                data: ofertasPorMes,
                borderColor: '#39A900',
                backgroundColor: 'rgba(57,169,0,0.1)',
                tension: 0.4,
                fill: true
            }]
        }
    });

    window.dashboardCharts.estadoOfertas = new chartConstructor(document.getElementById('estadoOfertas'), {
        type: 'doughnut',
        data: {
            labels: ['Activas', 'Vencidas'],
            datasets: [{
                data: [ofertasActivas, ofertasVencidas],
                backgroundColor: ['#39A900', '#dc2626']
            }]
        }
    });
}
