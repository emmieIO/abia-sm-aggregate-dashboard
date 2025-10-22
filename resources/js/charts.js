import ApexCharts from 'apexcharts';

// DOM Elements
const genderDiv = document.querySelector("#genderChart");
const ageDiv = document.querySelector("#ageChart");
const topStudents = document.querySelector('#topStudents');
const topSubjects = document.querySelector('#top-subjects');
const leastStudents = document.querySelector('#leastStudents');
const leastSubjects = document.querySelector('#least-subjects');

// Chart instances (scoped to avoid conflicts)
let genderChart = null;
let ageChart = null;
let topChart = null;
let leastChart = null;

// ────────────────────────────────────────────────
// Gender Distribution Chart
// ────────────────────────────────────────────────
function studentGenderDistribution(container) {
    if (!container || !container.dataset.options) return;

    let dataset;
    try {
        dataset = JSON.parse(container.dataset.options);
    } catch (e) {
        console.error("Invalid JSON in gender chart dataset:", e);
        return;
    }

    if (!Array.isArray(dataset) || dataset.length === 0) return;

    const data = dataset.map(dt => dt.total);
    const categories = dataset.map(dt => dt.sex);

    const options = {
        chart: {
            type: "bar",
            height: 350,
            id: "gender-chart"
        },
        series: [{ name: "Count", data }],
        xaxis: { categories },
        colors: ["#E91E63", "#2196F3", "#9C27B0", "#FF9800", "#4CAF50"],
        title: { text: "Students Gender Distribution", align: "left" },
        plotOptions: { bar: { distributed: true } },
        legend: { show: false },
        responsive: [{
            breakpoint: 480,
            options: { chart: { width: 300 } }
        }]
    };

    if (genderChart) genderChart.destroy();
    genderChart = new ApexCharts(container, options);
    genderChart.render();
}

// ────────────────────────────────────────────────
// Age (Birth Year) Distribution Chart
// ────────────────────────────────────────────────
function studentAgeDistribution(container) {
    if (!container || !container.dataset.options) return;

    let parsed;
    try {
        parsed = JSON.parse(container.dataset.options);
    } catch (e) {
        console.error("Invalid JSON in age chart dataset:", e);
        return;
    }

    if (typeof parsed !== 'object' || parsed === null || Object.keys(parsed).length === 0) return;

    const categories = Object.keys(parsed);
    const data = Object.values(parsed);

    const options = {
        chart: {
            type: "bar",
            height: 350,
            id: "age-chart"
        },
        series: [{ name: "Total", data }],
        xaxis: { categories },
        colors: ["#E91E63", "#2196F3", "#9C27B0", "#FF9800", "#4CAF50"],
        title: { text: "Students Birth Year Distribution", align: "left" },
        plotOptions: { bar: { distributed: true } },
        legend: { show: false },
        responsive: [{
            breakpoint: 480,
            options: { chart: { width: 300 } }
        }]
    };

    if (ageChart) ageChart.destroy();
    ageChart = new ApexCharts(container, options);
    ageChart.render();
}

// ────────────────────────────────────────────────
// Top Performing Students Chart
// ────────────────────────────────────────────────
function topPerformingStudents(container, dataset) {
    if (!container) return;

    let dataToUse = dataset;
    try {
        if (!dataToUse && container.dataset.options) {
            dataToUse = JSON.parse(container.dataset.options);
        } else if (typeof dataToUse === "string") {
            dataToUse = JSON.parse(dataToUse);
        }
    } catch (e) {
        console.error("Failed to parse top students dataset:", e);
        container.innerHTML = "<p class='text-center text-gray-500 mt-10'>Invalid data format.</p>";
        return;
    }

    if (!Array.isArray(dataToUse) || dataToUse.length === 0) {
        container.innerHTML = "<p class='text-center text-gray-500 mt-10'>No records found.</p>";
        return;
    }

    if (topChart) topChart.destroy();

    const options = {
        chart: {
            type: 'bar',
            height: 350,
            id: "top-students-chart"
        },
        series: [{ name: 'Average Score', data: dataToUse.map(item => item.average) }],
        xaxis: {
            categories: dataToUse.map(item => item.student_id),
            title: { text: 'Average Score' }
        },
        yaxis: {
            title: { text: 'Student ID' }
        },
        title: {
            text: `Top Performing Students in ${dataToUse[0].subject}`,
            align: 'center'
        },
        dataLabels: { enabled: true },
        plotOptions: {
            bar: { horizontal: true }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 300 },
                plotOptions: { bar: { horizontal: false } } // vertical on mobile for space
            }
        }]
    };

    topChart = new ApexCharts(container, options);
    topChart.render();
}

