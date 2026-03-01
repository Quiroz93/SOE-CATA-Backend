/**
 * Reportes Module
 * Initializes Chart.js visualizations for reporting dashboard
 */

import Chart from 'chart.js/auto';

// Store chart instances globally to destroy them on reload
window.reportesCharts = window.reportesCharts || {};
window.Chart = Chart;

document.addEventListener('DOMContentLoaded', function() {
    // Get data from DOM
    const dashboardData = document.getElementById('dashboardData');
    
    if (!dashboardData) return;

    try {
        // Parse data from data attributes
        const meses = JSON.parse(dashboardData.dataset.meses || '[]');
        const ofertasPorMes = JSON.parse(dashboardData.dataset.ofertasPorMes || '[]');
        const ofertasActivas = parseInt(dashboardData.dataset.ofertasActivas || '0');
        const ofertasVencidas = parseInt(dashboardData.dataset.ofertasVencidas || '0');
        
        // Calculate inactivas (assuming we have a data attribute for it)
        const inactivas = parseInt(dashboardData.dataset.inactivas || '0');

        // Destroy existing charts before creating new ones
        if (window.reportesCharts.ofertasMes) {
            window.reportesCharts.ofertasMes.destroy();
        }
        if (window.reportesCharts.estadoOfertas) {
            window.reportesCharts.estadoOfertas.destroy();
        }

        // Ofertas por Mes Chart
        const ctxMes = document.getElementById('ofertasMesChart');
        if (ctxMes) {
            window.reportesCharts.ofertasMes = new Chart(ctxMes.getContext('2d'), {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Ofertas Creadas',
                        data: ofertasPorMes,
                        borderColor: '#39A900',
                        backgroundColor: 'rgba(57, 169, 0, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#39A900',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 12 },
                                color: '#333'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#666' }
                        },
                        x: {
                            ticks: { color: '#666' }
                        }
                    }
                }
            });
        }

        // Estado Ofertas Chart
        const ctxEstado = document.getElementById('estadoOfertasChart');
        if (ctxEstado) {
            window.reportesCharts.estadoOfertas = new Chart(ctxEstado.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Activas', 'Vencidas', 'Inactivas'],
                    datasets: [{
                        data: [ofertasActivas, ofertasVencidas, inactivas],
                        backgroundColor: ['#39A900', '#FF9800', '#E0E0E0'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 12 },
                                color: '#333',
                                padding: 15
                            }
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error initializing reportes charts:', error);
    }
});