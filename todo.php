<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "to_do";

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
  <!-- Add some CSS styles -->
  <style>
    /* Use a custom font */
    @import url('https://fonts.googleapis.com/css?family=Roboto');

    /* Define a color scheme */
    :root {
      --primary-color: #2196f3;
      --secondary-color: #f44336;
      --background-color: #f0f0f0;
      --text-color: #333333;
    }

    /* Apply the font and background color to the body */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--background-color);
    }

    /* Center the main content */
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Style the heading */
    h1 {
      text-align: center;
      color: var(--primary-color);
      text-transform: uppercase;
      letter-spacing: 2px;
      /* Add a simple animation effect */
      animation-name: slide-in;
      animation-duration: 1s;
      animation-fill-mode: forwards;
    }

    /* Define the slide-in animation */
    @keyframes slide-in {
      from { transform: translateX(-100%); }
      to { transform: translateX(0); }
    }

    /* Style the form */
    form {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    /* Style the input field */
    input[type=text] {
      flex-grow: 1;
      padding: 10px;
      border: none;
      outline: none;
      border-radius: 5px;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }

    /* Style the submit button */
    input[type=submit] {
      margin-left: 10px;
      padding: 10px 20px;
      border: none;
      outline: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      cursor: pointer;
      /* Add a transition effect */
      transition: transform 0.3s;
    }

    /* Add a hover effect to the submit button */
    input[type=submit]:hover {
      transform: scale(1.1);
    }

    /* Style the table */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    /* Style the table header */
    th {
      background-color: var(--primary-color);
      color: white;
      padding: 10px;
      text-align: left;
    }

    /* Style the table cells */
    td {
      padding: 10px;
      border-bottom: 1px solid var(--background-color);
    }

    /* Style the status column */
    td:nth-child(3) {
      text-transform: uppercase;
      font-weight: bold;
      color: var(--secondary-color);
    }

    /* Style the action column */
    td:nth-child(4) {
      display: flex;
      align-items: center;
    }

    /* Style the select element */
    select {
      margin-right: 10px;
      padding: 5px;
      border: none;
      outline: none;
      border-radius: 5px;
      background-color: var(--background-color);
      color: var(--text-color);
    }

    /* Style the update and delete buttons */
    input[type=submit][name=update],
    input[type=submit][name=delete] {
      padding: 5px 10px;
      border: none;
      outline: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
    }

    /* Style the update button */
    input[type=submit][name=update] {
      background-color: var(--primary-color);
    }

    /* Style the delete button */
    input[type=submit][name=delete] {
      background-color: var(--secondary-color);
    }
  </style>
</head>
<body>
  <div class="container">
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
  </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
