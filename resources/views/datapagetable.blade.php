<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
        }

        .pagination-links {
            text-align: center;
            margin-top: 20px;
        }

        /* Style for the search input */
        #searchInput {
            width: 100%;
            padding: 8px;
            margin-top: 20px;
            box-sizing: border-box;
        }
    </style>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>Task</h1>

    <table id="dataTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Link</th>
                <th>Publication Date</th>
                <th>Creator</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item['title'] }}</td>
                <td>{{ strip_tags($item['description']) }}</td>
                <td><a href="{{ $item['link'] }}" target="_blank">Read more</a></td>
                <td>{{ $item['pubDate'] }}</td>
                <td>{{ $item['dc:creator']['#text'] ?? 'Unknown' }}</td>
                <td>
                    @if (isset($item['enclosure']['@url']))
                    @if (strpos($item['description'], $item['enclosure']['@url']) === false)
                    <img src="{{ $item['enclosure']['@url'] }}" alt="Image" width="100">
                    @else
                    Image in description
                    @endif
                    @else
                    No image
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true
            });
        });
    </script>
</body>

</html>
