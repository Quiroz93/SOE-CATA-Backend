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

        // Preinscritos data
        const preinscritosPorMes = JSON.parse(dashboardData.dataset.preinscritosPorMes || '[]');
        const preinscritosPendientes = parseInt(dashboardData.dataset.preinscritosPendientes || '0');
        const preinscritosAceptados = parseInt(dashboardData.dataset.preinscritosAceptados || '0');
        const preinscritosRechazados = parseInt(dashboardData.dataset.preinscritosRechazados || '0');

        // Destroy existing charts before creating new ones
        if (window.reportesCharts.ofertasMes) {
            window.reportesCharts.ofertasMes.destroy();
        }
        if (window.reportesCharts.estadoOfertas) {
            window.reportesCharts.estadoOfertas.destroy();
        }
        if (window.reportesCharts.preinscritosMes) {
            window.reportesCharts.preinscritosMes.destroy();
        }
        if (window.reportesCharts.preinscritosEstado) {
            window.reportesCharts.preinscritosEstado.destroy();
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

        // Preinscritos por Mes Chart
        const ctxPreinscritosMes = document.getElementById('preinscritosMesChart');
        if (ctxPreinscritosMes) {
            window.reportesCharts.preinscritosMes = new Chart(ctxPreinscritosMes.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Preinscritos',
                        data: preinscritosPorMes,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)',
                        borderColor: '#007BFF',
                        borderWidth: 1,
                        borderRadius: 4
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
                            ticks: { 
                                color: '#666',
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: { color: '#666' }
                        }
                    }
                }
            });
        }

        // Preinscritos por Estado Chart
        const ctxPreinscritosEstado = document.getElementById('preinscritosEstadoChart');
        if (ctxPreinscritosEstado) {
            window.reportesCharts.preinscritosEstado = new Chart(ctxPreinscritosEstado.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Pendientes', 'Aceptados', 'Rechazados'],
                    datasets: [{
                        data: [preinscritosPendientes, preinscritosAceptados, preinscritosRechazados],
                        backgroundColor: ['#FFC107', '#39A900', '#DC3545'],
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