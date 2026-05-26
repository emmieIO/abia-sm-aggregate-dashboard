import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#64748b';
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;

document.addEventListener('DOMContentLoaded', () => {
    renderGenderChart();
    renderAgeChart();
    renderStageChart();
    renderEnrollmentChart();
});

function readDataset(id, fallback = []) {
    const el = document.getElementById(id);
    if (!el) return { el: null, dataset: fallback };

    try {
        return { el, dataset: JSON.parse(el.dataset.options || JSON.stringify(fallback)) };
    } catch (error) {
        console.error(`Unable to parse chart data for ${id}`, error);
        return { el, dataset: fallback };
    }
}

function renderGenderChart() {
    const { el, dataset } = readDataset('genderChart');
    if (!el) return;

    new Chart(el.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: dataset.map((item) => item.sex || 'Unspecified'),
            datasets: [{
                data: dataset.map((item) => item.total),
                backgroundColor: ['#2563eb', '#db2777', '#f59e0b', '#64748b'],
                borderWidth: 0,
            }],
        },
        options: {
            cutout: '68%',
            plugins: {
                legend: { position: 'bottom' },
            },
        },
    });
}

function renderAgeChart() {
    const { el, dataset } = readDataset('ageChart', {});
    if (!el) return;

    new Chart(el.getContext('2d'), {
        type: 'line',
        data: {
            labels: Object.keys(dataset),
            datasets: [{
                label: 'Students',
                data: Object.values(dataset),
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 2,
            }],
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
            plugins: {
                legend: { display: false },
            },
        },
    });
}

function renderStageChart() {
    const { el, dataset } = readDataset('stageChart');
    if (!el) return;

    new Chart(el.getContext('2d'), {
        type: 'bar',
        data: {
            labels: dataset.map((item) => `Stage ${item.stage}`),
            datasets: [{
                data: dataset.map((item) => item.total),
                backgroundColor: '#7c3aed',
                borderRadius: 6,
            }],
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
            plugins: {
                legend: { display: false },
            },
        },
    });
}

function renderEnrollmentChart() {
    const { el, dataset } = readDataset('enrollmentChart');
    if (!el) return;

    const labels = dataset.map((item) => item.school || item.class_name || 'Unassigned');

    new Chart(el.getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Students',
                data: dataset.map((item) => item.total),
                backgroundColor: '#2563eb',
                borderRadius: 6,
            }],
        },
        options: {
            indexAxis: labels.length > 4 ? 'y' : 'x',
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
            plugins: {
                legend: { display: false },
            },
        },
    });
}
