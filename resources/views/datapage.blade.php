<!DOCTYPE html>
<html>

<head>
    <title>Task</title>

    <style>
        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }

        .pagination-links {
            text-align: center;
            margin-top: 20px;
        }

        #searchInput {
            width: 100%;
            padding: 8px;
            margin-top: 20px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <h1>RSS Feed Data</h1>

    <input type="text" id="searchInput" placeholder="Search for titles.." onkeyup="searchTable()">

    <div class="pagination-controls">
        <label for="rowsPerPage">Rows per page:</label>
        <select id="rowsPerPage" onchange="updateRowsPerPage()">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
        </select>
    </div>

    <table id="dataTable">
        <thead>
            <tr>
                <th onclick="sortTable('title')">Title</th>
                <th onclick="sortTable('description')">Description</th>
                <th onclick="sortTable('link')">Link</th>
                <th onclick="sortTable('pubDate')">Publication Date</th>
                <th onclick="sortTable('dc:creator')">Creator</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Data will be inserted here by JavaScript -->
        </tbody>
    </table>

    <div class="pagination-links" id="pagination"></div>

    <script>
        const data = @json($data);

        let rowsPerPage = 5;
        let currentPage = 1;
        let filteredData = data;
        let sortDirection = 1;

        function displayTablePage(page) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';

            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedItems = filteredData.slice(start, end);

            paginatedItems.forEach(item => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${item.title}</td>
                    <td>${stripTags(item.description)}</td>
                    <td><a href="${item.link}" target="_blank">Read more</a></td>
                    <td>${item.pubDate}</td>
                    <td>${item['dc:creator'] ? item['dc:creator']['#text'] : 'Unknown'}</td>
                    <td>
                        ${item.enclosure && item.enclosure['@url']
                            ? (item.description.includes(item.enclosure['@url'])
                                ? 'Image in description'
                                : `<img src="${item.enclosure['@url']}" alt="Image" width="100">`)
                            : 'No image'}
                    </td>
                `;
                tableBody.appendChild(row);
            });

            displayPagination();
        }

        function stripTags(html) {
            const div = document.createElement('div');
            div.innerHTML = html;
            return div.textContent || div.innerText || '';
        }

        function displayPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const pageCount = Math.ceil(filteredData.length / rowsPerPage);

            const prevButton = document.createElement('button');
            prevButton.innerText = 'Previous';
            prevButton.disabled = currentPage === 1;
            prevButton.addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    displayTablePage(currentPage);
                }
            });
            pagination.appendChild(prevButton);

            let startPage = Math.max(currentPage - 1, 1);
            let endPage = Math.min(currentPage + 1, pageCount);

            if (currentPage === 1) {
                endPage = Math.min(3, pageCount);
            } else if (currentPage === pageCount) {
                startPage = Math.max(pageCount - 2, 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.innerText = i;
                button.className = 'page-btn';
                button.addEventListener('click', function () {
                    currentPage = i;
                    displayTablePage(i);
                });

                if (i === currentPage) {
                    button.style.fontWeight = 'bold';
                }

                pagination.appendChild(button);
            }

            const nextButton = document.createElement('button');
            nextButton.innerText = 'Next';
            nextButton.disabled = currentPage === pageCount;
            nextButton.addEventListener('click', function () {
                if (currentPage < pageCount) {
                    currentPage++;
                    displayTablePage(currentPage);
                }
            });
            pagination.appendChild(nextButton);
        }

        function updateRowsPerPage() {
            rowsPerPage = parseInt(document.getElementById('rowsPerPage').value, 10);
            currentPage = 1;
            displayTablePage(currentPage);
        }

        function searchTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            filteredData = data.filter(item => {
                return (
                    item.title.toLowerCase().includes(searchInput) ||
                    stripTags(item.description).toLowerCase().includes(searchInput) ||
                    item.link.toLowerCase().includes(searchInput) ||
                    item.pubDate.toLowerCase().includes(searchInput) ||
                    (item['dc:creator'] ? item['dc:creator']['#text'] : 'Unknown').toLowerCase().includes(searchInput)
                );
            });
            currentPage = 1;
            displayTablePage(currentPage);
        }

        function sortTable(column) {
            if (sortColumn === column) {
                sortDirection *= -1;
            } else {
                sortDirection = 1;
                sortColumn = column;
            }

            filteredData.sort((a, b) => {
                const aValue = (column === 'dc:creator' ? a[column] ? a[column]['#text'] : 'Unknown' : a[column]) || '';
                const bValue = (column === 'dc:creator' ? b[column] ? b[column]['#text'] : 'Unknown' : b[column]) || '';

                if (aValue < bValue) return -1 * sortDirection;
                if (aValue > bValue) return 1 * sortDirection;
                return 0;
            });

            displayTablePage(currentPage);
        }

        displayTablePage(currentPage);
    </script>
</body>

</html>
