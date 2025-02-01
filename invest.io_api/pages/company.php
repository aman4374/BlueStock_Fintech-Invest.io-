<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invest.io - Company Analysis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php">Invest.io</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>


<nav aria-label="breadcrumb" class="mt-3">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Company Analysis</li>
  </ol>
</nav>

<div class="container my-4" id="companyContainer">
  <!-- Placeholder for data to be loaded dynamically -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const urlParams = new URLSearchParams(window.location.search);
  const companyId = urlParams.get('id');

  if (!companyId) {
    document.getElementById('companyContainer').innerHTML =
      '<div class="alert alert-danger">No company ID provided.</div>';
    return;
  }

  fetch(`proxy.php?id=${companyId}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        document.getElementById('companyContainer').innerHTML =
          `<div class="alert alert-danger">${data.error}</div>`;
        return;
      }
      
      const company = data.company;
      const analysisData = data.data.analysis || [];
      const prosAndConsData = data.data.prosandcons || [];
      const balanceSheetData = data.data.balancesheet || [];
      const profitLossData = data.data.profitandloss || [];
      const cashFlowData = data.data.cashflow || [];
      const documentsData = data.data.documents || [];


      // Company Information Section
      let html = `
<section class="mb-4">
  <div class="card shadow-sm">
    <div class="row g-0">
      <div class="col-md-4 text-center p-3">
        <img src="${company.company_logo}" alt="Company Logo" class="img-fluid rounded-circle">
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title">${company.company_name}</h5>
          <p class="card-text">${company.about_company}</p>
          <p><strong>Website:</strong> <a href="${company.website}" class="text-decoration-none">${company.website}</a></p>
          <p><strong>NSE:</strong> <a href="${company.nse_profile}" class="text-decoration-none">NSE Link</a></p>
          <p><strong>BSE:</strong> <a href="${company.bse_profile}" class="text-decoration-none">BSE Link</a></p>
          <p><strong>Chart:</strong>
          <a href="${company.chart_link}" class="btn btn-danger" role="button">Launch Chart</a></p>
          <p><strong>Symbol: </strong>${company.id}</p>
        </div>
      </div>
    </div>
  </div>
</section>

      `;

      // Analysis Section
      html += `
        <section class="mb-4">
          <h4 class="mb-3">Analysis</h4>
          <div class="row g-3">
      `;
      const analysisTitles = ["Compounded Sales Growth", "Compounded Profit Growth", "Stock Price CAGR", "Return on Equity"];
      const analysisKeys = ["compounded_sales_growth", "compounded_profit_growth", "stock_price_cagr", "roe"];
      analysisTitles.forEach((title, index) => {
        html += `
          <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm">
              <div class="card-body">
                <h6 class="card-title">${title}</h6>
                ${analysisData.map(data => `<p>${data[analysisKeys[index]]}</p>`).join('')}
              </div>
            </div>
          </div>
        `;
      });
      html += `</div></section>`;

      // Pros and Cons Section
      html += `
        <section class="mb-4">
          <h4 class="mb-3">Pros and Cons</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="card bg-success text-white">
                <div class="card-body">
                  <h6 class="card-title">Pros</h6>
                  <ul class="list-group list-group-flush">
                    ${prosAndConsData.map(row => row.pros && row.pros !== "NULL" ? `<li class="list-group-item bg-success text-white">${row.pros}</li>` : '').join('')}
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card bg-danger text-white">
                <div class="card-body">
                  <h6 class="card-title">Cons</h6>
                  <ul class="list-group list-group-flush">
                    ${prosAndConsData.map(row => row.cons && row.cons !== "NULL" ? `<li class="list-group-item bg-danger text-white">${row.cons}</li>` : '').join('')}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>
      `;

      // Balance Sheet Section
      html += `
        <section class="mb-4">
          <h4 class="mb-3">Balance Sheet</h4>
          <p>Consolidated Financial Statements . All Figures in INR Crores</p>
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Year</th>
                  <th>Equity Capital</th>
                  <th>Reserves</th>
                  <th>Borrowings</th>
                  <th>Other Liabilities</th>
                  <th>Total Liabilities</th>
                  <th>Fixed Assets</th>
                  <th>CWIP</th>
                  <th>Investments</th>
                  <th>Other Assets</th>
                  <th>Total Assets</th>
                </tr>
              </thead>
              <tbody>
                ${balanceSheetData.map(row => `
                  <tr>
                    <td>${row.year}</td>
                    <td>${row.equity_capital}</td>
                    <td>${row.reserves}</td>
                    <td>${row.borrowings}</td>
                    <td>${row.other_liabilities}</td>
                    <td>${row.total_liabilities}</td>
                    <td>${row.fixed_assets}</td>
                    <td>${row.cwip}</td>
                    <td>${row.investments}</td>
                    <td>${row.other_asset}</td>
                    <td>${row.total_assets}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </section>
      `;

      // Profit and Loss Section
      html += `
        <section class="mb-4">
          <h4 class="mb-3">Profit and Loss</h4>
          <p>Consolidated Financial Statements . All Figures in INR Crores</p>
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Year</th>
                  <th>Sales</th>
                  <th>Expenses</th>
                  <th>Operating Profit</th>
                  <th>OPM (%)</th>
                  <th>Other Income</th>
                  <th>Interest</th>
                  <th>Depreciation</th>
                  <th>Profit Before Tax</th>
                  <th>Tax (%)</th>
                  <th>Net Profit</th>
                  <th>EPS</th>
                  <th>Dividend</th>
                </tr>
              </thead>
              <tbody>
                ${profitLossData.map(row => `
                  <tr>
                    <td>${row.year}</td>
                    <td>${row.sales}</td>
                    <td>${row.expenses}</td>
                    <td>${row.operating_profit}</td>
                    <td>${row.opm_percentage}</td>
                    <td>${row.other_income}</td>
                    <td>${row.interest}</td>
                    <td>${row.depreciation}</td>
                    <td>${row.profit_before_tax}</td>
                    <td>${row.tax_percentage}</td>
                    <td>${row.net_profit}</td>
                    <td>${row.eps}</td>
                    <td>${row.dividend_payout}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </section>
      `;

      // Cash Flow Section
      html += `
        <section class="mb-4">
          <h4 class="mb-3">Cash Flow</h4>
          <p>Consolidated Financial Statements . All Figures in INR Crores</p>
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class>
                <tr>
                  <th>Year</th>
                  <th>Net Cash from Operating Activities</th>
                  <th>Net Cash from Investing Activities</th>
                  <th>Net Cash from Financing Activities</th>
                  <th>Net Increase in Cash</th>
                </tr>
              </thead>
              <tbody>
                ${cashFlowData.map(row => `
                  <tr>
                    <td>${row.year}</td>
                    <td>${row.operating_activity}</td>
                    <td>${row.investing_activity}</td>
                    <td>${row.financing_activity}</td>
                    <td>${row.net_cash_flow}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </section>
      `;

      // Documents Section
html += `
  <section class="mb-4">
    <h4 class="mb-3">Documents</h4>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class>
          <tr>
            <th>Year</th>
            <th>Annual Report</th>
          </tr>
        </thead>
        <tbody>
          ${documentsData.map(doc => `
            <tr>
              <td>${doc.Year}</td>
              <td>
                <a href="${doc.Annual_Report}" target="_blank" class="text-decoration-none text-primary">
                  View Report
                </a>
              </td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    </div>
  </section>
`;
document.getElementById('companyContainer').innerHTML = html;

      // Add generated content to container
    })
    .catch(error => {
      document.getElementById('companyContainer').innerHTML =
        '<div class="alert alert-danger">Error fetching company data.</div>';
    });
});
</script>

<footer id="large-footer" class="bg-light py-5 border-top">
  <div class="container">
    <div class="row">
      <!-- Logo Section -->
      <div class="col-12 col-md-4 mb-4 mb-md-0">
        <h2>
          <a href="/" class="text-dark text-decoration-none fw-bold fs-4">Invest.io</a>
        </h2>
        <p class="d-none d-md-block fs-5 fw-medium mt-2">Stock analysis and screening tool</p>
        <p class="d-none d-md-block text-muted small mt-4">
          Made with <span class="text-danger">&#9829;</span> in India.
        </p>
        <p class="d-none d-md-block text-muted small mt-2">
          <a href="/guides/terms/" class="text-decoration-underline">Terms</a> & 
          <a href="/guides/privacy/" class="text-decoration-underline">Privacy</a>.
        </p>
      </div>

      <!-- Links Section -->
      <div class="col-12 col-md-8">
        <div class="row">
          <!-- Product Links -->
          <div class="col-12 col-sm-4 mb-4 mb-sm-0">
            <div class="fw-semibold mb-3">Product</div>
            <ul class="list-unstyled">
              <li><a href="/premium/?driver=footer" class="text-muted text-decoration-none">Premium</a></li>
              <li><a href="/docs/changelog/" class="text-muted text-decoration-none">What's new?</a></li>
              <li><a href="https://bit.ly/learnscreener" class="text-muted text-decoration-none">Learn</a></li>
              <li>
                <button class="btn btn-light text-muted">
                  <i class="icon-flash"></i> Install
                </button>
              </li>
            </ul>
          </div>
          
          <!-- Team Links -->
          <div class="col-12 col-sm-4 mb-4 mb-sm-0">
            <div class="fw-semibold mb-3">Team</div>
            <ul class="list-unstyled">
              <li><a href="/guides/about-us/" class="text-muted text-decoration-none">About us</a></li>
              <li><a href="/support/" class="text-muted text-decoration-none">Support</a></li>
            </ul>
          </div>

          <!-- Theme Links -->
          <div class="col-12 col-sm-4">
            <div class="fw-semibold mb-3">Theme</div>
            <ul class="list-unstyled">
              <li>
                <button onclick="SetTheme('light')" class="btn btn-link text-muted text-decoration-none p-0">
                  <i class="icon-sun"></i> Light
                </button>
              </li>
              <li>
                <button onclick="SetTheme('dark')" class="btn btn-link text-muted text-decoration-none p-0">
                  <i class="icon-moon"></i> Dark
                </button>
              </li>
              <li>
                <button onclick="SetTheme('auto')" class="btn btn-link text-muted text-decoration-none p-0">
                  <i class="icon-monitor"></i> Auto
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer Bottom -->
    <div class="row mt-4 border-top pt-3 d-md-none">
      <div class="col-12">
        <p class="text-center small text-muted">
          <a href="/guides/terms/" class="text-decoration-underline">Terms</a> & 
          <a href="/guides/privacy/" class="text-decoration-underline">Privacy</a>.
        </p>
      </div>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU7rCZs6y4Aqyk9vc4b6f4o6tn5DQQTP6x70VbZ1H5Kl5Sfw" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-cuLJ/jT6EZGrnPYYlUBZFCZX5QOk5ZBQ0LkK47AX8LmKJlGbql6nZY5HfR3VjbdM" crossorigin="anonymous"></script>
</body>
</html>
