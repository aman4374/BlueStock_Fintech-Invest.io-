const jsonFilePath = 'assets/data/HDFC.json';
let chartInstance = null;
let showPrice = true;
let showPERatio = false;

// Fetch and render chart
async function fetchAndRenderChart(range = '1y') {
    const response = await fetch(jsonFilePath);
    const jsonData = await response.json();

    // Filter data based on the range
    const datasets = jsonData.datasets;
    const filteredData = datasets.map(dataset => {
        const filteredValues = dataset.values.filter(([date]) => isInRange(date, range));
        return { ...dataset, values: filteredValues };
    });

    renderChart(filteredData);
}

// Check if date is in range
function isInRange(date, range) {
    const now = new Date();
    const pastDate = new Date();
    switch (range) {
        case '1m':
            pastDate.setMonth(now.getMonth() - 1);
            break;
        case '6m':
            pastDate.setMonth(now.getMonth() - 6);
            break;
        case '1y':
            pastDate.setFullYear(now.getFullYear() - 1);
            break;
        case '3y':
            pastDate.setFullYear(now.getFullYear() - 3);
            break;
        case '5y':
            pastDate.setFullYear(now.getFullYear() - 5);
            break;
        case '10y':
            pastDate.setFullYear(now.getFullYear() - 10);
            break;
        case 'max':
            return true; // No filtering for max
    }
    return new Date(date) >= pastDate;
}

// Format date for chart labels
function formatDate(dateString) {
    const date = new Date(dateString);
    const month = date.toLocaleString('default', { month: 'short' });
    return `${month} ${date.getFullYear()}`;
}

// Render chart
function renderChart(datasets) {
    const ctx = document.getElementById('stockChart').getContext('2d');
    const labels = datasets[0].values.map(value => formatDate(value[0])); // Formatted Dates

    const chartData = datasets
        .filter(dataset => {
            if (dataset.metric === 'Price' && showPrice) return true;
            if (dataset.metric === 'PE Ratio' && showPERatio) return true;
            if (['DMA50', 'DMA200', 'Volume'].includes(dataset.metric)) {
                return document.getElementById(dataset.metric.toLowerCase()).checked;
            }
            return false;
        })
        .map(dataset => ({
            label: dataset.label,
            data: dataset.values.map(value => parseFloat(value[1])),
            borderColor: dataset.metric === 'Price' ? '#2f7ded' :
                         dataset.metric === 'DMA50' ? '#f3a952' :
                         dataset.metric === 'DMA200' ? '#8c9bb5' :
                         dataset.metric === 'PE Ratio' ? '#00a676' :
                         'rgba(173,216,230,0.5)',
            backgroundColor: dataset.metric === 'Volume' ? 'rgba(173,216,230,0.2)' : 'transparent',
            borderWidth: 2,
            yAxisID: dataset.metric === 'Volume' ? 'yVolume' : 'yPrice',
        }));

    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: chartData
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#2f7ded'
                    }
                }
            },
            scales: {
                yPrice: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Price', color: '#8c9bb5' },
                    grid: { color: 'rgba(140, 155, 181, 0.2)' }
                },
                yVolume: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: 'Volume', color: '#8c9bb5' },
                    grid: { drawOnChartArea: false }
                },
                x: {
                    title: { display: true, text: 'Date', color: '#8c9bb5' },
                    grid: { color: 'rgba(140, 155, 181, 0.2)' }
                }
            }
        }
    });
}

// Event listeners
document.querySelectorAll('.filters button').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.filters button').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        fetchAndRenderChart(button.dataset.range);
    });
});

document.querySelectorAll('.checkboxes input').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        fetchAndRenderChart(document.querySelector('.filters button.active').dataset.range);
    });
});

document.querySelectorAll('.right-buttons button').forEach(button => {
    button.addEventListener('click', () => {
        const metric = button.dataset.metric;
        if (metric === 'Price') {
            showPrice = true;
            showPERatio = false;
        } else if (metric === 'PE Ratio') {
            showPrice = false;
            showPERatio = true;
        }
        document.querySelectorAll('.right-buttons button').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        fetchAndRenderChart(document.querySelector('.filters button.active').dataset.range);
    });
});

// Initial load
fetchAndRenderChart();
