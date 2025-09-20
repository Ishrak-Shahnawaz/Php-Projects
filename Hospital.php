<?php
session_start(); // Start session for messages

// ================== Hospital Class ==================
class Hospital {
    private $conn;

    // Constructor: Connect to DB
    public function __construct() {
        $servername = "localhost";
        $username = "root";   // default for XAMPP
        $password = "";       // default empty
        $dbname = "hospital_db"; // <-- create this database

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Add patient
    public function addPatient($name, $age, $disease) {
        $sql = "INSERT INTO patients (name, age, disease) VALUES ('$name', '$age', '$disease')";
        return $this->conn->query($sql);
    }

    // Update patient
    public function updatePatient($id, $name, $age, $disease) {
        $sql = "UPDATE patients SET name='$name', age='$age', disease='$disease' WHERE id=$id";
        return $this->conn->query($sql);
    }

    // Delete patient
    public function deletePatient($id) {
        $sql = "DELETE FROM patients WHERE id=$id";
        return $this->conn->query($sql);
    }

    // Get all patients
    public function getPatients() {
        return $this->conn->query("SELECT * FROM patients");
    }

    // Get one patient
    public function getPatient($id) {
        return $this->conn->query("SELECT * FROM patients WHERE id=$id")->fetch_assoc();
    }
}

// ================== Object ==================
$hospital = new Hospital();

// ================== Handle Actions ==================
$edit_mode = false;
$edit_patient = null;

if (isset($_POST['add'])) {
    if ($hospital->addPatient($_POST['name'], $_POST['age'], $_POST['disease'])) {
        $_SESSION['message'] = "✅ Patient added successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to add patient.";
    }
    header("Location: hospital.php");
    exit;
}

if (isset($_POST['update'])) {
    if ($hospital->updatePatient($_POST['id'], $_POST['name'], $_POST['age'], $_POST['disease'])) {
        $_SESSION['message'] = "✅ Patient updated successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to update patient.";
    }
    header("Location: hospital.php");
    exit;
}

if (isset($_GET['delete'])) {
    if ($hospital->deletePatient($_GET['delete'])) {
        $_SESSION['message'] = "✅ Patient deleted successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to delete patient.";
    }
    header("Location: hospital.php");
    exit;
}

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_patient = $hospital->getPatient($_GET['edit']);
}

$patients = $hospital->getPatients();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hospital Management System</title>
</head>
<body>
    <h2>🏥 Hospital Management System</h2>

    <!-- Show session message -->
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green; font-weight: bold;">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </p>
    <?php endif; ?>

    <!-- Add / Edit Form -->
    <?php if ($edit_mode): ?>
        <h3>Edit Patient</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $edit_patient['id']; ?>">
            Name: <input type="text" name="name" value="<?php echo $edit_patient['name']; ?>" required><br><br>
            Age: <input type="number" name="age" value="<?php echo $edit_patient['age']; ?>" required><br><br>
            Disease: <input type="text" name="disease" value="<?php echo $edit_patient['disease']; ?>" required><br><br>
            <button type="submit" name="update">Update Patient</button>
            <a href="hospital.php">Cancel</a>
        </form>
    <?php else: ?>
        <h3>Add Patient</h3>
        <form method="POST">
            Name: <input type="text" name="name" required><br><br>
            Age: <input type="number" name="age" required><br><br>
            Disease: <input type="text" name="disease" required><br><br>
            <button type="submit" name="add">Add Patient</button>
        </form>
    <?php endif; ?>

    <hr>

    <!-- Show Patients -->
    <h3>Patient List</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Age</th><th>Disease</th><th>Actions</th>
        </tr>
        <?php while($row = $patients->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['disease']; ?></td>
                <td>
                    <a href="hospital.php?edit=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="hospital.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this patient?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
