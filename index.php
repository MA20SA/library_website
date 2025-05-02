<?php
include('includes/db.php');
include('includes/header.php');

// Books per page
$books_per_page = 3;

// Current page
// isset() to check if page has value or not ..
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculate Offset
$offset = ($page - 1) * $books_per_page;

// Get Total Books
$count_query = "SELECT COUNT(*) as total_books FROM books";
$count_result = mysqli_query($conn, $count_query);
$total_books = mysqli_fetch_assoc($count_result)['total_books'];

// Total Pages
$total_pages = ceil($total_books / $books_per_page);

// Get Books for Current Page
$query = "SELECT b.book_id,b.name as book_name,b.title,b.quantity_available, b.publish_date, i.path,
          a.name as author_name,p.name as publisher_name
          FROM `books` as b 
          LEFT JOIN books_images as i ON b.book_id = i.book_id 
          LEFT JOIN authors as a ON b.author_id = a.author_id 
          LEFT JOIN publishers as p ON b.publisher_id = p.publisher_id
          ORDER BY b.book_id DESC
          LIMIT $books_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Page</title>

    <!-- CSS and Bootstrap -->
    <link rel="stylesheet" href="includes/CSS/style.css?v=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function changePage(pageNumber) {
            window.location.href = "index.php?page=" + pageNumber;
        }

        function makeOrder(bookId, bookName) {
            bookName = encodeURIComponent(bookName);
            window.location.href = "AddOrder.php?bookid=" + bookId + "&bookname=" + bookName;
        }
    </script>
</head>

<body>

    <div class="BooksCardsView">
        <?php
        if (mysqli_num_rows($result) == 0) {
            echo "<h3 style='text-align: center;'>📚 لا توجد كتب متاحة حالياً</h3>";
        }
        ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div onclick="
                if(<?php echo $row['quantity_available']; ?> > 0) {
                 makeOrder('<?php echo htmlspecialchars($row['book_id']); ?>',
                  '<?php echo htmlspecialchars($row['book_name'], ENT_QUOTES); ?>') }"
                class="OneBookCard" key="<?php echo htmlspecialchars($row['book_id']); ?>">
                <img style="object-fit: fill;" class="imgOfCard" src="<?php echo htmlspecialchars($row["path"] ?  $row["path"] : "imagesOfWebsite/default-book.avif"); ?>" alt="noImg" />

                <p><?php echo htmlspecialchars($row['book_name']); ?>, </p>
                <span><?php echo htmlspecialchars($row['title']); ?></span>

                <p>Author: <?php echo htmlspecialchars($row['author_name']); ?></p>
                <p>Publisher: <?php echo htmlspecialchars($row['publisher_name']); ?></p>
                <p>Publish Date: <?php echo htmlspecialchars($row['publish_date']); ?></p>
                <p class="badge <?php echo ($row['quantity_available'] == 0) ? 'bg-danger' : 'bg-primary'; ?>">
                    <?php echo htmlspecialchars($row['quantity_available']); ?>
                </p>

            </div>
        <?php } ?>
    </div>

    <!-- Pagination Buttons -->
    <div style="text-align: center; margin: 25px;">

        <?php
        // that's mean $page != 1
        if ($page > 1): ?>
            <button onclick="changePage(<?php echo $page - 1; ?>)" class="btn btn-dark">&larr;</button>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <button
                onclick="changePage(<?php echo $i; ?>)"
                style="<?php
                        echo ($page == $i) ?
                            'background-color:rgb(22, 23, 24);margin:5px; padding:7px 10px; font-size:14px; color:white;
                  border:none; border-radius:5px'
                            : 'background-color:rgb(86, 89, 92); margin:5px; padding:7px 10px; font-size:14px; color:white;
                  border:none; border-radius:5px';
                        ?>">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>

        <?php
        // that's mean $page != last page
        if ($page < $total_pages): ?>
            <button onclick="changePage(<?php echo $page + 1; ?>)" class="btn btn-dark">&rarr;</button>
        <?php endif; ?>
    </div>

    <?php mysqli_close($conn); ?>

</body>

</html>

<?php include('includes/footer.php'); ?>