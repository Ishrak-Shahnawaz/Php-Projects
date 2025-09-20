<?php
// ================== Library Class ==================
class Library {
    private $conn;

    // Constructor: Connect to DB
    public function __construct() {
        $servername = "localhost";
        $username = "root";   // default for XAMPP
        $password = "";       // default is empty
        $dbname = "library_db";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Add book
    public function addBook($title, $author, $year) {
        $sql = "INSERT INTO books (title, author, year) VALUES ('$title', '$author', '$year')";
        return $this->conn->query($sql);
    }

    // Update book
    public function updateBook($id, $title, $author, $year) {
        $sql = "UPDATE books SET title='$title', author='$author', year='$year' WHERE id=$id";
        return $this->conn->query($sql);
    }

    // Delete book
    public function deleteBook($id) {
        $sql = "DELETE FROM books WHERE id=$id";
        return $this->conn->query($sql);
    }

    // Get all books
    public function getBooks() {
        return $this->conn->query("SELECT * FROM books");
    }

    // Get one book
    public function getBook($id) {
        return $this->conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
    }
}

// ================== Object ==================
$library = new Library();

// ================== Handle Actions ==================
$edit_mode = false;
$edit_book = null;

if (isset($_POST['add'])) {
    $library->addBook($_POST['title'], $_POST['author'], $_POST['year']);
}

if (isset($_POST['update'])) {
    $library->updateBook($_POST['id'], $_POST['title'], $_POST['author'], $_POST['year']);
}

if (isset($_GET['delete'])) {
    $library->deleteBook($_GET['delete']);
}

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_book = $library->getBook($_GET['edit']);
}

$books = $library->getBooks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library System</title>
</head>
<body>
    <h2>Library Management System</h2>

    <!-- Add / Edit Form -->
    <?php if ($edit_mode): ?>
        <h3>Edit Book</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $edit_book['id']; ?>">
            Title: <input type="text" name="title" value="<?php echo $edit_book['title']; ?>" required><br><br>
            Author: <input type="text" name="author" value="<?php echo $edit_book['author']; ?>" required><br><br>
            Year: <input type="number" name="year" value="<?php echo $edit_book['year']; ?>" required><br><br>
            <button type="submit" name="update">Update Book</button>
            <a href="library.php">Cancel</a>
        </form>
    <?php else: ?>
        <h3>Add Book</h3>
        <form method="POST">
            Title: <input type="text" name="title" required><br><br>
            Author: <input type="text" name="author" required><br><br>
            Year: <input type="number" name="year" required><br><br>
            <button type="submit" name="add">Add Book</button>
        </form>
    <?php endif; ?>

    <hr>

    <!-- Show Books -->
    <h3>Book List</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Title</th><th>Author</th><th>Year</th><th>Actions</th>
        </tr>
        <?php while($row = $books->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['year']; ?></td>
                <td>
                    <a href="library.php?edit=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="library.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>