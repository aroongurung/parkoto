<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fine Page | ParKoto</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .card {
      background-color: #697565;
      border: 0.2rem solid #181C14;
      padding: 2rem 2rem;
      min-height: 32rem;
      width: 32rem;
      max-width: 90vw;
      border-radius: 0.6rem;
      box-shadow: 0.4rem 0.4rem 0.4rem rgb(66, 61, 54);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .primary-btn {
      background-color: #3C3D37;
      padding: 0.6rem 1.8rem;
      border-radius: 0.4rem;
      color: #ECDFCC;
      transition: 0.3s ease-in-out;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      width: 100%;
    }

    .primary-btn:hover {
      background-color: #181C14;
    }

    label {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
      align-self: flex-start;
      color: #ECDFCC;
    }

    input {
      width: 100%;
      padding: 0.6rem 1.2rem;
      margin-bottom: 1rem;
      border: 0.1rem solid #181C14;
      border-radius: 0.4rem;
      background-color: #ECDFCC;
      color: #181C14;
      font-size: 0.95rem;
      box-sizing: border-box;
    }

    input:focus {
      outline: none;
      border-color: #181C14;
      box-shadow: 0 0 0 2px rgba(24, 28, 20, 0.2);
    }

    input::placeholder {
      color: #697565;
      font-size: 0.85rem;
      padding-left: 0.4rem;
    }

    body {
      background-color: #181C14;
      color: #181C14;
      min-height: 100vh;
    }

    table {
      background-color: #697565;
      border-collapse: collapse;
      width: 100%;
      margin-top: 1rem;
      border-radius: 0.6rem;
    }

    th,
    td {
      border: 0.1rem solid #181C14;
      padding: 0.75rem;
      text-align: left;
      color: #ECDFCC;
    }

    th {
      background-color: #3C3D37;
      font-weight: bold;
    }

    .edit-btn {
      background-color: #3C3D37;
      padding: 0.3rem 1rem;
      border-radius: 0.3rem;
      color: #ECDFCC;
      cursor: pointer;
      border: none;
      transition: 0.3s ease-in-out;
    }

    .edit-btn:hover {
      background-color: #181C14;
    }
  </style>
</head>

<body class="bg-[#181C14] flex justify-center items-center p-4" style="gap: 2rem; flex-wrap: wrap;">

  <!-- Fine Form Card -->
  <div class="flex items-center justify-center">
    <form action="./fine/fine_insert.php" method="post" class="card">
      <h3 class="text-3xl font-bold mb-1">Par<span class="text-red-600">Koto</span></h3>
      <h1 class="text-2xl font-semibold my-2 text-[#ECDFCC]">Fine / Penalty</h1>
      <p class="text-sm text-[#ECDFCC]/80 mb-4">Fill the details below</p>

      <label for="id">ID</label>
      <input type="number" name="id" id="id" placeholder="Enter ID" required>

      <label for="car">Car</label>
      <input type="text" name="car" id="car" placeholder="Enter car details" required>

      <label for="person">Person</label>
      <input type="text" name="person" id="person" placeholder="Enter person name" required>

      <label for="fine_date">Date</label>
      <input type="date" name="fine_date" id="fine_date" required>

      <label for="fine_amount">Amount</label>
      <input type="text" name="fine_amount" id="fine_amount" placeholder="Enter fine amount" required>

      <label for="fine_reason">Reason</label>
      <input type="text" name="fine_reason" id="fine_reason" placeholder="Write reason for fine" required>

      <button class="primary-btn my-4" type="submit" name="fine_add">Add Fine</button>
    </form>
  </div>

  <!-- Results Table -->
  <div>
    <table>
      <?php
      if (isset($_POST['car_search_query']) && isset($_POST['search'])) {
        $search = $_POST['search'];

        $stmt = $conn->prepare("SELECT * FROM signup WHERE id = ?");
        $stmt->bind_param("i", $search);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
          echo '<thead>
            <tr>
              <th>Sl no</th>
              <th>ID</th>
              <th>Car</th>
              <th>Person</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Reason</th>
              <th>Edit</th>
            </tr>
            </thead>
          ';
          $row = $result->fetch_assoc();
          echo '<tbody>
            <tr>
              <td>1</td>
              <td>' . htmlspecialchars($row['id']) . '</td>
              <td>' . htmlspecialchars($row['car']) . '</td>
              <td>' . htmlspecialchars($row['person_name']) . '</td>
              <td>' . htmlspecialchars($row['fine_date']) . '</td>
              <td>' . htmlspecialchars($row['fine_amount']) . '</td>
              <td>' . htmlspecialchars($row['fine_reason']) . '</td>
              <td><button class="edit-btn">Edit</button></td>
            </tr>
          </tbody>';
        } else {
          echo '<tbody><tr><td colspan="8" style="text-align:center;">No records found</td></tr></tbody>';
        }

        $stmt->close();
      }
      ?>
    </table>
  </div>

</body>

</html>