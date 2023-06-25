<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create a table if not exists
$sql = "CREATE TABLE IF NOT EXISTS tasks (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
task VARCHAR(255) NOT NULL,
status VARCHAR(10) NOT NULL DEFAULT 'pending'
)";

if ($conn->query($sql) === FALSE) {
  echo "Error creating table: " . $conn->error;
}

// Insert a new task from the form
if (isset($_POST['submit'])) {
  $task = $_POST['task'];
  $sql = "INSERT INTO tasks (task) VALUES ('$task')";

  if ($conn->query($sql) === TRUE) {
    echo "New task added successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Update the status of a task from the form
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $status = $_POST['status'];
  $sql = "UPDATE tasks SET status='$status' WHERE id=$id";

  if ($conn->query($sql) === TRUE) {
    echo "Task updated successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Delete a task from the form
if (isset($_POST['delete'])) {
  $id = $_POST['id'];
  $sql = "DELETE FROM tasks WHERE id=$id";

  if ($conn->query($sql) === TRUE) {
    echo "Task deleted successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Fetch all the tasks from the database
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>To Do List</title>
</head>
<body>
  <h1>To Do List</h1>
  <form action="" method="post">
    <label for="task">Enter a new task:</label>
    <input type="text" id="task" name="task" required>
    <input type="submit" name="submit" value="Add">
  </form>
  <table border="1">
    <tr>
      <th>ID</th>
      <th>Task</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php
    // Display the tasks in a table
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["task"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "<td>";
        // Create a form for each task to update or delete it
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
        echo "<select name='status'>";
        echo "<option value='pending'>Pending</option>";
        echo "<option value='done'>Done</option>";
        echo "</select>";
        echo "<input type='submit' name='update' value='Update'>";
        echo "<input type='submit' name='delete' value='Delete'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='4'>No tasks found</td></tr>";
    }
    ?>
  </table>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
