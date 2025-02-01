<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invest.io</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <div class="relative w-80">
            <div class="flex items-center border border-gray-300 rounded-lg bg-white shadow-sm">
                <img class="w-5 h-5 ml-3" src="public/Magnifying glass.svg" alt="Search Icon">
                <input 
                    type="text" 
                    id="searchBox" 
                    class="w-full px-3 py-2 text-gray-700 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Search for a company" 
                    onkeyup="filterCompanies()" 
                    onclick="showAllCompanies()"
                >
            </div>
            <div 
                id="dropdown" 
                class="absolute left-0 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto mt-2 hidden">
                <a href="pages/company.php?id=TCS" class="block px-4 py-2 hover:bg-blue-50">TCS</a>
                <a href="pages/company.php?id=INFY" class="block px-4 py-2 hover:bg-blue-50">Infosys</a>
                <a href="pages/company.php?id=HDFCBANK" class="block px-4 py-2 hover:bg-blue-50">HDFC</a>
                <a href="pages/company.php?id=SBILIFE" class="block px-4 py-2 hover:bg-blue-50">SBI</a>
                <a href="pages/company.php?id=WIPRO" class="block px-4 py-2 hover:bg-blue-50">Wipro</a>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const dropdown = document.getElementById("dropdown");

        function filterCompanies() {
            const input = document.getElementById("searchBox").value.toLowerCase();
            const options = dropdown.getElementsByTagName("a");

            for (let i = 0; i < options.length; i++) {
                const txtValue = options[i].textContent || options[i].innerText;
                options[i].style.display = txtValue.toLowerCase().includes(input) ? "block" : "none";
            }

            if (input === "") {
                showAllCompanies();
            }
        }

        function showAllCompanies() {
            dropdown.classList.remove("hidden");
        }

        document.addEventListener("click", function(event) {
            if (!dropdown.contains(event.target) && event.target.id !== "searchBox") {
                dropdown.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
