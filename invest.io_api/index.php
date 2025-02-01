<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invest.io</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex flex-col items-center justify-center min-h-screen">
        <!-- Image Section -->
        <div class="mb-6">
            <img class="w-[500px] h-[300px]" src="public/Stock market.svg" alt="Stock Market">
        </div>

        <!-- Title Section -->
        <div class="text-center text-3xl font-semibold mb-8">
            Find the right pick with <span class="text-white bg-purple-700 px-2 rounded">Invest.io</span>
        </div>

        <!-- Search Section -->
        <div class="relative w-80 mb-6">
            <div class="flex items-center border border-gray-300 rounded-lg bg-white shadow-sm">
                <img class="w-5 h-5 ml-3" src="public/Magnifying glass.svg" alt="Search Icon">
                <input 
                    type="text" 
                    id="searchBox" 
                    class="w-full px-3 py-2 text-gray-700 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Search for a company" 
                    onkeyup="fetchCompanies()" 
                >
            </div>
            <div 
                id="dropdown" 
                class="absolute left-0 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto mt-2 hidden">
            </div>
        </div>

        <!-- Suggestions Section -->
        <div class="mt-4">
            <p class="text-gray-500 px-12">Or analyse:</p>
            <div class="flex flex-wrap justify-center gap-2 mt-2 w-full">
                <!-- First row -->
                <div class="flex justify-center gap-2 w-full">
                    <a href="pages/company.php?id=HDFCBANK" class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">HDFC Bank</a>
                    <a href="pages/company.php?id=TCS" class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">TCS</a>
                    <a href="pages/company.php?id=INFY" class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">Infosys</a>
                </div>
                <!-- Second row -->
                <div class="flex justify-center gap-2 w-full mt-2">
                    <a href="pages/company.php?id=WIPRO" class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">Wipro</a>
                    <a href="pages/company.php?id=SBILIFE" class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">SBI Life Insurance</a>
                </div>
            </div>
        </div>

        <!-- View All Companies Button -->
        <div class="mt-6">
            <button 
                id="viewAllBtn"
                class="px-4 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800"
                onclick="window.location.href='view_all.html'">
                View All Companies
            </button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const PROXY_URL = "proxy2.php"; // URL for the proxy

        // Function to fetch companies by search term
        function fetchCompanies() {
            const searchTerm = document.getElementById("searchBox").value.trim();

            if (searchTerm === "") {
                document.getElementById("dropdown").classList.add("hidden");
                document.getElementById("dropdown").innerHTML = ""; // Clear the dropdown
                return;
            }

            fetch(PROXY_URL + `?id=${encodeURIComponent(searchTerm)}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.companies && data.companies.length > 0) {
                    populateDropdown(data.companies);
                } else {
                    document.getElementById("dropdown").innerHTML = `<p class='px-4 py-2 text-gray-500'>No companies found</p>`;
                    document.getElementById("dropdown").classList.remove("hidden");
                }
            })
            .catch(error => console.error("Error:", error));
        }

        // Populate dropdown
        function populateDropdown(companies) {
            const dropdown = document.getElementById("dropdown");
            dropdown.innerHTML = ""; // Clear previous results
            companies.forEach(company => {
                const companyLink = document.createElement("a");
                companyLink.href = `pages/company.php?id=${company.id}`;
                companyLink.className = "block px-4 py-2 hover:bg-blue-50";
                companyLink.textContent = company.company_name;
                dropdown.appendChild(companyLink);
            });
            dropdown.classList.remove("hidden");
        }
    </script>
</body>
</html>
