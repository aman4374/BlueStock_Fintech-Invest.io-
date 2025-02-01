<?php
include_once '../includes/db.php';

// Validate the company ID
if (!isset($_GET['id'])) {
    die("Invalid company ID.");
}

$companyId = ($_GET['id']); 

// Fetch company data
$query = "SELECT * FROM companies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$companyData = $stmt->get_result()->fetch_assoc();

if (!$companyData) {
    die("Company not found.");
}
// Fetch analysis data
$query = "SELECT * FROM analysis WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$analysisData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Fetch pros and cons data
$query = "SELECT * FROM prosandcons WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$prosAndConsData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// var_export($prosAndConsData);
// echo $prosAndConsData[0]['pros'];

// Fetch profit and loss data
$query = "SELECT * FROM profitandloss WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$profitLossData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// var_export($profitLossData);

// Fetch balance sheet data
$query = "SELECT * FROM balancesheet WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$balanceSheetData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch cashflow data
$query = "SELECT * FROM cashflow WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$cashFlowData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch documents data
$query = "SELECT * FROM documents WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $companyId);
$stmt->execute();
$documentsData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invest.io</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
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
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled">Disabled</a>
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

<div class="container my-4">
  <!-- Company Information -->
  <section class="mb-4">
    <div class="card shadow-sm">
      <div class="row g-0">
        <div class="col-md-4 text-center p-3">
          <img src="<?php echo htmlspecialchars($companyData['company_logo']); ?>" alt="Company Logo" class="img-fluid rounded-circle">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($companyData['company_name']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($companyData['about_company']); ?></p>
            <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($companyData['website']); ?>" class="text-decoration-none"><?php echo htmlspecialchars($companyData['website']); ?></a></p>
            <p><strong>NSE:</strong> <a href="<?php echo htmlspecialchars($companyData['nse_profile']); ?>" class="text-decoration-none">NSE Link</a></p>
            <p><strong>BSE:</strong> <a href="<?php echo htmlspecialchars($companyData['bse_profile']); ?>" class="text-decoration-none">BSE Link</a></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Analysis Section -->
  <section class="mb-4">
    <h4 class="mb-3">Analysis</h4>
    <div class="row g-3">
      <!-- Card Template -->
      <?php 
      $analysisTitles = ["Compounded Sales Growth", "Compounded Profit Growth", "Stock Price CAGR", "Return on Equity"];
      $analysisKeys = ["compounded_sales_growth", "compounded_profit_growth", "stock_price_cagr", "roe"];
      foreach ($analysisTitles as $index => $title): ?>
        <div class="col-lg-3 col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="card-title"><?php echo $title; ?></h6>
              <?php foreach ($analysisData as $data): ?>
                <p><?php echo htmlspecialchars($data[$analysisKeys[$index]]); ?></p>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Pros and Cons -->
  <section class="mb-4">
    <h4 class="mb-3">Pros and Cons</h4>
    <div class="row">
      <div class="col-md-6">
        <div class="card bg-success text-white">
          <div class="card-body">
            <h6 class="card-title">Pros</h6>
            <ul class="list-group list-group-flush">
              <?php foreach ($prosAndConsData as $row): 
                if ($row['pros'] != "NULL"): ?>
                  <li class="list-group-item bg-success text-white"><?php echo htmlspecialchars($row['pros']); ?></li>
              <?php endif; endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-danger text-white">
          <div class="card-body">
            <h6 class="card-title">Cons</h6>
            <ul class="list-group list-group-flush">
              <?php foreach ($prosAndConsData as $row): 
                if ($row['cons'] != "NULL"): ?>
                  <li class="list-group-item bg-danger text-white"><?php echo htmlspecialchars($row['cons']); ?></li>
              <?php endif; endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

 <!-- Balance Sheet Section -->
<section class="mb-4">
  <h4 class="mb-3">Balance Sheet</h4>
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
        <?php foreach ($balanceSheetData as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['year']); ?></td>
            <td><?php echo htmlspecialchars($row['equity_capital']); ?></td>
            <td><?php echo htmlspecialchars($row['reserves']); ?></td>
            <td><?php echo htmlspecialchars($row['borrowings']); ?></td>
            <td><?php echo htmlspecialchars($row['other_liabilities']); ?></td>
            <td><?php echo htmlspecialchars($row['total_liabilities']); ?></td>
            <td><?php echo htmlspecialchars($row['fixed_assets']); ?></td>
            <td><?php echo htmlspecialchars($row['cwip']); ?></td>
            <td><?php echo htmlspecialchars($row['investments']); ?></td>
            <td><?php echo htmlspecialchars($row['other_asset']); ?></td>
            <td><?php echo htmlspecialchars($row['total_assets']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
  <!-- Profit and Loss Section -->
<section class="mb-4">
  <h4 class="mb-3">Profit and Loss</h4>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class>
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
        <?php foreach ($profitLossData as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['year']); ?></td>
            <td><?php echo htmlspecialchars($row['sales']); ?></td>
            <td><?php echo htmlspecialchars($row['expenses']); ?></td>
            <td><?php echo htmlspecialchars($row['operating_profit']); ?></td>
            <td><?php echo htmlspecialchars($row['opm_percentage']); ?></td>
            <td><?php echo htmlspecialchars($row['other_income']); ?></td>
            <td><?php echo htmlspecialchars($row['interest']); ?></td>
            <td><?php echo htmlspecialchars($row['depreciation']); ?></td>
            <td><?php echo htmlspecialchars($row['profit_before_tax']); ?></td>
            <td><?php echo htmlspecialchars($row['tax_percentage']); ?></td>
            <td><?php echo htmlspecialchars($row['net_profit']); ?></td>
            <td><?php echo htmlspecialchars($row['eps']); ?></td>
            <td><?php echo htmlspecialchars($row['dividend_payout']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

 <!-- Cash Flow Section -->
<section class="mb-4">
  <h4 class="mb-3">Cash Flow</h4>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class>
        <tr>
          <th>Year</th>
          <th>Operating Cash Flow</th>
          <th>Investing Cash Flow</th>
          <th>Financing Cash Flow</th>
          <th>Net Cash Flow</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cashFlowData as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['year']); ?></td>
            <td><?php echo htmlspecialchars($row['operating_activity']); ?></td>
            <td><?php echo htmlspecialchars($row['investing_activity']); ?></td>
            <td><?php echo htmlspecialchars($row['financing_activity']); ?></td>
            <td><?php echo htmlspecialchars($row['net_cash_flow']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
  <!-- Documents Section -->
<section class="mb-4">
<h4 class="mb-3">Documents</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Year</th>
                    <th>Annual Report</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentsData as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Year']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['Annual_Report']); ?>" class="text-decoration-none text-primary" target="_blank">
                                View Report
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
</div>
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-3y8Fx1xsgSY7t7V7QlFLG4nJpTW+o0Uz7c+JvxsEO1HHiqlZ97hCZGz9HrH6k2jW" crossorigin="anonymous"></script>
</body>
</html>