// ────────────────────────────────────────────────
// Least Performing Students Chart
// ────────────────────────────────────────────────
function leastPerformingStudents(container, dataset) {
    if (!container) return;

    let dataToUse = dataset;
    try {
        if (!dataToUse && container.dataset.options) {
            dataToUse = JSON.parse(container.dataset.options);
        } else if (typeof dataToUse === "string") {
            dataToUse = JSON.parse(dataToUse);
        }
    } catch (e) {
        console.error("Failed to parse least students dataset:", e);
        container.innerHTML = "<p class='text-center text-gray-500 mt-10'>Invalid data format.</p>";
        return;
    }

    if (!Array.isArray(dataToUse) || dataToUse.length === 0) {
        container.innerHTML = "<p class='text-center text-gray-500 mt-10'>No records found.</p>";
        return;
    }

    if (leastChart) leastChart.destroy();

    const options = {
        chart: {
            type: 'bar',
            height: 350,
            id: "least-students-chart"
        },
        series: [{ name: 'Average Score', data: dataToUse.map(item => item.average) }],
        xaxis: {
            categories: dataToUse.map(item => item.student_id),
            title: { text: 'Average Score' }
        },
        yaxis: {
            title: { text: 'Student ID' }
        },
        title: {
            text: `Bottom Performing Students in ${dataToUse[0].subject}`,
            align: 'center'
        },
        dataLabels: { enabled: true },
        plotOptions: {
            bar: { horizontal: true }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 300 },
                plotOptions: { bar: { horizontal: false } }
            }
        }]
    };

    leastChart = new ApexCharts(container, options);
    leastChart.render();
}

// ────────────────────────────────────────────────
// Event Listeners & Initialization
// ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Render static charts
    if (genderDiv) studentGenderDistribution(genderDiv);
    if (ageDiv) studentAgeDistribution(ageDiv);

    // Render initial top/least charts if preloaded data exists
    if (topStudents?.dataset?.options) {
        topPerformingStudents(topStudents);
    }
    if (leastStudents?.dataset?.options) {
        leastPerformingStudents(leastStudents);
    }

    // Top subjects dropdown
    if (topSubjects) {
        topSubjects.addEventListener('change', async function (e) {
            const selectedOption = this.options[this.selectedIndex];
            const url = selectedOption?.getAttribute('data-route');
            if (!url) return;

            const scrollY = window.scrollY;

            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const dataset = await res.json();

                if (!Array.isArray(dataset) || dataset.length === 0) {
                    topStudents.innerHTML = "<p class='text-center text-gray-500 mt-10'>No records found for this subject.</p>";
                    return;
                }

                topPerformingStudents(topStudents, dataset);
            } catch (error) {
                console.error("Error fetching top students data:", error);
                topStudents.innerHTML = "<p class='text-center text-red-500 mt-10'>Failed to load data. Please try again.</p>";
            } finally {
                window.scrollTo(0, scrollY); // Preserve scroll position
            }
        });
    }

    // Least subjects dropdown
    if (leastSubjects) {
        leastSubjects.addEventListener('change', async function (e) {
            const selectedOption = this.options[this.selectedIndex];
            const url = selectedOption?.getAttribute('data-route');
            if (!url) return;

            const scrollY = window.scrollY;

            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const dataset = await res.json();

                if (!Array.isArray(dataset) || dataset.length === 0) {
                    leastStudents.innerHTML = "<p class='text-center text-gray-500 mt-10'>No records found for this subject.</p>";
                    return;
                }

                leastPerformingStudents(leastStudents, dataset);
            } catch (error) {
                console.error("Error fetching least students data:", error);
                leastStudents.innerHTML = "<p class='text-center text-red-500 mt-10'>Failed to load data. Please try again.</p>";
            } finally {
                window.scrollTo(0, scrollY);
            }
        });
    }
});

// Optional: Clean up on page unload (useful in SPAs)
window.addEventListener('beforeunload', () => {
    [genderChart, ageChart, topChart, leastChart].forEach(chart => {
        if (chart && typeof chart.destroy === 'function') chart.destroy();
    });
});